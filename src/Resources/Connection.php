<?php

namespace Fedejuret\Andreani\Resources;

use Fedejuret\Andreani\Andreani;
use Fedejuret\Andreani\Exceptions\InvalidConfigurationException;
use stdClass;

class Connection
{
    /** @var stdClass */
    protected $configuration;

    /** @var string */
    protected static $token;

    /**
     * @throws InvalidConfigurationException
     */
    public function __invoke()
    {
        if (empty(self::$token)) {
            throw new InvalidConfigurationException('Must login before making a request');
        }
    }

    /**
     * Make a request to the API
     *
     * @param stdClass $configuration Configuration of the request
     * @param ?array $arguments Arguments of the request
     * @param $apiRequest
     * @return Response
     */
    public function call(stdClass $configuration, ?array $arguments, $apiRequest): Response
    {
        $this->configuration = $configuration;

        $client = $this->getClient($configuration->url, $configuration->headers);
        $method = strtolower($configuration->method);

        $path = $configuration->path;

        if (strpos($path, ':') !== false) {
            $path = $this->replaceVariable($path, $apiRequest);
        }

        $response = $client->$method($path, $arguments);

        if (Andreani::$debug) {
            Console::log('Request: ' . $method . ' ' . $path, 'yellow');
            Console::log('Response: ' . $response->getCode(), 'white');
        }

        return $response;
    }

    /**
     * Get prepared client to make requests
     * 
     * It returns a logged in client
     * 
     * @param string $url URL of the API
     * @param array $headers Headers of the request
     * 
     * @return HttpRequest
     */
    protected function getClient(string $url, array $headers = []): HttpRequest
    {

        if (!in_array('Authorization', $headers)) {
            $headers['Authorization'] = 'Bearer ' . self::$token;
        }
        if (!in_array('x-authorization-token', $headers)) {
            $headers['x-authorization-token'] = self::$token;
        }

        return new HttpRequest($url, [
            'headers' => $headers
        ]);
    }

    /**
     * Login to the API and return the token
     *
     * @param string $url URL of the API
     * @param string $authHeader Base64 encoded string of the username and password
     *
     * @return string Bearer Token
     * @throws InvalidConfigurationException
     */
    public static function login(string $url, string $authHeader): string
    {

        if (isset(self::$token)) {
            return self::$token;
        }

        if (Andreani::$debug) {
            Console::log('Login to ' . $url, 'light_green');
        }

        $client = new HttpRequest($url, [
            'headers' => [
                'Authorization' => $authHeader
            ],
        ]);

        $response = $client->get('/login');

        $token = $response->getData()->token;

        if (empty($token)) {
            throw new InvalidConfigurationException('Invalid credentials');
        }

        if (Andreani::$debug) {
            Console::log('Token: ' . $token, 'white');
        }

        return $token;
    }

    /**
     * Set token to be used in the future
     * 
     * @param string $token Bearer Token
     * 
     * @return void
     */
    final public static function setToken(string $token): void
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
