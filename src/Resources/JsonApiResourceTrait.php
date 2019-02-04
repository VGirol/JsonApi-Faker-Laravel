<?php

namespace VGirol\JsonApi\Resources;

trait JsonApiResourceTrait
{

    protected $exportType;

    public function setExportType($type)
    {
        $this->exportType = $type;

        if (isset($this->collection)) {
            $this->collection->map->setExportType($type);
        }
    }

    public function getExportType()
    {
        return $this->exportType;
    }

    public function isResourceObject() : bool
    {
        return $this->exportType === JsonApiResourceType::RESOURCE_OBJECT;
    }

    public function isResourceIdentifier() : bool
    {
        return $this->exportType === JsonApiResourceType::RESOURCE_IDENTIFIER;
    }
}
