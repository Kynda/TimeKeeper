<?php
/**
 * Displays a table of Time items
 *    
 * @copright 2014 Joseph Hallenbeck
 */
?>
<div class="row">

    <div class="col-xs-12">

        <table class="table table-hover" id="time-table">
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
        <?php foreach( $items as $item ): ?>

            <tr>
                <td class="solid"><?= $item['id'] ?></td>
                <td class="solid"><?= $item['date'] ?></td>
                <td class="solid"><?= $item['start'] ?></td>
                <td class="solid"><?= $item['end'] ?></td>
                <td class="solid"><?= $item['hours'] ?></td>
                <td class="solid"><?= $item['account'] ?></td>
                <td class="solid"><?= $item['task'] ?></td>
                <td><?= markdown($item['notes']) ?></td>
                <td class="solid"><?= $item['billable'] ?></td>
                <td class="solid"><a class="edit" href="/edit/<?= $item['id'] ?>">Edit</a></td>
                <td class="solid"><a class="delete" href="/delete/<?= $item['id'] ?>">Remove</a></td>
            </tr>

        <?php endforeach; ?>
            </tbody>
        </table>

    </div>
</div>
