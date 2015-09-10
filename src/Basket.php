<?php

class Basket
{
    private $shippingOption;

    private $subTotal;

    private $weight;

    private $allShippingOptions = [];

    private $products = [];

    public function __construct()
    {
        $this->subTotal = \Cost::fromFloat(0.0);
        $this->weight = \Weight::fromFloat(0.0);
    }

    public function subTotal()
    {
        return $this->subTotal;
    }

    public function setSubTotal(Cost $subTotalCost)
    {
        $this->subTotal = $subTotalCost;
        return $this;
    }

    public function weight()
    {
        return $this->weight;
    }

    public function setWeight(Weight $weight)
    {
        $this->weight = $weight;
    }

    public function allShippingOptions()
    {
        return $this->allShippingOptions;
    }

    public function addShippingOption(ShippingOption $shippingOption)
    {
        $this->allShippingOptions[] = $shippingOption;
        return $this;
    }

    public function appliedShippingOption()
    {
        return $this->shippingOption;
    }

    public function applyShippingOption(ShippingOption $shippingOption)
    {
        $this->shippingOption = $shippingOption;
        return $this;
    }

    public function availableShippingMethods()
    {
        return array_filter($this->allShippingOptions, function($option) {
            if ($option->isAvailableToBasket($this)) {
                return $option;
            }
        });
    }

    public function shippingCost()
    {
        return $this->shippingOption->totalCost($this);
    }

    public function addProductWithQuantity(Product $product, $quantity)
    {
        for($i = 1; $i <= $quantity; $i++) {
            $this->products[] = $product;
        }
        return $this;
    }

    public function noOfProducts()
    {
        return count($this->products);
    }

    public function removeAllProducts()
    {
        $this->products = [];
    }

    public function getShippingOptionByName($name)
    {
        foreach($this->allShippingOptions as $option) {
            if ($option->isCalled($name)) {
                return $option;
            }
        }
        return null;
    }

}
