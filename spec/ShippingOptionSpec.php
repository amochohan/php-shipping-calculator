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

    function it_gives_its_total_cost_for_a_basket()
    {
        $cost = \Cost::fromFloat(10.0);
        $basket = new \Basket();
        $basket->setSubTotal($cost);

        $this->totalCost($basket)->shouldBeLike($cost);
        $this->totalCost($basket)->shouldHaveType('Cost');
    }

    function it_gives_its_name()
    {
        $this->name()->shouldEqual('Next day');
    }

    function it_can_set_a_minimum_required_goods_cost_before_being_available()
    {
        $this->setMinimumGoodsCost(\Cost::fromFloat(100.0));
    }

    function it_can_check_if_a_cost_is_equal_or_greater_than_its_required_amount()
    {
        $this->setMinimumGoodsCost(\Cost::fromFloat(5.0));
        $this->isBasketTooCheap(\Cost::fromFloat(10.0))->shouldReturn(false);
        $this->isBasketTooCheap(\Cost::fromFloat(4.0))->shouldReturn(true);
        $this->isBasketTooCheap(\Cost::fromFloat(5.0))->shouldReturn(false);

        $this->setMinimumGoodsCost(\Cost::fromFloat(0.0));
        $this->isBasketTooCheap(\Cost::fromFloat(0.0))->shouldReturn(false);
    }

    function it_can_check_if_a_cost_is_equal_or_lower_than_its_the_maximum_amount_allowed_for_the_shipping_option()
    {
        $this->setMaximumGoodsCostAllowed(\Cost::fromFloat(50.0));
        $this->isBasketTooExpensive(\Cost::fromFloat(50.0))->shouldReturn(false);
        $this->isBasketTooExpensive(\Cost::fromFloat(10.0))->shouldReturn(false);
        $this->isBasketTooExpensive(\Cost::fromFloat(51.0))->shouldReturn(true);

        $this->setMaximumGoodsCostAllowed(\Cost::fromFloat(0.0));
        $this->isBasketTooExpensive(\Cost::fromFloat(0.0))->shouldReturn(false);
    }

    function it_is_enabled_when_the_basket_cost_is_more_than_or_equal_the_minimum_basket_cost()
    {
        $this->setMinimumGoodsCost(\Cost::fromFloat(100.0));

        $this->basket->setSubTotal(\Cost::fromFloat(99.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(false);

        $this->basket->setSubTotal(\Cost::fromFloat(101.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(true);

        $this->basket->setSubTotal(\Cost::fromFloat(100.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(true);
    }

    function it_is_enabled_when_the_basket_cost_is_less_than_or_equal_the_maximum_basket_cost_allowed()
    {
        $this->setMaximumGoodsCostAllowed(\Cost::fromFloat(100.0));

        $this->basket->setSubTotal(\Cost::fromFloat(99.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(true);

        $this->basket->setSubTotal(\Cost::fromFloat(101.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(false);

        $this->basket->setSubTotal(\Cost::fromFloat(100.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(true);
    }

    function it_is_enabled_if_the_basket_cost_is_equal_or_between_the_min_and_max_basket_cost_allowed()
    {
        $this->setMaximumGoodsCostAllowed(\Cost::fromFloat(100.0));
        $this->setMinimumGoodsCost(\Cost::fromFloat(50.0));

        $this->basket->setSubTotal(\Cost::fromFloat(100.01));
        $this->isAvailableToBasket($this->basket)->shouldReturn(false);
        $this->basket->setSubTotal(\Cost::fromFloat(49.99));
        $this->isAvailableToBasket($this->basket)->shouldReturn(false);

        $this->basket->setSubTotal(\Cost::fromFloat(50.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(true);
        $this->basket->setSubTotal(\Cost::fromFloat(100.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(true);

        $this->basket->setSubTotal(\Cost::fromFloat(75.00));
        $this->isAvailableToBasket($this->basket)->shouldReturn(true);
    }

    function it_can_determine_a_heavy_basket()
    {
        $this->basket->setWeight(\Weight::fromFloat(9.0));
        $this->setMaximumBasketWeight(\Weight::fromFloat(10.0));
        $this->isBasketTooHeavy($this->basket->weight())->shouldReturn(false);

        $this->basket->setWeight(\Weight::fromFloat(11.0));
        $this->isBasketTooHeavy($this->basket->weight())->shouldReturn(true);

        $this->basket->setWeight(\Weight::fromFloat(10.0));
        $this->isBasketTooHeavy($this->basket->weight())->shouldReturn(false);
    }

    function it_sets_a_maximum_goods_cost_before_becoming_unavailable()
    {
        $this->setMaximumGoodsCostAllowed(\Cost::fromFloat(100));
    }

    function it_can_set_a_maximum_basket_weight_before_becoming_unavailable()
    {
        $this->setMaximumBasketWeight(\Weight::fromFloat(50.0));
    }

    function it_can_add_a_cost_modifier()
    {
        $priceModifier = new \CostShippingModifier();

        $priceModifier->setCost(\Cost::fromFloat(2.0));
        $priceModifier->setMinValue(\Cost::fromFloat(10.0));
        $priceModifier->setMaxValue(\Cost::fromFloat(20.0));

        $this->addModifier($priceModifier);
    }


}
