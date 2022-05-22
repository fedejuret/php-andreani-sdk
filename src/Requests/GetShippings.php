<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Resources\APIRequest;

class GetShippings implements APIRequest
{

    public $clientCode;
    public $contract;

    public function __construct($contract, $clientCode)
    {
        $this->contract = $contract;
        $this->clientCode = $clientCode;
    }

    public function getClassArgumentChain(): ?array
    {
        $data = [
            'codigoClient' => $this->clientCode,
            'contrato' => $this->contract
        ];

        return $data;
    }

    public function getServiceName(): string
    {
        return 'getShippings';
    }
}
