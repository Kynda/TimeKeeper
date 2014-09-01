/**
 *  Define Configuration for TimeKeeper.js Application
 *  
 *  @copright 2014 Joseph Hallenbeck
*/

// Add all our timeConfig selectors to TimeKeeper.
timeConfig = {};

// Forms
timeConfig.forms = {};
timeConfig.forms.edit = '#edit-form';

// Inputs
timeConfig.input = {};
timeConfig.input.datepicker = '.datepicker';
timeConfig.input.date = '[name=date]';
timeConfig.input.account = '[name=account]';
timeConfig.input.task = '[name=task]';
timeConfig.input.tasks = '#tasks';
timeConfig.input.billable = '[name=billable]';
timeConfig.input.return_uri = '[name=return_uri]';
timeConfig.input.end = '[name=end]';
timeConfig.input.start = '[name=start]';
timeConfig.input.account = '#account';
timeConfig.input.accounts = '#accounts';
timeConfig.input.hours = '[name=hours]';
timeConfig.input.alltime = '[name=alltime]'

// Containers
timeConfig.containers = {};
timeConfig.containers.times = '#times';
timeConfig.containers.totals = '#totals';
timeConfig.containers.add = '#add';
timeConfig.containers.filter = '#filter';

// Links
timeConfig.links = {};
timeConfig.links.add = 'a#add-link';
timeConfig.links.filter = 'a#filter-link';
timeConfig.links.edit = 'a.edit';
timeConfig.links.delete = 'a.delete';
timeConfig.links.cancel_delete = '#close';
timeConfig.links.confirm_delete = '#delete_btn';
timeConfig.links.thead = 'th a';

// Alerts
timeConfig.alerts = {};
timeConfig.alerts.save = '#save-alert';
timeConfig.alerts.delete = '#del-alert';

// Start TimeKeeper App
TimeKeeper.start( timeConfig );
