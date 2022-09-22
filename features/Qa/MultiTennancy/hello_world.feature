Feature: Check hello world in multi-tenancy world
  In order to work
  As a developer
  I need to be able to do multi-tenancy

  Scenario:
    Given that the following tenants exist:
      | Subdomain | Database              |
      | test      | parthenon_tenant_test |
    When I visit the subdomain "test" for multi-tenancy
    Then I will see hello world