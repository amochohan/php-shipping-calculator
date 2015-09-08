<?php

class ShippingOption
{
    private $name;

    private $cost;

    private $minimumGoodsCostRequired;

    public static function withNameAndFlatCost($name, Cost $flatCost)
    {
        $shippingOption = new ShippingOption();

        $shippingOption->name = $name;

        $shippingOption->cost = $flatCost;

        return $shippingOption;
    }

    public function totalCost()
    {
        return $this->cost;
    }

    public function name()
    {
        return $this->name;
    }

    public function setMinimumGoodsCost(Cost $theMinimumCost)
    {
        $this->minimumGoodsCostRequired = $theMinimumCost;
        return $this;
    }

    public function isCostGreaterThanRequiredGoodsCost(Cost $currentCost)
    {
        return $currentCost->float() >= $this->minimumGoodsCostRequired->float();
    }

    private function hasMinimumCostRequirement()
    {
        return isset ($this->minimumGoodsCostRequired);
    }

    public function isAvailableToBasket(Basket $basket)
    {
        if (! $this->hasMinimumCostRequirement()) {
            return true;
        }
        return $this->isCostGreaterThanRequiredGoodsCost($basket->subTotal());
    }
}
