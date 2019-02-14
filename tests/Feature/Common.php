<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use VGirol\JsonApi\Tools\Assert\JsonApiAssertCrud;
use Illuminate\Foundation\Testing\DatabaseMigrations;

trait Common
{
    use DatabaseMigrations;
    use JsonApiAssertCrud;

    protected function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:refresh', ['--path' => 'packages/vgirol/jsonapi/tests/tools/migrations']);

        // Add test routes
        require(__DIR__ . '/../tools/routes/routes.php');
        // https://github.com/laravel/framework/issues/19020#issuecomment-409873471
        app('router')->getRoutes()->refreshNameLookups();
        app('router')->getRoutes()->refreshActionLookups();
    }
}
