<?php

namespace VGirol\JsonApi\Tests\Tools\Models;

use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApi\Models\JsonApiModelInterface;

class RelatedModelForTest extends Model implements JsonApiModelInterface
{
    protected $table = 't_related_rel';
    protected $primaryKey = 'REL_ID';
    public $timestamps = false;

    protected $resourceObjectType = 'related';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'REL_NAME',
        'REL_DATE'
    ];

    public function getResourceType(): string
    {
        return $this->resourceObjectType;
    }
}
