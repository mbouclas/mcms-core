<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('menu_id')->unsigned()->index();
            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
            $table->integer('item_id')->unsigned()->nullable()->index();
            $table->string('model')->nullable();
            $table->string('slug_pattern')->nullable();
            $table->string('permalink')->nullable();
            $table->text('title');
            $table->string('link')->nullable();
            $table->text('settings')->nullable();
            $table->boolean('active');
            $table->boolean('sync')->nullable()->nullable();
            $table->integer('orderBy')->unsigned()->nullable();
            $table->timestamps();
            NestedSet::columns($table);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('menu_items');
    }
}
