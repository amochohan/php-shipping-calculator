<?php

namespace spec\DrawMyAttention\ShippingCalculator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WeightSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromFloat', [0.0]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('DrawMyAttention\ShippingCalculator\Weight');
    }

    function it_returns_a_float_value()
    {
        $this->float()->shouldEqual(0.0);
        $this->float()->shouldNotEqual(0);
    }
}
