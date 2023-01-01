Feature:
  In order to be correctly billed
  As a Customer
  I need to be able to set my billing address

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

  Scenario:
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    When I set my billing address as:
      | Street Line One | 1 Example Way |
      | City            | Example Town  |
      | State           | Example State |
      | Country         | US            |
      | Postcode        | 21043         |
    Then the customer for "Example" billing address should have a street line one as "1 Example Way"
