<?php
namespace VGirol\JsonApi\Tests\Feature\Response;

use Illuminate\Http\Request;
use VGirol\JsonApi\Tests\TestCase;
use VGirol\JsonApi\Tools\Assert\JsonApiAssertCrud;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use PHPUnit\Framework\Assert as PHPUnit;

class JsonApiResponseTest extends TestCase
{

    use DatabaseMigrations;
    use JsonApiAssertCrud;
    use JsonApiFetchingSingleResourceTest;
    use JsonApiFetchingResourceCollectionTest;
    use JsonApiCreatingResourceTest;
    use JsonApiUpdatingResourceTest;
    use JsonApiDeletingResourceTest;

    protected function setUp()
    {
        parent::setUp();
        $this->artisan('migrate:refresh', ['--path' => 'packages/vgirol/jsonapi/tests/tools/migrations']);
        require(__DIR__ . '/../tools/routes/routes.php');
        $this->resetParams();
    }
}
