<?php

class ShippingOption
{
    private $name;

    private $cost;

    private $minimumGoodsCostRequired;
    private $maximumGoodsCostAllowed;

    private $maximumBasketWeightAllowed;

    private $modifiers;

    public static function withNameAndFlatCost($name, Cost $flatCost)
    {
        $shippingOption = new ShippingOption();

        $shippingOption->name = $name;

        $shippingOption->cost = $flatCost;

        $shippingOption->minimumGoodsCostRequired = \Cost::fromFloat(0.0);
        $shippingOption->maximumGoodsCostAllowed = \Cost::fromFloat(0.0);

        $shippingOption->maximumBasketWeightAllowed = \Weight::fromFloat(0.0);

        return $shippingOption;
    }

    public function totalCost(Basket $basket)
    {
        if (! $this->costModifiersExist()) {
            return $this->cost;
        }
        foreach($this->modifiers as $modifier) {
            if ($modifier->isValidForBasket($basket)) {
                return $modifier->cost();
            }
        }

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

    public function isBasketTooCheap(Cost $currentCost)
    {
        if ($this->minimumGoodsCostRequired->float() == 0) {
            return false;
        }
        return $currentCost->float() < $this->minimumGoodsCostRequired->float();
    }

    public function isBasketTooExpensive(Cost $currentCost)
    {
        if ($this->maximumGoodsCostAllowed->float() == 0) {
            return false;
        }
        return $currentCost->float() > $this->maximumGoodsCostAllowed->float();
    }

    public function isAvailableToBasket(Basket $basket)
    {
        return (! $this->isBasketTooCheap($basket->subTotal()) &&
            ! $this->isBasketTooExpensive($basket->subTotal()) &&
            ! $this->isBasketTooHeavy($basket->weight()));
    }

    public function setMaximumGoodsCostAllowed(Cost $theMaximumCost)
    {
        $this->maximumGoodsCostAllowed = $theMaximumCost;
        return $this;
    }

    public function setMaximumBasketWeight(Weight $weight)
    {
        $this->maximumBasketWeightAllowed = $weight;
        return $this;
    }

    public function isBasketTooHeavy(Weight $weight)
    {
        if ($this->maximumBasketWeightAllowed->float() == 0) {
            return false;
        }
        return $weight->float() > $this->maximumBasketWeightAllowed->float();
    }

    public function addModifier($modifier)
    {
        $this->modifiers[] = $modifier;
    }

    /**
     * @return bool
     */
    private function costModifiersExist()
    {
        return sizeof($this->modifiers) > 0;
    }

}
