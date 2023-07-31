== Automatic fix common errors == 

vendor/bin/phpcbf --standard=Magento2 app/code/Smile/Onestock/

== Check for errors ==

vendor/bin/phpcs -q --standard=Magento2 app/code/Smile/Onestock/

== Check for issues ==

vendor/bin/phpmd  app/code/Smile/Onestock/ ansi cleancode, codesize, controversial, design, naming, unusedcode

== Run integration test ==

export HOST=some_host
export USER_ID=some_user_id
export SITE_ID=some_site_id
export PASSWORD=some_password
./vendor/phpunit/phpunit/phpunit -c app/code/Smile/Onestock/phpunit.xml
