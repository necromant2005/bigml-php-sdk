<?php
namespace BigML\Client;
use InvalidArgumentException;
use RuntimeException;
use ZendRest\Client\RestClient;
use Zend\Json\Json;

class BigMl
{
    const ACCESS_POINT = 'https://bigml.io/';

    const FIELD_USERNAME = 'username';
    const FIELD_API_KEY  = 'api_key';

    protected $client = null;

    protected $options = array(
        'version' => 'andromeda',
        'mode'     => 'dev',
    );

    public function __construct(array $options)
    {
        if (!array_key_exists(self::FIELD_USERNAME, $options)) {
            throw new InvalidArgumentException(self::FIELD_USERNAME . ' is missed!');
        }
        if (!array_key_exists(self::FIELD_API_KEY, $options)) {
            throw new InvalidArgumentException(self::FIELD_API_KEY . ' is missed!');
        }
        foreach ($options as $name => $value) {
            $this->setOption($name, $value);
        }
    }

    public function getOption($name)
    {
        return array_key_exists($name, $this->options) ? $this->options[$name] : null;
    }

    public function setOption($name, $value)
    {
        $this->options[$name] = $value;
    }

    public function getClient()
    {
        if ($this->client) {
            return $this->client;
        }
        $uri = self::ACCESS_POINT;
        if ($this->getOption('mode')) {
            $uri .= $this->getOption('mode') . '/';
        }
        $uri .= $this->getOption('version') . '/';
        return $this->setClient(new RestClient($uri));
    }

    public function setClient(RestClient $client)
    {
        return $this->client = $client;
    }

    public function restGet($path, array $query = array())
    {
        $path = '?username=' . $this->getOption('username') . ';api_key=' . $this->getOption('api_key');
        $response = $this->getClient()->restGet($path, $query);
        if (!$response->isOk()) {
            throw new RuntimeException($response->getReasonPhrase(), $response->getStatusCode(), new RuntimeException($response));
        }
        return Json::decode($response->getBody());
    }

    public function restPost($path, array $query = null)
    {

    }

    public function restPut($path, array $query = null)
    {

    }

    public function restDelete($path)
    {
        $client = $this->getClient();
        return $client->restDelete();
    }

    protected function prepareQuery()
    {

    }
}