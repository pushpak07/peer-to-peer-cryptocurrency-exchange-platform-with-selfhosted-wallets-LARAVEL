<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->softDeletes();

            $presence = ['online', 'away', 'offline'];

            $table->enum('presence', $presence)->default('offline');
            $table->timestamp('last_seen')->nullable();
            $table->string('name')->unique();
            $table->string('email')->unique();
            $table->string('phone')->nullable();

            $table->string('timezone')->nullable();
            $table->string('currency')->default(config('settings.default_currency'));

            $status = ['active', 'inactive'];

            $table->enum('status', $status)->default('active');
            $table->boolean('verified_phone')->default(false);
            $table->string('password');

            $table->string('google2fa_secret')->nullable();
            $table->string('token')->nullable();
            $table->timestamp('token_expiry')->nullable();

            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
