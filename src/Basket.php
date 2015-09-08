<?php

class Basket
{
    protected $shippingOption;

    private $subTotal;

    private $allShippingOptions = [];

    public function setSubTotal(Cost $subTotalCost)
    {
        $this->subTotal = $subTotalCost;
    }

    public function subTotal()
    {
        return $this->subTotal;
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

}
