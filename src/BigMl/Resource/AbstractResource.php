<?php
namespace BigMl\Resource;
use BigMl\Client\BigML as Client;
use RuntimeException;
use Zend\Json\Json;

abstract class AbstractResource
{
    const CODE_WAITING    = 0;   // Waiting The resource is waiting for another resource to be finished before BigML.io can start processing it.
    const CODE_QUEUED     = 1;   // Queued  The task that is going to create the resource has been accepted but has been queued because there are other tasks using the system.
    const CODE_STARTED    = 2;   // Started The task to create the resource has been started and you should expect partial results soon.
    const CODE_IN_PROCESS = 3;   // In Progress The task is partially completed but still needs to do more computations.
    const CODE_SUMMARIZED = 4;   // Summarized  This status is specific to datasets. Although the dataset computation is complete, the dataset needs to be serialized before it can be used to create a model.
    const CODE_FINISHED   = 5;   // Finished    The task is complete and the resource is final.
    const CODE_FAULTY     = -1;  // Faulty  The task has failed. We either could not process the task as you requested it or there is an internal issue.
    const CODE_UNKNOWN    = -2;  // Unknown The task has reached a state that we cannot verify at this time. This a status you should never see unless BigML.io has suffered a major outage.
    const CODE_RUNNABLE   = -3;  // Runnable    The task has reached a faulty state because of a network or computer error, or because a dependent resource was not ready yet. If you repeat the request it might work this time.
    const CODE_INTERNAL   = -9999;  // Internal error non well formated response

    protected $client = null;

    protected $resource = '';

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

    public function wait($id, $tick = 5)
    {
        do {
            $response = $this->retrieve($id, array('full' => false));
            if (is_array($response) && array_key_exists('status', $response) && is_array($response['status'])
                && array_key_exists('code', $response['status'])
                && array_key_exists('message', $response['status'])) {
                $code = $response['status']['code'];
            } else {
                $code = self::CODE_INTERNAL;
            }
            if ($code < 0) {
                throw new RuntimeException('Error while waiting resource: ' . $id, $code, new RuntimeException(Json::encode($response)));
            }
            if ($code != self::CODE_FINISHED) {
                sleep($tick);
            }
        } while ($code != self::CODE_FINISHED);
        return $response;
    }

    abstract public function create($data);
    abstract public function retrieve($id, array $data = array());
    abstract public function update($id, $data);
    abstract public function delete($id);
}