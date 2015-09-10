<?php

namespace spec\DrawMyAttention\ShippingCalculator;

use DrawMyAttention\ShippingCalculator\Basket;
use DrawMyAttention\ShippingCalculator\Product;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ProductQuantityShippingModifierSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('DrawMyAttention\ShippingCalculator\ProductQuantityShippingModifier');
    }

    function it_implements_shipping_modifier_contract()
    {
        $this->shouldImplement('DrawMyAttention\ShippingCalculator\ShippingModifierContract');
    }

    function it_can_determine_if_a_basket_has_too_many_products_in_it_to_use_this_modifier()
    {
        $basket = new Basket();
        $basket->addProductWithQuantity(new Product(), 4);

        $this->setMinValue(3);
        $this->setMaxValue(6);

        $this->isBasketTooFull($basket)->shouldReturn(false);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 7);
        $this->isBasketTooFull($basket)->shouldReturn(true);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 6);
        $this->isBasketTooFull($basket)->shouldReturn(false);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 3);
        $this->isBasketTooFull($basket)->shouldReturn(false);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 1);
        $this->isBasketTooFull($basket)->shouldReturn(false);
    }

    function it_can_determine_if_a_basket_has_too_few_products_in_it_to_use_this_modifier()
    {
        $basket = new Basket();
        $basket->addProductWithQuantity(new Product(), 4);

        $this->setMinValue(3);
        $this->setMaxValue(6);

        $this->isBasketContentTooSmall($basket)->shouldReturn(false);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 7);
        $this->isBasketContentTooSmall($basket)->shouldReturn(false);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 6);
        $this->isBasketContentTooSmall($basket)->shouldReturn(false);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 3);
        $this->isBasketContentTooSmall($basket)->shouldReturn(false);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 1);
        $this->isBasketContentTooSmall($basket)->shouldReturn(true);
    }

    function it_can_determine_if_the_modifier_is_valid_for_a_basket_based_on_the_total_products_it_holds()
    {
        $basket = new Basket();
        $basket->addProductWithQuantity(new Product(), 4);

        $this->setMinValue(3);
        $this->setMaxValue(6);

        $this->isValidForBasket($basket)->shouldReturn(true);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 7);
        $this->isValidForBasket($basket)->shouldReturn(false);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 6);
        $this->isValidForBasket($basket)->shouldReturn(true);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 3);
        $this->isValidForBasket($basket)->shouldReturn(true);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 1);
        $this->isValidForBasket($basket)->shouldReturn(false);

    }

    function it_is_valid_when_the_basket_product_count_is_between_the_min_max_allowed_values_when_the_max_is_zero()
    {
        $basket = new Basket();
        $basket->addProductWithQuantity(new Product(), 4);

        $this->setMinValue(3);
        $this->setMaxValue(0);

        $this->isValidForBasket($basket)->shouldReturn(true);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 7);
        $this->isValidForBasket($basket)->shouldReturn(true);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 3);
        $this->isValidForBasket($basket)->shouldReturn(true);

        $basket->removeAllProducts();
        $basket->addProductWithQuantity(new Product(), 1);
        $this->isValidForBasket($basket)->shouldReturn(false);
    }

}
