<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->timestamp('activated_at')->nullable();
            $table->boolean('active')->nullable();
            $table->boolean('awaits_moderation')->nullable();
            $table->string('confirmation_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('activated_at');
            $table->dropColumn('active');
            $table->dropColumn('awaits_moderation');
            $table->dropColumn('confirmation_code');
        });
    }
}
