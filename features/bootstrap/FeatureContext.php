<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext
{

    private $shippingOption;
    private $basket;

    public function __construct()
    {
        $this->shippingOption = new ShippingOption();
        $this->basket = new Basket();
    }

    /**
     * @Transform :aCost
     * @Transform :totalCost
     */
    public function transformStringToACost($string)
    {
        return Cost::fromFloat((float)$string);
    }

    /**
     * @Transform :shippingOption
     */
    public function transformStringToAShippingOption($string)
    {
        return ShippingOption::withNameAndFlatCost($string, Cost::fromFloat(10.0));
    }

    /**
     * @Given there is a shipping option called :name with a flat cost of :aCost
     */
    public function thereIsAShippingOptionCalledWithAFlatPriceOf($name, Cost $aCost)
    {
        $this->shippingOption = ShippingOption::withNameAndFlatCost($name, $aCost);
        $this->basket->addShippingOption($this->shippingOption);
    }

    /**
     * @When the customer applies the :shippingOption shipping option to the basket
     */
    public function theCustomerAppliesTheShippingOptionToTheBasket(ShippingOption $shippingOption)
    {
        $this->basket->applyShippingOption($shippingOption);
    }

    /**
     * @Then the shipping total should be :totalCost
     */
    public function theShippingTotalShouldBe(Cost $totalCost)
    {
        PHPUnit_Framework_Assert::assertEquals($totalCost, $this->shippingOption->totalCost());
    }

    /**
     * @When the basket contains goods with a total value of :totalCost
     */
    public function theBasketContainsGoodsWithATotalValueOf(Cost $totalCost)
    {
        $this->basket->setSubTotal($totalCost);
    }

    /**
     * @Then the :shippingOption shipping option can not be used
     */
    public function theShippingOptionCanNotBeUsed(ShippingOption $shippingOption)
    {
        $availableMethods = $this->basket->availableShippingMethods();

        PHPUnit_Framework_Assert::assertContainsOnlyInstancesOf('ShippingOption', $availableMethods);
        PHPUnit_Framework_Assert::assertNotContains($shippingOption, $availableMethods);
    }

    /**
     * @Given there is a shipping option called :name with a flat cost of :aCost available for orders under :totalCost
     */
    public function thereIsAShippingOptionCalledWithAFlatCostOfAvailableForOrdersUnder($name, Cost $aCost, Cost $totalCost)
    {
        $shippingOption = ShippingOption::withNameAndFlatCost($name, $aCost)->setMaximumGoodsCostAllowed($totalCost);
        $this->basket->addShippingOption($shippingOption);
    }

}
