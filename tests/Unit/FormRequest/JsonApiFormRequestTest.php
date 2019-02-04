<?php
namespace VGirol\JsonApi\Tests\Unit\FormRequest;

use VGirol\JsonApi\Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use MohammedManssour\FormRequestTester\TestsFormRequests;
use VGirol\JsonApi\Tests\Tools\Requests\ModelForTestFormRequest;
use Illuminate\Support\Facades\Route;

class JsonApiFormRequestTest extends TestCase
{

    use DatabaseMigrations;
    use TestsFormRequests;

    protected function setUp() {
        parent::setUp();
        $this->artisan('migrate:refresh', ['--path' => 'packages/vgirol/jsonapi/tests/tools/migrations']);
        require(__DIR__.'/../../tools/routes/routes.php');
    }

    public function testPostValidationSuccess()
    {
        // Creates a form
        $form = [
            'data' => [
                'type' => $this->resourceType,
                'id' => '',
                'attributes' => [
                    'tst_name' => 'asd',
                    'tst_creation_date' => '2019-01-01'
                ]
            ]
        ];

        $this->formRequest(ModelForTestFormRequest::class, $form, [
            'method' => 'post',
            'route' => $this->endpoint
        ])
            ->assertValidationPassed();
    }

    public function testPostValidationError()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->model)->create();

        $this->assertDatabaseHas($model->getTable(), [ 'TST_ID' => $model->getKey() ]);

        // Creates a form
        $form = [
            'data' => [
                'type' => 'bad type',
                'id' => '',
                'attributes' => [
                    'tst_name' => $model->TST_NAME,
                    'tst_creation_date' => '2019-01-01'
                ]
            ]
        ];

        $this->formRequest(ModelForTestFormRequest::class, $form, [
            'method' => 'post',
            'route' => $this->endpoint
        ])
            ->assertValidationFailed()
            ->assertValidationErrors(['data.type'])
            ->assertValidationErrors(['data.attributes.tst_name'])
            ->assertValidationErrorsMissing(['data.id'])
            ->assertValidationErrorsMissing(['data.attributes.tst_number'])
            ->assertValidationErrorsMissing(['data.attributes.tst_creation_date'])
            ->assertValidationMessages([
                '(409) The data.attributes.tst name has already been taken.',
                '(409) The selected data.type is invalid.'
            ]);
    }

    public function testPatchValidationSuccess()
    {
        // Creates an object with filled out fields
        $model = factoryJsonapi($this->model)->create();

        // Creates a form
        $form = [
            'data' => [
                'type' => $this->resourceType,
                'id' => strval($model->getKey()),
                'attributes' => [
                    'tst_name' => 'asd'
                ]
            ]
        ];

        $this->formRequest(ModelForTestFormRequest::class, $form, [
            'method' => 'patch',
            'route' => $this->endpoint . '/' . $model->getKey()
        ])
            ->assertValidationPassed();

        // // Creates a form request object
        // $request = ModelForTestFormRequest::create($this->endpoint . '/' . $model->getKey(), 'PATCH', ['id' => $model->getKey()]);

        // // Gets the rules
        // $rules = $request->preparedRules();

        // // Validates the rules
        // $validator = Validator::make($form, $rules);
        // $fails = $validator->fails();

        // $this->assertFalse($fails, 'Form request validation fails :' . "\n" . $validator->errors()->__toString());
    }
}
