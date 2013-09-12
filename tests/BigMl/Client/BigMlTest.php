<?php
namespace BigMl\Client;
use PHPUnit_Framework_TestCase;

class BigMlTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730',
            'version' => 'andromeda',
            'mode' => 'dev',
        ));
        $this->assertEquals('alfred', $client->getOption('username'));
        $this->assertEquals('79138a622755a2383660347f895444b1eb927730', $client->getOption('api_key'));
        $this->assertEquals('andromeda', $client->getOption('version'));
        $this->assertEquals('dev', $client->getOption('mode'));
    }

    public function testConstructDefault()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $this->assertEquals('alfred', $client->getOption('username'));
        $this->assertEquals('79138a622755a2383660347f895444b1eb927730', $client->getOption('api_key'));
        $this->assertEquals('andromeda', $client->getOption('version'));
        $this->assertEquals('dev', $client->getOption('mode'));
    }

    public function testConstructGetClient()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $this->assertInstanceof('ZendRest\Client\RestClient', $client->getClient());
    }

    public function testConstructSetClient()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $mock = $this->getMock('ZendRest\Client\RestClient');
        $client->setClient($mock);
        $this->assertEquals($mock, $client->getClient());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testConstructWithoutParameters()
    {
        $client = new BigMl(array());
    }

    public function testGetOption()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $this->assertEquals('alfred', $client->getOption('username'));
    }

    public function testSetOption()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $client->setOption('username', 'testing');
        $this->assertEquals('testing', $client->getOption('username'));
    }
}