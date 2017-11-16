<?php

namespace Postedin\Docode;

use GuzzleHttp\Client;

class DocodeApi
{
    private $token;

    private $client;

    public function __construct($token, array $options = [])
    {
        $this->token = $token;

        $guzzleOptions = $options['guzzleOptions'] ?? [];

        $this->client = new Client(array_merge([
            'base_uri' => 'http://docode.cl/app/api/',
        ], $guzzleOptions));
    }

    public function getProfile(): Profile
    {
        return new Profile($this->request('GET', 'profile'));
    }

    public function request($method, $path): array
    {
        $response = $this->client->request($method, $path);

        return json_decode($response->getBody(), true);
    }
}
