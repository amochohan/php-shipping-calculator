<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ShippingOptionSpec extends ObjectBehavior
{
    private $basket;

    public function __construct()
    {
        $this->basket = new \Basket();
    }

    function let()
    {
        $this->beConstructedThrough('withNameAndFlatCost', ['Next day', \Cost::fromFloat(10.0)]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('ShippingOption');
    }

    function it_gives_its_total_cost()
    {
        $this->totalCost()->shouldBeLike(\Cost::fromFloat(10.0));
        $this->totalCost()->shouldHaveType('Cost');
    }

    function it_gives_its_name()
    {
        $this->name()->shouldEqual('Next day');
    }

    function it_can_set_a_minimum_required_goods_cost_before_being_available()
    {
        $this->setMinimumGoodsCost(\Cost::fromFloat(100.0));
    }

    function it_is_disabled_when_the_basket_cost_is_less_than_the_required_cost()
    {
        $this->setMinimumGoodsCost(\Cost::fromFloat(100.0));
        $this->basket->setSubTotal(\Cost::fromFloat(99.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(false);
    }

    function it_is_always_available_if_a_minimum_required_cost_isnt_set()
    {
        $this->basket->setSubTotal(\Cost::fromFloat(0.0));
        $this->isAvailableToBasket($this->basket)->shouldReturn(true);
    }

}
