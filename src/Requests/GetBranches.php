<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Resources\APIRequest;

class GetBranches implements APIRequest
{

    public function getServiceName(): string
    {
        return 'branches';
    }
}
