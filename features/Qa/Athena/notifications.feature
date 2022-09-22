Feature: User sign up
  In order to keep up to date
  A System User
  I need to receive notifications

  Scenario: There is an unread one
    Given the following notifications exist:
      | Message | Url Path             | Url Details | Read  |
      | Test    | app_backoffice_index | []          | false |
    And an admin user "admin@example.org" with the password "RealPassword" exist
    And I have logged in as "admin@example.org" with the password "RealPassword"
    When I go to the Athena main page
    Then I should see "Test"
