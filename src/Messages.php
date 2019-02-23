<?php
namespace VGirol\JsonApiAssert;

abstract class Messages
{
    const ACCEPT_HEADER_WITHOUT_PARAMETERS = 'A request that include the JSON:API media type in her Accept header MUST specify the media type %s there at least once without any media type parameters.';
    const ATTRIBUTES_OBJECT_IS_NOT_ARRAY = 'An attributes object MUST be an array or an arrayable object with a "toArray" method.';
    const CONTENT_TYPE_HEADER_MISSING = 'Clients MUST send all JSON:API data in request documents with the header "Content-Type: %s" without any media type parameters.';
    const CONTENT_TYPE_HEADER_ALLREADY_SET = 'The response header "Content-Type" is allready set with bad value : a response MUST specify the header "Content-Type: %s" without any media type parameters.';
    const CONTENT_TYPE_HEADER_WITHOUT_PARAMETERS = 'A request MUST specify the header "Content-Type: %s" without any media type parameters.';
    const ERROR_CODE_IS_NOT_STRING = 'The value of the "code" member MUST be a string.';
    const ERROR_DETAILS_IS_NOT_STRING = 'The value of the "details" member MUST be a string.';
    const ERROR_OBJECT_NOT_ARRAY = 'An error object MUST be an array.';
    const ERROR_OBJECT_NOT_EMPTY = 'An error object MUST NOT be empty.';
    const ERROR_SOURCE_OBJECT_NOT_ARRAY = 'An error source object MUST be an array.';
    const ERROR_SOURCE_PARAMETER_IS_NOT_STRING = 'The value of the "parameter" member MUST be a string.';
    const ERROR_SOURCE_POINTER_IS_NOT_STRING = 'The value of the "pointer" member MUST be a string.';
    const ERROR_SOURCE_POINTER_START = 'The value of the "pointer" member MUST start with a slash (/).';
    const ERROR_STATUS_IS_NOT_STRING = 'The value of the "status" member MUST be a string.';
    const ERROR_TITLE_IS_NOT_STRING = 'The value of the "title" member MUST be a string.';
    const ERRORS_OBJECT_NOT_ARRAY = 'Top-level "errors" member MUST be an array of error objects.';
    const FIELDS_HAVE_SAME_NAME = 'A resource CAN NOT have an attribute and relationship with the same name.';
    const FIELDS_NAME_NOT_ALLOWED = 'A resource CAN NOT have an attribute or relationship named "type" or "id".';
    const HAS_MEMBER = 'Failed asserting that a JSON object HAS the member "%s".';
    const OBJECT_NOT_ARRAY = 'A resource linkage MUST be an array of resource objects or resource identifier objects.';
    const LINK_OBJECT_MISS_HREF_MEMBER = 'A link object MUST contain an "href" member.';
    const LINKS_OBJECT_NOT_ARRAY = 'A links object MUST be an array.';
    const MEMBER_NAME_HAVE_RESERVED_CHARACTERS = 'Member names MUST contain only allowed characters.';
    const MEMBER_NAME_IS_NOT_STRING = 'Each member key MUST be a string.';
    const MEMBER_NAME_IS_TOO_SHORT = 'Member names MUST contain at least one character.';
    const MEMBER_NAME_NOT_ALLOWED = 'Any object that constitutes or is contained in an attribute MUST NOT contain a "relationships" or "links" member.';
    const MEMBER_NAME_START_AND_END_WITH_ALLOWED_CHARACTERS = 'Member names MUST start and end with a globally allowed character.';
    const META_OBJECT_IS_NOT_ARRAY = 'A meta object MUST be an array.';
    const NOT_HAS_MEMBER = 'Failed asserting that a JSON object NOT HAS the member "%s".';
    const ONLY_ALLOWED_MEMBERS = 'Unless otherwise noted, objects defined by this specification MUST NOT contain any additional members.';
    const PRIMARY_DATA_NOT_ARRAY = 'Primary data MUST be an array or an arrayable object with a "toArray" method.';
    const PRIMARY_DATA_SAME_TYPE = 'All elements of resource collection MUST be of same type (resource object or resource identifier object).';
    const RESOURCE_ID_MEMBER_IS_ABSENT = 'A resource object MUST contain the "id" top-level members.';
    const RESOURCE_ID_MEMBER_IS_EMPTY = 'The value of the "id" member CAN NOT be empty.';
    const RESOURCE_ID_MEMBER_IS_NOT_STRING = 'The value of the "id" member MUST be a string.';
    const RESOURCE_IDENTIFIER_IS_NOT_ARRAY = 'A resource identifier object MUST be an array.';
    const RESOURCE_IS_NOT_ARRAY = 'A resource object MUST be an array.';
    const RESOURCE_LINKAGE_NOT_ARRAY = '';
    const RESOURCE_TYPE_MEMBER_IS_ABSENT = 'A resource object MUST contain the "type" top-level members.';
    const RESOURCE_TYPE_MEMBER_IS_EMPTY = 'The value of the "type" member CAN NOT be empty.';
    const RESOURCE_TYPE_MEMBER_IS_NOT_STRING = 'The value of the "type" member MUST be a string.';
    const TEST_FAILED = 'Failed asserting that test has failed.';
    const TOP_LEVEL_DATA_AND_ERROR = 'The members "data" and "errors" MUST NOT coexist in the same JSON document.';
    const TOP_LEVEL_DATA_AND_INCLUDED = 'If a document does not contain a top-level "data" member, the "included" member MUST NOT be present either.';
    const TOP_LEVEL_MEMBERS = 'A JSON document MUST contain at least one of the following top-level members: "%s".';
    const COMPOUND_DOCUMENT_ONLY_ONE_RESOURCE = 'A compound document MUST NOT include more than one resource object for each "type" and "id" pair.';
    const CONTAINS_AT_LEAST_ONE = 'contains at least one element of "%s"';
}
