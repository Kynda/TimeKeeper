TimeKeeper
===============================================================================

**The Development Branch is broken until further notice.**

TimeKeeper is a very simple time-keeping application for tracking billable
hours, accounts, and tasks.

Features
-------------------------------------------------------------------------------

- Track time by account, task, and billable
- Filter tracked time by account, task, and billable to see total hours per
  account, task, or timeframe.

Installation
-------------------------------------------------------------------------------

### Docker

1. Clone this repo to a local system
2. Run `docker compose up`
3. TimeKeeper should now be runnong on http://localhost:8081

### Sans Docker

Note, TimeKeeper expects to run locally and does not support user authentication
at this time.

Requirements

- Apache or NGINX
- PHP 8.0 or Greater
- SQLITE
- Composer
- Bower

1. Clone this repo to a local system
2. Run `composer install`
3. Run `bower install`
4. Configure your web server to serve `web/index.php`

The file `.vhost` has an example Apache configuration that can be used for (4).
An environment variable for `DB\_DATABASE` should be set and pointed at
`.data.db`.

Time is built using
-------------------------------------------------------------------------------

- The Slim Framework
- SQLITE
- Bootstrap
- Composer
- Bower
- DBAL
- jQuery

ChangeLog
-------------------------------------------------------------------------------

### Version 0.2.0
### 2021-06-02

- Added docker support
- Converted database from MySQL to SQLITE
- Switched back-end framework to Slim with PHP 8.0 support
- License changed to GPLv3

### Version 0.1.0
#### 2014-09-14

- Converted all views to bootstrap3
- Front-end libraries now load through bower making installation easier
- Config.php now defines the database parameters

### Version 0.0.0
#### 2013-04-03

- Base features implemented.
- Single user per install.
- Allows adding and editing of time, creation of accounts and tasks.
- Allows filtering of time by time, billable, accounts, tasks.
- Lists time by filters, allows reordering, totals hours billable/non-billable.

### Version 0.0.0
#### 2013-03-27

- Initial Commit
