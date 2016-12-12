<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUpdatesLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('updates_log', function (Blueprint $table) {
            $table->increments('id')->unique()->index();
            $table->string('module');
            $table->string('operation_id');
            $table->string('operation');
            $table->string('handler');
            $table->boolean('result')->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('updates_log');
    }
}
