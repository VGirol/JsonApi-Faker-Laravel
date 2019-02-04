<?php

namespace VGirol\JsonApi\Tests\Tools\Models;

use Illuminate\Database\Eloquent\Model;
use VGirol\JsonApi\Models\JsonApiModelInterface;

class ModelForTest extends Model implements JsonApiModelInterface
{
    protected $table = 't_test_tst';
    protected $primaryKey = 'TST_ID';
    public $timestamps = false;

    protected $resourceObjectType = 'jsonapitest';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TST_NAME',
        'TST_NUMBER',
        'TST_CREATION_DATE'
    ];

    public function getResourceType(): string
    {
        return $this->resourceObjectType;
    }

    public function relatedModelForTest()
    {
        return $this->hasMany('VGirol\JsonApi\Tests\Models\RelatedModelForTest', $this->getKeyName());
    }
}
