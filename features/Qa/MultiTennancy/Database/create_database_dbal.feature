Feature: Create Database for DBAL
  In order to onboard new users
  As a multi tenant site user who uses orm
  I need to create new databases

  Scenario: Standard
    When I create a database for tenant:
      | Database | testy_mctestfacey |
    Then there should be a database called "testy_mctestfacey"