<?php

interface ShippingModifierContract
{
    /**
     * Return the Cost associated with this ShippingOption modifier.
     *
     * @return mixed
     */
    public function cost();

    /**
     * Set the Cost associated with this ShippingOption modifier.
     *
     * @param Cost $theCost
     * @return mixed
     */
    public function setCost(Cost $theCost);

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
    public function setMinValue($value);

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
    public function setMaxValue($value);

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
    public function isValidForBasket(Basket $basket);
}