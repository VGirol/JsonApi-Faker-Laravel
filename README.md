<<<<<<< HEAD
# JsonApi-Assert-Laravel
=======
# JsonApi-Assert
>>>>>>> c1c6d7926d945bca5f8a0a608a032f3d4c51fc0b

## Installation

## Documentation

<<<<<<< HEAD
Illuminate\Foundation\Testing\TestResponse (macros)

- assertJsonApiResponse406
- assertJsonApiResponse415

VGirol\JsonApiAssert\Laravel\JsonApiAssertResponse

- assertResourceIdentifierObjectEqualsModel($model, $resource)
- assertResourceObjectEqualsModel($model, $resource)
=======
VGirol\JsonApiAssert\Assert

- assertContainsAtLeastOneMember($expected, $actual, $message = '')
- assertContainsOnlyAllowedMembers($expected, $actual, $message = '')
- assertFieldHasNoForbiddenMemberName($field)
- assertHasAttributes($json)
- assertHasData($json)
- assertHasErrors($json)
- assertHasIncluded($json)
- assertHasLinks($json)
- assertHasMember($json, $key)
- assertHasMeta($json)
- assertHasRelationships($json)
- assertHasValidStructure($json)
- assertHasValidTopLevelMembers($json)
- assertIsArrayOfObjects($data, $message = '')
- assertIsNotArrayOfObjects($data, $message = '')
- assertIsNotForbiddenFieldName($name)
- assertIsNotForbiddenMemberName($name)
- assertIsValidAttributesObject($attributes)
- assertIsValidErrorLinksObject($links)
- assertIsValidErrorObject($error)
- assertIsValidErrorsObject($errors)
- assertIsValidErrorSourceObject($source)
- assertIsValidIncludedCollection($included, $data)
- assertIsValidJsonapiObject($jsonapi)
- assertIsValidLinkObject($link)
- assertIsValidLinksObject($links, $allowedMembers)
- assertIsValidMemberName($name, $strict = false)
- assertIsValidMetaObject($meta)
- assertIsValidPrimaryData($data)
- assertIsValidRelationshipLinksObject($data, $withPagination)
- assertIsValidRelationshipObject($relationship)
- assertIsValidRelationshipsObject($relationships)
- assertIsValidResourceCollection($list, $checkType)
- assertIsValidResourceIdentifierObject($resource)
- assertIsValidResourceLinkage($data)
- assertIsValidResourceLinksObject($data)
- assertIsValidResourceObject($resource)
- assertIsValidSingleResource($resource)
- assertIsValidTopLevelLinksMember($links)
- assertNotHasMember($json, $key)
- assertResourceIdMember($resource)
- assertResourceObjectHasValidTopLevelStructure($resource)
- assertResourceTypeMember($resource)
- assertTestFail($fn, $expectedFailureMessage)
- assertValidFields($resource)


VGirol\JsonApiAssert\AssertResponse

- assertResponse406($response)
- assertResponse415($response)
- assertResponseHeaders($headers)
- 
>>>>>>> c1c6d7926d945bca5f8a0a608a032f3d4c51fc0b
