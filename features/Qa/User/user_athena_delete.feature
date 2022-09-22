Feature: Delete users
  In order to administer users
  A System User
  I need to be delete users

  Background:
    Given that the following users exist:
      | Name            | Email               | Password      |
      | Iain Cambridge  | user.1@example.org  | some_password |

  Scenario: Delete
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I delete the user information for the user "user.1@example.org"
    Then the user "user.1@example.org" should be deleted

  Scenario: Delete
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I undelete the user information for the user "user.1@example.org"
    Then the user "user.1@example.org" should not be deleted
