<?php

namespace VGirol\JsonApi\Commands;

use Illuminate\Routing\Console\ControllerMakeCommand;

class JsonApiControllerMakeCommand extends ControllerMakeCommand
{
    use CanParseClassName;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'jsonapi:controller';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new JsonApi controller class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/controller.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $replace = $this->buildFormRequestReplacements($name);

        return str_replace(
            array_keys($replace), array_values($replace), parent::buildClass($name)
        );
    }

    /**
     * Build the replacements for a form request class.
     *
     * @return array
     */
    protected function buildFormRequestReplacements($name)
    {
        $formRequestClass = $this->parseName($name, 'request');

        if (! class_exists($formRequestClass)) {
            if ($this->confirm("A {$formRequestClass} form request does not exist. Do you want to generate it ?", true)) {
                $this->call('jsonapi:request', ['name' => $formRequestClass]);
            }
        }

        return [
            'DummyFormRequest' => class_basename($formRequestClass),
        ];
    }
}
