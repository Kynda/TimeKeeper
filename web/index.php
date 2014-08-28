<?php
/**
 * @version 1.0.0
 * @package Time
 * @author Joe Hallenbeck
 * 
 * @todo Add Auth Service Provider / Multi-user support.
 */

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../config.php';

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

/*******************************************************************
 * 
 * Register Service Providers
 * 
 *******************************************************************/

$app->register( new Silex\Provider\DoctrineServiceProvider(), array(
   'db.options' => $config['db'] ));

$app->register( new Kynda\Provider\ViewServiceProvider(), array(
    'view.options' => array(
        'templates' => '/templates/',
        'header'    => 'head',
        'body'      => 'body'
    ),    
    'view.postJavascript' => array(
        '/vendors/jquery/dist/jquery.min.js',
        '/vendors/bootstrap/dist/js/bootstrap.min.js',
        '/vendors/jquery-ui/jquery-ui.min.js',
        '/vendors/date.format/date.format.js',
        '/js/timekeeper.js',
        '/js/main.js'
    ),
    'view.styles' => array(
        '/vendors/jquery-ui/themes/cupertino/jquery-ui.min.css',
        '/vendors/jquery-ui/themes/cupertino/theme.css',
        '/css/style.css'
    )
) );

$app->register( new Kynda\Provider\TimeServiceProvider() );

/*******************************************************************
 * 
 * POST Paths
 * 
 *******************************************************************/

$app->post('/filter', function( Request $request ) use ( $app ) {   
    
    $uri = $app['time']->getFilterURI( $request );
    return $app->redirect( '/list/' . $uri );
});

$app->post('/save', function( Request $request ) use ( $app ) {
    
    $app['time']->add( $request );
    return $app->redirect( $request->get('return_uri') );
});

$app->post('/delete', function( Request $request ) use ( $app ) {  
    $app['time']->delete( $request->get('id'));
    return $app->redirect( $request->get('return_uri' ) );    
});

$app->post('/json/tasks', function( Request $request ) use( $app ) {            
    $tasks = $app['time']->getTasksForAccounts( $request->get('accounts') );    
    return json_encode( $tasks );
});


/*******************************************************************
 * 
 * GET Paths
 * 
 *******************************************************************/

$app->get('/', function () use ($app) {
    $subRequest = Request::create('/list', 'GET');
    return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);
});

$app->get('/list/{start}/{end}/{accounts}/{tasks}/{billable}/{orderby}', 
    function( $start, $end, $accounts, $tasks, $billable, $orderby ) 
        use( $app, $config ) {    
    
    $view = $app['view'];
    
    $view->pagetitle = 'Time';
    $view->name = $config['name'];    
    $view->uri = "/list/$start/$end/$accounts/$tasks/$billable";        
    
    $params = array(        
        'start'     => $start,
        'end'       => $end,
        'paccounts' => explode(',', $accounts ),
        'ptasks'    => explode(',', $tasks ),
        'billable'  => $billable,
        'orderby'   => $orderby
    ); 
    $view->add( $params );
    
    $view->tasks = $app['time']->getTasks();
    $view->accounts = $app['time']->getAccounts();    
    $view->items = $app['time']->getFilteredCollection( func_get_args() );

    return $view->page( array( 'panels/list', 'panels/tabs', 'panels/table' ) );
})
->value('start', date('Y-m-d') )
->value('end', date('Y-m-d' ) )
->value('accounts', 'any')
->value('tasks', 'any')
->value('billable', 'any')
->value('orderby', 'date,start' );

$app->get('/filter/{start}/{end}/{accounts}/{tasks}/{billable}/{orderby}', 
    function( $start, $end, $accounts, $tasks, $billable, $orderby ) use ( $app ) {

    $view = $app['view'];

    $params = array(
        'start' => $start,
        'end' => $end,
        'paccounts' => explode(',', $accounts ),
        'ptasks' => explode(',', $tasks ),
        'billable' => $billable,
        'orderby' => $orderby
    );
    $view->add( $params );

    $view->tasks = $app['time']->getTasks();
    $view->accounts = $app['time']->getAccounts();

    return $view->show( 'forms/filter' );

})
->value('start', date('Y-m-d') )
->value('end', date('Y-m-d') )
->value('accounts', 'any')
->value('tasks', 'any') 
->value('billable', 'any')
->value('orderby', 'date,start');

$app->get('/edit/{id}', function( $id ) use ( $app ) {
    
    $view = $app['view'];       
    
    $view->add( $app['time']->get( $id ) );  
    $view->tasks = $app['time']->getTasks();
    $view->accounts = $app['time']->getAccounts();
    
    return $view->show( 'forms/edit' );
    
})
->value( 'id', 0 );

/*******************************************************************
 * 
 * Run Silex Application
 * 
 *******************************************************************/

$app->run();
