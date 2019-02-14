<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRelatedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('t_related_rel', function (Blueprint $table) {
            $table->increments('REL_ID');
            $table->unsignedInteger('TST_ID');
            $table->string('REL_NAME', 255)->unique();
            $table->date('REL_DATE');

            $table->foreign('TST_ID', 'rel_test_foreign')->references('TST_ID')->on('t_test_tst')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('t_related_rel');
    }
}
