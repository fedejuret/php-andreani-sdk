<?php

namespace Fedejuret\Andreani\Resources;

interface APIRequest
{
    public function getClassArgumentChain(): ?array;
    public function getServiceName(): string;
}