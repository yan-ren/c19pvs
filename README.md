# c19pvs
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

Now you have the db setup, in php/db_constant.php file, change the constant variables to use the values just created
For example, user name is 'test', password is 'passowrd'
```php
DEFINE('DB_USERNAME', 'test');
DEFINE('DB_PASSWORD', 'password');
DEFINE('DB_SERVER', '127.0.0.1:3306');
DEFINE('DB_NAME', 'knc353_2');
```
6. Run php server locally
```bash
$ cd CS19PVS
$ php -S 127.0.0.1:8001
```

## Upload to encs server
```bash
rsync -avz --delete --no-p --exclude '.git' /Users/yan.ren/github.com/yan.ren/c19pvs/ ya_re@login.encs.concordia.ca:/www/groups/k/kn_comp353_2/
```

base url
https://knc353.encs.concordia.ca/index.php