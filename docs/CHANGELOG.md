# Changelog



<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of contents**

+ [VERSION 4.*](#version-4)
+ [VERSION 3.*](#version-3)
+ [VERSION 2.0.1](#version-201)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->



## VERSION 4.*

Version 4.0.0-beta*

tiamo/spss Version ~2.2.2

PHP 8+ only because of several dependency problems to be more up to date and goin to be
more strict in the code base. PHP 8 offers so many good improvements and bug fixes that
there is no reason to not switching to it.


2021-03

+ Updates dependencies (composer, test tools and test runner configs)
+ Merges `origin`
+ Updates tests/ fixes implementation
+ Fixes MachineFloatingPoint for PHP 7.4, 8.0; Updates tests
+ Fixes CS Errors (Too long lines. 80 chars very good, > 120 becomes unreadable)



## VERSION 3.*

2019-09

+ Updates developer tools to phpunit 8+
+ Updates tests


2019-03

+ Updates dependencies to use also php7.3
+ Adds phpbin.sh to source your php version within the developer scripts
+ Updates test runner scripts


2018-08

+ Merges parts from @SamMousa of #3 done in a5f8b18 , Thank you! :)
+ Updates developer tools to phpunit 7+
+ Leaves hints for min/max doubles to find out when it comes up


2018-05

+ Update Reader interface/ construction
    + Adds optional flag to disable reading the data/contents to improve the
      reader performance when just analyse the stucture of an spss/pspp file
      @ thanks to stephanw for the hint

+ Updates Testing/ developer enviroment<br>
    + Adds phing as default tool for tests, code coverage and additional task
      you may need for your production/ deployment/ development
    + php 5* = OFF (Maybe it works. Not tested)
    + VERSION/TAG 2.0.2 Created by accident (if you got it)
    + Beginning with php7.0 and already depricated: php7.2++ first and future...
      not in at all :)


## VERSION 2.0.1

Last version which may work with php 5.3++
