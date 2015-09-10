<?php

class ShippingOption
{
    private $name;

    private $cost;

    private $minimumGoodsCostRequired;
    private $maximumGoodsCostAllowed;

    private $maximumBasketWeightAllowed;
    private $minimumBasketWeightRequired;

    private $modifiers;

    private $multiplier;

    public static function withNameAndFlatCost($name, Cost $flatCost)
    {
        $shippingOption = new ShippingOption();

        $shippingOption->name = $name;

        $shippingOption->cost = $flatCost;

        $shippingOption->minimumGoodsCostRequired = \Cost::fromFloat(0.0);
        $shippingOption->maximumGoodsCostAllowed = \Cost::fromFloat(0.0);

        $shippingOption->maximumBasketWeightAllowed = \Weight::fromFloat(0.0);
        $shippingOption->minimumBasketWeightRequired = \Weight::fromFloat(0.0);

//        $shippingOption->multiplier = \Multiplier::fromFloat(0.0);

        return $shippingOption;
    }

    public function totalCost(Basket $basket)
    {
        if ($this->costModifiersExist()) {

            $validModifiers = $this->getApplicableModifiersForBasket($basket);

            if($this->hasValidModifiers($validModifiers)) {
                $validModifiers = $this->sortModifiersByCostDesc($validModifiers);
                return $this->getFirstValidModifier($validModifiers)->cost();
            }

        }

        // Modifiers weren't assigned so calculate the cost of this
        // shipping option, by applying any multipliers that may
        // have been set.
        if ($this->multiplierExists()) {
            return $this->applyMultiplierToCost($basket);
        }

        // If none of the modifiers are applicable to the current
        // basket state, then return the default base cost of
        // the shipping option.
        return $this->cost;
    }

    private function getApplicableModifiersForBasket($basket)
    {
        return array_filter($this->modifiers, function($modifier) use ($basket) {
            if ($modifier->isValidForBasket($basket)) {
                return $modifier->cost();
            }
        });
    }

    private function getFirstValidModifier($validModifiers)
    {
        return array_shift($validModifiers);
    }

    private function hasValidModifiers($validModifiers)
    {
        return (sizeof($validModifiers) > 0);
    }

    public function sortModifiersByCostDesc($modifiers)
    {
        usort($modifiers, ['ShippingOption', 'costSort']);
        return $modifiers;
    }

    public static function costSort($modifierB,$modifierA) {
        return $modifierA->cost()->float() == $modifierB->cost()->float() ? 0.0 : ( $modifierA->cost()->float() > $modifierB->cost()->float() ) ? 1 : -1;
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
            ! $this->isBasketTooHeavy($basket->weight()) &&
            ! $this->isBasketToolight($basket->weight()));
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

    public function costModifiersExist()
    {
        return !empty($this->modifiers);
    }

    public function setMultiplier($multiplier)
    {
        $this->multiplier = $multiplier;
    }

    public function multiplierExists()
    {
        return !empty($this->multiplier);
    }

    private function applyMultiplierToCost(Basket $basket)
    {
        return \Cost::fromFloat(
            $this->multiplier->multipliedCost($basket)->float() + $this->cost->float()
        );
    }

    public function setMinimumBasketWeight(Weight $weight)
    {
        $this->minimumBasketWeightRequired = $weight;
        return $this;
    }

    public function isBasketTooLight(Weight $weight)
    {
        if ($this->minimumBasketWeightRequired->float() == 0) {
            return false;
        }
        return $weight->float() < $this->minimumBasketWeightRequired->float();
    }

}
