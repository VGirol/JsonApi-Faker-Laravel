<?php

namespace VGirol\JsonApiAssert\Laravel\Tests\Tools\Models;

use Illuminate\Database\Eloquent\Model;

class DummyModel extends Model
{
    protected $table = 't_test_tst';
    protected $primaryKey = 'TST_ID';
    public $timestamps = false;

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
}
