<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_fields', function (Blueprint $table) {
            $table->increments('id');
            $table->string('model');
            $table->text('title');
            $table->string('varName');
            $table->string('slug');
            $table->string('type');
            $table->text('meta')->nullable();
            $table->text('settings')->nullable();
            $table->boolean('active')->default(false);
            $table->integer('orderBy')->default(0);
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
        Schema::dropIfExists('extra_fields');
    }
}
