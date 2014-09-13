<?php
/**
 * View Service PRovider
 *
 * @copyright 2014 Joseph Hallenbeck
 */

namespace Kynda\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Exposes the View generator to the Silex Application
 */
class ViewServiceProvider implements ServiceProviderInterface {        

    /**
     * Registers an instance of View with the Silex Application
     * 
     * @param \Silex\Application $app
     * @param \Silex\Application $app
     * @return \Kynda\View
     */
    public function register( Application $app )
    {        
        $app['view'] = function( $app ) {
            return new \Kynda\View( $app );                        
        };
    }
    
    public function boot( Application $app )
    {
        
    }
}