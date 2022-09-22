Feature:
  In order to know if things work
  As a business owner
  I want to have an ab session

  Scenario: It receives a response from Symfony's kernel
    When a demo scenario sends a request to "/"
    Then there should be a new ab session

  Scenario: It receives a response from Symfony's kernel
    When a demo scenario sends a request to "/?utm_source=uptime_check"
    Then there should not be a new ab session