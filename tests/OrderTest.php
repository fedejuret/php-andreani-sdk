<?php

use PHPUnit\Framework\TestCase;
use Fedejuret\Andreani\Andreani;
use Fedejuret\Andreani\Entities\Phone;
use Fedejuret\Andreani\Entities\Origin;
use Fedejuret\Andreani\Entities\Sender;
use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Entities\Receiver;
use Fedejuret\Andreani\Resources\Response;
use Fedejuret\Andreani\Entities\Destination;
use Fedejuret\Andreani\Requests\CreateOrder;
use Fedejuret\Andreani\Requests\GetOrder;

use function PHPUnit\Framework\assertEquals;

class OrderTest extends TestCase
{

    private $andreani;

    public function __construct()
    {
        $this->andreani = new Andreani('usuario_test', 'DI$iKqMClEtM', 'sandbox');
        parent::__construct();
    }

    /**
     * @test
     */
    public function testCreateOrderRequest()
    {

        $package = new Package();
        $package->weight = 1;
        $package->length = 120;
        $package->width = 50;
        $package->height = 20;
        $package->volume = 200;

        $sender = new Sender('Federico Juretich', 'fedejuret@gmail.com', 'DNI', '72637626');
        $sender->addPhone(new Phone('+5492923565508', 'mobile'));

        $receiver = new Receiver('Federico Juretich', 'fedejuret@gmail.com', 'DNI', '72637626');
        $receiver->addPhone(new Phone('+5492923565508', 'mobile'));

        $destination = new Destination('postal');
        $destination->postalCode = '8000';
        $destination->city = 'Buenos Aires';
        $destination->street = 'Belgrano';
        $destination->streetNumber = '20';
        $destination->country = 'Argentina';

        $origin = new Origin('postal');
        $origin->postalCode = '8000';
        $origin->city = 'Bahia Blanca';
        $origin->street = 'Belgrano';
        $origin->streetNumber = '20';
        $origin->country = 'Argentina';

        $order = new CreateOrder('400006709', $origin, $destination, $sender);
        $order->addPackage($package);
        $order->addReceiver($receiver);

        $response = $this->andreani->call($order);

        $this->assertTrue($response instanceof Response);
        $this > assertEquals(202, $response->getCode());

        $data = $response->getData();
        
        $this->assertNotEmpty($data->bultos);
        return $data->bultos[0]->numeroDeEnvio;
    }

    /**
     * @test
     * @depends testCreateOrderRequest
     */
    public function testGetOrderStatus($orderId)
    {

        $order = new GetOrder($orderId);

        $response = $this->andreani->call($order);

        $this->assertTrue($response instanceof Response);
        $this->assertEquals(200, $response->getCode());

        $data = $response->getData();

        $this->assertNotEmpty($data->bultos);

    }
}