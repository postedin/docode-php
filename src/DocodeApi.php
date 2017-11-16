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

    public function getAnalyses(): array
    {
        return array_map(function ($data) {
            return new Analysis($data);
        }, $this->request('GET', 'analyses'));
    }

    public function getAnalysis($id): Analysis
    {
        return new Analysis($this->request('GET', 'analyses/' . $id));
    }

    public function request($method, $path): array
    {
        $response = $this->client->request($method, $path);

        $responseJson = json_decode($response->getBody(), true);

        $code = $response->getStatusCode();
        if ($code >= 200 && $code < 300) {
            return $responseJson;
        }

        if ($code == 403) {
            throw new Exceptions\InvalidTokenException($responseJson['detail'], $response);
        }

        if ($code == 404) {
            throw new Exceptions\NotFoundException($responseJson['detail'], $response);
        }

        throw new Exceptions\ApiException($responseJson['detail'] ?? 'unkown error', $response);
    }
}
