Feature: User sign up
  In order to administer a user
  A System User
  I need to be view a users details

  Background:
    Given that the following users exist:
      | Name            | Email               | Password      |
      | Iain Cambridge  | user.1@example.org  | some_password |

  Scenario: Basic read
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I view the user information for the user "user.1@example.org"
    Then I should see "Iain Cambridge"
    And I should see "user.1@example.org"
