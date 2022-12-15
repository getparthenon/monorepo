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

namespace App\Tests\Behat\Qa\Common\Elasticsearch;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Parthenon\Common\Elasticsearch\ClientFactory;
use Parthenon\Common\Elasticsearch\QueryBuilder;

class ClientContext implements Context
{
    private array $result;

    public function __construct(private ClientFactory $clientFactory)
    {
    }

    /**
     * @Given there is a clean elasticsearch with the index :arg1
     */
    public function thereIsACleanElasticsearchWithTheIndex($indexName)
    {
        $rawClient = $this->clientFactory->buildRawClient();

        $rawClient->indices()->delete(['index' => '_all']);
        $rawClient->indices()->create(['index' => $indexName]);
    }

    /**
     * @When I save the following data in the index :arg1:
     */
    public function iSaveTheFollowingDataInTheIndex($indexName, PyStringNode $string)
    {
        $jsonArray = json_decode($string->getRaw(), true);
        $client = $this->clientFactory->build();
        $client->save($indexName, $jsonArray);
        $rawClient = $this->clientFactory->buildRawClient();
        $rawClient->indices()->refresh(['index' => $indexName]);
    }

    /**
     * @Then there should be a record in the index :arg1 with the field :arg2 and value :arg3
     */
    public function thereShouldBeARecordInTheIndexWithTheFieldAndValue($indexName, $fieldName, $fieldValue)
    {
        $rawClient = $this->clientFactory->buildRawClient();
        $rawClient->indices()->refresh(['index' => $indexName]);
        $results = $rawClient->search([
            'index' => $indexName,
            'body' => [
                'query' => [
                    'match' => [
                        $fieldName => $fieldValue,
                    ],
                 ],
            ],
        ]);
        if (1 !== $results['hits']['total']['value']) {
            throw new \Exception("Didn't find the document");
        }
    }

    /**
     * @When I search :arg1 for :arg2 with the value :arg3
     */
    public function iSearchForWithTheValue($indexName, $fieldName, $fieldValue)
    {
        $query = (new QueryBuilder())->query('match', $fieldName, $fieldValue)->build();

        $client = $this->clientFactory->build();
        $this->result = $client->search($indexName, $query);
    }

    /**
     * @Then I should find :arg1 result
     */
    public function iShouldFindResult($arg1)
    {
        if (!isset($this->result)) {
            throw new \Exception('No result found');
        }

        if (intval($arg1) !== $this->result['hits']['total']['value']) {
            throw new \Exception(sprintf('Count was %d', $this->result['hits']['total']['value']));
        }
    }

    /**
     * @When I delete in the index :arg1 the document id :arg2
     */
    public function iDeleteInTheIndexTheDocumentId($indexName, $documentId)
    {
        $client = $this->clientFactory->build();
        $client->delete($indexName, $documentId);
    }

    /**
     * @Then there should not be a record in the index :arg1 with the field :arg2 and value :arg3
     */
    public function thereShouldNotBeARecordInTheIndexWithTheFieldAndValue($indexName, $fieldName, $fieldValue)
    {
        $rawClient = $this->clientFactory->buildRawClient();
        $rawClient->indices()->refresh(['index' => $indexName]);
        $results = $rawClient->search([
            'index' => $indexName,
            'body' => [
                'query' => [
                    'match' => [
                        $fieldName => $fieldValue,
                    ],
                ],
            ],
        ]);
        if (0 !== $results['hits']['total']['value']) {
            throw new \Exception('Found the documents');
        }
    }

    /**
     * @Given there is a clean elasticsearch
     */
    public function thereIsACleanElasticsearch()
    {
        $rawClient = $this->clientFactory->buildRawClient();

        $rawClient->indices()->delete(['index' => '_all']);
    }

    /**
     * @When I create an index :arg1
     */
    public function iCreateAnIndex($indexName)
    {
        $client = $this->clientFactory->build();
        $client->createIndex($indexName);
    }

    /**
     * @Then there should be an index :arg1
     */
    public function thereShouldBeAnIndex($indexName)
    {
        $rawClient = $this->clientFactory->buildRawClient();
        $indices = $rawClient->indices()->get(['index' => '_all']);

        if (!isset($indices[$indexName])) {
            throw new \Exception('No index found');
        }
    }

    /**
     * @When I create an alias :arg1 for :arg2
     */
    public function iCreateAnAliasFor($aliasName, $indexName)
    {
        $client = $this->clientFactory->build();
        $client->createAlias($indexName, $aliasName);
    }

    /**
     * @Then there should be an alias for :arg1 to the index :arg2
     */
    public function thereShouldBeAnAliasForToTheIndex($aliasName, $indexName)
    {
        $rawClient = $this->clientFactory->buildRawClient();
        $indices = $rawClient->indices()->get(['index' => '_all']);

        if (!isset($indices[$indexName])) {
            throw new \Exception('No index found');
        }

        if (!isset($indices[$indexName]['aliases'])) {
            throw new \Exception('No aliases created');
        }

        if (!isset($indices[$indexName]['aliases'][$aliasName])) {
            throw new \Exception('There is no alias for '.$aliasName.' found');
        }
    }
}
