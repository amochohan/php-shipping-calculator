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
    private $costShippingModifier ;

    public function __construct()
    {
        $this->shippingOption = new ShippingOption();
        $this->basket = new Basket();
        $this->costShippingModifier = new CostShippingModifier();
    }

    /**
     * @Transform :aCost
     * @Transform :totalCost
     * @Transform :minCost
     * @Transform :maxCost
     */
    public function transformStringToACost($string)
    {
        return Cost::fromFloat((float)$string);
    }

    /**
     * @Transform :weightCostMultiplier
     */
    public function transformStringToAWeightCostMultiplier($string)
    {
        return WeightCostMultiplier::fromFloat((float)$string);
    }

    /**
     * @Transform :aWeight
     * @Transform :minWeight
     * @Transform :maxWeight
     */
    public function transformStringToAWeight($string)
    {
        return Weight::fromFloat((float)$string);
    }

    /**
     * @Transform :shippingOption
     */
    public function transformStringToAShippingOption($string)
    {
        return ShippingOption::withNameAndFlatCost($string, Cost::fromFloat(0.0));
    }

    /**
     * @Given there is a shipping option called :name with a flat cost of £:aCost
     */
    public function thereIsAShippingOptionCalledWithAFlatPriceOf($name, Cost $aCost)
    {
        $shippingOption = ShippingOption::withNameAndFlatCost($name, $aCost);
        $this->basket->addShippingOption($shippingOption);
    }

    /**
     * @When the customer applies the :shippingOption shipping option to the basket
     */
    public function theCustomerAppliesTheShippingOptionToTheBasket(ShippingOption $shippingOption)
    {
        array_map(function($option) use ($shippingOption) {
            if($option->name() == $shippingOption->name()) {
                $this->basket->applyShippingOption($option);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @Then the shipping total should be £:totalCost
     */
    public function theShippingTotalShouldBe(Cost $totalCost)
    {
        PHPUnit_Framework_Assert::assertEquals($totalCost->float(), $this->basket->shippingCost()->float());
    }

    /**
     * @When the basket contains goods with a total value of £:totalCost
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
     * @Given there is a shipping option called :name with a flat cost of £:aCost available for orders under £:totalCost
     */
    public function thereIsAShippingOptionCalledWithAFlatCostOfAvailableForOrdersUnder($name, Cost $aCost, Cost $totalCost)
    {
        $shippingOption = ShippingOption::withNameAndFlatCost($name, $aCost)->setMaximumGoodsCostAllowed($totalCost);
        $this->basket->addShippingOption($shippingOption);
    }

    /**
     * @Given the :shippingOption shipping option is only available for orders weighing under :aWeight
     */
    public function theShippingOptionIsOnlyAvailableForOrdersWeighingUnder(ShippingOption $shippingOption, Weight $aWeight)
    {
        $shippingOption->setMaximumBasketWeight($aWeight);
    }

    /**
     * @When the basket contains goods with a total weight of :aWeight:kg
     */
    public function theBasketContainsGoodsWithATotalWeightOf(Weight $aWeight)
    {
        $this->basket->setWeight($aWeight);
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders less than £:totalCost
     */
    public function theShippingOptionCostsForOrdersLessThan(ShippingOption $shippingOption, Cost $aCost, $totalCost)
    {
        $costShippingModifier = new CostShippingModifier();
        $costShippingModifier->setCost($aCost);
        $costShippingModifier->setMaxValue($totalCost);

        array_map(function($option) use ($shippingOption, $costShippingModifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($costShippingModifier);
            }
        }, $this->basket->allShippingOptions());

    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders between £:minCost and £:maxCost
     */
    public function theShippingOptionCostsForOrdersBetweenAnd(ShippingOption $shippingOption, Cost $aCost, Cost $minCost, Cost $maxCost)
    {
        $costShippingModifier = new CostShippingModifier();
        $costShippingModifier->setCost($aCost);
        $costShippingModifier->setMinValue($minCost);
        $costShippingModifier->setMaxValue($maxCost);

        array_map(function($option) use ($shippingOption, $costShippingModifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($costShippingModifier);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders more than £:totalCost
     */
    public function theShippingOptionCostsForOrdersMoreThan(ShippingOption $shippingOption, Cost $aCost, Cost $totalCost)
    {
        $costShippingModifier = new CostShippingModifier();
        $costShippingModifier->setCost($aCost);
        $costShippingModifier->setMinValue($totalCost);

        array_map(function($option) use ($shippingOption, $costShippingModifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($costShippingModifier);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @When the basket contains goods with a value of £:totalCost
     */
    public function theBasketContainsGoodsWithAValueOf(Cost $totalCost)
    {
        $this->basket->setSubTotal($totalCost);
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders weighing under :aWeight
     */
    public function theShippingOptionCostsPsForOrdersWeighingUnderKg(ShippingOption $shippingOption, Cost $aCost, Weight $aWeight)
    {
        $shippingModifier = new WeightShippingModifier();
        $shippingModifier->setCost($aCost);
        $shippingModifier->setMaxValue($aWeight);

        array_map(function($option) use ($shippingOption, $shippingModifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($shippingModifier);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders weighing between :minWeight and :maxWeight
     */
    public function theShippingOptionCostsPsForOrdersWeighingBetweenKgAndKg(ShippingOption $shippingOption, Cost $aCost, Weight $minWeight, Weight $maxWeight)
    {
        $shippingModifier = new WeightShippingModifier();
        $shippingModifier->setCost($aCost);
        $shippingModifier->setMinValue($minWeight);
        $shippingModifier->setMaxValue($maxWeight);

        array_map(function($option) use ($shippingOption, $shippingModifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($shippingModifier);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders weighing more than :aWeight
     */
    public function theShippingOptionCostsPsForOrdersWeighingMoreThanKg(ShippingOption $shippingOption, Cost $aCost, Weight $aWeight)
    {
        $shippingModifier = new WeightShippingModifier();
        $shippingModifier->setCost($aCost);
        $shippingModifier->setMinValue($aWeight);

        array_map(function($option) use ($shippingOption, $shippingModifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($shippingModifier);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @When the basket contains goods that weigh :aWeight
     */
    public function theBasketContainsGoodsThatWeighKg(Weight $aWeight)
    {
        $this->basket->setWeight($aWeight);
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders containing under :quantity products
     */
    public function theShippingOptionCostsPsForOrdersContainingUnderProducts(ShippingOption $shippingOption, $aCost, $quantity)
    {
        $modifier = new ProductQuantityShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMaxValue($quantity);

        array_map(function($option) use ($shippingOption, $modifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($modifier);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders containing between :minQuantity and :maxQuantity products
     */
    public function theShippingOptionCostsPsForOrdersContainingBetweenAndProducts(ShippingOption $shippingOption, Cost $aCost, $minQuantity, $maxQuantity)
    {
        $modifier = new ProductQuantityShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMinValue($minQuantity);
        $modifier->setMaxValue($maxQuantity);

        array_map(function($option) use ($shippingOption, $modifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($modifier);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders containing more than :quantity products
     */
    public function theShippingOptionCostsPsForOrdersContainingMoreThanProducts(ShippingOption $shippingOption, Cost $aCost, $quantity)
    {
        $modifier = new ProductQuantityShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMinValue($quantity);

        array_map(function($option) use ($shippingOption, $modifier) {
            if($option->name() == $shippingOption->name()) {
                $option->addModifier($modifier);
            }
        }, $this->basket->allShippingOptions());
    }

    /**
     * @When the basket contains :quantity products
     */
    public function theBasketContainsProducts($quantity)
    {
        $this->basket->addProductWithQuantity(new Product(), $quantity);

    }

    /**
     * @Given there is a shipping option called :name with a base cost of £:aCost and a weight multiplier cost of £:weightCostMultiplier
     */
    public function thereIsAShippingOptionCalledWithABaseCostOfPsAndAWeightMultiplierCostOfPs($name, Cost $aCost, WeightCostMultiplier $weightCostMultiplier)
    {
        $shippingOption = ShippingOption::withNameAndFlatCost($name, $aCost);
        $shippingOption->setMultiplier($weightCostMultiplier);
        $this->basket->addShippingOption($shippingOption);
    }

}
