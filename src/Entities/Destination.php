<?php

namespace Fedejuret\Andreani\Entities;

class Destination
{

    /** @var array */
    private $avaibleDestinationTypes = ['postal', 'branchoffice'];

    /** @var string */
    public $destinationType;

    /** @var string */
    public $city;

    /** @var string */
    public $region;

    /** @var string */
    public $country;

    /** @var string */
    public $postalCode;

    /** @var string */
    public $street;

    /** @var string */
    public $streetNumber;

    public $branchId;

    /**
     * @param string $destinationType It can be: postal or branchoffice
     * 
     * @throws \Exception
     */
    public function __construct(string $destinationType)
    {
        if (!in_array($destinationType, $this->avaibleDestinationTypes)) {
            throw new \Exception('Destination type not valid. Available types are: ' . implode(', ', $this->avaibleDestinationTypes));
        }

        $this->destinationType = $destinationType;
    }

    /**
     * Get avaible destination types
     * 
     * @return string
     */
    public function getAvaibleDestinationTypes(): array
    {
        return $this->avaibleDestinationTypes;
    }

    /**
     * Return the destination type
     * 
     * @return string
     */
    public function getDestinationType(): string
    {
        return $this->destinationType;
    }

    /**
     * Parse destination object to array
     * 
     * @return array
     */
    public function getParsedDestination(): array
    {
        if ($this->destinationType == 'postal') {
            return [
                'postal' => [
                    'localidad' => $this->city,
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
