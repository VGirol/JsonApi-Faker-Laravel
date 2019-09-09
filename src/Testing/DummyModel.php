<?php

namespace VGirol\JsonApiFaker\Laravel\Testing;

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
        'TST_CONTENT',
        'TST_CREATION_DATE',
    ];

    /**
     * Undocumented function.
     *
     * @return static
     */
    public static function fake()
    {
        return (new static())->fakeAttributes();
    }

    /**
     * Undocumented function.
     *
     * @return static
     */
    public function fakeAttributes()
    {
        $faker = \Faker\Factory::create();

        $attributes = [
            'TST_ID'            => $faker->numberBetween(0, 100),
            'TST_NAME'          => $faker->numerify('test###'),
            'TST_CONTENT'       => $faker->sentence,
            'TST_CREATION_DATE' => $faker->date(),
        ];

        $this->setRawAttributes($attributes);

        return $this;
    }
}
