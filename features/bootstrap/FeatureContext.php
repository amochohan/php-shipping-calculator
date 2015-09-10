<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use DrawMyAttention\ShippingCalculator\Basket;
use DrawMyAttention\ShippingCalculator\Cost;
use DrawMyAttention\ShippingCalculator\CostShippingModifier;
use DrawMyAttention\ShippingCalculator\Product;
use DrawMyAttention\ShippingCalculator\ProductQuantityShippingModifier;
use DrawMyAttention\ShippingCalculator\ShippingOption;
use DrawMyAttention\ShippingCalculator\Weight;
use DrawMyAttention\ShippingCalculator\WeightCostMultiplier;
use DrawMyAttention\ShippingCalculator\WeightShippingModifier;

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
        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $this->basket->applyShippingOption($option);
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

        PHPUnit_Framework_Assert::assertContainsOnlyInstancesOf('DrawMyAttention\ShippingCalculator\ShippingOption', $availableMethods);
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
     * @Given the :shippingOption shipping option is only available for orders weighing over :aWeight
     */
    public function theShippingOptionIsOnlyAvailableForOrdersWeighingOver(ShippingOption $shippingOption, Weight $aWeight)
    {
        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->setMinimumBasketWeight($aWeight);
        $this->basket->applyShippingOption($option);
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
        $modifier = new CostShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMaxValue($totalCost);

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders between £:minCost and £:maxCost
     */
    public function theShippingOptionCostsForOrdersBetweenAnd(ShippingOption $shippingOption, Cost $aCost, Cost $minCost, Cost $maxCost)
    {
        $modifier = new CostShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMinValue($minCost);
        $modifier->setMaxValue($maxCost);

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders more than £:totalCost
     */
    public function theShippingOptionCostsForOrdersMoreThan(ShippingOption $shippingOption, Cost $aCost, Cost $totalCost)
    {
        $modifier = new CostShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMinValue($totalCost);

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
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
        $modifier = new WeightShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMaxValue($aWeight);

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders weighing between :minWeight and :maxWeight
     */
    public function theShippingOptionCostsPsForOrdersWeighingBetweenKgAndKg(ShippingOption $shippingOption, Cost $aCost, Weight $minWeight, Weight $maxWeight)
    {
        $modifier = new WeightShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMinValue($minWeight);
        $modifier->setMaxValue($maxWeight);

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders weighing more than :aWeight
     */
    public function theShippingOptionCostsPsForOrdersWeighingMoreThanKg(ShippingOption $shippingOption, Cost $aCost, Weight $aWeight)
    {
        $modifier = new WeightShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMinValue($aWeight);

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
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

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
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

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
    }

    /**
     * @Given the :shippingOption shipping option costs £:aCost for orders containing more than :quantity products
     */
    public function theShippingOptionCostsPsForOrdersContainingMoreThanProducts(ShippingOption $shippingOption, Cost $aCost, $quantity)
    {
        $modifier = new ProductQuantityShippingModifier();
        $modifier->setCost($aCost);
        $modifier->setMinValue($quantity);

        $option = $this->basket->getShippingOptionByName($shippingOption->name());
        $option->addModifier($modifier);
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

    /**
     * @Then the :shippingOption shipping option can be used
     */
    public function theShippingOptionCanBeUsed(ShippingOption $shippingOption)
    {
        PHPUnit_Framework_Assert::assertContainsOnlyInstancesOf('DrawMyAttention\ShippingCalculator\ShippingOption', $this->basket->availableShippingMethods());
        PHPUnit_Framework_Assert::assertTrue($this->assertArrayContainsSameOptionByName($this->basket->availableShippingMethods(), $shippingOption->name()));
    }

    private function assertArrayContainsSameOptionByName($theArray, $optionName)
    {
        foreach($theArray as $arrayItem) {
            if($arrayItem->name() == $optionName) {
                return true;
            }
        }
        return false;
    }

}
