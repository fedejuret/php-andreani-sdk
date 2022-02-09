<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Resources\APIRequest;

class Order implements APIRequest
{
    /** @var string */
    public $contract;

    /** @var array */
    public $origin;

    /** @var array */
    public $destination;

    /** @var array */
    public $sender;

    /** @var array */
    public $receiver;

    /** @var array */
    public $packages;

    public function __construct($contract, $origin, $destination, $sender, $receiver, $packages)
    {
        $this->contract = $contract;
        $this->origin = $origin;
        $this->destination = $destination;
        $this->sender = $sender;
        $this->receiver = $receiver;

        if(array_map(function ($package) {
            return $package instanceof Package;
        }, $packages)) {
            $this->packages = $packages;
        } else {
            throw new \Exception('Package must be an instance of Fedejuret\Andreani\Entities\Package');
        }
    }

    public function getServiceName()
    {
        return 'order';
    }
}
