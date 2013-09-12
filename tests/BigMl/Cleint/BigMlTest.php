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
        $this->assertInstanceOf('BigMl\Client\BigMl', $client);
        $this->assertEquals(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730',
            'version' => 'andromeda',
            'mode' => 'dev',
        ), $client->getOptions());
    }

    public function testConstructDefault()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $this->assertInstanceOf('BigMl\Client\BigMl', $client);
        $this->assertEquals(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730',
            'version' => 'andromeda',
            'mode' => 'dev',
        ), $client->getOptions());
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
        $this->assertEquals('testting', $client->getOption('username'));
    }
}