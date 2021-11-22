# c19pvs
## System Specification
The server used for the project runs Scientific Linux 7.9

The version of MYSQL in use this term is 8.0.22 and PHP is version 7.4.14
## Setup local dev environment
Goal: setup a local dev environment which is the same as encs server
1. Install MySQL to computer, verify installation success by entering MySQL server
```bash
$ mysql
```
2. Login in to MySQL server, create a user, we will use this user later on to access the db. You can change user name and passowrd. Following command creates a user called 'test' with password 'passowrd'
```mysql
mysql> CREATE USER 'test'@'localhost' IDENTIFIED WITH mysql_native_password BY 'password'
```
3. Grant user privileges, so that the user can access the db
```mysql
mysql> GRANT ALL PRIVILEGES ON *.* TO 'test'@'localhost';
mysql> FLUSH PRIVILEGES;
mysql> exit
```
4. Create a db called 'knc353_2', which is the same as encs server
```mysql
mysql> CREATE DATABASE knc353_2;
```
5. In your terminal, load the db data to knc353_2 using the db dump file in /db_dump
```bash
$ cd db_dump
$ mysql knc353_2 < xxxxx.sql
```

Now you have the local db setup. In php/db_constant.php file, change the constant variables to use the values just created. For example, user name is 'test', password is 'passowrd'.
```php
DEFINE('DB_USERNAME_LOCAL', 'test');
DEFINE('DB_PASSWORD_LOCAL', 'password');
DEFINE('DB_SERVER_LOCAL', '127.0.0.1:3306');
DEFINE('DB_NAME_LOCAL', 'knc353_2');
```
Note: because of the config.php has following
```php
if ($_SERVER['REMOTE_ADDR']=='127.0.0.1')
```
If the request is from the localhost then following values will be used
```php
DEFINE('DB_USERNAME_LOCAL', 'test');
DEFINE('DB_PASSWORD_LOCAL', 'password');
DEFINE('DB_SERVER_LOCAL', '127.0.0.1:3306');
DEFINE('DB_NAME_LOCAL', 'knc353_2');
```
othwerise, following values will be used
```php
DEFINE('DB_USERNAME', 'knc353_2');
DEFINE('DB_PASSWORD', '65026502');
DEFINE('DB_SERVER', 'knc353.encs.concordia.ca:3306');
DEFINE('DB_NAME', 'knc353_2');
```
6. Run php server locally
```bash
$ cd c19pvs
$ php -S 127.0.0.1:8001
```

## Upload to encs server
```bash
rsync -avz --delete --group=knc353_2 -p --exclude .git --exclude examples/ /Users/yan.ren/github.com/yan.ren/c19pvs/ ya_re@login.encs.concordia.ca:/www/groups/k/kn_comp353_2/
```

homepage url
https://knc353.encs.concordia.ca/index.php

## Design

#### Homepage
https://knc353.encs.concordia.ca/index.php

#### Manage
https://knc353.encs.concordia.ca/manage.php has buttons for 1-8, 10 each button leads to a seperate pages that can perform 1-8

#### Booking
https://knc353.encs.concordia.ca/booking.php for 9, 11, 12

#### Search
https://knc353.encs.concordia.ca/search.php for 13, 14, 15

#### Vaccine
https://knc353.encs.concordia.ca/vaccine.php for 16, 17

#### Report
https://knc353.encs.concordia.ca/report.php for 18, 19, 20

## How To
#### How to get latest change in main into your feature branch using git rebase
1. get lastest change on remote main branch to local main branch
```git
git checkout main
git pull
```
2. rebase your feature branch on main
```git
git checkout your_feature_branch
git rebase main
```
3. now your local feature branch is rebased on main, need to push local feature branch to remote
```git
git push --force origin your_feature_branch
```