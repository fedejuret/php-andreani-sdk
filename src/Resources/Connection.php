<?php

namespace Fedejuret\Andreani\Resources;

use Fedejuret\Andreani\Resources\Response;
use GuzzleHttp\Client;

class Connection
{

    protected $configuration;
    protected $authHeader;
    protected $token;

    public function __construct($authHeader)
    {
        $this->authHeader = $authHeader;
    }

    public function call($configuration, $arguments): Response
    {
        $client = $this->getClient($configuration->url, $configuration->headers);
        $method = $configuration->method;

        if ($method === 'get') {
            $response = $client->$method($configuration->path, [
                'query' => $arguments,
            ]);
        } else if ($method === 'post') {
            $response = $client->$method($configuration->path, [
                'json' => $arguments,
            ]);
        }

        return $this->getResponse($response->getStatusCode(), $response->getBody()->getContents());
    }

    protected function getClient($url, $headers = []): Client
    {

        $client = new Client([
            'base_uri' => 'https://apisqa.andreani.com'
        ]);

        $response = $client->get('/login', [
            'headers' => [
                'Authorization' => $this->authHeader
            ]
        ]);

        $this->token = json_decode($response->getBody()->getContents())->token;

        if (!in_array('Authorization', $headers)) {
            $headers['Authorization'] = 'Bearer ' . $this->token;
        }
        if(!in_array('x-authorization-token', $headers)){
            $headers['x-authorization-token'] = $this->token;
        }

        $client = new Client([
            'base_uri' => $url,
            'headers' => $headers
        ]);

        return $client;
    }

    protected function getResponse($code, $data): Response
    {
        return new Response($code, $data);
    }
}
