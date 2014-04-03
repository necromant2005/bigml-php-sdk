<?php
namespace BigMl\Resource;

class Evaluation extends AbstractResource
{
    protected $resource = 'evaluation';

    public function create($data)
    {
        return $this->getClient()->restPost($this->getResource(), $data);
    }

    public function retrieve($id, array $data = array())
    {
        return $this->getClient()->restGet($id, $data);
    }

    public function update($id, $data)
    {
        return $this->getClient()->restPut($id, $data);
    }

    public function delete($id)
    {
        return $this->getClient()->restDelete($id);
    }
}