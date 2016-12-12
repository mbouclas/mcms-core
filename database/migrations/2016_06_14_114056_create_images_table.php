<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('item_id')->index();
            $table->string('model')->index();
            $table->string('type');
            $table->text('title')->nullable();
            $table->text('alt')->nullable();
            $table->text('copies')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('orderBy')->unsigned()->nullable()->default(0);
            $table->boolean('active')->nullable();
            $table->text('settings')->nullable();
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
        Schema::drop('images');
    }
}
