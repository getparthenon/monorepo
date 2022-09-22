Feature: Elasticsearch search
  In order to use the data in Elasticsearch
  As a developer
  I need to be able to search data in Elasticsearch

  Scenario: Save
    Given there is a clean elasticsearch with the index "lol"
    And I save the following data in the index "lol":
    """
    {
      "id": 30,
      "name": "Sally Jones",
      "age": 40
    }
    """
    When I search "lol" for "name" with the value "Sally Jones"
    Then I should find 1 result