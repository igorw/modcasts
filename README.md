# modcasts

phpbb modding screencasts

modcasts is a site with screencasts on phpBB modding.

## powered by

* doctrine 2
* symfony 2 (some components)
* twig
* sass

## requirements

* PHP 5.3

## install

1. populate submodules using `git submodule update --init`
2. adjust configuration in config/*.yml
3. initialize the db with `./doctrine orm:schem-tool:create`
4. compile stylesheets with `make stylesheets`
5. PROFIT!

## todo

* backend
* comments (gravatar?)
* password salting
* pagination
* PHPUnit tests?
* search (lucene)
* add timeout to csrf token
* use container to create responses
* advanced routing
