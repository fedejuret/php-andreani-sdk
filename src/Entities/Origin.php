<?php

namespace Fedejuret\Andreani\Entities;

class Origin
{

    private $avaibleOriginTypes = ['postal', 'branchoffice'];

    public $originType;

    /** POSTAL */
    public $city;
    public $region;
    public $country;
    public $postalCode;
    public $street;
    public $streetNumber;

    /** Sucursal */
    public $branchId;

    public function __construct(string $originType)
    {
        if (!in_array($originType, $this->avaibleOriginTypes)) {
            throw new \Exception('Destination type not valid. Available types are: ' . implode(', ', $this->avaibleoriginTypes));
        }

        $this->originType = $originType;
    }

    public function getAvaibleOriginTypes(): array
    {
        return $this->avaibleoriginTypes;
    }

    public function getOriginType(): string
    {
        return $this->originType;
    }

    public function getParsedOrigin(): array
    {
        if ($this->originType == 'postal') {
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
        } else if ($this->originType == 'branchoffice') {
            return [
                'sucursal' => [
                    'id' => $this->branchId
                ]
            ];
        }
    }
}