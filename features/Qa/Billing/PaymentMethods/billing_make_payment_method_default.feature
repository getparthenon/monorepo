Feature:
  In order to correctly manage my payment methods
  As a Customer
  I need to know what payment methods I have

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
    Given the following payment methods exist:
      | Team    | Last Four | Expiry Month | Expiry Year | Brand | Default |
      | Example | 4344      | 10           | 2041        | Visa  | False   |
      | Example | 4340      | 10           | 2045        | Visa  | True    |
      | Example | 4341      | 12           | 2046        | Visa  | False   |

  Scenario:
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    When I mark the payment method with the last four 4344 as default
    Then the payment method with the last four 4344 will be marked as default
    Then the payment method with the last four 4340 will not be marked as default
