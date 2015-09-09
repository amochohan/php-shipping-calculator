<?php

class Basket
{
    public $shippingOption;

    private $subTotal;

    private $weight;

    private $allShippingOptions = [];

    public function __construct()
    {
        $this->subTotal = \Cost::fromFloat(0.0);
        $this->weight = \Weight::fromFloat(0.0);
    }

    public function setSubTotal(Cost $subTotalCost)
    {
        $this->subTotal = $subTotalCost;
    }

    public function subTotal()
    {
        return $this->subTotal;
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
    }

    public function applyShippingOption(ShippingOption $shippingOption)
    {
        $this->shippingOption = $shippingOption;
    }

    public function availableShippingMethods()
    {
        $availableMethods = [];

        foreach($this->allShippingOptions as $shippingOption) {
            if($shippingOption->isAvailableToBasket($this)) {
                $availableMethods[] = $shippingOption;
            }
        }

        return $availableMethods;
    }

    public function shippingCost()
    {
        return $this->shippingOption->totalCost($this);
    }
}
