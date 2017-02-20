<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateDynamicTablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dynamic_tables', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('table_id')->unsigned()->nullable();
            $table->text('title');
            $table->string('slug');
            $table->string('model');
            $table->text('description')->nullable();
            $table->integer('user_id')->unsigned();
            $table->boolean('active')->default(false);
            $table->text('thumb')->nullable();
            $table->text('settings')->nullable();
            $table->text('meta')->nullable();
            $table->integer('orderBy')->unsigned();
            $table->timestamps();
            NestedSet::columns($table);

            $table->index(['slug', 'active', 'model']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('dynamic_tables');
    }
}
