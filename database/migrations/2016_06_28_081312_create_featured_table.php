<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFeaturedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('featured', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('item_id')->index();
            $table->integer('category_id')->index();
            $table->string('model')->index();
            $table->integer('orderBy')->nullable();
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
        Schema::drop('featured');
    }
}
