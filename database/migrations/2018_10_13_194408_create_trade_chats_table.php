<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradeChatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_chats', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('trade_id')->unsigned()->nullable();
            $table->foreign('trade_id')->references('id')
                ->on('trades')->onDelete('cascade');

            $table->longText('content');
            $table->enum('type', ['text', 'media'])->default('text');

            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

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
        Schema::dropIfExists('trade_chats');
    }
}
