Feature: Elasticsearch client
  In order to keep data in Elasticsearch up to date
  As a developer
  I need to be able to delete data in Elasticsearch

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
    When I delete in the index "lol" the document id 30
    Then there should not be a record in the index "lol" with the field "name" and value "Sally Jones"