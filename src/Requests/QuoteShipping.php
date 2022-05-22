<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Exceptions\InvalidConfigurationException;
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

    /**
     * @param QuoteShipping $service
     * 
     * @throws \Fedejuret\Andreani\Exceptions\InvalidConfigurationException
     * 
     * @return array
     */
    public function getClassArgumentChain(): array
    {

        if (count($this->packages) === 0) {
            throw new InvalidConfigurationException('There are no packages in this request');
        }

        $packages = array_map(function ($package) {

            if (!$package instanceof Package) {
                throw new InvalidConfigurationException('Package must be an instance of Fedejuret\Andreani\Entities\Package');
            }

            return [
                'kilos' => $package->weight,
                'largoCm' => $package->length,
                'anchoCm' => $package->width,
                'valorDeclarado' => $package->value
            ];
        }, $this->packages);

        return [
            'contrato' => $this->contract,
            'cliente' => $this->client,
            'sucursalOrigen' => $this->branchOrigin,
            'cpDestino' => $this->postalCodeDestination,
            'bultos' => $packages,
        ];
    }

    public function getServiceName(): string
    {
        return 'quoteShipping';
    }
}
