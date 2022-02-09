<?php

namespace Fedejuret\Andreani;

use Fedejuret\Andreani\Requests\Branches;
use Fedejuret\Andreani\Requests\QuoteShipping;
use Fedejuret\Andreani\Resources\Response;
use Fedejuret\Andreani\Resources\APIRequest;
use Fedejuret\Andreani\Resources\Connection;

class Andreani
{
    protected $connection;
    protected $configuration;
    protected $requestArgumentConverter;

    public function __construct($user, $password, $enviroment)
    {
        $this->configuration = $this->getConfiguration($enviroment);
        $this->connection = $this->getConnection($user, $password);
    }

    public function call(APIRequest $apiRequest): Response
    {
        $serviceName = $apiRequest->getServiceName();
        $configuration = $this->configuration->$serviceName;
        return $this->connection->call($configuration, $this->requestArgumentConverter->getArgumentChain($apiRequest));
    }

    protected function getConfiguration($environment)
    {
        $path = __DIR__ . '/configuration.json';
        $configuration = json_decode(file_get_contents($path));
        $requestConverter = $configuration->resources->request_converter;
        $this->requestArgumentConverter = new $requestConverter();
        return $configuration->services->$environment;
    }

    protected function getConnection($user, $password)
    {
        $authHeaders = 'Basic ' . base64_encode($user . ':' . $password);
        return new Connection($authHeaders);
    }
}

