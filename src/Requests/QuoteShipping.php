<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Resources\APIRequest;

class QuoteShipping implements APIRequest
{
    public $contract;
    public $packages;
    public $postalCodeDestination;
    public $client;
    public $branchOrigin;

    public function __construct($contract, $packages, $postalCodeDestination, $branchOrigin, $client)
    {
        $this->contract = $contract;
        $this->postalCodeDestination = $postalCodeDestination;
        $this->branchOrigin = $branchOrigin;
        $this->client = $client;

        if (array_map(function ($package) {
            return $package instanceof Package;
        }, $packages)) {
            $this->packages = $packages;
        } else {
            throw new \Exception('Package must be an instance of Fedejuret\Andreani\Entities\Package');
        }
    }

    public function getServiceName()
    {
        return 'quoteShipping';
    }
}
