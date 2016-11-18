<?php

use yii\base\InvalidParamException;

class ClientInitializeTest extends Codeception\TestCase\Test
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    protected $client;

    protected function _before()
    {
        $this->client = new \bmwx591\privat24\Client();
    }

    protected function _after()
    {
    }

    public function testId()
    {
        $this->expectException(InvalidParamException::class);
        $this->client->setId('e');
        $this->client->setId(-1);
        $this->client->setId(null);
        $this->client->setId(1);
        $this->assertEquals(1, $this->client->getId());
    }

    public function testPassword()
    {
        $this->expectException(InvalidParamException::class);
        $this->client->setPassword(-1);
        $this->client->setPassword(null);
        $this->client->setPassword(1);
        $this->client->setPassword('e-');
        $this->client->setPassword('e');
        $this->assertRegExp('/^[0-9a-zA-Z]{1,32}$/', $this->client->getPassword());
    }

    public function testIsTest()
    {
        $this->expectException(InvalidParamException::class);
        $this->client->setIsTest(1);
        $this->client->setIsTest(null);
        $this->client->setIsTest('a');
        $this->client->setIsTest(true);
        $this->assertTrue($this->client->getIsTest());
        $this->client->setIsTest(false);
        $this->assertFalse($this->client->getIsTest());
    }
}
