<?php
namespace BigMl\Resource;
use BigML\Client\BigML as Client;

abstract class AbstractResource
{
    protected $client = null;

    protected $resource = 'source';

    public function __construct(Client $client)
    {
        $this->setClient($client);
    }

    public function setClient(Client $client)
    {
        $this->client = $client;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getResource()
    {
        return $this->resource;
    }

    abstract public function create($data);
    abstract public function retrieve($id);
    abstract public function update($id, $data);
    abstract public function delete($id);
}