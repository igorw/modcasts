# modcasts

phpbb modding screencasts

modcasts is a site with screencasts on phpBB modding.

# powered by

* doctrine 2
* symfony 2 (some components)
* twig
* sass

# install

1. adjust configuration in config/*.yml
2. initialize the db with ./doctrine orm:schem-tool:create
3. compile css with
    * sass public/style.sass public/style.css
    * sass public/backend.sass public/backend.css
4. PROFIT!

# todo

* RSS/Atom feed
* backend
* comments (gravatar?)
* password salting
* pagination
* PHPUnit tests?
* search (lucene)
* add timeout to csrf token
