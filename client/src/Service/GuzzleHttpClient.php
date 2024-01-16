<?php

namespace App\Service;

namespace App\Service;

use GuzzleHttp\Client;

class GuzzleHttpClient
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'http://nginx/',
        ]);
    }

    public function getClient(): Client
    {
        return $this->client;
    }
}
