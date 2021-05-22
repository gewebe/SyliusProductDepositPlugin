@shop_product_deposit
Feature: Adding a product with deposit price to the cart
    In order to use product deposit and calc the final price
    As a customer
    I want to add products with deposit price to my cart and see final price

    Background:
        Given the store operates on a single channel in "United States"
        And default tax zone is "US"
        And the store has a tax category "Returnable" with a code "returnable"
        And the store has "Returnable Bottle" tax rate of 15% for "Returnable" within the "US" zone
        And the store has a "Water Bottle" configurable product
        And the product "Water Bottle" has a "1 Liter" variant priced at "$4.00"
        And this variant has a deposit priced at "$0.50" in "United States" channel
        And the product "Water Bottle" has a "5 Liter" variant priced at "$19.00"
        And this variant has a deposit priced at "$1.00" in "United States" channel
        And this variant deposit is in the "Returnable" tax category
        And I view product "Water Bottle"

    @ui
    Scenario: Browse a product with deposit price
        When I select "1 Liter" variant
        Then the product deposit price should be "$0.50"

    @ui
    Scenario: Adding a product with deposit to the cart
        When I add "1 Liter" variant of this product to the cart
        Then I should be on my cart summary page
        And I should be notified that the product has been successfully added
        And there should be one item in my cart
        And this item should have name "Water Bottle"
        And this item should have variant "1 Liter"
        And I should see "Water Bottle" with unit price "$4.00" in my cart
        And I should see "Water Bottle" with deposit price "$0.50" in my cart
        And total price of "Water Bottle" item should be "$4.50"
        And my cart total should be "$4.50"
        And there should be no taxes charged

    @ui
    Scenario: Adding a product with deposit and tax to the cart
        When I add "5 Liter" variant of this product to the cart
        Then I should be on my cart summary page
        And I should be notified that the product has been successfully added
        And there should be one item in my cart
        And this item should have name "Water Bottle"
        And this item should have variant "5 Liter"
ad        And I should see "Water Bottle" with unit price "$19.00" in my cart
        And I should see "Water Bottle" with deposit price "$1.00" in my cart
        And total price of "Water Bottle" item should be "$20.15"
        And my cart total should be "$20.15"
        And my cart taxes should be "$0.15"
