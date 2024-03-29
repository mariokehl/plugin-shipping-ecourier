<?php

namespace BambooEcourier\API;

use Plenty\Modules\Plugin\Libs\Contracts\LibraryCallContract;

class EcourierWS
{
    // URI for testing (valid for every customer)
    const BASE_URI_DEMO = 'https://bamboo-demo.de/ecourier/sf/web/transfer/sj/';

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
    private $echo = true;

    /**
     * @param string $uri The endpoint uri (don't forget the ending /)
     * @param array $options A array of config values
     * @param boolean $demo Set to true if you want to use demo system
     */
    public function __construct(
        $baseUri,
        array $options = [],
        $demo = true
    ) {
        $this->baseUri = $demo ? self::BASE_URI_DEMO : $baseUri;
        $this->apiKey = isset($options['apiKey']) === true ? $options['apiKey'] : 'Missing API-Key!';
    }

    /**
     * @param mixed $parameters The value ready to be JSON encoded
     * @return \stdClass|array Object or array in case of error
     */
    public function EcourierWS_CreateOrder($parameters)
    {
        /** @var LibraryCallContract $libCall */
        $libCall = pluginApp(LibraryCallContract::class);

        $res = $libCall->call(
            'BambooEcourier::guzzle_connector',
            [
                'uri' => $this->baseUri . 'order/new',
                'apiKey' => $this->apiKey,
                'echo' => $this->echo,
                'payload' => json_encode($parameters)
            ]
        );

        return $res;
    }
}
