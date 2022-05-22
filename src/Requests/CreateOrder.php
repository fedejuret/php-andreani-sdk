<?php

namespace Fedejuret\Andreani\Requests;

use Fedejuret\Andreani\Entities\Origin;
use Fedejuret\Andreani\Entities\Sender;
use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Entities\Receiver;
use Fedejuret\Andreani\Entities\Destination;
use Fedejuret\Andreani\Resources\APIRequest;

class CreateOrder implements APIRequest
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

    /**
     * Add packages to the order
     * 
     * @param Package $package
     */
    public function addPackage(Package $package): void
    {
        $this->packages[] = $package;
    }

    /**
     * Get packages of the order
     * 
     * @return array
     */
    public function getPackages(): ?array
    {
        return $this->packages;
    }

    /**
     * Add receiver to the order
     * 
     * @param Receiver $receiver
     */
    public function addReceiver(Receiver $receiver): void
    {
        $this->receiver[] = $receiver;
    }

    /**
     * Get receivers of the order
     * 
     * @return array
     */
    public function getReceivers(): ?array
    {
        return $this->receiver;
    }

    public function getServiceName(): string
    {
        return 'order';
    }
}
