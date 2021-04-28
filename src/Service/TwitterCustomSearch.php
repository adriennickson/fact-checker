<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Abraham\TwitterOAuth\TwitterOAuth;

// https://twitteroauth.com/
// https://developer.twitter.com/en/portal/projects/new
class TwitterCustomSearch
{

    private $apiKey;
    private $apiSecretKey;
    private $bearerToken;

    public function __construct(ContainerBagInterface $params)
    {
        $this->apiKey = $params->get('twitter.api.key');
        $this->apiSecretKey = $params->get('twitter.api.secretkey');
        $this->bearerToken = $params->get('twitter.api.bearertoken');
    }

    function request(string $query){
        $connection = new TwitterOAuth($this->apiKey, $this->apiSecretKey, $this->bearerToken);
        $content = $connection->get("search/tweets", ["q" => $query]);
        return $content;
    }

}
