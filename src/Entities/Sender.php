<?php

namespace Fedejuret\Andreani\Entities;

class Sender
{

    public $name;
    public $email;
    public $identityType;
    public $identityNumber;

    private $phones = [];

    public function __construct($name, $email, $identityType, $identityNumber)
    {
        $this->name = $name;
        $this->email = $email;
        $this->identityType = $identityType;
        $this->identityNumber = $identityNumber;
    }

    public function addPhone(Phone $phone)
    {
        $this->phones[] = $phone;
    }

    public function getPhones()
    {
        return $this->phones;
    }

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
