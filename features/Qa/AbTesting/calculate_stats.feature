Feature:
  In order to know how well things are working
  As a business owner
  I need conversation rates to be calculated

  Scenario: total
    Given there is a session AB experiment for "landing_page_header_one" for "user_signup"
    And there are 100 AB sessions for "experiment" for "landing_page_header_one"
    And there are 99 AB sessions for "control" for "landing_page_header_one"
    And 55 AB sessions for "experiment" for "landing_page_header_one" result in "user_signup"
    And 34 AB sessions for "control" for "landing_page_header_one" result in "user_signup"
    When the AB experiment for "landing_page_header_one" is calculated
    Then the results for "landing_page_header_one" total conversion rate for "experiment" should be 55%
    Then the results for "landing_page_header_one" total conversion rate for "control" should be 34.34%


  Scenario: total
    Given there is a user AB experiment for "landing_page_header_one" for "user_signup"
    And there are 200 user AB sessions for "experiment" for "landing_page_header_one"
    And there are 198 user AB sessions for "control" for "landing_page_header_one"
    And 110 user AB sessions for "experiment" for "landing_page_header_one" result in "user_signup"
    And 68 user AB sessions for "control" for "landing_page_header_one" result in "user_signup"
    When the AB experiment for "landing_page_header_one" is calculated
    Then the results for "landing_page_header_one" total conversion rate for "experiment" should be 55%
    Then the results for "landing_page_header_one" total conversion rate for "control" should be 34.34%
