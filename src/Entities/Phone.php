<?php

namespace Fedejuret\Andreani\Entities;

class Phone
{

    private $allowedTypes = ['mobile', 'home', 'work', 'other'];
    private $maxPhoneLength = 15;

    public $number;
    public $type;

    /**
     * @param string $number
     * @param string $type It can be: mobile, home, work or other
     */
    public function __construct($number, string $type)
    {

        if (!in_array($type, $this->allowedTypes)) {
            throw new \Exception('Invalid phone type. Available types are: ' . implode(', ', $this->allowedTypes));
        }

        if (strlen($number) > $this->maxPhoneLength) {
            throw new \Exception('Phone number is too long. Max length is ' . $this->maxPhoneLength);
        }

        $this->number = $number;
        $this->type = $type;
    }

    public function getAllowTypes(): array
    {
        return $this->allowedTypes;
    }

    public function getParsedPhoneType(): int
    {
        switch ($this->type) {
            case 'work':
                return 1;
            case 'mobile':
                return 2;
            case 'home':
                return 3;
            case 'other':
                return 4;
        }
    }
}
