<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Resources\APIRequest;

class GetShipping implements APIRequest
{

    public $andreaniNumber;

    public function __construct(string $andreaniNumber)
    {
        $this->andreaniNumber = $andreaniNumber;
    }

    public function getClassArgumentChain(): ?array
    {
        return null;
    }

    public function getServiceName(): string
    {
        return 'getShipping';
    }
}
