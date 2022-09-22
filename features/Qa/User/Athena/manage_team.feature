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

  Scenario: Basic read
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I go to teams athena page
    Then I should see "Example"
    And I should see "Second"


  Scenario: read team info
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    And I go to teams athena page
    And I go to the team "Example" page
    Then I should see "sally.brown@example.org"
    And I should see "tim.brown@example.org "