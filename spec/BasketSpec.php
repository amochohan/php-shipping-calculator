<?php

namespace spec;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class BasketSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Basket');
    }

    function it_has_a_zero_subtotal_when_constructed()
    {
        $this->subTotal()->shouldBeLike(\Cost::fromFloat(0.0));
    }

    function it_has_a_zero_weight_when_constructed()
    {
        $this->subTotal()->shouldBeLike(\Cost::fromFloat(0.0));
    }

    function it_can_have_a_shipping_option_applied()
    {
        $this->applyShippingOption(\ShippingOption::withNameAndFlatCost('Next day', \Cost::fromFloat(10.0)));
    }

    function it_can_set_its_subtotal()
    {
        $this->setSubTotal(\Cost::fromFloat(80.0));
    }

    function it_can_get_its_subtotal()
    {
        $this->setSubTotal(\Cost::fromFloat(12.34));
        $this->subTotal()->shouldBeLike(\Cost::fromFloat(12.34));
    }

    function it_can_add_a_new_shipping_option_to_an_array_of_available_shipping_options()
    {
        $this->addShippingOption(\ShippingOption::withNameAndFlatCost('Next day', \Cost::fromFloat(10.0)));
    }

    function it_returns_all_shipping_methods_that_have_been_made_available()
    {
        $this->addShippingOption(\ShippingOption::withNameAndFlatCost('Next day', \Cost::fromFloat(10.0)));
        $this->addShippingOption(\ShippingOption::withNameAndFlatCost('3-5 day', \Cost::fromFloat(3.0)));
        $this->allShippingOptions()->shouldBeArray();
        $this->allShippingOptions()->shouldHaveCount(2);
        $this->allShippingOptions()[0]->shouldBeAnInstanceOf('ShippingOption');
        $this->allShippingOptions()[1]->shouldBeAnInstanceOf('ShippingOption');
        $this->allShippingOptions()[0]->name()->shouldEqual('Next day');
        $this->allShippingOptions()[1]->name()->shouldEqual('3-5 day');
    }

    function it_returns_shipping_methods_available_to_the_customer_based_on_their_basket_cost()
    {
        $this->setSubTotal(\Cost::fromFloat(99.00));

        $this->addShippingOption(
            (\ShippingOption::withNameAndFlatCost('Next day', \Cost::fromFloat(10.0))->setMinimumGoodsCost(\Cost::fromFloat(100.0)))
        );

        $this->addShippingOption(
            (\ShippingOption::withNameAndFlatCost('3-5 day', \Cost::fromFloat(3.0))->setMinimumGoodsCost(\Cost::fromFloat(80.0)))
        );

        $this->availableShippingMethods()->shouldBeArray();
        $this->availableShippingMethods()->shouldHaveCount(1);

    }

    function it_can_set_its_weight()
    {
        $this->setWeight(\Weight::fromFloat(10.0));
    }

    function it_can_get_its_weight()
    {
        $this->setWeight(\Weight::fromFloat(100.0));
        $this->weight()->shouldBeLike(\Weight::fromFloat(100.0));
    }

    function it_returns_shipping_methods_available_to_the_customer_based_on_their_basket_weight()
    {
        $this->setWeight(\Weight::fromFloat(15.00));

        $heavyShippingOption = (\ShippingOption::withNameAndFlatCost('Heavy items', \Cost::fromFloat(10.0))->setMaximumBasketWeight(\Weight::fromFloat(30.0)));
        $mediumShippingOption = (\ShippingOption::withNameAndFlatCost('Medium weight items', \Cost::fromFloat(8.0))->setMaximumBasketWeight(\Weight::fromFloat(20.0)));
        $lightShippingOption = \ShippingOption::withNameAndFlatCost('Light items', \Cost::fromFloat(6.0))->setMaximumBasketWeight(\Weight::fromFloat(10.0));

        $this->addShippingOption($heavyShippingOption);
        $this->addShippingOption($mediumShippingOption);
        $this->addShippingOption($lightShippingOption);

        $this->availableShippingMethods()->shouldBeArray();
        $this->availableShippingMethods()->shouldHaveCount(2);
        $this->availableShippingMethods()->shouldContain($heavyShippingOption);
        $this->availableShippingMethods()->shouldContain($mediumShippingOption);
        $this->availableShippingMethods()->shouldNotContain($lightShippingOption);

    }

    function it_calculates_the_shipping_cost()
    {
        $cost = \Cost::fromFloat(10.0);
        $shippingOption = \ShippingOption::withNameAndFlatCost('Standard delivery', $cost);
        $this->addShippingOption($shippingOption);
        $this->applyShippingOption($shippingOption);
        $this->shippingCost()->shouldBeLike($cost);
    }
}
