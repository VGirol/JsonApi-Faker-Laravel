<?php

namespace VGirol\JsonApi\Commands;

use Illuminate\Foundation\Console\RequestMakeCommand;

class JsonApiRequestMakeCommand extends RequestMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'jsonapi:request';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new JsonApi form request class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/request.stub';
    }
}
