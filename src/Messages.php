<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel;

/**
 * All the messages.
 */
abstract class Messages
{
    const ERROR_NOT_MODEL_INSTANCE =
    'An item of the provided collection is not an instance of \\Illuminate\\Database\\Eloquent\\Model.';
    const ERROR_MODEL_NOT_SET = 'The model is not set.';
    const ERROR_MODEL_NOT_NULL = 'Model can not be null.';
    const ERROR_TYPE_NOT_NULL = 'Resource type can not be null.';
    const ERROR_NOT_FACTORY_INSTANCE =
    'Each item of the provided array must be an instance of ResourceObjectFactory or ResourceIdentifierFactory.';
}
