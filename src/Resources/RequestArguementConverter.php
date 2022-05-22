<?php

namespace Fedejuret\Andreani\Resources;

use ReflectionClass;

final class RequestArguementConverter implements ArgumentConverter
{

    /** 
     * Methods that need to be converted to an array
     * 
     * @var array
     */
    protected $convertRequests = [
        \Fedejuret\Andreani\Requests\CreateOrder::class,
        \Fedejuret\Andreani\Requests\GetShippings::class,
        \Fedejuret\Andreani\Requests\QuoteShipping::class
    ];

    /**
     * @param APIRequest $service
     */
    public function getArgumentChain(APIRequest $service): ?array
    {

        foreach ($this->convertRequests as $class) {
            if ($service instanceof $class) {
                return $service->getClassArgumentChain();
            }
        }

        return null;
    }
}
