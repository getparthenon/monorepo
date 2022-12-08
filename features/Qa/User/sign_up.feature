Feature: User sign up
  In order to use the site repeatedly and have it remember who I am
  A Customer
  I need to be able to sign up


  Scenario: Sign up with a password with no email confirmation
    Given email confirmation is enabled for new users
    Given I have given the field "email" the value "parthenon.user@example.org"
    And I have given the field "password" the value "randomP@ssw0rld!"
    When I try to sign up
    Then there will be a new user registered
    And the user "parthenon.user@example.org" will not be confirmed

  Scenario: Sign up with a password with no email confirmation
    Given email confirmation is disabled for new users
    Given I have given the field "email" the value "parthenon.user@example.org"
    And I have given the field "password" the value "randomP@ssw0rld!"
    When I try to sign up
    Then there will be a new user registered
    And the user "parthenon.user@example.org" will be confirmed

  Scenario: Sign up with log in enabled
    Given logged in after sign up is enabled
    Given I have given the field "email" the value "parthenon.user@example.org"
    And I have given the field "password" the value "randomP@ssw0rld!"
    When I try to sign up
    Then the payload will contain the user data

  Scenario: Sign up with log in disabled
    Given logged in after sign up is not enabled
    Given I have given the field "email" the value "parthenon.user@example.org"
    And I have given the field "password" the value "randomP@ssw0rld!"
    When I try to sign up
    Then the payload will not contain the user data
