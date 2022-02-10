<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Resources\APIRequest;

class GetOrder implements APIRequest
{

    public $orderId;

    public function __construct(string $orderId)
    {
        $this->orderId = $orderId;
    }

    public function getServiceName()
    {
        return 'getOrder';
    }
}
