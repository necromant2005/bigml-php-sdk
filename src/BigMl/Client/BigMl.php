<?php
namespace BigMl\Client;
use InvalidArgumentException;
use RuntimeException;
use Zend\Http\Client as HttpClient;
use Zend\Json\Json;
use Zend\Http\Response;
use Zend\Json\Exception\RuntimeException as JsonRuntimeException;

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
        return $this->setClient(new HttpClient());
    }

    public function setClient(HttpClient $client)
    {
        return $this->client = $client;
    }

    public function restGet($path, array $query = array())
    {
        $client = $this->getClient();
        $client->resetParameters();
        $client->setUri($this->prepareUri($path));
        $client->setEncType('application/json');
        $client->setParameterGet($query);
        $client->setMethod('GET');
        $response = $client->send();
        return $this->processResponse($response);
    }

    public function restPost($path, array $query = array())
    {
        $client = $this->getClient();
        $client->resetParameters();
        $client->setUri($this->prepareUri($path));
        $client->setEncType('application/json');
        $client->setMethod('POST');
        $client->setRawBody(Json::encode($query));
        $response = $client->send();
        return $this->processResponse($response);
    }

    public function restPut($path, array $query = null)
    {
        $client = $this->getClient();
        $client->resetParameters();
        $client->setUri($this->prepareUri($path));
        $client->setEncType('application/json');
        $client->setMethod('PUT');
        $client->setRawBody(Json::encode($query));
        $response = $client->send();
        return $this->processResponse($response);
    }

    public function restDelete($path)
    {
        $client = $this->getClient();
        $client->resetParameters();
        $client->setUri($this->prepareUri($path));
        $client->setEncType('application/json');
        $client->setMethod('DELETE');
        $response = $client->send();
        return $this->processResponse($response);
    }

    protected function prepareUri($path)
    {
        $uri = self::ACCESS_POINT;
        if ($this->getOption(self::FIELD_MODE)) {
            $uri .= $this->getOption(self::FIELD_MODE) . '/';
        }
        $uri .= $this->getOption(self::FIELD_VERSION) . '/';
        return $uri . $path . '?' 
            . self::FIELD_USERNAME . '=' . $this->getOption(self::FIELD_USERNAME) . ';' 
            . self::FIELD_API_KEY . '=' . $this->getOption(self::FIELD_API_KEY);
    }

    protected function processResponse(Response $response)
    {
        if (!$response->isOk()) {
            try {
                $data = Json::decode($response->getBody(), Json::TYPE_ARRAY);
                if (is_array($data) && array_key_exists('status', $data) && is_array($data['status'])
                    && array_key_exists('code', $data['status']) 
                    && array_key_exists('message', $data['status'])) {
                    throw new RuntimeException($data['status']['message'], $data['status']['code'], new RuntimeException($response, -1, new RuntimeException( $this->getClient()->getLastRawRequest() )));
                }
            } catch (JsonRuntimeException $e) {}
            throw new RuntimeException($response->getReasonPhrase(), $response->getStatusCode(), new RuntimeException($response, -1, new RuntimeException( $this->getClient()->getLastRawRequest() )));
        }
        return Json::decode($response->getBody(), Json::TYPE_ARRAY);
    }

    public static function factory($name, $options)
    {
        $className = 'BigMl\\Resource\\' . ucfirst($name);
        return new $className(new self($options));
    }
}