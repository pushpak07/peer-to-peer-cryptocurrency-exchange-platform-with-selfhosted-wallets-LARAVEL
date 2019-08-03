<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLitecoinAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('litecoin_addresses', function(Blueprint $table){
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            $table->dropColumn('private');
            $table->dropColumn('wif');
            $table->dropColumn('public');

            $table->integer('wallet_id')->unsigned()->nullable();
            $table->foreign('wallet_id')->references('id')
                ->on('litecoin_wallets')->onDelete('cascade');

            $table->dropColumn('total_received');
            $table->dropColumn('balance');
            $table->dropColumn('total_sent');

            $table->dropForeign(['trade_id']);
            $table->dropColumn('trade_id');

            $table->string('label')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('litecoin_addresses', function(Blueprint $table){
            $table->integer('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->longText('private');
            $table->longText('wif')->nullable();
            $table->longText('public');

            $table->dropForeign(['wallet_id']);
            $table->dropColumn('wallet_id');

            $table->bigInteger('total_received')->default(0);
            $table->bigInteger('balance')->default(0);
            $table->bigInteger('total_sent')->default(0);

            $table->integer('trade_id')->unsigned()->nullable();
            $table->foreign('trade_id')->references('id')
                ->on('trades')->onDelete('cascade');

            $table->dropColumn('label');
        });
    }
}
