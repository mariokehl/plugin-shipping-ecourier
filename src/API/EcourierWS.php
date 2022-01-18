<?php

namespace BambooEcourier\API;

use Plenty\Modules\Plugin\Libs\Contracts\LibraryCallContract;

class EcourierWS
{
    // URI for testing (valid for every customer)
    const BASE_URI_DEMO = 'https://bamboo-demo.de/ecourier/sf/web/transfer/sj/';

    /**
     * @var LibraryCallContract $externalSdk
     */
    private $externalSdk;

    /**
     * @var string $baseUri
     */
    private $baseUri;

    /**
     * @var string $apiKey
     */
    private $apiKey;

    /**
     * @var boolean $echo
     */
    private $echo = false;

    /**
     * @param LibraryCallContract $externalSdk
     * @param string $uri The endpoint uri (don't forget the ending /)
     * @param array $options A array of config values
     * @param boolean $echo Set to true if you want to use echo mode
     */
    public function __construct(
        $externalSdk,
        $baseUri,
        array $options = [],
        $echo = false
    ) {
        $this->externalSdk = $externalSdk;
        $this->baseUri = $echo ? self::BASE_URI_DEMO : $baseUri;
        $this->apiKey = isset($options['apiKey']) === true ? $options['apiKey'] : 'Missing API-Key!';
        $this->echo = $echo;
    }

    /**
     * @param mixed $parameters
     * @return mixed
     */
    public function EcourierWS_CreateOrder($parameters)
    {
        return $this->externalSdk->call(
            'BambooEcourier::guzzle_connector',
            [
                'uri'       => $this->baseUri . 'order/new',
                'apiKey'    => $this->apiKey,
                'mode'      => $this->echo,
                'payload'   => json_encode($parameters)
            ]
        );
    }
}
