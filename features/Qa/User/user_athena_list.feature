Feature: User sign up
  In order to administer users
  A System User
  I need to be view a list of users

  Background:
    Given that the following users exist:
      | Name            | Email               | Password      |
      | Iain Cambridge  | user.1@example.org  | some_password |
      | User 2          | user.2@example.org  | some_password |
      | User 3          | user.3@example.org  | some_password |
      | User 4          | user.4@example.org  | some_password |
      | User 5          | user.5@example.org  | some_password |
      | User 6          | user.6@example.org  | some_password |
      | User 7          | user.7@example.org  | some_password |
      | User 8          | user.8@example.org  | some_password |
      | User 9          | user.9@example.org  | some_password |
      | User 10         | user.10@example.org | some_password |
      | User 11         | user.11@example.org | some_password |
      | User 12         | user.12@example.org | some_password |
      | User 13         | user.13@example.org | some_password |
      | User 14         | user.14@example.org | some_password |
      | User 15         | user.15@example.org | some_password |
      | User 16         | user.16@example.org | some_password |
      | User 17         | user.17@example.org | some_password |
      | User 18         | user.18@example.org | some_password |
      | User 19         | user.19@example.org | some_password |
      | User 20         | user.20@example.org | some_password |
      | User 21         | user.21@example.org | some_password |
      | User 22         | user.22@example.org | some_password |
      | User 23         | user.23@example.org | some_password |
      | User 24         | user.24@example.org | some_password |
      | User 25         | user.25@example.org | some_password |
      | User 26         | user.26@example.org | some_password |
      | User 27         | user.27@example.org | some_password |
      | User 28         | user.28@example.org | some_password |
      | User 29         | user.29@example.org | some_password |
      | User 30         | user.30@example.org | some_password |
      | User 31         | user.31@example.org | some_password |
      | User 32         | user.32@example.org | some_password |
      | User 33         | user.33@example.org | some_password |
      | User 34         | user.34@example.org | some_password |
      | User 35         | user.35@example.org | some_password |

    Scenario: Basic list
      Given an admin user "admin@example.org" with the password "RealPassword" exist
      And I have logged in as "admin@example.org" with the password "RealPassword"
      When I view the user list page with the following settings:
        | limit | 10 |
      Then I should see 10 items

  Scenario: Basic list with filter
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I view the user list page with the following settings:
      | filters[name] | Iain |
    Then I should see "Iain"
    But I should not see "User 2"

  Scenario: Order by Email
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I view the user list page with the following settings:
      | limit    | 10 |
      | sort_key | email |
    Then I should see "admin@example.org"
    And I should see "User 10"

  Scenario: Order by Email user deleted
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    And the user "user.1@example.org" is marked as deleted
    When I view the user list page with the following settings:
      | limit    | 10 |
      | sort_key | email |
    Then I should not see "user.1@example.org"
    And I should see "User 10"

  Scenario: Order by Email DESC
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I view the user list page with the following settings:
      | limit    | 10 |
      | sort_key | email |
      | sort_type | desc |
    Then I should see "User 35"
    And I should see "User 9"


  Scenario: Order by Email ASC click next
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    And I view the user list page with the following settings:
      | limit    | 10 |
      | sort_key | email |
      | sort_type | ASC |
    When I click next
    Then I should see "User 19"
    But I should not see "admin@example.org"


  Scenario: Order by Email
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I view the user list page with the following settings:
      | limit    | 10 |
      | sort_key | email |
    And I click next
    When I click back
    Then I should see "admin@example.org"
    And I should see "User 10"

  Scenario: Order by Email ASC click next then back
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    And I view the user list page with the following settings:
      | limit    | 10 |
      | sort_key | email |
      | sort_type | ASC |
    And I click next
    When I click back
    Then I should not see "User 19"

  Scenario: Order by Email ASC click next
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I view the user list page with the following settings:
      | limit    | 10 |
      | sort_key | is_confirmed |
      | sort_type | ASC |
    Then I should see 10 items

  Scenario: Order by Email DESC click next
    Given an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    And I view the user list page with the following settings:
      | limit    | 10 |
      | sort_key | email |
      | sort_type | desc |
    When I click next
    Then I should see "User 2"
