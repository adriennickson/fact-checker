<?php

namespace App\Service;

use Google\Client;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

// https://developers.google.com/custom-search/v1/overview
class GoogleCustomSearch
{

    private $apiKey;

    public function __construct(ContainerBagInterface $params)
    {
        $this->apiKey = $params->get('google.custom.search.api.key');
    }

    function request(string $query){
        $ch = curl_init( 'https://customsearch.googleapis.com/customsearch/v1?'. http_build_query([
            'cx'=> '48677839be897be72',
            'key' => $this->apiKey,
            'q' => $query
        ]) );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        # Return response instead of printing.
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
        # Send request.
        $result = curl_exec($ch);
        curl_close($ch);
        # Print response.
        return json_decode($result);
    }

}
