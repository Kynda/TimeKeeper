#TimeKeeper
----------------------------------------
Time is a very simple time-keeping application for tracking billable hours, accounts, and tasks. For simplicity, Time expects to run off a development station behind a firewall as it does not do authentication checks. A 5 minute demo of TimeKeeper can be found at timekeeper.kynda.net

###Features
- Track time by account, task, and billable
- Filter tracked time by account, task, and billable to see total hours per account, task, or timeframe.

###Requirements
- Apache
- PHP 5.3 or Greater
- MySQL
- Localhost Environment or htaccess authentication

###Time is built using
- The Silex MicroFramework
- Bootstrap
- Composer
- DBAL

###Future Features
- PHP User Authentication for multiple users and PHP based user login.
- SuperAdmin support for allow/disallowing account creation and team-member assignment.
- Team support where multiple users can review team-member contributions to projects.
- Integration with Task to allow closing of outstanding tickets through the Timeinterface.
- Refactoring into a Silex Module for inclusion in other projects.

##ChangeLog
----------------------------------------

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
