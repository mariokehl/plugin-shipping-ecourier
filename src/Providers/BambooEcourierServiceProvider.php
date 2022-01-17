<?php

namespace BambooEcourier\Providers;

use Plenty\Plugin\ServiceProvider;

/**
 * Class BambooEcourierServiceProvider
 * @package BambooEcourier\Providers
 */
class BambooEcourierServiceProvider extends ServiceProvider
{
    /**
    * Register the route service provider
    */
    public function register()
    {
        $this->getApplication()->register(BambooEcourierRouteServiceProvider::class);
    }
}