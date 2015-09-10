<?php

namespace DrawMyAttention\ShippingCalculator;

abstract class BaseShippingModifier
{
    /**
     * Return the Cost associated with this ShippingOption modifier.
     *
     * @return mixed
     */
    public function cost()
    {
        return $this->cost;
    }

    /**
     * Set the Cost associated with this ShippingOption modifier.
     *
     * @param Cost $theCost
     * @return mixed
     */
    public function setCost(Cost $theCost)
    {
        $this->cost = $theCost;
        return $this;
    }

    /**
     * Set the minimum value the Basket must satisfy in order to use this modifier.
     *
     * For example, a modifier can be enabled only when a Basket
     * has a weight above 12kg. The minimum value unit should
     * be determined by this contract's implementation.
     *
     * @param $value
     * @return mixed
     */
    public function setMinValue($value)
    {
        $this->minValue = $value;
        return $this;
    }

    /**
     * Set the maximimum value the Basket must satisfy in order to use this modifier.
     *
     * For example, a modifier can be enabled only when a Basket has
     * a total cost over £100.00. The minimum value unit should be
     * determined by this contract's implementation.
     *
     * @param $value
     * @return mixed
     */
    public function setMaxValue($value)
    {
        $this->maxValue = $value;
        return $this;
    }
}