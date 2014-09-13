<?php
/**
 * Display Help Panel on Left
 *    
 * @copright 2014 Joseph Hallenbeck
 */
?>
<div class="col-xs-5">
    
    <h1>Hello, <?= $name ?></h1>
    
    <ul class="list-inline" id="totals">
        
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
                <th>Site URI</th>
                <td class="solid">
                    <ul class="list-inline">
                        <li>ticket - #{Ticket No.}</li> 
                        <li>module - {Name}</li>
                        <li>cms</li>
                        <li>planning</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Office Work</th>
                <td class="solid">
                    <ul class="list-inline">
                        <li>bids</li>
                        <li>meeting</li>
                        <li>email</li>
                        <li>planning</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Server</th>
                <td class="solid">
                    <ul class="list-inline">
                        <li>upgrades</li>
                        <li>ssl</li>
                        <li>pci</li>
                        <li>red alert</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Tools</th>
                <td class="solid">
                    <ul class="list-inline">
                        <li>internal projects</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <th>Research</th>
                <td class="solid">
                    <ul class="list-inline">
                        <li>linux<li>
                        <li>databases</li>
                        <li>javascript</li>
                        <li>php</li>
                        <li>project management</li>
                    </ul>
                </td>
            </tr>
        </tbody>
    </table>   

</div>

