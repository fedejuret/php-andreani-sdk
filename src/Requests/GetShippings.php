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

    public function getServiceName()
    {
        return 'getShippings';
    }
}
