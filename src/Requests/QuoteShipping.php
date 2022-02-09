<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Resources\APIRequest;

class QuoteShipping implements APIRequest
{
    public $contract;
    public $postalCodeDestination;
    public $client;
    public $branchOrigin;

    private $packages = [];

    public function __construct($contract, $postalCodeDestination, $branchOrigin, $client)
    {
        $this->contract = $contract;
        $this->postalCodeDestination = $postalCodeDestination;
        $this->branchOrigin = $branchOrigin;
        $this->client = $client;
    }

    public function addPackage(Package $package)
    {
        $this->packages[] = $package;
    }

    public function getPackages()
    {
        return $this->packages;
    }

    public function getServiceName()
    {
        return 'quoteShipping';
    }
}
