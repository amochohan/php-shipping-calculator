<?php

class WeightShippingModifier
{
    private $cost;

    private $minValue;
    private $maxValue;

    public function __construct()
    {
        $this->cost = \Cost::fromFloat(0.0);
        $this->minValue = \Weight::fromFloat(0.0);
        $this->maxValue = \Weight::fromFloat(0.0);
    }

    public function cost()
    {
        return $this->cost;
    }

    public function setCost(Cost $theCost)
    {
        $this->cost = $theCost;
        return $this;
    }

    public function setMaxValue($value)
    {
        $this->maxValue = $value;
    }

    public function setMinValue($value)
    {
        $this->minValue = $value;
        return $this;
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
