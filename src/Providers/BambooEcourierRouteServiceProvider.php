<?php

namespace BambooEcourier\Providers;

use Plenty\Plugin\RouteServiceProvider;
use Plenty\Plugin\Routing\Router;

/**
 * Class BambooEcourierRouteServiceProvider
 * @package BambooEcourier\Providers
 */
class BambooEcourierRouteServiceProvider extends RouteServiceProvider
{
    /**
     * @param Router $router
     */
    public function map(Router $router)
    {
        $router->get('hello-world','BambooEcourier\Controllers\BambooEcourierController@getHelloWorldPage');
    }
}