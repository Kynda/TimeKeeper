<?php
/**
 * Filter Time Form
 *    
 * @copright 2014 Joseph Hallenbeck
 */
?>
<form method="post" name="filter" role="form" action="/filter" id="filter-form"> 

    <div class="row">

    <div class="col-xs-4">

        <div class="form-group">
            <label for="start">Start Date</label>
            <input type="text" name="start" value="<?= $start ?>" id="start" class="datepicker form-control" />
        </div>

        <div class="form-group">
            <label for="end">End Date</label>
            <input type="text" name="end" value="<?= $end ?>" id="end" class="datepicker form-control" />                            
        </div>

        <div class="checkbox">
            <label>
                <input name="alltime" type="checkbox" value="1">All Time
            </label>
        </div>

        <div class="form-group">
            <label for="billable" class="sr-only">Billable</label>
            <select name="billable" id="billable" class="form-control">
                <option value="any" <?= ($billable === 'any') ? "selected='selected'" : null ?> >Any</option>
                <option value="billable" <?= ($billable === 'billable') ? "selected='selected'" : null ?> >Billable</option>
                <option value="nonbillable" <?= ($billable === 'nonbillable') ? "selected='selected'" : null ?>>Non-Billable</option>
            </select>
        </div>
    </div>

    <div class="col-xs-4">

        <div class="form-group">
            <label for="accounts">Accounts</label>
            <select multiple="multiple" size="5" name="accounts[]" id="accounts" class="form-control">
                <option value="any" <?= in_array( 'any', $paccounts ) ? "selected='selected'" : null ?> >Any</option>
                <?php foreach( $accounts as $account ): ?>
                <option value="<?= $account['account'] ?>" 
                    <?= in_array( $account['account'], $paccounts ) ? "selected='selected'" : null ?> >
                    <?= $account['account'] ?>
                </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-primary btn-block">Filter</button>
        </div>

    </div>

    <div class="col-xs-4">

        <div class="form-group">
            <label for="tasks">Tasks</label>
            <select multiple="multiple" size="5" name="tasks[]" id="tasks" class="form-control">
                <option value="any" <?= in_array( 'any', $tasks ) ? "selected='selected'" : null ?>>Any</option>
                <?php foreach( $tasks as $task ): ?>
                    <option value="<?= $task['task'] ?>"
                    <?= in_array( $task['task'], $ptasks ) ? "selected='selected'" : null ?> >
                    <?= $task['task'] ?>
                </option>
                <?php endforeach; ?>
            </select> 
        </div>

    </div>

</form>

