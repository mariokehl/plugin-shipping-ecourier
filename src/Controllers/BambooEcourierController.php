<?php

namespace BambooEcourier\Controllers;

use Plenty\Plugin\Controller;
use Plenty\Plugin\Templates\Twig;

class BambooEcourierController extends Controller
{
    /**
     * @param Twig $twig
     * @return string
     */
    public function getHelloWorldPage(Twig $twig):string
    {
        return $twig->render('BambooEcourier::Index');
    }
}