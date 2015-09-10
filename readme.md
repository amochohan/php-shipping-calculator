## PHP Shipping cost calculator

[![Build Status](https://travis-ci.org/drawmyattention/php-shipping-calculator.svg)](https://travis-ci.org/drawmyattention/php-shipping-calculator)

### Usage

Shipping cost is calculated either using a flat rate, or can be adjusted based on the total value of the a basket.
The shipping cost can be hidden if the goods total or basket weight is above or below a given value.

#### Basic flat rate shipping options

Basic flat rate shipping options do not change in price, irrespective of external variables.

    $standard = ShippingOption::withNameAndFlatCost('Standard', Cost::fromFloat(4.99));
    $nextDay = ShippingOption::withNameAndFlatCost('Next day', Cost::fromFloat(8.99));

*Note: to be made available for use, each ```ShippingOption``` must be added to the ```Basket```*

    $basket = new Basket();
    $basket->addShippingOption($standard)
        ->addShippingOption($nextDay);
    
    
#### Determining which shipping options are available

Return an array of shipping methods which can be used by the customer, factoring in pricing, weight, and quantity 
requirements of each shipping option.

    $basket->availableShippingMethods();
        
#### Price modifiers

The basic flat rate shipping price of any shipping option can be adjusted according the the cost value of a basket, 
its weight or the number of products it holds.

**Example:** Next day delivery has a flat rate cost of 8.99. However, if the customer orders over 10.00 of goods, this cost goes down to 5.99.
     
     // Set up the next day shipping option with its default price of 8.99
     $nextDayOption = ShippingOption::withNameAndFlatCost('Next day', Cost::fromFloat(8.99));
     
     // Create a new modifier that changes the price based on the basket Cost (other modifiers are available for Weight and Quantity)
     $nextDayModifier = new CostShippingModifier();
    
     // Set the new cost of the option to 5.99
     $nextDayModifier->setCost(Cost::fromFloat(5.99))
         // When the minimum value (Cost, in this case) of the basket exceeds 10.0 
         ->setMinValue(Cost::fromFloat(10.0));
     
     // Add the modifier to the shipping option.
     $nextDayOption->addModifier($nextDayModifier);
     
     // Remember to add the shipping option to the basket
     $basket->addShippingOption($nextDayOption);

**Example:** Next day delivery has a flat rate cost of 8.99. However, if the basket weighs over 55.00(unit agnostic) of goods, this cost increases to 12.99.

    // Set up the next day shipping option with its default price of 8.99
     $nextDayOption = ShippingOption::withNameAndFlatCost('Next day', Cost::fromFloat(8.99));
     
     // Create a new modifier that changes the price based on the basket Weight
     $nextDayModifier = new WeightShippingModifier();
    
     // Set the new cost of the option to 12.99
     $nextDayModifier->setCost(Cost::fromFloat(12.99))
         // When the minimum value (Weight, in this case) of the basket exceeds 55.0 
         ->setMinValue(Cost::fromFloat(55.0));
     
     // Add the modifier to the shipping option.
     $nextDayOption->addModifier($nextDayModifier);
     
     // Remember to add the shipping option to the basket
     $basket->addShippingOption($nextDayOption);

**Note:** Multiple modifiers can be added to each Shipping Option. Only valid modifiers according to the Cost, 
product quantity and Weight of the basket are considered when calculating the shipping cost of a shipping option.

#### Hiding a shipping option when the basket cost is above or below a specified value 

It is sometimes useful to only offer a shipping option based on different scenarios. One example is, if the basket contains 
products over a certain value, shipping must be done using a carrier that offers a higher level of insurance. This may be cost prohibitive on smaller value orders.

    // Set up the exclusive shipping option with its default price of 8.99
    $exclusiveOption = ShippingOption::withNameAndFlatCost('Exclusive', Cost::fromFloat(8.99));
    
    // Only allow the option to be used when the basket cost is over 100.0
    $exclusiveOption->setMinimumGoodsCost(\Cost::fromFloat(100.0));


### Development background

This framework agnostic shipping calculator serves to illustrate how such a feature could be building, following 
BDD principles.

Development is moved forward using Behat for defining features and PHPSpec to help describe the behaviour of each class.

### Contributing

Please submit any contributions via a pull request. Any submissions should be backed by tests in order to be merged.

### Licence

This project is open-sourced software licenced under the [MIT license](http://opensource.org/licenses/MIT)