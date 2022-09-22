Feature:
  In order to correctly bill clients
  As a business owner
  I need to be able to be able to invoice clients

  Scenario: Basic sum total
    Given I have the following invoice items
      | Title    | Value   |
      | Item one | 10.10   |
      | Item two | 20.50   |
    When I generate an invoice
    Then the invoice should have the total of 30.60

  Scenario: Multiple Items total
    Given I have the following invoice items
      | Title    | Value   | Quantity |
      | Item one | 10.10   | 4        |
      | Item two | 20.50   | 2        |
    When I generate an invoice
    Then the invoice should have the total of 81.40

  Scenario: Basic sum with VAT total
    Given I have the following invoice items
      | Title    | Value   | VAT % |
      | Item one | 10.10   | 20    |
      | Item two | 20.50   | 0     |
    When I generate an invoice
    Then the invoice should have the total of 32.62

  Scenario: Multiple items with VAT total
    Given I have the following invoice items
      | Title    | Value   | Quantity | VAT % |
      | Item one | 10.10   | 4        | 20    |
      | Item two | 20.50   | 2        | 0     |
    When I generate an invoice
    Then the invoice should have the total of 89.48

  Scenario: Multiple items with VAT total
    Given I have the following invoice items
      | Title    | Value   | Quantity | VAT % |
      | Item one | 10.10   | 4        | 20    |
      | Item two | 20.50   | 2        | 0     |
    When I generate an invoice
    Then the invoice should have the total without vat of 81.40

  Scenario: Multiple items with VAT total with VAT Number
    Given I have the following invoice items
      | Title    | Value   | Quantity | VAT % |
      | Item one | 10.10   | 4        | 20    |
      | Item two | 20.50   | 2        | 0     |
    And I have the vat number "GB90494994"
    When I generate an invoice
    Then the invoice should have the total of 81.40

  Scenario: Multiple items with VAT total with VAT Number
    Given I have the following invoice items
      | Title    | Value   | Quantity | VAT % |
      | Item one | 10.10   | 4        | 20    |
      | Item two | 20.50   | 2        | 0     |
    And I have the vat number "GB90494994"
    When I generate an invoice
    Then the invoice should have the total without vat of 81.40

  Scenario: Basic sum with VAT
    Given I have the following invoice items
      | Title    | Value   | VAT % |
      | Item one | 10.10   | 20    |
      | Item two | 20.50   | 0     |
    When I generate an invoice
    Then the invoice should have the vat of 2.02

  Scenario: Multiple items with VAT total
    Given I have the following invoice items
      | Title    | Value   | Quantity | VAT % |
      | Item one | 10.10   | 4        | 20    |
      | Item two | 20.50   | 2        | 0     |
    When I generate an invoice
    Then the invoice should have the vat of 8.08


