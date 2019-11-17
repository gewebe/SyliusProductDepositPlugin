@managing_product_deposit
Feature: Adding a product with deposit price to the cart
    In order to use product deposit and calc the final price
    As a customer
    I want to add products with deposit price to my cart and see final price

    Background:
        Given the store operates on a single channel in "United States"
        And default tax zone is "US"
        And the store has a tax category "Returnable bottle" with a code "returnable"
        And the store has a "Water Bottle" configurable product
        And the product "Water Bottle" has a "1 Liter" variant priced at "$4.00"
        And this variant has a deposit priced at "$0.50" in "United States" channel
        And the product "Water Bottle" has a "5 Liter" variant priced at "$19.00"
        And this variant has a deposit priced at "$0.75" in "United States" channel

    @ui
    Scenario: Adding a product with deposit to the cart
        Given I view product "Water Bottle"
        And I select "1 Lite" variant
        And the product deposit price should be "$0.50"
        When I add "1 Liter" variant of this product to the cart
        Then I should be on my cart summary page
        And I should be notified that the product has been successfully added
        And there should be one item in my cart
        And this item should have name "Water Bottle"
        And this item should have variant "1 Liter"
        And I should see "Water Bottle" with unit price "$4.50" in my cart
