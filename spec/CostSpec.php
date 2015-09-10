<?php

namespace spec\DrawMyAttention\ShippingCalculator;

use DrawMyAttention\ShippingCalculator\Cost;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CostSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromFloat', [0.0]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DrawMyAttention\ShippingCalculator\Cost');
    }

    function it_returns_a_float_value()
    {
        $this->float()->shouldEqual(0.0);
        $this->float()->shouldNotEqual(0);
    }

    function it_checks_if_a_value_equals_the_current_cost()
    {
        $this->equals(Cost::fromFloat(0.0))->shouldReturn(true);
        $this->equals(Cost::fromFloat(10.0))->shouldReturn(false);
    }

}
