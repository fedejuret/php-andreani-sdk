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

    public function __construct(string $contract, $postalCodeDestination, $branchOrigin, $client)
    {
        $this->contract = $contract;
        $this->postalCodeDestination = $postalCodeDestination;
        $this->branchOrigin = $branchOrigin;
        $this->client = $client;
    }

    /**
     * @param Package $package
     * 
     * @return void
     */
    public function addPackage(Package $package): void
    {
        $this->packages[] = $package;
    }

    /**
     * Get packages of the order
     * 
     * @return array
     */
    public function getPackages(): array
    {
        return $this->packages;
    }

    public function getServiceName(): string
    {
        return 'quoteShipping';
    }
}
