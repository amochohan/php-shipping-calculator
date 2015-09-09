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
    Given there is a shipping option called "Next day" with a flat cost of £10
    And there is a shipping option called "Standard delivery" with a flat cost of £4
    When the customer applies the "Next day" shipping option to the basket
    Then the shipping total should be £10

  Scenario: A shipping option can be hidden if the goods total is below £100
    Given there is a shipping option called "Next day" with a flat cost of £10
    And there is a shipping option called "Standard delivery" with a flat cost of £0
    When the basket contains goods with a total value of £80
    Then the "Standard delivery" shipping option can not be used

  Scenario: A shipping option can be hidden if the goods total is above a threshold
    Given there is a shipping option called "Next day" with a flat cost of £10 available for orders under £80
    And there is a shipping option called "Standard delivery" with a flat cost of £4
    When the basket contains goods with a total value of £81
    Then the "Next day" shipping option can not be used

  Scenario: A shipping option can be hidden if the basket weight is below a threshold
    Given there is a shipping option called "Next day (light)" with a flat cost of £10
    And the "Next day (light)" shipping option is only available for orders weighing under 10kg
    And there is a shipping option called "Next day (heavy)" with a flat cost of £10
    And the "Next day (heavy)" shipping option is only available for orders weighing under 30kg
    When the basket contains goods with a total weight of 15kg
    Then the "Next day (light)" shipping option can not be used

  Scenario: A shipping option can have a sliding price scale based on the basket total
    Given there is a shipping option called "Standard delivery" with a flat cost of £0
    And the "Standard delivery" shipping option costs £50 for orders less than £30
    And the "Standard delivery" shipping option costs £5 for orders between £30 and £50
    And the "Standard delivery" shipping option costs £0 for orders more than £50
    When the basket contains goods with a value of £45
    And the customer applies the "Standard delivery" shipping option to the basket
    Then the shipping total should be £5
