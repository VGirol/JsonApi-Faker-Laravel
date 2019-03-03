<?php

namespace VGirol\JsonApiAssert\Laravel\Asserts;

use PHPUnit\Framework\Assert as PHPUnit;
use PHPUnit\Framework\Constraint\ArraySubset;
use Illuminate\Foundation\Testing\TestResponse;
use VGirol\JsonApiAssert\Assert as JsonApiAssert;

trait AssertErrors
{
    public static function assertErrorResponse(TestResponse $response, $statusCode, $expectedErrors)
    {
        $response->assertStatus($statusCode);

        $response->assertHeader(static::$headerName, static::$mediaType);

        // Decode JSON response
        $json = $response->json();

        // Checks response structure
        JsonApiAssert::assertHasValidStructure($json);

        // Checks errors member
        static::assertErrors($response, $expectedErrors);
    }

    public static function assertErrors(TestResponse $response, $expectedErrors)
    {
        // Decode JSON response
        $json = $response->json();

        // Checks errors member
        JsonApiAssert::assertHasErrors($json);
        $errors = $json['errors'];

        JsonApiAssert::assertIsValidErrorsObject($errors);

        JsonApiAssert::assertIsValidErrorsObject($expectedErrors);

        PHPUnit::assertGreaterThanOrEqual(count($expectedErrors), count($errors));

        foreach ($expectedErrors as $expectedError) {
            $test = false;
            foreach ($errors as $error) {
                $constraint = new ArraySubset($expectedError, false);
                $test = $test || $constraint->evaluate($error, '', true);
            }

            PHPUnit::assertTrue(
                $test,
                sprintf(
                    'Failed asserting that "errors" member %s contains the expected error %s.',
                    var_export($errors, true),
                    var_export($expectedError, true)
                )
            );
        }
    }
}
