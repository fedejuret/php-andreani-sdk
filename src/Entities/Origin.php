<?php

namespace Fedejuret\Andreani\Entities;

use Fedejuret\Andreani\Exceptions\InvalidConfigurationException;

class Origin
{

    /** @var array */
    private $avaibleOriginTypes = ['postal', 'branchoffice'];

    /** @var string */
    public $originType;

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
     * @param string $originType It can be: postal or branchoffice
     * 
     * @throws \Fedejuret\Andreani\Exceptions\InvalidConfigurationException
     */
    public function __construct(string $originType)
    {
        if (!in_array($originType, $this->avaibleOriginTypes)) {
            throw new InvalidConfigurationException('Destination type not valid. Available types are: ' . implode(', ', $this->avaibleoriginTypes));
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
