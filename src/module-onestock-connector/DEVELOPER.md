== Automatic fix common errors == 

vendor/bin/phpcbf --standard=Magento2 app/code/Smile/Onestock/

== Check for errors ==

vendor/bin/phpcs -q --standard=Magento2 app/code/Smile/Onestock/

== Check for issues ==

vendor/bin/phpmd  app/code/Smile/Onestock/ ansi cleancode, codesize, controversial, design, naming, unusedcode
