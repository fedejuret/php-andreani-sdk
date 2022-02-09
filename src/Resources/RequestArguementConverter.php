<?php

namespace Fedejuret\Andreani\Resources;

use Fedejuret\Andreani\Entities\Package;

class RequestArguementConverter implements ArgumentConverter
{

    public function getArgumentChain(APIRequest $service)
    {

        if ($service->getServiceName() == 'quoteShipping') {
            return $this->getArgumentChainForQuoteShipping($service);
        }

        if ($service->getServiceName() == 'order') {
            dump(json_encode($this->getArgumentChainForOrder($service)));
            return $this->getArgumentChainForOrder($service);
        }
    }

    private function getArgumentChainForQuoteShipping(APIRequest $service)
    {

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
        }, $service->packages);

        return [
            'contrato' => $service->contract,
            'cliente' => $service->client,
            'sucursalOrigen' => $service->branchOrigin,
            'cpDestino' => $service->postalCodeDestination,
            'bultos' => $packages,
        ];
    }

    private function getArgumentChainForOrder(APIRequest $service)
    {

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
        }, $service->packages);

        return [
            'contrato' => $service->contract,
            'origen' => $service->origin,
            'destino' => $service->destination,
            'remitente' => $service->sender,
            'destinatario' => $service->receiver,
            'bultos' => $packages,
        ];
    }
}
