<?php

namespace spec\DrawMyAttention\ShippingCalculator;

use DrawMyAttention\ShippingCalculator\Basket;
use DrawMyAttention\ShippingCalculator\Cost;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CostShippingModifierSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('DrawMyAttention\ShippingCalculator\CostShippingModifier');
    }

    function it_implements_shipping_modifier_contract()
    {
        $this->shouldImplement('DrawMyAttention\ShippingCalculator\ShippingModifierContract');
    }

    function it_has_a_cost()
    {
        $this->setCost(Cost::fromFloat(10.0));
    }

    function it_has_a_min_value_when_it_becomes_applicable()
    {
        $this->setMinValue(10);
    }

    function it_has_a_max_value_when_it_becomes_applicable()
    {
        $this->setMaxValue(20);
    }

    function it_determines_if_a_basket_is_too_cheap_for_the_cost_modifier()
    {
        $basket = new Basket();
        $basket->setSubTotal(Cost::fromFloat(3.0));

        $this->setMinValue(Cost::fromFloat(5.0));
        $this->setMaxValue(Cost::fromFloat(20.0));

        $this->isBasketTooCheap($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(5.0));
        $this->isBasketTooCheap($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(6.0));
        $this->isBasketTooCheap($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(20.0));
        $this->isBasketTooCheap($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(21.0));
        $this->isBasketTooCheap($basket)->shouldReturn(false);

    }

    function it_determines_if_a_basket_is_too_expensive_for_the_cost_modifier()
    {
        $basket = new Basket();
        $basket->setSubTotal(Cost::fromFloat(3.0));

        $this->setMinValue(Cost::fromFloat(5.0));
        $this->setMaxValue(Cost::fromFloat(20.0));

        $this->isBasketTooExpensive($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(5.0));
        $this->isBasketTooExpensive($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(6.0));
        $this->isBasketTooExpensive($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(20.0));
        $this->isBasketTooExpensive($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(21.0));
        $this->isBasketTooExpensive($basket)->shouldReturn(true);
    }

    function it_determines_if_a_basket_subtotal_is_between_the_min_and_max_allowed_values()
    {
        $basket = new Basket();
        $basket->setSubTotal(Cost::fromFloat(3.0));

        $this->setMinValue(Cost::fromFloat(5.0));
        $this->setMaxValue(Cost::fromFloat(20.0));

        $this->isValidForBasket($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(5.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(6.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(20.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(21.0));
        $this->isValidForBasket($basket)->shouldReturn(false);
    }

    function it_is_valid_when_the_basket_subtotal_is_between_the_min_max_allowed_values_when_the_min_is_zero()
    {
        $basket = new Basket();
        $basket->setSubTotal(Cost::fromFloat(3.0));

        $this->setMinValue(Cost::fromFloat(0.0));
        $this->setMaxValue(Cost::fromFloat(20.0));

        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(5.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(6.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(20.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(21.0));
        $this->isValidForBasket($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(0.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
    }

    function it_is_valid_when_the_basket_subtotal_is_between_the_min_max_allowed_values_when_the_max_is_zero()
    {
        $basket = new Basket();
        $basket->setSubTotal(Cost::fromFloat(3.0));

        $this->setMinValue(Cost::fromFloat(5.0));
        $this->setMaxValue(Cost::fromFloat(0.0));

        $this->isValidForBasket($basket)->shouldReturn(false);
        $basket->setSubTotal(Cost::fromFloat(5.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(6.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(20.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(21.0));
        $this->isValidForBasket($basket)->shouldReturn(true);
        $basket->setSubTotal(Cost::fromFloat(0.0));
        $this->isValidForBasket($basket)->shouldReturn(false);

    }

}
