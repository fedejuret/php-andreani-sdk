<?php

namespace Fedejuret\Andreani\Resources;

interface ArgumentConverter
{

    public function getArgumentChain(APIRequest $consulta);
}
