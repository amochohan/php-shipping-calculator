<?php

namespace DrawMyAttention\ShippingCalculator;

class CostShippingModifier extends BaseShippingModifier implements ShippingModifierContract
{
    protected $cost;

    protected $minValue;

    protected $maxValue;

    public function __construct()
    {
        $this->minValue = Cost::fromFloat(0.0);
        $this->maxValue = Cost::fromFloat(0.0);
        $this->cost     = Cost::fromFloat(0.0);
    }

    public function isValidForBasket(Basket $basket)
    {
        return (! $this->isBasketTooCheap($basket) && ! $this->isBasketTooExpensive($basket));
    }

    /**
     * @param Basket $basket
     * @return bool
     */
    public function isBasketTooCheap(Basket $basket)
    {
        return $basket->subTotal()->float() < $this->minValue->float();
    }

    /**
     * @param Basket $basket
     * @return bool
     */
    public function isBasketTooExpensive(Basket $basket)
    {
        if ($this->hasNoMaxValueRequirement()) {
            return false;
        }
        return $basket->subTotal()->float() > $this->maxValue->float();
    }

    /**
     * @return bool
     */
    private function hasNoMaxValueRequirement()
    {
        return $this->maxValue->float() == 0.0;
    }

}
