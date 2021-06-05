<?php
/**
 * TimeServicePRovider
 *
 * @copyright 2014 Joseph Hallenbeck
 */

namespace Kynda\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Exposes the Time table to the Silex Application
 */
class TimeServiceProvider implements ServiceProviderInterface {

    /**
     * Registers an instance of Time with the Silex Application
     *
     * @param \Silex\Application $app
     * @param \Silex\Application $app
     * @return \Kynda\Time
     */
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
