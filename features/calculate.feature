Feature: Calculate the total shipping cost of a basket
  In order to provide complete information required to gain confidence in making a purchase
  As a customer
  I need the total shipping cost to be calculated for my basket

  Rules:
   - The shipping cost can be a flat rate
   - The shipping cost can be hidden if the goods total is above or below a set value
   - The shipping cost can be hidden if the weight is above or below a set value
   - Shipping costs are determined by basket value, weight, country, and speed of delivery
   - The shopping cost can have a base price
   - The shipping cost can be a flat rate based on weight (eg £10 if weight < 1kg, £15 if weight < 2kg)
   - The shipping cost can be a flat rate based on by goods value (eg £10 if goods total < £10, £15 if weight < £20)
   - The shipping cost can be a flat rate based on goods quantity (eg £10 if quantity < 3, £5 if quantity < 6)
   - The shipping cost can be a flat rate based on speed of delivery
   - The shipping cost can be a variable rate modified by goods total
   - The shipping cost can be a variable rate modified by goods quantity
   - The shipping cost can be a variable rate modified by goods weight
   - Items can be shipped internationally

  Scenario: A flat rate shipping charge can be charged
    Given there is a shipping option called "Next day" with a flat cost of "£10"
    And there is a shipping option called "3-5 day" with a flat cost of "£4"
    When the customer applies the "Next day" shipping option to the basket
    Then the shipping total should be "£10"

  Scenario: A shipping cost can be hidden if the goods total is below £100
    Given there is a shipping option called "Next day" with a flat cost of "£10"
    And there is a shipping option called "Premium" with a flat cost of "£0"
    When the basket contains goods with a total value of "£80"
    Then the "Premium" shipping option can not be used

  Scenario: A shipping cost can be hidden if the goods total is above a threshold
    Given there is a shipping option called "Next day" with a flat cost of "£10" available for orders under "£80"
    And there is a shipping option called "Basic" with a flat cost of "£5"
    When the basket contains goods with a total value of "£81"
    Then the "Next day" shipping option can not be used