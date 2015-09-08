<?php

class ShippingOption
{
    private $name;

    private $cost;

    private $minimumGoodsCostRequired;
    private $maximumGoodsCostAllowed;

    public static function withNameAndFlatCost($name, Cost $flatCost)
    {
        $shippingOption = new ShippingOption();

        $shippingOption->name = $name;

        $shippingOption->cost = $flatCost;

        $shippingOption->minimumGoodsCostRequired = \Cost::fromFloat(0.0);
        $shippingOption->maximumGoodsCostAllowed = \Cost::fromFloat(0.0);

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
        if ($this->minimumGoodsCostRequired->float() == 0) {
            return true;
        }
        return $currentCost->float() >= $this->minimumGoodsCostRequired->float();
    }

    public function isCostLessThanMaxmimumAllowedGoodsCost(Cost $currentCost)
    {
        if ($this->maximumGoodsCostAllowed->float() == 0) {
            return true;
        }
        return $currentCost->float() <= $this->maximumGoodsCostAllowed->float();
    }

    public function isAvailableToBasket(Basket $basket)
    {
        return ($this->isCostGreaterThanRequiredGoodsCost($basket->subTotal()) &&
            $this->isCostLessThanMaxmimumAllowedGoodsCost($basket->subTotal()) );
    }

    public function setMaximumGoodsCostAllowed(Cost $theMaximumCost)
    {
        $this->maximumGoodsCostAllowed = $theMaximumCost;
        return $this;
    }
}
