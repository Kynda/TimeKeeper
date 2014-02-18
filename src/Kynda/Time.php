<?php

namespace Kynda;

use Symfony\Component\HttpFoundation\Request;

class Time {
    
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
    
    protected $validation;
            
    protected $db;
    
    public function __construct( $db )
    {
        $this->db = $db;                
    }
    
    public function getCollection( array $args=null, array $params=null )
    {        
        $sql = 'SELECT 
                    *, 
                    ( SELECT SUM(`hours`) FROM `time` %query% ) AS total_hours,
                    ( SELECT SUM(`hours`) FROM `time` WHERE `billable`= 1  %subquery% ) AS billable_hours,
                    ( SELECT SUM(`hours`) FROM `time` WHERE `billable`= 0 %subquery% ) AS nonbillable_hours
                FROM `time` %query%';       
        
        if( is_array( $args) )
        {
            
            $query = '';
            $subquery = '';
            
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
                            $query .= ' WHERE ' . $arg;
                            $subquery .= ' AND ' . $arg;
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
    
    public function getFilteredCollection( array $args )
    {
        $params = array();
        
        $where_time = ' id >= 0 ';
        if( $args[0] != 'any' && $args[1] != 'any' )
        {
            $params['start'] = $args[0];
            $params['end'] = $args[1];
            $where_time = 'date >= :start && date <= :end ';
        }
        
        $accounts = str_replace('any', '', $args[2] );
        $accounts_in = ( $accounts == '' ) ? $accounts :  ' && `account` IN (' . $this->cleanIns( $accounts ) . ')';
        
        $tasks = str_replace('any', '', $args[3] );
        $tasks_in = ( $tasks == '' ) ? $tasks :  ' && `task` IN (' . $this->cleanIns( $tasks ) . ')';
        
        $billable = '';
        if( $args[4] != 'any' )
        {
            $billable = ' && `billable` = :billable';
            $params['billable'] = $args[4] == 'billable' ? 1 : 0;
        }        
        
        $filter = array(
            'where'     => $where_time . $accounts_in . $tasks_in . $billable,
            'orderby'   => $args[5]
        );
        
        return $this->getCollection( $filter, $params );
    }
    
    public function getAccounts()
    {
        return $this->db->fetchAll( 'SELECT DISTINCT `account` FROM time ORDER BY `account`');
    }
    
    public function getTasks()
    {
         return $this->db->fetchAll( 'SELECT DISTINCT `task` FROM time ORDER BY `task`');
    }
    
    public function getTasksForAccounts( $accounts )
    {        
        
        if( in_array( 'any', $accounts ) )
        {
            return $this->getTasks();
        }
        
        return $this->db->fetchAll( 'SELECT DISTINCT `task` FROM time WHERE `account` IN (' . $this->cleanIns( implode(',', $accounts ) ) . ') ORDER BY `task`' );
    }
    
    
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
        
        $accounts = $request->get('accounts') ? in_array( 'any', $request->get('accounts') ) ? 'any' : implode(',', $request->get('accounts') ) : 'any';
        $tasks = $request->get('tasks') ? in_array( 'any', $request->get('tasks' ) ) ? 'any' : implode(',', $request->get('tasks') ) : 'any';
        $billable = $request->get('billable') ? $request->get('billable') : 'any';
        
        return "$start/$end/$accounts/$tasks/$billable";
         
    } 
    
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
    
    public function delete( $id )
    {
        $this->db->executeQuery( 'DELETE FROM `time` WHERE `id`=?', array( $id ) );
    }      
    
    public function add( Request $request )
    {
        $request->get( 'id' ) ? $this->update( $request ) : $this->insert( $request );
    }     
    
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
            'billable'  => $request->get('billable'),
            'id'        => $request->get('id')            
        );
        
        $this->db->executeQuery( $sql, $params );        
    }
}
?>
