<?php

namespace Fedejuret\Andreani;

use stdClass;
use Fedejuret\Andreani\Resources\Response;
use Fedejuret\Andreani\Resources\APIRequest;
use Fedejuret\Andreani\Resources\Connection;

abstract class Enviroment
{
    const PRODUCTION = 'production';
    const SANDBOX = 'sandbox';
}

/**
 * Class Andreani
 * 
 * @package Fedejuret\Andreani
 * 
 * @author Federico Juretich <fedejuret@gmail.com>
 * @license MIT
 */
class Andreani
{
    private $connection;
    private $configuration;
    private $entireConfiguration;
    private $requestArgumentConverter;
    private $enviroment;

    public function __construct($user, $password, $enviroment = Enviroment::SANDBOX)
    {
        $this->enviroment = $enviroment;
        $this->configuration = $this->getConfiguration($enviroment);
        $this->connection = $this->getConnection($user, $password);
    }

    /**
     * Make a request to Andreani API
     * 
     * @param APIRequest $request
     * 
     * @return Response
     */
    public function call(APIRequest $apiRequest): Response
    {

        $serviceName = $apiRequest->getServiceName();
        $configuration = $this->configuration->$serviceName;

        return $this->connection->call($configuration, $this->requestArgumentConverter->getArgumentChain($apiRequest), $apiRequest);
    }

    /**
     * Get the configuration for the enviroment
     * 
     * @param string $enviroment
     * 
     * @return stdClass
     */
    protected function getConfiguration(string $environment): stdClass
    {
        $path = __DIR__ . '/configuration.json';
        $configuration = json_decode(file_get_contents($path));
        $requestConverter = $configuration->resources->request_converter;

        $this->requestArgumentConverter = new $requestConverter();
        $this->entireConfiguration = $configuration;

        return $configuration->services->$environment;
    }

    /**
     * Get a connection to Andreani API
     * 
     * @param string $user
     * @param string $password
     * 
     * @return \Fedejuret\Andreani\Resources\Connection
     */
    protected function getConnection(string $user, string $password): Connection
    {

        $url = $this->entireConfiguration->urls->{$this->enviroment};

        $authHeaders = 'Basic ' . base64_encode($user . ':' . $password);

        $connection = new Connection();

        $bearerToken = $connection::login($url, $authHeaders);
        $connection::setToken($bearerToken);

        return $connection;
    }
}
