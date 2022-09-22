Feature: Elasticsearch client
  In order to store data in Elasticsearch
  As a developer
  I need to be able to save data in Elasticsearch

  Scenario: Save
    Given there is a clean elasticsearch with the index "lol"
    When I save the following data in the index "lol":
    """
    {
      "id": 30,
      "name": "Sally Jones",
      "age": 40
    }
    """
    Then there should be a record in the index "lol" with the field "name" and value "Sally Jones"