#TimeKeeper
----------------------------------------
Time is a very simple time-keeping application for tracking billable hours, 
accounts, and tasks. For simplicity, Time expects to run off a development 
station behind a firewall as it does not do authentication checks. 
A 5 minute demo of TimeKeeper can be found at timekeeper.kynda.net

###Features
- Track time by account, task, and billable
- Filter tracked time by account, task, and billable to see total hours per account, task, or timeframe.

###Requirements
- Apache
- PHP 5.3 or Greater
- MySQL
- Localhost Environment or htaccess authentication
- Composer
- Bower

###Installation
- Note: This is not for public facing use.
- Import the MySQL dump (`database_installer.sql`) into your MySQL database of
  choice.
- Rename `config.example.php` to `config.php` and fill in your database name,
  user name, and password
- Run `composer install`
- Run `bower install`
- Should be done!

###Time is built using
- The Silex MicroFramework
- Bootstrap
- Composer
- Bower
- DBAL
- jQuery

##ChangeLog
----------------------------------------

###Version 1.1.0
####August 28, 2014
- Converted all views to bootstrap3
- Front-end libraries now load through bower making installation easier
- Config.php now defines the database parameters

###Version 1.0.0
####April 3, 2013
- Base features implemented.
- Single user per install.
- Allows adding and editing of time, creation of accounts and tasks.
- Allows filtering of time by time, billable, accounts, tasks.
- Lists time by filters, allows reordering, totals hours billable/non-billable.

###Version 0.0.0
####March 27, 2013
- Initial Commit
