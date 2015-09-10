<?php

namespace DrawMyAttention\ShippingCalculator;

class WeightShippingModifier extends BaseShippingModifier implements ShippingModifierContract
{
    protected $cost;

    protected $minValue;
    protected $maxValue;

    public function __construct()
    {
        $this->cost = Cost::fromFloat(0.0);
        $this->minValue = Weight::fromFloat(0.0);
        $this->maxValue = Weight::fromFloat(0.0);
    }

    public function isBasketTooHeavy(Basket $basket)
    {
        if ($this->hasNoMaxWeightLimitation()) {
            return false;
        }
        return $basket->weight()->float() > $this->maxValue->float();
    }

    public function isBasketTooLight(Basket $basket)
    {
        return $basket->weight()->float() < $this->minValue->float();
    }

    public function isValidForBasket(Basket $basket)
    {
        return (! $this->isBasketTooLight($basket) && ! $this->isBasketTooHeavy($basket));
    }

    private function hasNoMaxWeightLimitation()
    {
        return $this->maxValue->float() == 0.0;
    }
}
