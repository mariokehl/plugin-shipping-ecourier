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
    private $echo = false;

    /**
     * @param string $uri The endpoint uri (don't forget the ending /)
     * @param array $options A array of config values
     * @param boolean $echo Set to true if you want to use echo mode
     */
    public function __construct(
        $baseUri,
        array $options = [],
        $echo = false
    ) {
        $this->baseUri = $echo ? self::BASE_URI_DEMO : $baseUri;
        $this->apiKey = isset($options['apiKey']) === true ? $options['apiKey'] : 'Missing API-Key!';
        $this->echo = $echo;
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
                'uri'       => $this->baseUri . 'order/new',
                'apiKey'    => $this->apiKey,
                'mode'      => $this->echo,
                'payload'   => json_encode($parameters)
            ]
        );

        return $res;
    }
}
