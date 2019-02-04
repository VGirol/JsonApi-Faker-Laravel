<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTestTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_test_tst', function (Blueprint $table) {
            $table->increments('TST_ID');
            $table->string('TST_NAME', 255)->unique();
            $table->unsignedInteger('TST_NUMBER')->nullable();
            $table->date('TST_CREATION_DATE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_test_tst');
    }
}
