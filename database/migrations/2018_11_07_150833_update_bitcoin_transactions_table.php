<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBitcoinTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bitcoin_transactions', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn('address_id');

            $table->dropColumn('double_spend');
            $table->string('transaction_id');

            $table->dropColumn('type');
            $table->dropColumn('value');

            $table->enum('state', [
                'unconfirmed', 'confirmed', 'pendingApproval',
                'rejected', 'removed', 'signed'
            ]);

            $table->dropColumn('output_address');
            $table->dropColumn('fees');

            $table->dropColumn('received');
            $table->timestamp('date')->nullable();
            $table->dropColumn('preference');

            $table->integer('wallet_id')->unsigned()->nullable();
            $table->foreign('wallet_id')->references('id')
                ->on('bitcoin_wallets')->onDelete('cascade');
        });

        Schema::table('bitcoin_transactions', function (Blueprint $table) {
            $table->enum('type', ['send', 'receive']);
            $table->bigInteger('value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('bitcoin_transactions', function (Blueprint $table) {
            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')
                ->on('bitcoin_addresses')->onDelete('cascade');

            $table->boolean('double_spend')->nullable();

            $table->dropColumn('transaction_id');
            $table->dropColumn('type');
            $table->dropColumn('value');
            $table->dropColumn('state');

            $table->longText('output_address')->nullable();
            $table->integer('fees')->nullable();

            $table->dropColumn('date');
            $table->timestamp('received')->nullable();
            $table->string('preference')->nullable();

            $table->dropForeign(['wallet_id']);
            $table->dropColumn('wallet_id');
        });

        Schema::table('bitcoin_transactions', function (Blueprint $table) {
            $table->enum('type', ['incoming', 'outgoing']);
            $table->integer('value')->nullable();
        });
    }
}
