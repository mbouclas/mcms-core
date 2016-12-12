<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediaLibraryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_library', function (Blueprint $table) {
            $table->increments('id')->unique()->index();
            $table->string('collection_name');
            $table->string('name');
            $table->string('file_name');
            $table->string('disk')->default('local');
            $table->unsignedInteger('size')->nullable();
            $table->text('manipulations')->nullable();
            $table->text('custom_properties')->nullable();
            $table->unsignedInteger('order_column')->nullable();
            $table->integer('user_id')->unsigned();
            $table->boolean('active')->nullable()->default(false);
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
        Schema::drop('media_library');
    }
}
