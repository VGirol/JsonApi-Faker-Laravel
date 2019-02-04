<?php

namespace VGirol\JsonApi\Commands;

trait CanParseClassName {

    protected function parseName($name, $result = 'root')
    {
        switch ($result) {
            case 'request':
                return $this->getRequestName($name);
        }

        return $this->getRootName($name);
    }

    private function getRootName($name)
    {
        $class = explode('\\', $name);
        $class = array_pop($class);
        $root = str_replace([
            'Controller',
            'ResourceCollection',
            'Resource',
            'FormRequest'
        ], NULL, $class);

        return $root;
    }

    private function getRequestName($name)
    {
        return 'App\\Http\\Requests\\'.$this->getRootName($name).'FormRequest';
    }
}
