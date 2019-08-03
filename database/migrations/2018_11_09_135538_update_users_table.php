<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('schedule_delete')->default(false);
            $table->boolean('schedule_deactivate')->default(false);
            $table->dropColumn('timezone');

        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone')->nullable()->default(env('APP_TIMEZONE', 'UTC'));
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
            $table->dropColumn('schedule_delete');
            $table->dropColumn('schedule_deactivate');
            $table->dropColumn('timezone');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('timezone')->nullable();
        });
    }
}
