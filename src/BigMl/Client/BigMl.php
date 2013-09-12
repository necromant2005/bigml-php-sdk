<?php
namespace BigML\Client;
use InvalidArgumentException;
use ZendRest\Client\RestClient;

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
        return $this->client = new RestClient();
    }

    public function setClient(RestClient $client)
    {
        $this->client = $client;
    }
}