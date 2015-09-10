<?php

namespace DrawMyAttention\ShippingCalculator;

class ProductQuantityShippingModifier extends BaseShippingModifier implements ShippingModifierContract
{
    protected $cost;

    protected $minValue;
    protected $maxValue;

    public function __construct()
    {
        $this->cost = Cost::fromFloat(0.0);
        $this->minValue = 0;
        $this->maxValue = 0;
    }

    /**
     * Return whether the modifier can be used for the given Basket.
     *
     * Usually, this is a simple check to ensure that the minimum
     * and maximum values have not been exceeded. Additionally
     * the rules of whether to account for inclusive values
     * or exclusive can be set by each implementation.
     *
     * @param Basket $basket
     * @return mixed
     */
    public function isValidForBasket(Basket $basket)
    {
        return (! $this->isBasketContentTooSmall($basket) && ! $this->isBasketTooFull($basket));
    }

    public function isBasketTooFull(Basket $basket)
    {
        if ($this->hasNoMaxProductQuantityLimitation()) {
            return false;
        }
        return $basket->noOfProducts() > $this->maxValue;
    }

    public function isBasketContentTooSmall(Basket $basket)
    {
        return $basket->noOfProducts() < $this->minValue;
    }

    private function hasNoMaxProductQuantityLimitation()
    {
        return $this->maxValue == 0;
    }

}
