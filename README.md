################
##Pre-requisites
################

-PHP

-Composer

-Internet Connection

################
##Initial Setup
################

#DOWNLOAD DEPENDENCIES

-project/root:/php composer.phar --install

-After first install not a bad idea to run upate every once in a while

-project/root:/php composer.phar --update

#SETUP YOUR local.php

-Navigate to config/autoload

-:/cp local.php.example local.php

-Edit local.php to your needs

#SETUP Doctrine

-Create database in your favorite db engine

-Ensure your local.php is correct

-go to bin folder

-Run php ira.php orm:schema-tool:create