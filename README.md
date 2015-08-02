Id number services
========================

This is the demo on the Id number validation service.

What's inside?
--------------

The parameters configuration is located inside app/config/parameters.yml, there are two custom parameters:
  
  * "protocol" which represent the protocol under which the API is available at (http/https)
  * "http_host" which represent the host name of the API
    
The source code is located in src/Demo/ and it contains both the API and the front end. For simplicity reason both have been added to the same project.

  * src/Demo/FrontEndBundle contains the front end code. The folders src/Demo/FrontEndBundle/Lib/ and src/Demo/FrontEndBundle/Entity/ (models) contains customs classes.
    src/Demo/FrontEndBundle/Tests/ contains the tests for that bundle.

  * src/Demo/IdServicesBundle contains the back end code. The folders src/Demo/IdServicesBundle/Lib/ contains customs classes.
    src/Demo/IdServicesBundle/Tests/ contains the tests for that bundle.


The controllers are located in their respective controller folders.

>Please refer to the "Screens.zip" file emailed to you for details about the front end, Tests and Apis.

Useful references to symfony:
------------------------------

http://symfony.com/doc/2.7/book/installation.html
http://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
http://symfony.com/doc/2.7/book/doctrine.html
http://symfony.com/doc/2.7/book/templating.html
http://symfony.com/doc/2.7/book/security.html
http://symfony.com/doc/2.7/cookbook/email.html
http://symfony.com/doc/2.7/cookbook/logging/monolog.html
http://symfony.com/doc/2.7/cookbook/assetic/asset_management.html
http://symfony.com/doc/2.7/bundles/SensioGeneratorBundle/index.html
http://symfony.com/doc/current/book/testing.html
