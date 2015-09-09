<?php

require('vendor/autoload.php');

$basket = new Basket();

$nextDay = ShippingOption::withNameAndFlatCost('Next day', Cost::fromFloat(10.0));
$nextDayCostModifier = new CostShippingModifier();
$nextDayCostModifier->setCost(Cost::fromFloat(5.0))->setMinValue(Cost::fromFloat(20.0));
$nextDay->addModifier($nextDayCostModifier);

$standard = ShippingOption::withNameAndFlatCost('Standard', Cost::fromFloat(4.0));
$standardCostModifier = new CostShippingModifier();
$standardCostModifier->setCost(Cost::fromFloat(0.0))->setMinValue(Cost::fromFloat(20.0));
$standard->addModifier($standardCostModifier);

$basket->addShippingOption($nextDay)
    ->addShippingOption($standard);

$basket->setSubTotal(Cost::fromFloat(20.0));

$basket->applyShippingOption($standard);

var_dump($basket->shippingCost());