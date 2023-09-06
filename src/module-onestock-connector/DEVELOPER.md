== Automatic fix common errors == 

vendor/bin/phpcbf

== Check for errors ==

vendor/bin/phpcs

== Check for issues ==

 vendor/bin/phpmd . ansi phpmd.xml.dist

 == Check for static errors ==

 vendor/bin/phpstan

