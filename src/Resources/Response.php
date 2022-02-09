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

    public function getData()
    {
        return $this->data;
    }

}
