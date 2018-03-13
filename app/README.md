<p align="center">
    <h1 align="center">Test Task Simple game app</h1>
    <br>
</p>

Based on Yii 2 Basic Project Template but not include it ( you should install it )


DIRECTORY STRUCTURE
-------------------

      assets/             contains assets definition
      commands/           contains console commands (controllers)
      config/             contains application configurations
      controllers/        contains Web controller classes
      mail/               contains view files for e-mails
      models/             contains model classes
      runtime/            contains files generated during runtime
      tests/              contains various tests for the basic application
      views/              contains view files for the Web application
      web/                contains the entry script and Web resources



REQUIREMENTS
------------

The minimum requirement by this project template that your Web server supports PHP 5.4.0.


INSTALLATION
------------

clone to web folder,
run composer update.


CONFIGURATION
-------------

### Database

Edit the file `config/db.php` with real data, for example:

```php
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=localhost;dbname=yii2basic',
    'username' => 'root',
    'password' => '1234',
    'charset' => 'utf8',
];
```

run migration from migration folder and for user module:
yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
yii migrate/up migrations

**NOTES:**
- Yii won't create the database for you, this has to be done manually before you can access it.
- Check and edit the other files in the `config/` directory to customize your application as required.
- Refer to the README in the `tests` directory for information specific to basic application tests.


TESTING
-------

Include required in task Unit test : UserAccountTest

 USAGE CONSOLE COMMAND
 ----------------------
 
use command for batch send of cash prizes :
yii prize/send-cash {n} 

n - quantity of items

 LIVE PREVIEW 
 ------------
 
url: game.vanyok.in.ua
user: admin
pass: admingame