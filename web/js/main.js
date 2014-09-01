// Add all our dom selectors to TimeKeeper.
dom = {};

// Forms
dom.forms = {};
dom.forms.edit = '#edit-form';

// Inputs
dom.input = {};
dom.input.datepicker = '.datepicker';
dom.input.date = '[name=date]';
dom.input.account = '[name=account]';
dom.input.task = '[name=task]';
dom.input.tasks = '#tasks';
dom.input.billable = '[name=billable]';
dom.input.return_uri = '[name=return_uri]';
dom.input.end = '[name=end]';
dom.input.start = '[name=start]';
dom.input.account = '#account';
dom.input.accounts = '#accounts';
dom.input.hours = '[name=hours]';
dom.input.alltime = '[name=alltime]'

// Containers
dom.containers = {};
dom.containers.times = '#times';
dom.containers.totals = '#totals';
dom.containers.add = '#add';
dom.containers.filter = '#filter';

// Links
dom.links = {};
dom.links.add = 'a#add-link';
dom.links.filter = 'a#filter-link';
dom.links.edit = 'a.edit';
dom.links.delete = 'a.delete';
dom.links.cancel_delete = '#close';
dom.links.confirm_delete = '#delete_btn';
dom.links.thead = 'th a';

// Alerts
dom.alerts = {};
dom.alerts.save = '#save-alert';
dom.alerts.delete = '#del-alert';

// Start TimeKeeper App
TimeKeeper.start( dom );
