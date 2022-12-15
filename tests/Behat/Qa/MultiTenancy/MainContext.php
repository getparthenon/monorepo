<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 16.12.2025
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Tests\Behat\Qa\MultiTenancy;

use App\Entity\Tenant;
use App\MultiTenant\Repository\LinkRepository;
use App\Repository\Orm\TenantRepository;
use App\Repository\Orm\UserRepository;
use App\Tests\Behat\Skeleton\SendRequestTrait;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\AfterScenarioScope;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Behat\Gherkin\Node\TableNode;
use Behat\Mink\Session;
use Parthenon\MultiTenancy\Database\DatabaseSwitcherInterface;
use Parthenon\MultiTenancy\Database\DbalDatabaseCreator;
use Parthenon\MultiTenancy\Dbal\TenantConnection;
use Parthenon\MultiTenancy\Entity\TenantInterface;
use Parthenon\MultiTenancy\TenantProvider\SimpleTenantProvider;
use Parthenon\User\Entity\UserInterface;
use Ramsey\Uuid\Uuid;

class MainContext implements Context
{
    use SendRequestTrait;

    private array $fields = [];
    private TenantInterface $tenant;

    public function __construct(
        private Session $session,
        private LinkRepository $linkRepository,
        private TenantRepository $tenantRepository,
        private DbalDatabaseCreator $dbalDatabaseCreator,
        private DatabaseSwitcherInterface $databaseSwitcher,
        private UserRepository $userRepository
    ) {
    }

    /**
     * @AfterScenario
     */
    public function tearDown(AfterScenarioScope $afterScenario)
    {
        if (!isset($this->tenant)) {
            return;
        }
        $this->linkRepository->getEntityManager()->getConnection()->close();
        gc_collect_cycles();
        $this->linkRepository->getEntityManager()->getConnection()->setCurrentTenantProvider(new SimpleTenantProvider(Tenant::createWithSubdomainAndDatabase('parthenon_test', 'parthenon_test')));
        $this->linkRepository->getEntityManager()->getConnection()->connect(true);
        $connection = $this->tenantRepository->getEntityManager()->getConnection();
        // $connection->createSchemaManager()->dropDatabase($this->tenant->getDatabase());
    }

    /**
     * @BeforeScenario
     */
    public function startUp(BeforeScenarioScope $event)
    {
        $em = $this->linkRepository->getEntityManager();

        $metaData = $em->getMetadataFactory()->getAllMetadata();

        $tool = new \Doctrine\ORM\Tools\SchemaTool($em);
        $tool->dropSchema($metaData);
        $tool->createSchema($metaData);
        $this->fields = [];
    }

    /**
     * @Given that the following tenants exist:
     */
    public function thatTheFollowingTenantsExist(TableNode $table)
    {
        foreach ($table->getColumnsHash() as $row) {
            $tenant = new Tenant();
            $uuid = Uuid::uuid4();
            $tenant->setId($uuid);
            $tenant->setDatabase($row['Database']);
            $tenant->setSubdomain($row['Subdomain']);
            $tenant->setCreatedAt(new \DateTime());
            $this->tenantRepository->getEntityManager()->persist($tenant);
        }
        $this->tenantRepository->getEntityManager()->flush();
    }

    /**
     * @When I visit the subdomain :arg1 for multi-tenancy
     */
    public function iVisitTheSubdomainForMultiTenancy($arg1)
    {
        $this->session->visit('/multi-tenancy');
    }

    /**
     * @Then I will see hello world
     */
    public function iWillSeeHelloWorld()
    {
        if ('hello world' !== $this->session->getPage()->getContent()) {
            throw new \Exception('Unable to see hello world');
        }
    }

    /**
     * @When I create a database for tenant:
     */
    public function iCreateADatabaseForTenant(TableNode $table)
    {
        $database = $table->getRowsHash()['Database'];

        $tenant = new Tenant();
        $uuid = Uuid::uuid4();
        $tenant->setId($uuid);
        $tenant->setDatabase($database);

        $this->dbalDatabaseCreator->createDatabase($tenant);
    }

    /**
     * @Then there should be a database called :arg1
     */
    public function thereShouldBeADatabaseCalled($databaseName)
    {
        $databases = $this->linkRepository->getEntityManager()->getConnection()->createSchemaManager()->listDatabases();

        if (!in_array($databaseName, $databases)) {
            throw new \Exception('Database does not exist');
        }
        /** @var TenantConnection $connection */
        $connection = $this->linkRepository->getEntityManager()->getConnection();
        $this->linkRepository->getEntityManager()->getConnection()->setCurrentTenantProvider(new SimpleTenantProvider(Tenant::createWithSubdomainAndDatabase('parthenon_test', 'parthenon_test')));
        $connection->connect(true);
        $connection->createSchemaManager()->dropDatabase($databaseName);
    }

    /**
     * @Given I have entered the tenant subdomain as :arg1
     */
    public function iHaveEnteredTheTenantSubdomainAs($subdomain)
    {
        $this->fields['subdomain'] = $subdomain;
    }

    /**
     * @Given I have entered the tenant admin user email as :arg1
     */
    public function iHaveEnteredTheTenantAdminUserEmailAs($email)
    {
        $this->fields['email'] = $email;
    }

    /**
     * @Given I have entered the tenant admin user name as :arg1
     */
    public function iHaveEnteredTheTenantAdminUserNameAs($name)
    {
        $this->fields['name'] = $name;
    }

    /**
     * @Given I have entered the tenant admin user password as :arg1
     */
    public function iHaveEnteredTheTenantAdminUserPasswordAs($password)
    {
        $this->fields['password'] = $password;
    }

    /**
     * @When I sign up as a new tenant
     */
    public function iSignUpAsANewTenant()
    {
        $this->sendJsonRequest('POST', '/tenant/signup', $this->fields);
    }

    /**
     * @Then there should be a tenant for :arg1 subdomain
     */
    public function thereShouldBeATenantForSubdomain($subdomain)
    {
        $tenant = $this->tenantRepository->findOneBy(['subdomain' => $subdomain]);

        if (!$tenant instanceof TenantInterface) {
            throw new \Exception('No tenant found');
        }
        $this->tenant = $tenant;
    }

    /**
     * @Then there should not be a tenant for :arg1 subdomain
     */
    public function thereShouldNotBeATenantForSubdomain($subdomain)
    {
        $tenant = $this->tenantRepository->findOneBy(['subdomain' => $subdomain]);

        if ($tenant instanceof TenantInterface) {
            throw new \Exception('Tenant found');
        }
    }

    /**
     * @Then there should be a user :arg1 for the :arg2 subdomain
     */
    public function thereShouldBeAUserForTheSubdomain($email, $arg2)
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user instanceof UserInterface) {
            throw new \Exception('No user found');
        }
    }
}
