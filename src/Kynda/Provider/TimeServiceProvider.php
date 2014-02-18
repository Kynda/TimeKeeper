<?php

namespace Kynda\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class TimeServiceProvider implements ServiceProviderInterface {        

    public function register( Application $app )
    {        
        $app['time'] = $app->share( function( $app ) {
            return new \Kynda\Time( $app['db'] );
        });
    }
    
    public function boot( Application $app )
    {
        
    }
}