<?php

namespace DrawMyAttention\ShippingCalculator;

class WeightCostMultiplier
{
    private $float;

    public static function fromFloat($float)
    {
        $weightCostMultiplier = new WeightCostMultiplier();

        $weightCostMultiplier->float = $float;

        return $weightCostMultiplier;
    }

    public function multipliedCost(Basket $basket)
    {
        return Cost::fromFloat($basket->weight()->float() * $this->float);
    }
}
