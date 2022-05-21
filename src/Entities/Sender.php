<?php

namespace Fedejuret\Andreani\Entities;

class Sender
{
    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $identityType;

    /** @var string */
    public $identityNumber;

    /** @var array */
    private $phones = [];

    public function __construct($name, $email, $identityType, $identityNumber)
    {
        $this->name = $name;
        $this->email = $email;
        $this->identityType = $identityType;
        $this->identityNumber = $identityNumber;
    }

    /**
     * Add phone to list
     *
     * @param Phone $phone
     *
     * @return void
     */
    public function addPhone(Phone $phone)
    {
        $this->phones[] = $phone;
    }

    /**
     * Get phones
     *
     * @return array
     */
    public function getPhones(): array
    {
        return $this->phones;
    }

    /**
     * Parse this object to array
     *
     * @return array
     */
    public function getParsedSender(): array
    {

        $phones = array_map(function (Phone $phone) {
            return [
                'numero' => $phone->number,
                'tipo' => $phone->getParsedPhoneType(),
            ];
        }, $this->phones);

        return [
            'nombreCompleto' => $this->name,
            'email' => $this->email,
            'documentoTipo' => $this->identityType,
            'documentoNumero' => $this->identityNumber,
            'telefonos' => $phones
        ];
    }
}
