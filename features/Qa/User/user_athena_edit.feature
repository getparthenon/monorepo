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
    When I edit the user "user.1@example.org" with the data:
      | name | Johnny Walker |
    Then the name for "user.1@example.org" should be "Johnny Walker"
