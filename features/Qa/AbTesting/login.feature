Feature:
  In order to know if things work
  As a business owner
  I want to have an ab session

  Background:
    Given the following accounts exist:
      | Name        | Email                   | Password  | Team    | Api Key |
      | Sally Brown | sally.brown@example.org | AF@k3P@ss | Example | A-Key   |

  Scenario: Login attaches user to session
    When I have logged in as "sally.brown@example.org" with the password "AF@k3P@ss"
    Then the a/b testing session should have the user "sally.brown@example.org" attached to it