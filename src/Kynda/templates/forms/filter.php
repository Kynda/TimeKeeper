
            <form method="post" name="filter" action="/filter">

                            <div class="span4">
                                <div class="pull-right" style="margin-right:80px">
                                    <input type="checkbox" name="alltime" style="margin:-4px 0 0"  />
                                    All Time
                                </div>
                                
                                <label>Start Date</label>
                                <input type="text" name="start" value="<?= $start ?>" class="datepicker" />

                                <label>End Date</label>
                                <input type="text" name="end" value="<?= $end ?>" class="datepicker" />                            

                                <select name="billable">
                                    <option value="any" <?= ($billable === 'any') ? "selected='selected'" : null ?> >Any</option>
                                    <option value="billable" <?= ($billable === 'billable') ? "selected='selected'" : null ?> >Billable</option>
                                    <option value="nonbillable" <?= ($billable === 'nonbillable') ? "selected='selected'" : null ?>>Non-Billable</option>
                                </select>
                            </div>

                            <div class="span3" style="margin-left:0px">
                                <label>Accounts</label>
                                <select multiple="multiple" size="5" name="accounts[]" id="accounts">
                                    <option value="any" <?= in_array( 'any', $paccounts ) ? "selected='selected'" : null ?> >Any</option>
                                <?php foreach( $accounts as $account ): ?>
                                    <option value="<?= $account['account'] ?>" 
                                            <?= in_array( $account['account'], $paccounts ) ? "selected='selected'" : null ?> >
                                        <?= $account['account'] ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-block">Filter</button>
                            </div>

                            <div class="span3">
                                <label>Tasks</label>
                                <select multiple="multiple" size="5" name="tasks[]" id="tasks">
                                    <option value="any" <?= in_array( 'any', $tasks ) ? "selected='selected'" : null ?>>Any</option>
                                <?php foreach( $tasks as $task ): ?>
                                    <option value="<?= $task['task'] ?>"
                                            <?= in_array( $task['task'], $ptasks ) ? "selected='selected'" : null ?> >
                                        <?= $task['task'] ?>
                                    </option>
                                <?php endforeach; ?>
                                </select>                                                                                    
                            </div>

            </form>
  
