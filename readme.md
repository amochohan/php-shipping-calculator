## PHP Shipping cost calculator

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

### Development background

This framework agnostic shipping calculator serves to illustrate how such a feature could be building, following 
BDD principles.

Development is moved forward using Behat for defining features and PHPSpec to help describe the behaviour of each class.

### Contributing

Please submit any contributions via a pull request. Any submissions should be backed by tests in order to be merged.

### Licence

This project is open-sourced software licenced under the [MIT license](http://opensource.org/licenses/MIT)