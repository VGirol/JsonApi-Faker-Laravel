<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Tools\Models;

use Illuminate\Database\Eloquent\Model;

class ModelForTest extends Model
{
    protected $table = 't_test_tst';
    protected $primaryKey = 'TST_ID';
    public $timestamps = false;

    protected $resourceObjectType = 'test';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'TST_ID',
        'TST_NAME',
        'TST_NUMBER',
        'TST_CREATION_DATE'
    ];

    public function getResourceType(): string
    {
        return $this->resourceObjectType;
    }
}
