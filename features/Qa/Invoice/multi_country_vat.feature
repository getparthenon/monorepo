Feature: Support different VAT rates for different countries
   In order to correctly pay taxes in multiple countries
   As a business
   I need to be able to charge the correct VAT based on a country

   Background:
     Given the follow tax rules exist:
      | Country         | Goods Type | Percentage |
      | United Kingdom  | Digital    | 20         |
      | United Kingdom  | Book       | 0          |
      | United Kingdom  | Food       | 0          |
      | Sweden          | Digital    | 25         |
      | Sweden          | Food       | 12         |
      | Sweden          | Book       | 6          |
      | Germany         | Digital    | 20         |
      | Germany         | Book       | 7          |
      | Germany         | Food       | 7          |

   Scenario: German vat rules applied
     Given I have the following invoice items
      | Title      | Value   | Goods Type |
      | Item one   | 10.10   | Digital    |
      | Item two   | 20.50   | Book       |
      | Item three | 20.50   | Food       |
     And the invoice is for the country of "Germany"
     When I generate an invoice
     Then the invoice should have the total of 55.99
     And the invoice row for "Item one" should have the vat of 2.02
     And the invoice row for "Item two" should have the vat of 1.435
     And the invoice row for "Item three" should have the vat of 1.435
     And the invoice should have the vat of 4.89

  Scenario: Sweden vat rules applied
    Given I have the following invoice items
      | Title      | Value   | Goods Type |
      | Item one   | 10.10   | Digital    |
      | Item two   | 20.50   | Book       |
      | Item three | 20.50   | Food       |
    And the invoice is for the country of "Sweden"
    When I generate an invoice
    Then the invoice should have the total of 57.32
    And the invoice row for "Item one" should have the vat of 2.525
    And the invoice row for "Item two" should have the vat of 1.23
    And the invoice row for "Item three" should have the vat of 2.46
    And the invoice should have the vat of 6.22

  Scenario: Sweden vat rules applied
    Given I have the following invoice items
      | Title      | Value   | Goods Type |
      | Item one   | 10.10   | Digital    |
      | Item two   | 20.50   | Book       |
      | Item three | 20.50   | Food       |
    And the invoice is for the country of "United Kingdom"
    When I generate an invoice
    Then the invoice should have the total of 53.12
    And the invoice row for "Item one" should have the vat of 2.02
    And the invoice row for "Item two" should have the vat of 0
    And the invoice row for "Item three" should have the vat of 0
    And the invoice should have the vat of 2.02
