<?php

namespace spec\DrawMyAttention\ShippingCalculator;

use DrawMyAttention\ShippingCalculator\Basket;
use DrawMyAttention\ShippingCalculator\Cost;
use DrawMyAttention\ShippingCalculator\Weight;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WeightShippingModifierSpec extends ObjectBehavior
{
    private $exampleCost;

    public function __construct()
    {
        $this->exampleCost = Cost::fromFloat(10.0);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DrawMyAttention\ShippingCalculator\WeightShippingModifier');
    }

    function it_implements_shipping_modifier_contract()
    {
        $this->shouldImplement('DrawMyAttention\ShippingCalculator\ShippingModifierContract');
    }

    function it_can_set_its_cost()
    {
        $this->setCost($this->exampleCost);
    }

    function it_can_return_its_cost()
    {
        $this->setCost($this->exampleCost);
        $this->cost()->shouldBeLike($this->exampleCost);
    }

    function it_should_have_no_cost_when_initialised()
    {
        $this->cost()->shouldBeLike(Cost::fromFloat(0.0));
    }

    function it_can_set_a_maximum_allowed_weight_value()
    {
        $this->setMaxValue(Weight::fromFloat(10.0));
    }

    function it_can_set_a_minimum_allowed_weight_value()
    {
        $this->setMinValue(Weight::fromFloat(5.0));
    }

    function it_can_determine_if_a_basket_is_too_heavy_to_use_this_modifier()
    {
        $basket = new Basket();
        $basket->setWeight(Weight::fromFloat(15.0));

        $this->setMinValue(Weight::fromFloat(10.0));
        $this->setMaxValue(Weight::fromFloat(20.0));

        $this->isBasketTooHeavy($basket)->shouldReturn(false);
        $basket->setWeight(Weight::fromFloat(5.0));
        $this->isBasketTooHeavy($basket)->shouldReturn(false);
        $basket->setWeight(Weight::fromFloat(21.0));
        $this->isBasketTooHeavy($basket)->shouldReturn(true);

        $basket->setWeight(Weight::fromFloat(20.0));
        $this->isBasketTooHeavy($basket)->shouldReturn(false);
        $basket->setWeight(Weight::fromFloat(10.0));
        $this->isBasketTooHeavy($basket)->shouldReturn(false);

    }

    function it_can_determine_if_a_basket_is_too_light_to_use_this_modifier()
    {
        $basket = new Basket();
        $basket->setWeight(Weight::fromFloat(15.0));

        $this->setMinValue(Weight::fromFloat(10.0));
        $this->setMaxValue(Weight::fromFloat(20.0));
        $this->isBasketTooLight($basket)->shouldReturn(false);

        $basket->setWeight(Weight::fromFloat(5.0));
        $this->isBasketTooLight($basket)->shouldReturn(true);
        $basket->setWeight(Weight::fromFloat(21.0));
        $this->isBasketTooLight($basket)->shouldReturn(false);

        $basket->setWeight(Weight::fromFloat(20.0));
        $this->isBasketTooLight($basket)->shouldReturn(false);
        $basket->setWeight(Weight::fromFloat(10.0));
        $this->isBasketTooLight($basket)->shouldReturn(false);

    }

    function it_can_determine_if_the_modifier_is_valid_for_a_basket_based_on_its_weight()
    {
        $basket = new Basket();
        $basket->setWeight(Weight::fromFloat(15.0));

        $this->setMinValue(Weight::fromFloat(10.0));
        $this->setMaxValue(Weight::fromFloat(20.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setWeight(Weight::fromFloat(5.0));
        $this->isValidForBasket($basket)->shouldReturn(false);
        $basket->setWeight(Weight::fromFloat(21.0));
        $this->isValidForBasket($basket)->shouldReturn(false);

        $basket->setWeight(Weight::fromFloat(20.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setWeight(Weight::fromFloat(10.0));
        $this->isValidForBasket($basket)->shouldReturn(true);

    }

    function it_is_valid_when_the_basket_weight_is_between_the_min_max_allowed_values_when_the_max_is_zero()
    {
        $basket = new Basket();
        $basket->setWeight(Weight::fromFloat(15.0));

        $this->setMinValue(Weight::fromFloat(10.0));
        $this->setMaxValue(Weight::fromFloat(0.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setWeight(Weight::fromFloat(5.0));
        $this->isValidForBasket($basket)->shouldReturn(false);
        $basket->setWeight(Weight::fromFloat(21.0));
        $this->isValidForBasket($basket)->shouldReturn(true);

        $basket->setWeight(Weight::fromFloat(20.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setWeight(Weight::fromFloat(10.0));
        $this->isValidForBasket($basket)->shouldReturn(true);

    }

}
