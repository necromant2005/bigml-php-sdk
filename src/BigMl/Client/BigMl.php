<?php
namespace BigML\Client;
use InvalidArgumentException;
use RuntimeException;
use ZendRest\Client\RestClient;
use Zend\Json\Json;
use Zend\Http\Response;

class BigMl
{
    const ACCESS_POINT = 'https://bigml.io/';

    const FIELD_USERNAME = 'username';
    const FIELD_API_KEY  = 'api_key';
    const FIELD_VERSION  = 'version';
    const FIELD_MODE     = 'mode';

    protected $client = null;

    protected $options = array(
        self::FIELD_VERSION => 'andromeda',
        self::FIELD_MODE    => 'dev',
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
        if ($this->getOption(self::FIELD_MODE)) {
            $uri .= $this->getOption(self::FIELD_MODE) . '/';
        }
        $uri .= $this->getOption(self::FIELD_VERSION) . '/';
        return $this->setClient(new RestClient($uri));
    }

    public function setClient(RestClient $client)
    {
        return $this->client = $client;
    }

    public function restGet($path, array $query = array())
    {
        return $this->processResponse($this->getClient()->restGet($this->preparePath($path), $query));
    }

    public function restPost($path, array $query = null)
    {
        return $this->processResponse($this->getClient()->restPost($this->preparePath($path), $query));
    }

    public function restPut($path, array $query = null)
    {
        return $this->processResponse($this->getClient()->restPut($this->preparePath($path), $query));
    }

    public function restDelete($path)
    {
        return $this->processResponse($this->getClient()->restDelete($this->preparePath($path)));
    }

    protected function preparePath($path)
    {
        return $path . '?' 
            . self::FIELD_USERNAME . '=' . $this->getOption(self::FIELD_USERNAME) . ';' 
            . self::FIELD_API_KEY . '=' . $this->getOption(self::FIELD_API_KEY);
    }

    protected function processResponse(Response $response)
    {
        if (!$response->isOk()) {
            throw new RuntimeException($response->getReasonPhrase(), $response->getStatusCode(), new RuntimeException($response));
        }
        return Json::decode($response->getBody(), Json::TYPE_ARRAY);
    }

    public static function factory($name, $options)
    {
        $className = 'BigMl\\Resource\\' . ucfirst($name);
        return new $className(new self($options));
    }
}