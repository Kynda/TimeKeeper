<?php
/**
 * Time Model interfaces with the Time Table
 *
 * @copyright 2014 Joseph Hallenbeck
 */

namespace Kynda;

use Symfony\Component\HttpFoundation\Request;

/**
 * Time Model
 */
class Time {

    /**
     * URI segment for start date (Y-M-D)
     */
    const START_OFFSET = 0;

    /**
     * URI segment for end date (Y-M-D)
     */
    const END_OFFSET = 1;

    /**
     * URI segment for accounts
     */
    const ACCOUNTS_OFFSET = 2;

    /**
     * URI segment for tasks
     */
    const TASKS_OFFSET = 3;

    /**
     * URI segment for billable
     */
    const BILLABLE_OFFSET = 4;

    /**
     * URI segment for order by
     */
    const ORDERBY_OFFSET = 5;


    /**
     * @var array $fields  Fields in table.
     */
    protected $fields = array(
                            'id',
                            'user_id',
                            'date',
                            'start',
                            'end',
                            'hours',
                            'account',
                            'task',
                            'notes',
                            'billable',
                            'public',
                            'total_hours',
                            'billable_hours',
                            'nonbillable_hours'
                        );

    /**
     * @var $validation Validator object
     */
    protected $validation;

    /**
     * @var DBal Database connection
     */
    protected $db;

    /**
     * Time Constructor
     *
     * @param DBal $db
     */
    public function __construct( $db )
    {
        $this->db = $db;
    }

    /**
     * Retrieve a collection of rows selected from the Time table
     *
     * @param array $args Arguments of query
     * @param array $params Parameteres of query
     * @return array
     */
    public function getCollection( array $args=null, array $params=null )
    {
        # Construct our base query.
        $sql = 'SELECT
                    *,
                    ( SELECT SUM(`hours`) FROM `time` %query% ) AS total_hours,
                    ( SELECT SUM(`hours`) FROM `time`
                        WHERE `billable`= 1  %subquery% ) AS billable_hours,
                    ( SELECT SUM(`hours`) FROM `time`
                        WHERE `billable`= 0 %subquery% ) AS nonbillable_hours
                FROM `time` %query%';

        if( is_array( $args) )
        {

            $query = '';
            $subquery = '';

            # Args can be 'prepared', 'where', 'groupby', 'orderby', and 'limit'
            #   'prepared' Replaces our base query with a new one
            #   'where' Defines the where substring of our query
            #   'groupby' Defines the groupby substring of our query
            #   'orderby' Defines the orderby substring of our query
            #   'limit' Defines the limit substring of our query
            foreach( $args as $key => $arg )
            {
                if( $args[$key] != '' )
                {

                    switch ( $key )
                    {
                        case 'prepared':
                            $sql = $args;
                            break;

                        case 'where':
                            $query .= ' WHERE users_id=3 AND ' . $arg;
                            $subquery .= ' AND  users_id=3 AND ' . $arg;
                            break;

                        case 'groupby':
                            $query .= ' GROUP BY ' . $arg;
                            $subquery .= ' GROUP BY ' . $arg;
                            break;

                        case 'orderby':

                            $this->cleanFields( $arg );

                            $query .= ' ORDER BY ' . $arg;
                            break;

                        case 'limit':
                            $query .= ' LIMIT ' . $arg;
                            $subquery .= ' LIMIT ' . $arg;
                            break;
                    }
                }
            }

        }

        $sql = str_replace( '%query%', $query, $sql );
        $sql = str_replace( '%subquery%', $subquery, $sql);

        if( $params )
        {
            return $this->db->fetchAll( $sql, $params );
        }
        return $this->db->fetchAll( $sql );
    }

    /**
     * Retrieve a collection of rows from the Time table based on an array of
     * arguments.
     *
     * @param array $args
     * @return array
     */
    public function getFilteredCollection( array $args )
    {
        $params = array();

        $where_time = ' id >= 0 ';
        if( $args[ Time::START_OFFSET ] != 'any' && $args[ Time::END_OFFSET ] != 'any' )
        {
            $params['start'] = $args[ Time::START_OFFSET ];
            $params['end'] = $args[ Time::END_OFFSET ];
            $where_time = 'date >= :start && date <= :end ';
        }

        $accounts = str_replace('any', '', $args[ Time::ACCOUNTS_OFFSET ] );
        $accounts_in = ( $accounts == '' ) ?
            $accounts :  ' && `account` IN (' . $this->cleanIns( $accounts ) . ')';

        $tasks = str_replace('any', '', $args[ Time::TASKS_OFFSET ] );
        $tasks_in = ( $tasks == '' ) ?
            $tasks :  ' && `task` IN (' . $this->cleanIns( $tasks ) . ')';

        $billable = '';
        if( $args[ Time::BILLABLE_OFFSET ] != 'any' )
        {
            $billable = ' && `billable` = :billable';
            $params['billable'] = $args[ Time::BILLABLE_OFFSET ] == 'billable' ? 1 : 0;
        }

        $filter = array(
            'where'     => $where_time . $accounts_in . $tasks_in . $billable,
            'orderby'   => $args[ Time::ORDERBY_OFFSET ]
        );

        return $this->getCollection( $filter, $params );
    }

    /**
     * Retrieve all accounts referenced in the Time table
     *
     * @return array
     */
    public function getAccounts()
    {
        $sql = 'SELECT DISTINCT `account` FROM time WHERE users_id=3 ORDER BY `account`';
        return $this->db->fetchAll( $sql );
    }

    /**
     * Retrieve all tasks referenced in the Time table
     *
     * @return array
     */
    public function getTasks()
    {
        $sql = 'SELECT DISTINCT `task` FROM time WHERE users_id=3 ORDER BY `task`';
         return $this->db->fetchAll( $sql ) ;
    }

    /**
     * Retrieve all tasks associated with a list of accounts.
     *
     * @param $accounts A comma seperated list of accounts
     * @return array
     */
    public function getTasksForAccounts( $accounts )
    {

        if( in_array( 'any', $accounts ) )
        {
            return $this->getTasks();
        }

        $sql = 'SELECT DISTINCT `task`
                FROM time WHERE `account`
                    IN (' . $this->cleanIns( implode(',', $accounts ) ) . ')
                WHERE users_id=3
                ORDER BY `task`';

        return $this->db->fetchAll( $sql );
    }

    /**
     * Given an arbitrary request generate a url pathname that represents the
     * state generated by this request.
     *
     * @return string
     */
    public function getFilterURI( Request $request )
    {
        if( $request->get('alltime') )
        {
            $start = 'any';
            $end = 'any';
        }
        else
        {
            $start = $request->get('start') ? $request->get('start') : date('Y-m-d');
            $end = $request->get('end') ? $request->get('end') : date('Y-m-d');
        }

        $accounts = $request->get('accounts') ?
            in_array( 'any', $request->get('accounts') ) ?
                'any' : implode(',', $request->get('accounts') ) : 'any';
        $tasks = $request->get('tasks') ?
            in_array( 'any', $request->get('tasks' ) ) ?
                'any' : implode(',', $request->get('tasks') ) : 'any';
        $billable = $request->get('billable') ? $request->get('billable') : 'any';

        return "$start/$end/$accounts/$tasks/$billable";

    }

    /**
     * Given a start and end date, determine the number of weekdays that fall
     * between these two inclusively.
     *
     * @param string $start A date string for our start date
     * @param string $end A date string for our end date
     * @return int
     */
    public function getWorkdayCount($start, $end)
    {
        $workingDays = [1, 2, 3, 4, 5];

        $from = new \DateTime($start);
        $to = new \DateTime($end);
        $to->modify('+1 day');
        $interval = new \DateInterval('P1D');
        $periods = new \DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $workingDays)) continue;
            $days++;
        }
        return $days;
    }

    /**
     * Clean a given array to ensure it's contents represent possible field
     * names.
     *
     * @param array $arg
     */
    protected function cleanFields( &$arg )
    {
        $fields = explode( ',', $arg );
        foreach( $fields as $key => $field )
        {
            if( ! in_array(
                     str_replace( array( ' desc', ' asc'), '', $field ),
                    $this->fields ) )
            {
                 throw new \RuntimeException( 'Invalid Query' );
            }

            $fields[$key] = preg_replace('/(\w+)/', '`$1`', $field, 1);
        }
        $arg = implode(',', $fields );
    }

    /**
     * Clean a given where-in request to ensure it contains safe values.
     *
     * @param array $ins
     */
    protected function cleanIns( $ins, $strings=true )
    {
        if( preg_match('/[^(a-zA-Z0-9#.,\- )]+/i', $ins, $matches )  )
        {
            throw new \RuntimeException( 'Invalid characters in Where In' );
        }

        if( $strings )
        {
            $ins = preg_replace('/([a-zA-Z0-9#.\- ]+)/i', "'$1'", $ins );
        }

        return $ins;
    }

    /**
     * Retrieve a given row from the Time table by id
     *
     * @param int $id
     * @return array
     */
    public function get( $id )
    {

        $result = $this->db->fetchAll( 'SELECT * FROM time WHERE `id`=?', array( $id ) );

        if( $result )
        {
            return $result[0];
        }

        return array(
            'id'        => 0,
            'date'      => date('Y-m-d'),
            'start'     => '00:00:00',
            'end'       => '00:00:00',
            'hours'     => 0,
            'account'   => '',
            'task'      => '',
            'notes'     => '',
            'billable'  => 1
        );
    }

    /**
     * Delete a given row from the Time table by id
     *
     * @param int $id
     * @return void
     */
    public function delete( $id )
    {
        $this->db->executeQuery( 'DELETE FROM `time` WHERE `id`=?', array( $id ) );
    }

    /**
     * Add a row to the Time table based on a request.
     *
     * @param Request $request
     * @return void;
     */
    public function add( Request $request )
    {
        $request->get( 'id' ) ? $this->update( $request ) : $this->insert( $request );
    }

    /**
     * Insert an empty row in the Time table with defualt values based on
     * request.
     *
     * @param Request @request
     * @return void
     */
    protected function insert( Request $request )
    {
        $sql = 'INSERT INTO `time`
                    (`date`, `start`, `end`, `hours`, `account`, `task`, `notes`, `billable`)
                VALUES
                    (:date, :start, :end, :hours, :account, :task, :notes, :billable )';

        $params = array(
            'date'      => $request->get('date'),
            'start'     => $request->get('start'),
            'end'       => $request->get('end'),
            'hours'     => $request->get('hours'),
            'account'   => $request->get('account'),
            'task'      => $request->get('task'),
            'notes'     => $request->get('notes'),
            'billable'  => ( $request->get('billable') ) ? 1 : 0
        );

        $this->db->executeQuery( $sql, $params );
    }

    /**
     * Update a row in the Time table based upon a given request
     *
     * @param Request @request
     * @return void
     */
    protected function update( Request $request )
    {
        $sql = 'UPDATE `time`
                SET
                    `date`=:date,
                    `start`=:start,
                    `end`=:end,
                    `hours`=:hours,
                    `account`=:account,
                    `task`=:task,
                    `notes`=:notes,
                    `billable`=:billable
                WHERE `id`=:id';

        $params = array(
            'date'      => $request->get('date'),
            'start'     => $request->get('start'),
            'end'       => $request->get('end'),
            'hours'     => $request->get('hours'),
            'account'   => $request->get('account'),
            'task'      => $request->get('task'),
            'notes'     => $request->get('notes'),
            'billable'  => $request->get('billable') ?: 0,
            'id'        => $request->get('id')
        );

        $this->db->executeQuery( $sql, $params );
    }
}
