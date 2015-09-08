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

    function it_returns_shipping_methods_available_to_the_customer_based_on_their_basket()
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
}
