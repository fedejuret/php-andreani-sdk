<?php

use Fedejuret\Andreani\Andreani;
use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Requests\QuoteShipping as QuoteShippingRequest;
use Fedejuret\Andreani\Resources\Response;
use PHPUnit\Framework\TestCase;

use function PHPUnit\Framework\assertEquals;

class QuoteShippingTest extends TestCase
{

    /**
     * @test
     */
    public function testQuoteShipping()
    {
        $andreani = new Andreani('usuario_test', 'DI$iKqMClEtM', 'sandbox');

        $package = new Package();
        $package->weight = 1;
        $package->length = 120;
        $package->width = 50;
        $package->height = 20;
        $package->volume = 200;

        $quoteShipping = new QuoteShippingRequest('400006709', 8000, null, null);
        $quoteShipping->addPackage($package);

        $response = $andreani->call($quoteShipping);

        $this->assertTrue($response instanceof Response);
        $this>assertEquals(200, $response->getCode());
    }
}
