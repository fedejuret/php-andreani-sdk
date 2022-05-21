<?php

namespace Fedejuret\Andreani\Resources;

use Fedejuret\Andreani\Entities\Package;
use Fedejuret\Andreani\Entities\Receiver;
use Fedejuret\Andreani\Requests\CreateOrder;
use Fedejuret\Andreani\Requests\QuoteShipping;
use Fedejuret\Andreani\Exceptions\InvalidConfigurationException;
use Fedejuret\Andreani\Requests\GetShippings;

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

        if ($service->getServiceName() == 'getShippings') {
            return $this->getArgumentChainForGetShippings($service);
        }
    }

    /**
     * @param QuoteShipping $service
     * 
     * @throws \Fedejuret\Andreani\Exceptions\InvalidConfigurationException
     * 
     * @return array
     */
    private function getArgumentChainForQuoteShipping(QuoteShipping $service): array
    {

        if (count($service->getPackages()) === 0) {
            throw new InvalidConfigurationException('There are no packages in this request');
        }

        $packages = array_map(function ($package) {

            if (!$package instanceof Package) {
                throw new InvalidConfigurationException('Package must be an instance of Fedejuret\Andreani\Entities\Package');
            }

            return [
                'kilos' => $package->weight,
                'largoCm' => $package->length,
                'anchoCm' => $package->width,
                'valorDeclarado' => $package->value
            ];
        }, $service->getPackages());

        $data = [
            'contrato' => $service->contract,
            'cliente' => $service->client,
            'sucursalOrigen' => $service->branchOrigin,
            'cpDestino' => $service->postalCodeDestination,
            'bultos' => $packages,
        ];

        return $data;
    }

    /**
     * @param CreateOrder $service
     * 
     * @throws \Fedejuret\Andreani\Exceptions\InvalidConfigurationException
     * 
     * @return array
     */
    private function getArgumentChainForOrder(CreateOrder $service): array
    {

        if (count($service->getPackages()) === 0) {
            throw new InvalidConfigurationException('There are no packages in this request');
        }

        $packages = array_map(function ($package) {

            if (!$package instanceof Package) {
                throw new InvalidConfigurationException('Package must be an instance of Fedejuret\Andreani\Entities\Package');
            }

            return [
                'kilos' => $package->weight,
                // 'largoCm' => $package['length'],
                // 'anchoCm' => $package['width'],
                'volumenCm' => $package->volume
            ];
        }, $service->getPackages());

        $data = [
            'contrato' => $service->contract,
            'origen' => $service->origin->getParsedOrigin(),
            'destino' => $service->destination->getParsedDestination(),
            'remitente' => $service->sender->getParsedSender(),
            'destinatario' => array_map(function (Receiver $receiver) {
                return $receiver->getParsedReceiver();
            }, $service->getReceivers()),
            'bultos' => $packages,
        ];

        return $data;
    }

    /**
     * @param GetShippings $service
     * 
     * @return array
     */
    private function getArgumentChainForGetShippings(GetShippings $service): array
    {
        $data = [
            'codigoClient' => $service->clientCode,
            'contrato' => $service->contract
        ];

        return $data;
    }
}
