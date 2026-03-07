# YiiPowered

Showcase of Yii powered websites and projects.

## DIRECTORY STRUCTURE

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

## REQUIREMENTS

The minimum requirement by this project template that your Web server supports PHP 7.0.

## INSTALLATION (Locally)

### 1. Framework and dependencies

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

You can then install this application template using the following command:

```
composer install
```

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

Permissions tree should be already initialized at step 3, so you can
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
0 4 * * * php yii image/fetch > /dev/null 2>&1
0 5 * * * php yii check/all > /dev/null 2>&1
```

## INSTALLATION (Docker)

1. `docker-compose up -d --build`.
2. There are `.php-orig` sample configs in `config` directory. Copy these to `.php` without `-orig` and adjust to your
   needs.
3. `docker exec -it yiipowered_php chmod -R 777 assets runtime web/assets`
4. `docker exec -it yiipowered_php bash`.
5. `composer install && php yii migrate`.
6. Use `user/assign` to assign roles to users.

The application will be available at `http://localhost`.

### Twitter (X) Auth Setup

To enable Twitter (X) authentication via `yiisoft/yii2-authclient`:

1. Go to the [X Developer Portal](https://developer.twitter.com/en/portal/dashboard) and create a new App.
2. Under User Authentication Settings, enable OAuth 1.0a.
3. Set the App permissions to "Read" and enable "Request email address from users".
4. Add your application's redirect URI (e.g. `http://yourdomain.com/site/auth?authclient=twitter`) to the allowed Callback URIs.
5. Obtain your "API Key" and "API Key Secret".
6. In `config/authclients.php`, fill in the `consumerKey` and `consumerSecret` for the `twitter` client with the obtained keys:
   ```php
   'twitter' => [
       'class' => \yii\authclient\clients\Twitter::class,
       'attributeParams' => [
           'include_email' => 'true'
       ],
       'consumerKey' => 'TWITTER_API_KEY',
       'consumerSecret' => 'TWITTER_API_SECRET',
   ]
   ```

## Testing

The application includes unit tests for verifying the authentication flows (GitHub and Twitter). Since `AuthHandler` heavily relies on Yii2 Active Records (`User`, `Auth`), these tests require a proper Yii2 testing environment.

To run the unit tests, you first need to configure the Codeception testing framework:

1. Install Codeception and the Yii2 module via Composer:

   ```bash
   composer require --dev codeception/codeception codeception/module-yii2
   ```

2. Initialize Codeception:

   ```bash
   vendor/bin/codecept bootstrap
   ```

3. Configure your test database and Yii application mocks inside `tests/unit.suite.yml` and test configuration files.

4. Run the unit tests:
   ```bash
   vendor/bin/codecept run unit
   ```

The test cases can be found in `tests/unit/components/AuthHandlerTest.php`.
