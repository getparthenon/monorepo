Feature: Plans have features that are on or off
  In order to make the most money possible
  As a SaaS operator
  I want to limit features to higher paid plans

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

  Scenario: List Items
    Given the Plan "Standard" does not have the feature "list_items"
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    When I try to list items
    Then I will be refused

  Scenario: List Items
    Given the Plan "Standard" does have the feature "list_items"
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    When I try to list items
    Then the items will be listed