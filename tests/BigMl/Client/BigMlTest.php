<?php
namespace BigMl\Client;
use PHPUnit_Framework_TestCase;
use ReflectionMethod;

class BigMlTest extends PHPUnit_Framework_TestCase
{
    public function testConstruct()
    {
        $client = new BigMl(array(
            'access_point' => 'https://bigml.io/dev/',
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730',
            'version' => 'andromeda',
        ));
        $this->assertEquals('alfred', $client->getOption('username'));
        $this->assertEquals('79138a622755a2383660347f895444b1eb927730', $client->getOption('api_key'));
        $this->assertEquals('andromeda', $client->getOption('version'));
        $this->assertEquals('https://bigml.io/dev/', $client->getOption('access_point'));
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
        $this->assertEquals('https://bigml.io/', $client->getOption('access_point'));
    }

    public function testConstructGetClient()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $this->assertInstanceof('Zend\Http\Client', $client->getClient());
    }

    public function testConstructSetClient()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $mock = $this->getMock('Zend\Http\Client');
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

    public function testRestGet()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $client->setClient($this->getClientMock());
        $this->assertEquals(array('status' => 'ok'), $client->restGet('source'));
    }

    public function testRestPut()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $client->setClient($this->getClientMock());
        $this->assertEquals(array('status' => 'ok'), $client->restPut('source'));
    }

    public function testRestPost()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $client->setClient($this->getClientMock());
        $this->assertEquals(array('status' => 'ok'), $client->restPost('source'));
    }

    public function testRestDelete()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $client->setClient($this->getClientMock());
        $this->assertEquals(array('status' => 'ok'), $client->restDelete('source'));
    }

    public function testPrepareUri()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));

        $method = new ReflectionMethod(
          get_class($client), 'prepareUri'
        );
        $method->setAccessible(TRUE);
        $this->assertEquals(
            'https://bigml.io/andromeda/abc?username=alfred;api_key=79138a622755a2383660347f895444b1eb927730', $method->invoke($client, 'abc')
        );
    }

    public function testPrepareUriForPredictionDefault()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));

        $method = new ReflectionMethod(
          get_class($client), 'prepareUri'
        );
        $method->setAccessible(TRUE);
        $this->assertEquals(
            'https://bigml.io/andromeda/prediction?username=alfred;api_key=79138a622755a2383660347f895444b1eb927730', $method->invoke($client, 'prediction')
        );
    }

    public function testPrepareUriForPredictionCustom()
    {
        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730',
            'access_point_prediction' => 'https://prediction.bigml.io/',
        ));

        $method = new ReflectionMethod(
          get_class($client), 'prepareUri'
        );
        $method->setAccessible(TRUE);
        $this->assertEquals(
            'https://prediction.bigml.io/andromeda/prediction/abc?username=alfred;api_key=79138a622755a2383660347f895444b1eb927730', $method->invoke($client, 'prediction/abc')
        );
    }

    public function testProcessResponse()
    {
        $response = $this->getMock('Zend\Http\Response');
        $response->expects($this->once())
             ->method('getBody')
             ->will($this->returnValue(json_encode(array('abc' => 123))));
        $response->expects($this->once())
             ->method('isSuccess')
             ->will($this->returnValue(true));

        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $method = new ReflectionMethod(
          get_class($client), 'processResponse'
        );
        $method->setAccessible(TRUE);
        $this->assertEquals(array('abc' => 123), $method->invoke($client, $response));
    }

    /**
     * @expectedException RuntimeException
     */
    public function testProcessResponseError()
    {
        $response = $this->getMock('Zend\Http\Response');
        $response->expects($this->once())
             ->method('isSuccess')
             ->will($this->returnValue(false));

        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $method = new ReflectionMethod(
          get_class($client), 'processResponse'
        );
        $method->setAccessible(TRUE);
        $method->invoke($client, $response);
    }

    public function testProcessResponseErrorParseJsonTooBig()
    {
        $response = $this->getMock('Zend\Http\Response');
        $response->expects($this->once())
             ->method('getBody')
             ->will($this->returnValue(file_get_contents(__DIR__ . '/_files/out.json')));
        $response->expects($this->once())
             ->method('isSuccess')
             ->will($this->returnValue(true));

        $client = new BigMl(array(
            'username' => 'alfred',
            'api_key' => '79138a622755a2383660347f895444b1eb927730'
        ));
        $method = new ReflectionMethod(
          get_class($client), 'processResponse'
        );
        $method->setAccessible(TRUE);
        try {
            $method->invoke($client, $response);
        } catch (\Exception $e) {
            echo $e . PHP_EOL;
        }
    }

    public function testFactory()
    {
        $source = BigMl::factory('source', array(
            'username' => 'alfred',
            'api_key'  => '79138a622755a2383660347f895444b1eb927730'
        ));
        $this->assertInstanceof('BigMl\Resource\AbstractResource', $source);
        $this->assertInstanceof('BigMl\Resource\Source', $source);
        $this->assertInstanceof('BigMl\Client\BigMl', $source->getClient());
    }

    private function getClientMock()
    {
        $response = $this->getMock('Zend\Http\Response');
        $response->expects($this->once())
             ->method('getBody')
             ->will($this->returnValue(json_encode(array('status' => 'ok'))));
        $response->expects($this->once())
             ->method('isSuccess')
             ->will($this->returnValue(true));

        $client = $this->getMock('Zend\Http\Client');
        $client->expects($this->once())
             ->method('send')
             ->will($this->returnValue($response));
        return $client;
    }


}