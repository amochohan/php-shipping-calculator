<?php

namespace spec\DrawMyAttention\ShippingCalculator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('DrawMyAttention\ShippingCalculator\Product');
    }
}
