<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMaillogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_log', function (Blueprint $table) {
            $table->increments('id')->unique()->index();
            $table->string('to', 255)->index();
            $table->string('from', 255)->index();
            $table->text('subject');
            $table->longText('body');
            $table->boolean('read')->default(false);
            $table->integer('attempt');
            $table->dateTime('sended_at');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('mail_log');
    }
}
