<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExtraFieldGroupItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('extra_field_group_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('extra_field_id')->unsigned()->index();
            $table->foreign('extra_field_id')->references('id')->on('extra_fields')->onDelete('cascade');
            $table->integer('extra_field_group_id')->unsigned()->index();
            $table->foreign('extra_field_group_id')->references('id')->on('extra_field_groups')->onDelete('cascade');
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
        Schema::dropIfExists('extra_field_group_items');
    }
}
