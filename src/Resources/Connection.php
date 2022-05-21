<?php

namespace Fedejuret\Andreani\Resources;

use Exception;
use Fedejuret\Andreani\Resources\Response;
use GuzzleHttp\Client;

class Connection
{

    protected $configuration;
    protected static $token;

    public function __invoke()
    {

        if (empty(self::$token)) {
            throw new Exception('Must login before making a request');
        }
    }

    public function call($configuration, $arguments, $apiRequest): Response
    {
        $this->configuration = $configuration;

        $client = $this->getClient($configuration->url, $configuration->headers);
        $method = strtolower($configuration->method);

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

        if (!in_array('Authorization', $headers)) {
            $headers['Authorization'] = 'Bearer ' . self::$token;
        }
        if (!in_array('x-authorization-token', $headers)) {
            $headers['x-authorization-token'] = self::$token;
        }

        $client = new Client([
            'base_uri' => $url,
            'headers' => $headers
        ]);

        return $client;
    }

    final public static function login(string $url, string $authHeader): string
    {

        $client = new Client([
            'base_uri' => $url
        ]);

        $response = $client->get('/login', [
            'headers' => [
                'Authorization' => $authHeader
            ]
        ]);

        return json_decode($response->getBody()->getContents())->token;
    }

    final public static function setToken(string $token)
    {
        self::$token = $token;
    }

    protected function getResponse($code, $data): Response
    {
        return new Response($code, $data);
    }

    /**
     * Replace the variable in the path with the value of the argument
     * 
     * @param string $path
     * @param APIRequest $apiRequest
     * 
     * @return string
     */
    private function replaceVariable(string $path, APIRequest $apiRequest): string
    {

        if (!isset($this->configuration->variables)) {
            return $path;
        }

        foreach ($this->configuration->variables as $variable) {
            $path = str_replace(':' . $variable, $apiRequest->$variable, $path);
        }

        return $path;
    }
}
