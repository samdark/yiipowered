YiiPowered
==========

Showcase of Yii powered websites and projects.

DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      vendor/             contains dependent 3rd-party packages
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources


REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 7.0.


INSTALLATION
------------

### 1. Framework and dependencies

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this application template using the following command:

~~~
composer global require "fxp/composer-asset-plugin"
composer install
~~~


### 2. Configs

There are more `.php-orig` sample configs in `config` directory. Copy these to `.php` without `-orig` and adjust to your
needs.

### 3. Database

Create a database. By this moment you should have `config/db.php`. Specify your database connection there.

Then apply migrations by running:

```
yii migrate
```

### 4. Permissions 

Permissions tree should be already initialized at step 3 so you can 
use `user/assign` to assign roles to users:

```
yii user/assign alex admin
```

Will assign admin role to user with username=alex.

### 5. You need LESS compiler in order to compile styles. In order to install it:

- Install nodeJS
- `npm install -g less`

### 6. Cron

```
*/10 * * * * php yii queue/run > /dev/null 2>&1
```
