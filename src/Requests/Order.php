<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Entities\Origin;
use Fedejuret\Andreani\Entities\Sender;
use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Entities\Receiver;
use Fedejuret\Andreani\Entities\Destination;
use Fedejuret\Andreani\Resources\APIRequest;

class Order implements APIRequest
{
    /** @var string */
    public $contract;

    /** @var Origin */
    public $origin;

    /** @var Destination */
    public $destination;

    /** @var Sender */
    public $sender;

    /** @var array */
    public $receiver;

    /** @var array */
    private $packages = [];

    public function __construct(string $contract, Origin $origin, Destination $destination, Sender $sender)
    {
        $this->contract = $contract;
        $this->origin = $origin;
        $this->destination = $destination;
        $this->sender = $sender;
    }

    public function addPackage(Package $package)
    {
        $this->packages[] = $package;
    }

    public function getPackages()
    {
        return $this->packages;
    }

    public function addReceiver(Receiver $receiver)
    {
        $this->receiver[] = $receiver;
    }

    public function getReceivers()
    {
        return $this->receiver;
    }

    public function getServiceName()
    {
        return 'order';
    }
}
