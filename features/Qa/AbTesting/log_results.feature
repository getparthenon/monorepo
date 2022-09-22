Feature:
  In order to know how well things are working
  As a business owner
  I need results to be logged

  Background:
    Given the following accounts exist:
      | Name        | Email                   | Password  | Team    | Api Key |
      | Sally Brown | sally.brown@example.org | AF@k3P@ss | Example | A-Key   |


  Scenario: Log login
    When I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    Then there will be a "user_login" result logged

  Scenario: Log sign up
    Given I have given the field "email" the value "ootliers.user@example.org"
    And I have given the field "password" the value "randomP@ssw0rld!"
    When I try to sign up
    Then there will be a "user_signup" result logged
