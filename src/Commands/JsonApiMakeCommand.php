<?php

namespace VGirol\JsonApi\Commands;

use Illuminate\Console\Command;

class JsonApiMakeCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jsonapi:make {name} {table}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create all the files associated to a JsonApi model : model, form request, resource, controller ...';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');
        $table = $this->argument('table');

        $this->call('jsonapi:model', [
            'name' => 'Models/'.$name,
            '--table' => $table
        ]);

        $this->call('jsonapi:resource', [
            'name' => $name.'Resource'
        ]);

        $this->call('jsonapi:resource', [
            'name' => $name.'ResourceCollection'
        ]);

        $this->call('jsonapi:request', [
            'name' => $name.'FormRequest'
        ]);

        $this->call('jsonapi:controller', [
            'name' => $name.'Controller'
        ]);

        $this->call('model:config');
    }
}
