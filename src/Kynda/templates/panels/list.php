<h1>Hello, <?= $name ?></h1>
<div class="row">
    <div class="span6">
        <ul class="inline" id="totals">
        <?php if( isset( $items[0] ) ): ?>
            <li><strong>Total Hours:</strong> <?= $items[0]['total_hours'] ?></li>
            <li><strong>Billable Hours:</strong> <?= $items[0]['billable_hours'] ?></li>
            <li><strong>Non-Billable Hours:</strong> <?= $items[0]['nonbillable_hours'] ?></li>
        <?php endif; ?>
        </ul>

        <table class="table table-condensed task-table">
            <thead>
                <tr>
                    <th>Account</th>
                    <th>Task</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Site URI</td>
                    <td class="solid">
                        <ul class="unstyled">
                            <li>ticket - #{Ticket No.}, module - {Name}, cms, planning</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Office Work</td>
                    <td class="solid">
                        <ul class="unstyled">
                            <li>bids, meeting, email, planning</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Server</td>
                    <td class="solid">
                        <ul class="unstyled">
                            <li>upgrades, ssl, pci, red alert</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Tools</td>
                    <td class="solid">
                        <ul class="unstyled">
                            <li>Internal Development Projects</li>
                        </ul>
                    </td>
                </tr>
                <tr>
                    <td>Research</td>
                    <td class="solid">
                        <ul class="unstyled">
                            <li>linux, databases, javascript, php, project management</li>
                        </ul>
                    </td>
                </tr>
            </tbody>
        </table>   
    </div>
</div>

<table class="table table-hover" id="time-table"
    <thead>
        <tr>
            <th><a href="<?= $uri ?>/id asc">#</a></th>
            <th><a href="<?= $uri ?>/date asc,start">Date</a></th>
            <th><a href="<?= $uri ?>/start asc">Start</a></th>
            <th><a href="<?= $uri ?>/end asc">End</a></th>
            <th><a href="<?= $uri ?>/hours asc">Hours</a></th>
            <th><a href="<?= $uri ?>/account asc">Account</a></th>
            <th><a href="<?= $uri ?>/task asc">Task</a></th>
            <th><a href="<?= $uri ?>/notes asc">Notes</a></th>
            <th><a href="<?= $uri ?>/billable asc">Billable</a></th>
            <th>Edit</th>
            <th>Remove</th>
        </tr>
    </thead>
    <tbody id="times">
<?php 
foreach( $items as $item ): ?>

    <tr>
        <td class="solid"><?= $item['id'] ?></td>
        <td class="solid"><?= $item['date'] ?></td>
        <td class="solid"><?= $item['start'] ?></td>
        <td class="solid"><?= $item['end'] ?></td>
        <td class="solid"><?= $item['hours'] ?></td>
        <td class="solid"><?= $item['account'] ?></td>
        <td class="solid"><?= $item['task'] ?></td>
        <td><?= $item['notes'] ?></td>
        <td class="solid"><?= $item['billable'] ?></td>
        <td class="solid"><a class="edit" href="/edit/<?= $item['id'] ?>">Edit</a></td>
        <td class="solid"><a class="delete" href="/delete/<?= $item['id'] ?>">Remove</a></td>
    </tr>

<?php endforeach; ?>
    </tbody>
</table>

<div class="alert alert-block alert-error hide fade in span4" id="del-alert">
    <button type="button" class="close" id="close_delete">x</button>
    <h4 class="alert-heading">Are you sure you want to delete this item?</h4>
    <table class="table table-condensed task-table">
        <tr id="delete_row"></tr>
    </table>
    <p>
    <a class="btn btn-danger" id="delete_btn" href="#">Delete</a>
    </p>
</div>