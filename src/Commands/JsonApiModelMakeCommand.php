<?php

namespace VGirol\JsonApi\Commands;

use Symfony\Component\Console\Input\InputOption;
use Illuminate\Foundation\Console\ModelMakeCommand;

class JsonApiModelMakeCommand extends ModelMakeCommand
{
    use CanParseClassName;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'jsonapi:model';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new JsonApi Eloquent model class';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        if ($this->option('pivot')) {
            return __DIR__ . '/stubs/pivot.model.stub';
        }

        return __DIR__ . '/stubs/model.stub';
    }

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected function buildClass($name)
    {
        $replace = $this->buildModelReplacements($name);

        return str_replace(
            array_keys($replace),
            array_values($replace),
            parent::buildClass($name)
        );
    }

    protected function buildModelReplacements($name)
    {
        $root = $this->parseName($name);
        $table = $this->option('table');
        $code = strtoupper(substr($table, -3));

        return [
            'DummyTable' => $table,
            'DummyKey' => $code.'_ID',
            'DummyCreatedAt' => $code.'_CREATED_AT',
            'DummyUpdatedAt' => $code.'_UPDATED_AT',
            'DummyResource' => strtolower($root),
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array_merge(
            parent::getOptions(),
            [
                ['table', 't', InputOption::VALUE_NONE, 'The model table name'],
            ]
        );
    }
}
