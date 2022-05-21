<?php

namespace Fedejuret\Andreani\Resources;

use Fedejuret\Andreani\Resources\Response;
use Fedejuret\Andreani\Exceptions\InvalidConfigurationException;

class Connection
{

    protected $configuration;
    protected static $token;

    public function __invoke()
    {
        if (empty(self::$token)) {
            throw new InvalidConfigurationException('Must login before making a request');
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

        $response = $client->$method($path, $arguments);

        return $response;
    }

    protected function getClient($url, $headers = []): HttpRequest
    {

        if (!in_array('Authorization', $headers)) {
            $headers['Authorization'] = 'Bearer ' . self::$token;
        }
        if (!in_array('x-authorization-token', $headers)) {
            $headers['x-authorization-token'] = self::$token;
        }

        $client = new HttpRequest($url, [
            'headers' => $headers
        ]);

        return $client;
    }

    final public static function login(string $url, string $authHeader): string
    {

        if (isset(self::$token)) {
            return self::$token;
        }

        $client = new HttpRequest($url, [
            'headers' => [
                'Authorization' => $authHeader
            ],
        ]);

        $response = $client->get('/login');

        return $response->getData()->token;
    }

    final public static function setToken(string $token)
    {
        self::$token = $token;
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
