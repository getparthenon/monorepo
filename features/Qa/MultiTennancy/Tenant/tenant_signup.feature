Feature: Tenant Signup
  In order to use the application
  As a new tenant
  I need to be able to sign up

  Scenario: Sign up success
    Given I have entered the tenant subdomain as "test"
    And I have entered the tenant admin user name as "Test"
    And I have entered the tenant admin user email as "admin.user@example.org"
    And I have entered the tenant admin user password as "Fak3pässword"
    When I sign up as a new tenant
    Then there should be a tenant for "test" subdomain
    And there should be a user "admin.user@example.org" for the "test" subdomain

  Scenario: Sign up success
    Given I have entered the tenant subdomain as "test1"
    And I have entered the tenant admin user name as "Test"
    And I have entered the tenant admin user email as "admin.user@example.org"
    And I have entered the tenant admin user password as "Fak3pässword"
    When I sign up as a new tenant
    Then there should not be a tenant for "test1" subdomain
