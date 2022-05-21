<?php
namespace Fedejuret\Andreani\Tests;

use Fedejuret\Andreani\Requests\GetBranches;
use Fedejuret\Andreani\Resources\Response;
use Fedejuret\Andreani\Tests\AndreaniTests;

class BranchesTest extends AndreaniTests
{

    /**
     * @test
     */
    public function testGetAllBranches()
    {

        $response = $this->andreani->call(new GetBranches());

        $this->assertTrue($response instanceof Response);
        $this->assertTrue($response->getCode() == 200);

        $data = $response->getData(true);
        
        $this->assertIsArray($data);
        $this->assertArrayHasKey('id', $data[0]);
    }
}
