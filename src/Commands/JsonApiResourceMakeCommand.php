<?php

namespace VGirol\JsonApi\Commands;

use Illuminate\Foundation\Console\ResourceMakeCommand;

class JsonApiResourceMakeCommand extends ResourceMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'jsonapi:resource';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new JsonApi resource';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return $this->collection()
                    ? __DIR__.'/stubs/resource-collection.stub'
                    : __DIR__.'/stubs/resource.stub';
    }
}
