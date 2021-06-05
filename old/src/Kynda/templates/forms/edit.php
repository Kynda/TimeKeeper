<?php
/**
 * Edit Time Form
 *    
 * @copright 2014 Joseph Hallenbeck
 */
?>
<form method="post" name="filter" role="form" action="/save" id="edit-form">

    <div class="row">

        <div class="col-xs-4">

            <div class="form-group">
                <label for="date">Date</label>
                <input id="date" type="text" name="date" value="<?= $date ?>" class="datepicker form-control" />
            </div>

            <div class="form-group">
                <label for="start">Start Time</label>
                <select type="text" name="start" id="start" class="form-control">
                <?php for( $x = 0; $x < 24; $x++ ):
                    for( $i = 0; $i <= 45; $i += 15 ) :
                        $time = date('H:i:s', strtotime("$x:$i:00" ) );
                ?>
                    <option value="<?= $time ?>" <?= $start == $time ? 'selected="selected"' : null ?> ><?= $time ?></option>
                    <?php endfor; ?>
                <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="end">End Time</label>
                <select type="text" name="end" id="end" class="form-control">
                <?php for( $x = 0; $x < 24; $x++ ):
                        for( $i = 0; $i <= 45; $i += 15 ) :
                            $time = date('H:i:s', strtotime("$x:$i:00" ) );
                ?>
                    <option value="<?= $time ?>" <?= $end == $time ? 'selected="selected"' : null ?> ><?= $time ?></option>
                    <?php endfor; ?>
                <?php endfor; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="hours">Hours</label>
                <input id="hours" name="hours" type="text" value="<?= $hours ?>" disabled class="form-control" />                    
            </div>

        </div>

        <div class="col-xs-8">

            <div class="row">
 
               <div class="col-xs-6">
               <?php 

                $account_array = '';
                foreach( $accounts as $acc ) {
                    $account_array .=  "'{$acc['account']}',";
                }
                $account_array = rtrim( $account_array, ",");

                ?>
                <script>
                    var accounts = [<?= $account_array ?> ];
                </script>

                    <div class="form-group">
                        <label for="account">Account</label>
                        <input type="text" value="<?= $account ?>" id="account" class="form-control" name="account" />
                    </div>

                </div>

                <div class="col-xs-6">                                                
                <?php

                $task_array = '';
                foreach( $tasks as $tas ) {
                    $task_array .=  "'{$tas['task']}',";
                }
                $task_array = rtrim( $task_array, ",");

                ?>
                <script>
                    var tasks = [<?= $task_array ?>];
                </script>

                    <div class="form-group">
                        <label for="task">Task</label>
                        <input type="text" value="<?= $task ?>" id="task" class="form-control" name="task" />
                    </div>

                    <div class="checkbox">
                        <label>
                            <input name="billable" type="checkbox" value="1" <?= $billable ? 'checked="checked"' : null ?> />Billable?
                        </label>                       
                    </div>
                </div>

            </div>

            <div class="row">

                <div class="col-xs-12">

                    <div class="form-group">    
                        <label for="notes">Notes</label>
                        <textarea class="form-control" id="notes" rows="4" name="notes"><?= $notes ?></textarea>
                    </div>
                </div>

            </div>

            <div class="row">            
                <div class="col-xs-4">
                    <button type="submit" class="btn btn-primary btn-block">Submit</button></div>
                </div>
            </div>

        </div>
    </div>
    
    <input type="hidden" value="<?= $id ?>" name="id" />
    <input type="hidden" value="" name="return_uri" />

</form>
