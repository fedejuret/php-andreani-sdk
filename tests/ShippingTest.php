<?php

namespace Fedejuret\Andreani\Tests;

use Fedejuret\Andreani\Andreani;
use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Resources\Response;
use Fedejuret\Andreani\Tests\AndreaniTests;
use Fedejuret\Andreani\Requests\GetShippings;
use Fedejuret\Andreani\Requests\QuoteShipping as QuoteShippingRequest;

class ShippingTest extends AndreaniTests
{

    /**
     * @test
     */
    public function testQuoteShipping()
    {

        $package = new Package();
        $package->weight = 1;
        $package->length = 120;
        $package->width = 50;
        $package->height = 20;
        $package->volume = 200;

        $quoteShipping = new QuoteShippingRequest('400006709', 8000, null, 'CL0003750');
        $quoteShipping->addPackage($package);

        $response = $this->andreani->call($quoteShipping);

        $this->assertTrue($response instanceof Response);
        $this->assertEquals(200, $response->getCode());
    }

    /**
     * @test
     */
    public function testGetShippings()
    {
        $this->markTestSkipped('This test is not implemented yet');

        $getShippings = new GetShippings('400006709', 'CL0003750');

        $response = $this->andreani->call($getShippings);

        $this->assertTrue($response instanceof Response);
        $this->assertEquals(200, $response->getCode());
        $this->assertTrue(is_array($response->getData()));
    }
}
