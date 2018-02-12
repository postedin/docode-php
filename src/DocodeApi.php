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
            'base_uri' => 'https://docode.cl/app/api/',
        ], $guzzleOptions));
    }

    public function getProfile(): Profile
    {
        return new Profile($this->request('GET', 'profile'));
    }

    public function getAnalyses(array $options = []): array
    {
        if (empty($options['excludeFields'])) {
            $options['excludeFields'] = ['result'];
        }

        $query = '';
        if (! empty($options['excludeFields'])) {
            $query .= 'fields!=' . implode(',', $options['excludeFields']);
        }

        if (! empty($options['fields'])) {
            $query .= 'fields=' . implode(',', $options['fields']);
        }

        return array_map(function ($data) {
            return new Analysis($this, $data);
        }, $this->request('GET', 'analyses', compact('query')));
    }

    public function createAnalysis($filename, $content, $callbackUrl = false): Analysis
    {
        $multipart = [
            [
                'name' => 'file',
                'contents' => $content,
                'filename' => $filename,
            ],
        ];

        if ($callbackUrl) {
            $multipart[] = [
                'name' => 'callback_url',
                'contents' => $callbackUrl,
            ];
        }

        return new Analysis($this, $this->request('POST', 'analyses', compact('multipart')));
    }

    public function getAnalysis($id): Analysis
    {
        return new Analysis($this, $this->request('GET', 'analyses/' . $id));
    }

    public function analyseWeb($id): Analysis
    {
        return new Analysis($this, $this->request('POST', 'analyses/' . $id));
    }


    public function request($method, $path, array $options = []): array
    {
        $options['headers'] = array_merge([
            'Authorization' => 'Token ' . $this->token,
        ], $options['headers'] ?? []);

        try {
            $response = $this->client->request($method, $path, $options);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
        }

        $responseJson = json_decode($response->getBody(), true);

        $code = $response->getStatusCode();
        if ($code >= 200 && $code < 300) {
            return $responseJson;
        }

        if ($code == 403) {
            throw new Exceptions\InvalidTokenException($responseJson['detail'] ?? ($responseJson['error'] ?? 403), $response);
        }

        if ($code == 404) {
            throw new Exceptions\NotFoundException($responseJson['detail'] ?? ($responseJson['error'] ?? 404), $response);
        }

        if ($code == 415) {
            throw new Exceptions\UnsupportedMediaTypeException($responseJson['detail'] ?? ($responseJson['error'] ?? 415), $response);
        }

        if (! empty($responseJson['error'])) {
            throw new Exceptions\ApiException('unkown error: ' . $responseJson['error'], $response);
        }

        throw new Exceptions\ApiException($responseJson['detail'] ?? 'unkown error: ' . $response->getBody(), $response);
    }
}
