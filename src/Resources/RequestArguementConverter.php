<?php

namespace Fedejuret\Andreani\Resources;

use Fedejuret\Andreani\Entities\Origin;
use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Entities\Receiver;
use Fedejuret\Andreani\Requests\Order;
use Fedejuret\Andreani\Requests\QuoteShipping;

class RequestArguementConverter implements ArgumentConverter
{

    public function getArgumentChain(APIRequest $service)
    {

        if ($service->getServiceName() == 'quoteShipping') {
            return $this->getArgumentChainForQuoteShipping($service);
        }

        if ($service->getServiceName() == 'order') {
            return $this->getArgumentChainForOrder($service);
        }
    }

    private function getArgumentChainForQuoteShipping(QuoteShipping $service)
    {

        if (count($service->getPackages()) === 0) {
            throw new \Exception('There are no packages in this request');
        }

        $packages = array_map(function ($package) {

            if (!$package instanceof Package) {
                throw new \Exception('Package must be an instance of Fedejuret\Andreani\Entities\Package');
            }

            return [
                'kilos' => $package->weight,
                'largoCm' => $package->length,
                'anchoCm' => $package->width,
                'valorDeclarado' => $package->value
            ];
        }, $service->getPackages());

        return [
            'contrato' => $service->contract,
            'cliente' => $service->client,
            'sucursalOrigen' => $service->branchOrigin,
            'cpDestino' => $service->postalCodeDestination,
            'bultos' => $packages,
        ];
    }

    private function getArgumentChainForOrder(Order $service)
    {

        if (count($service->getPackages()) === 0) {
            throw new \Exception('There are no packages in this request');
        }

        $packages = array_map(function ($package) {

            if (!$package instanceof Package) {
                throw new \Exception('Package must be an instance of Fedejuret\Andreani\Entities\Package');
            }

            return [
                'kilos' => $package->weight,
                // 'largoCm' => $package['length'],
                // 'anchoCm' => $package['width'],
                'volumenCm' => $package->volume
            ];
        }, $service->getPackages());

        return [
            'contrato' => $service->contract,
            'origen' => $service->origin->getParsedOrigin(),
            'destino' => $service->destination->getParsedDestination(),
            'remitente' => $service->sender->getParsedSender(),
            'destinatario' => array_map(function (Receiver $receiver) {
                return $receiver->getParsedReceiver();
            }, $service->getReceivers()),
            'bultos' => $packages,
        ];
    }
}
