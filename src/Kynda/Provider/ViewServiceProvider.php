<?php

namespace Kynda\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class ViewServiceProvider implements ServiceProviderInterface {        

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