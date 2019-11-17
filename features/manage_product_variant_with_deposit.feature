@managing_product_deposit
Feature: Adding deposit price to a product variant
    In order to sell product variants with deposit price
    As an Administrator
    I want to be able to add a deposit price to product variants

    Background:
        Given the store operates on a single channel in "United States"
        And the store has a product "Water Bottle"
        And the store has a tax category "Returnable bottle" with a code "returnable"
        And the product "Water Bottle" has a "1 Liter" variant priced at "$4.00"
        And I am logged in as an administrator

    @ui
    Scenario: Adding deposit to a product variant
        When I want to modify the "1 Liter" product variant
        And I set the deposit price to "$0.50" for "United States" channel
        And I set the deposit tax category to "Returnable bottle"
        And I save my changes
        Then I should be notified that it has been successfully edited
        And the variant with code "1_Liter" should have a deposit price with "$0.50" for channel "United States"
        And this variant should have a deposit tax category code "returnable"
