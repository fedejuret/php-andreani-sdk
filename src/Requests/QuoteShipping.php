<?php

namespace Fedejuret\Andreani\Requests;

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
        // TODO: Add validation for packages
        $this->packages = $packages;
        $this->postalCodeDestination = $postalCodeDestination;
        $this->branchOrigin = $branchOrigin;
        $this->client = $client;
    }

    public function getServiceName()
    {
        return 'quoteShipping';
    }
}
