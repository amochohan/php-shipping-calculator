<?php

namespace spec\DrawMyAttention\ShippingCalculator;

use DrawMyAttention\ShippingCalculator\Basket;
use DrawMyAttention\ShippingCalculator\Weight;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WeightCostMultiplierSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromFloat', [0.06]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DrawMyAttention\ShippingCalculator\WeightCostMultiplier');
    }

    function it_can_calculate_the_total_multiplier_cost_for_a_basket_based_on_the_basket_weight()
    {
        $basket = new Basket();
        $basket->setWeight(Weight::fromFloat(55.0));
        $this->multipliedCost($basket)->float()->shouldReturn(3.3);
        $this->multipliedCost($basket)->shouldReturnAnInstanceOf('DrawMyAttention\ShippingCalculator\Cost');
    }
}
