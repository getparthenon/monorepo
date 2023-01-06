Feature:
  In order to know
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
    Given the following payment methods exist:
      | Team    | Last Four | Expiry Month | Expiry Year | Brand |
      | Example | 4344      | 10           | 2041        | Visa  |
      | Example | 4340      | 10           | 2045        | Visa  |
      | Example | 4341      | 12           | 2046        | Visa  |

  Scenario:
    Given I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    When I fetch the payment methods
    Then there will be 3 payment methods
    And there will be a payment method with the last four "4344"
