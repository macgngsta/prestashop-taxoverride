prestashop-taxoverride
======================

Tax Override for Prestashop (v1.0)

This repository contains some classes that can be used to override some of the tax functionality in Prestashop. This is an naive and un-optimized code that does what is necessary. The original requirements were to provide accurate Washington and Canada taxes.

We override Prestashop's TaxRulesGroup.php in order to inject our custom logic to call the TaxOverrideService.
TaxOverrideService is an interface and is designed so that additional implementations for different purposes can be developed easily.

See the top of the other service implementations for additional comments.

Usage
----

Drop the included code into prestashop's root folder. It should look something like:
$LOCATION_OF_PRESTASHOP/override/classes/TaxRulesGroup.php
$LOCATION_OF_PRESTASHOP/override/classes/inc/

Notes
----

This solution has only been tested on Prestashop 1.4.6.2 on a shared 1&amp1 instance

Some design decisions were made
* Not using Prestashop's tax system - speed of execution and time to market
* Curl instead of fopen - 1&amp1 shared hosting did not support fopen
* Separate files for classes - didn't have a good include mechanism, keep it simple


Improvements
----

There are many things that can be improved about this code
* As a plugin - it would be eventually nice to be able to add other override implementations in a graphical manner
* Caching - going against the washington tax service can be quite expensive
* Improved registering of other overrides - currently registration is driven by includes and inclusion of php branch logic

