Feature: Elasticsearch client
  In order to store data in Elasticsearch
  As a developer
  I need to be able to save data in Elasticsearch

  Scenario: Create an index
    Given there is a clean elasticsearch
    When I create an index "lol"
    Then there should be an index "lol"


  Scenario: Create an alias
    Given there is a clean elasticsearch
    And I create an index "lol"
    When I create an alias "lol_alias" for "lol"
    Then there should be an alias for "lol_alias" to the index "lol"