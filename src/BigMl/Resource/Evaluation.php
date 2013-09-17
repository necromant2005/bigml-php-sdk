<?php
namespace BigMl\Resource;

class Prediction extends AbstractResource
{
    protected $resource = 'evaluation';

    public function create($data)
    {
        return $this->getClient()->restPost($this->getResource(), $data);
    }

    public function retrieve($id)
    {
        return $this->getClient()->restGet($id);
    }

    public function update($id, $data)
    {
        return $this->getClient()->restPost($id, $data);
    }

    public function delete($id)
    {
        return $this->getClient()->restDelete($id);
    }
}