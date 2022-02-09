<?php

namespace Fedejuret\Andreani\Entities;

class Destination
{

    private $avaibleDestinationTypes = ['postal', 'branchoffice'];

    public $destinationType;

    /** POSTAL */
    public $city;
    public $region;
    public $country;
    public $postalCode;
    public $street;
    public $streetNumber;

    /** Sucursal */
    public $branchId;

    /**
     * @param string $destinationType It can be: postal or branchoffice
     * @throws \Exception
     */
    public function __construct(string $destinationType)
    {
        if (!in_array($destinationType, $this->avaibleDestinationTypes)) {
            throw new \Exception('Destination type not valid. Available types are: ' . implode(', ', $this->avaibleDestinationTypes));
        }

        $this->destinationType = $destinationType;
    }

    public function getAvaibleDestinationTypes(): array
    {
        return $this->avaibleDestinationTypes;
    }

    public function getDestinationType(): string
    {
        return $this->destinationType;
    }

    public function getParsedDestination(): array
    {
        if ($this->destinationType == 'postal') {
            return [
                'postal' => [
                    'ciudad' => $this->city,
                    'region' => $this->region,
                    'pais' => $this->country,
                    'codigoPostal' => $this->postalCode,
                    'calle' => $this->street,
                    'numero' => $this->streetNumber
                ]
            ];
        } else if ($this->destinationType == 'branchoffice') {
            return [
                'sucursal' => [
                    'id' => $this->branchId
                ]
            ];
        }
    }
}
