<?php

declare(strict_types=1);

/*
 * Copyright Iain Cambridge 2020-2022.
 *
 * Use of this software is governed by the Business Source License included in the LICENSE file and at https://getparthenon.com/docs/next/license.
 *
 * Change Date: 01-10-2025 ( 3 years after 2.0.0 release )
 *
 * On the date above, in accordance with the Business Source License, use of this software will be governed by the open source license specified in the LICENSE file.
 */

namespace App\Tests\Behat\Qa\AbTesting;

use App\Entity\User;
use App\Repository\Orm\ExperimentRepository;
use App\Repository\Orm\UserRepository;
use App\Tests\Behat\Skeleton\UserTrait;
use Behat\Behat\Context\Context;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Doctrine\DBAL\Connection;
use Parthenon\AbTesting\Calculation\Calculate;
use Parthenon\AbTesting\Entity\Experiment;
use Parthenon\AbTesting\Entity\Variant;
use Parthenon\AbTesting\Repository\ExperimentLogRepositoryInterface;
use Parthenon\AbTesting\Repository\SessionRepositoryInterface;
use Ramsey\Uuid\Uuid;

class MainContext implements Context
{
    use UserTrait;
    private Connection $connection;
    private UserRepository $userRepository;
    private ExperimentRepository $experimentRepository;
    private SessionRepositoryInterface $sessionRepository;
    private ExperimentLogRepositoryInterface $experimentLogRepository;

    private Calculate $calculate;

    public function __construct(Connection $connection, UserRepository $userRepository, ExperimentRepository $experimentRepository, SessionRepositoryInterface $sessionRepository, ExperimentLogRepositoryInterface $experimentLogRepository, Calculate $calculate)
    {
        $this->connection = $connection;
        $this->userRepository = $userRepository;
        $this->experimentRepository = $experimentRepository;
        $this->sessionRepository = $sessionRepository;
        $this->experimentLogRepository = $experimentLogRepository;
        $this->calculate = $calculate;
    }

    /**
     * @Then there should be a new ab session
     */
    public function thereShouldBeANewAbSession()
    {
        $query = $this->connection->executeQuery('SELECT COUNT(*)  FROM ab_sessions');
        $count = $query->fetchNumeric()[0];
        if (1 !== $count) {
            throw new \Exception('No new session!');
        }
    }

    /**
     * @Then there should not be a new ab session
     */
    public function thereShouldNotBeANewAbSession()
    {
        $query = $this->connection->executeQuery('SELECT COUNT(*)  FROM ab_sessions');
        $count = $query->fetchNumeric()[0];
        if (0 !== $count) {
            throw new \Exception('A new session!');
        }
    }

    /**
     * @Then the a\/b testing session should have the user :arg1 attached to it
     */
    public function theABTestingSessionShouldHaveTheUserAttachedToIt($email)
    {
        $user = $this->findUser($email);
        $query = $this->connection->executeQuery('SELECT * FROM ab_sessions');
        $session = $query->fetchAssociative();

        if ($session['user_id'] !== (string) $user->getId()) {
            throw new \Exception('Session not attached to user');
        }
    }

    /**
     * @Then there will be a :arg1 result logged
     */
    public function thereWillBeAResultLogged($id)
    {
        $query = $this->connection->prepare('SELECT COUNT(*)  FROM ab_result_log WHERE result_string_id = :id');
        $query->bindValue(':id', $id);
        $result = $query->executeQuery();
        $count = $result->fetchNumeric()[0];
        if (1 !== $count) {
            throw new \Exception('No result!');
        }
    }

    /**
     * @Given there is a session AB experiment for :arg1 for :arg2
     */
    public function thereIsAnAbExperimentFor($id, $resultId)
    {
        $experiment = new Experiment();
        $controlVariant = new Variant();
        $controlVariant->setName('control');
        $controlVariant->setPercentage(50);
        $controlVariant->setExperiment($experiment);

        $experimentVariant = new Variant();
        $experimentVariant->setName('experiment');
        $experimentVariant->setPercentage(50);
        $experimentVariant->setExperiment($experiment);

        $experiment->setType('session');
        $experiment->setName($id);
        $experiment->setDesiredResult($resultId);
        $experiment->setCreatedAt(new \DateTime('now'));
        $this->experimentRepository->getEntityManager()->persist($experiment);
        $experiment->setVariants([$controlVariant, $experimentVariant]);
        $this->experimentRepository->getEntityManager()->persist($experiment);

        $this->experimentRepository->getEntityManager()->flush();
    }

    /**
     * @Given there is a user AB experiment for :arg1 for :arg2
     */
    public function thereIsAnUserAbExperimentFor($id, $resultId)
    {
        $experiment = new Experiment();
        $controlVariant = new Variant();
        $controlVariant->setName('control');
        $controlVariant->setPercentage(50);
        $controlVariant->setExperiment($experiment);

        $experimentVariant = new Variant();
        $experimentVariant->setName('experiment');
        $experimentVariant->setPercentage(50);
        $experimentVariant->setExperiment($experiment);

        $experiment->setType('user');
        $experiment->setName($id);
        $experiment->setDesiredResult($resultId);
        $experiment->setCreatedAt(new \DateTime('now'));
        $this->experimentRepository->getEntityManager()->persist($experiment);
        $experiment->setVariants([$controlVariant, $experimentVariant]);
        $this->experimentRepository->getEntityManager()->persist($experiment);

        $this->experimentRepository->getEntityManager()->flush();
    }

    /**
     * @Given there are :number AB sessions for :decision for :decisionId
     */
    public function thereAreAbSessionsForFor($decsion, $decisionId, $number)
    {
        for ($i = 0; $i < $number; ++$i) {
            $sessionId = $this->sessionRepository->createSession('Test Suite', '127.0.0.2');
            $this->experimentLogRepository->saveDecision($sessionId, $decisionId, $decsion);
        }
    }

    /**
     * @Given there are :number user AB sessions for :decision for :decisionId
     */
    public function thereAreUserAbSessionsForFor($decsion, $decisionId, $number)
    {
        for ($i = 0; $i < $number; ++$i) {
            $uuid = Uuid::uuid4();
            $user = new User();
            $user->setId($uuid);

            $sessionId = $this->sessionRepository->createSession('Test Suite', '127.0.0.2', $user);
            $this->experimentLogRepository->saveDecision($sessionId, $decisionId, $decsion);
        }
    }

    /**
     * @Given :number user AB sessions for :decision for :decisionId result in :resultId
     */
    public function userAbSessionsForForResultIn($decsion, $decsionId, $resultId, $number)
    {
        $output = $this->connection->executeQuery("INSERT INTO ab_result_log (id, session_id, user_id, result_string_id, created_at) 
        select uuid_in(md5(random()::text || clock_timestamp()::text)::cstring), abel.session_id, abas.user_id, TEXT '{$resultId}' as result, now() FROM ab_experiment_log abel INNER JOIN ab_sessions abas ON abas.id = abel.session_id WHERE abel.decision_string_id = '{$decsionId}' AND abel.decision_output = '{$decsion}' LIMIT $number");
    }

    /**
     * @Given :number AB sessions for :decision for :decisionId result in :resultId
     */
    public function abSessionsForForResultIn($decsion, $decsionId, $resultId, $number)
    {
        $output = $this->connection->executeQuery("INSERT INTO ab_result_log (id, session_id, result_string_id, created_at) 
        select uuid_in(md5(random()::text || clock_timestamp()::text)::cstring), session_id, TEXT '{$resultId}' as result, now() FROM ab_experiment_log WHERE decision_string_id = '{$decsionId}' AND decision_output = '{$decsion}' LIMIT $number");
    }

    /**
     * @When the AB experiment for :arg1 is calculated
     */
    public function theAbExperimentForIsCalculated($name)
    {
        $experiment = $this->experimentRepository->findOneBy(['name' => $name]);
        $this->calculate->process($experiment);
    }

    /**
     * @Then the results for :arg1 total conversion rate for :arg2 should be :arg3%
     */
    public function theResultsForTotalConversionRateForShouldBe($name, $type, $percentage)
    {
        /** @var Experiment $experiment */
        $experiment = $this->experimentRepository->findOneBy(['name' => $name]);
        $this->experimentRepository->getEntityManager()->refresh($experiment);

        /** @var Variant[] $variants */
        $variants = $experiment->getVariants();
        foreach ($variants as $variant) {
            if ('experiment' === $variant->getName()) {
                $experimentVariant = $variant;
            } elseif ('control' === $variant->getName()) {
                $controlVariant = $variant;
            }
        }

        if ('experiment' === $type) {
            $actual = $experimentVariant->getStats()->getConversionPercentage();
        } elseif ('control' === $type) {
            $actual = $controlVariant->getStats()->getConversionPercentage();
        }

        if ($actual != $percentage) {
            throw new \Exception("Percentage doesn't match. Got ".$actual.' wanted '.$percentage);
        }
    }

    /**
     * @BeforeScenario
     */
    public function startup(BeforeScenarioScope $event)
    {
        try {
            $this->connection->executeStatement('CREATE EXTENSION IF NOT EXISTS timescaledb CASCADE;
CREATE TABLE IF NOT EXISTS "orders" (
	"id" serial,
	"site_id" text,
	"order_number" text,
	"total" text,
	"site_time" timestamp,
	"utc_time" timestamp
);
select create_hypertable(\'orders\', \'utc_time\');
CREATE TABLE IF NOT EXISTS "order_items" (
	"id" serial,
	"site_id" text,
	"order_number" text,
	"item_name" text,
	"price" text,
	"quantity" numeric(9,2),
	"site_time" timestamp,
	"utc_time" timestamp
);
select create_hypertable(\'order_items\', \'utc_time\');
CREATE TABLE IF NOT EXISTS "pageloads" (
	"id" serial,
	"site_id" text,
	"page_load_test_id" text,
	"url" text,
    "raw_duration_ms" integer DEFAULT 0,
	"site_time" timestamp,
	"utc_time" timestamp
);
select create_hypertable(\'pageloads\', \'utc_time\');
CREATE TABLE IF NOT EXISTS "ab_sessions" (
	"id" uuid,
	"user_id" uuid DEFAULT NULL,
	"user_agent" varchar(255),
	"ip_address" varchar(255),
	"created_at" timestamp
);
select create_hypertable(\'ab_sessions\', \'created_at\');
CREATE TABLE IF NOT EXISTS "ab_experiment_log" (
	"id" uuid,
	"session_id" uuid,
	"decision_string_id" text,
	"decision_output" text,
	"created_at" timestamp
);
select create_hypertable(\'ab_experiment_log\', \'created_at\');
CREATE TABLE IF NOT EXISTS "ab_result_log" (
	"id" uuid,
	"session_id" uuid,
	"user_id" uuid,
	"result_string_id" text,
	"created_at" timestamp
);
select create_hypertable(\'ab_result_log\', \'created_at\');');
        } catch (\Throwable $e) {
            $this->connection->executeStatement('TRUNCATE orders');
            $this->connection->executeStatement('TRUNCATE order_items');
            $this->connection->executeStatement('TRUNCATE pageloads');
            $this->connection->executeStatement('TRUNCATE ab_sessions');
            $this->connection->executeStatement('TRUNCATE ab_experiment_log');
            $this->connection->executeStatement('TRUNCATE ab_result_log');
        }
    }
}
