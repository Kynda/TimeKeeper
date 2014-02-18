<form method="post" name="filter" action="/save" id="edit-form">

                <div class="span4">
                    <label>Date</label>
                    <input type="text" name="date" value="<?= $date ?>" class="datepicker" />
                    
                    <label>Start Time</label>
                    <select type="text" name="start" id="start">
                    <?php for( $x = 0; $x < 24; $x++ ):
                            for( $i = 0; $i <= 45; $i += 15 ) :
                               $time = date('H:i:s', strtotime("$x:$i:00" ) );
                    ?>
                        <option value="<?= $time ?>" <?= $start == $time ? 'selected="selected"' : null ?> ><?= $time ?></option>
                        <?php endfor; ?>
                    <?php endfor; ?>
                    </select>
                    
                    <label>End Time</label>
                    <select type="text" name="end" id="end">
                    <?php for( $x = 0; $x < 24; $x++ ):
                            for( $i = 0; $i <= 45; $i += 15 ) :
                               $time = date('H:i:s', strtotime("$x:$i:00" ) );
                    ?>
                        <option value="<?= $time ?>" <?= $end == $time ? 'selected="selected"' : null ?> ><?= $time ?></option>
                        <?php endfor; ?>
                    <?php endfor; ?>
                    </select>
                    
                    <label>Hours</label>
                    <input id="hours" name="hours" type="text" value="<?= $hours ?>" disabled>                    
                                        
                </div>

                <div class="span6">
                    <div class="row">
                        <div class="span3" style="margin-left:0px">
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
                            <label>Account</label>
                            <input type="text" value="<?= $account ?>" id="account" class="input-large" name="account" >
                        </div>
                        <div class="span3">                                                
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
                            <label>Task</label>
                            <input type="text" value="<?= $task ?>" id="task" class="input-large" name="task" >

                            <label class="checkbox inline">
                                <input name="billable" type="checkbox" value="1" <?= $billable ? 'checked="checked"' : null ?> >Billable?
                            </label>                                                                           
                        </div>
                    </div>
                    <div class="row">
                        <label>Notes</label>
                        <textarea rows="4" name="notes"><?= $notes ?></textarea>
                    
                        <input type="hidden" value="<?= $id ?>" name="id" />
                        <input type="hidden" value="" name="return_uri" />
                        
                        <div class="span4"><button type="submit" class="btn btn-primary btn-block">Submit</button></div>
                    </div>
                </div>

</form>

<div class="alert alert-block alert-info hide fade in span4" id="save-alert">
    <button type="button" class="close" id="close_delete">x</button>
    <h4 class="alert-heading">Time Saved</h4>
    <table class="table table-condensed task-table">
        <tr id="delete_row"></tr>
    </table>
    <p>
    </p>
</div>