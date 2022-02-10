<?php

namespace Fedejuret\Andreani\Resources;

class Response
{

    private $code;
    private $data;

    public function __construct($code, $data)
    {
        $this->code = $code;
        $this->data = $data;
    }

    public function getCode()
    {
        return $this->code;
    }

    public function getData($array = false)
    {
        return $array ? json_decode($this->data, true) : json_decode($this->data);
    }

}
