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

    public function call($configuration, $arguments, $apiRequest): Response
    {
        $this->configuration = $configuration;
        $client = $this->getClient($configuration->url, $configuration->headers);
        $method = $configuration->method;

        $path = $configuration->path;

        if (strpos($path, ':') !== false) {
            $path = $this->replaceVariable($path, $apiRequest);
        }
        
        if ($method === 'get') {
            $response = $client->$method($path, [
                'query' => $arguments,
            ]);
        } else if ($method === 'post') {
            $response = $client->$method($path, [
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
        if (!in_array('x-authorization-token', $headers)) {
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

    private function replaceVariable(string $path, APIRequest $apiRequest)
    {

        foreach ($this->configuration->variables as $variable) {
            $path = str_replace(':' . $variable, $apiRequest->$variable, $path);
        }
        
        return $path;
    }
}
