<?php

namespace Fedejuret\Andreani\Tests;

use Fedejuret\Andreani\Andreani;
use PHPUnit\Framework\TestCase;

class AndreaniTests extends TestCase
{
    /** @var Andreani */
    protected $andreani; 

    public function __construct()
    {
        parent::__construct();
        $this->andreani = new Andreani('usuario_test', 'DI$iKqMClEtM', 'sandbox');
    }

}
