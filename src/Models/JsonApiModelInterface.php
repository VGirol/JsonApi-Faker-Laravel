<?php

namespace VGirol\JsonApi\Models;

interface JsonApiModelInterface {
    function getResourceType(): string;
    function getAttributes();
}
