<?php

class CostShippingModifier
{
    private $cost;

    public $minValue;

    public $maxValue;

    public function __construct()
    {
        $this->minValue = \Cost::fromFloat(0.0);
        $this->maxValue = \Cost::fromFloat(0.0);
        $this->cost     = \Cost::fromFloat(0.0);
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

    public function setMinValue($value)
    {
        $this->minValue = $value;
    }

    public function setMaxValue($value)
    {
        $this->maxValue = $value;
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
