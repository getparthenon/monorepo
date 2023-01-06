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
      | Team    | Last Four | Expiry Month | Expiry Year | Brand |
      | Example | 4344      | 10           | 2041        | Visa  |
      | Example | 4340      | 10           | 2045        | Visa  |
      | Example | 4341      | 12           | 2046        | Visa  |

  Scenario:
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    When I delete the payment method with the last four 4344
    Then the payment method with the last four 4344 will be marked as deleted

  Scenario:
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    And I delete the payment method with the last four "4344"
    When I fetch the payment methods
    Then I should not see the payment method for "4344"
