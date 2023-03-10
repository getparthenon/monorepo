Feature: User sign up
  In order to administer a user
  A System User
  I need to be view a users details

  Background:
    Given the following teams exist:
      | Name    | Plan     |
      | Example | Standard |
      | Second  | Basic    |
    Given the following accounts exist:
      | Name        | Email                   | Password  | Team    |
      | Sally Brown | sally.brown@example.org | AF@k3P@ss | Example |
      | Tim Brown   | tim.brown@example.org   | AF@k3P@ss | Example |
      | Sally Braun | sally.braun@example.org | AF@k3Pass | Second  |
    Given an admin user "admin@example.org" with the password "RealPassword" exist

  Scenario: Basic read
    Given the following payments exist:
      | Customer | Amount | Currency | Provider | Completed | Refunded | Charged Back |
      | Example  | 10.00  | USD      | stripe   | true      | false    | false        |
    Given I have logged in as "admin@example.org" with the password "RealPassword"
    When I go to payment athena page
    Then I should see "Example"
    And I should see "10.00"
