<?php

declare(strict_types=1);

namespace VGirol\JsonApiFaker\Laravel;

/**
 * All the messages
 */
abstract class Messages
{
    const ERROR_NO_MODEL = "The model is not set.";
    const ERROR_TYPE_NOT_NULL = "Resource type can not be null.";
    const ERROR_NO_FACTORY =
    "Each item of the provided array must be an instance of ResourceObjectFactory or ResourceIdentifierFactory.";
}
