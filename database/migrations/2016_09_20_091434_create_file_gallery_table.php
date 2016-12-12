<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFileGalleryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('file_gallery', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('item_id')->index();
            $table->string('model')->index();
            $table->string('file_name')->index();
            $table->string('url')->index();
            $table->text('title')->nullable();
            $table->text('description')->nullable();
            $table->integer('user_id')->unsigned();
            $table->integer('orderBy')->unsigned()->nullable()->default(0);
            $table->boolean('active')->nullable();
            $table->text('info')->nullable();
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
        Schema::table('file_gallery', function (Blueprint $table) {
            Schema::drop('file_gallery');
        });
    }
}
