<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDynamicTablesItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_tables_items', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('item_id')->index();
            $table->integer('dynamic_table_id')->unsigned()->index();
            $table->foreign('dynamic_table_id')->references('id')->on('dynamic_tables')->onDelete('cascade');
            $table->string('model')->index();
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
        Schema::drop('dynamic_tables_items');
    }
}
