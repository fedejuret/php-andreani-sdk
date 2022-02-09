<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Resources\APIRequest;

class Branches implements APIRequest
{

    public function getServiceName()
    {
        return 'branches';
    }
}
