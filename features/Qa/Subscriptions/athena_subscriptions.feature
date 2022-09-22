Feature: Athena Subscriptions
  In order to keep subscriptions up to date
  A System User
  I need to able to manage subscriptions

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

  Scenario: See two teams
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I go to the Athena subscription page
    Then I should see "Example"
    Then I should see "Second"

  Scenario: See plan information
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    And I go to the Athena subscription page
    When I view the subscription for "Example"
    Then I should see "Standard"

  Scenario: Update Plan information
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    And I go to the Athena subscription page
    And I view the subscription for "Example"
    And I go edit the subscription
    When I update the plan to "Basic"
    Then the plan for "Example" should be "Basic"
