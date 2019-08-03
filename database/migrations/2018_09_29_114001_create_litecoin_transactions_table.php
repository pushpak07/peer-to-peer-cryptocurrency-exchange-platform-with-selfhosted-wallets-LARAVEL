<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLitecoinTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('litecoin_transactions', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('address_id')->unsigned();
            $table->foreign('address_id')->references('id')
                ->on('litecoin_addresses')->onDelete('cascade');

            $table->enum('type', ['incoming', 'outgoing']);

            // Required for outgoing transactions
            $table->longText('output_address')->nullable();

            $table->integer('value')->nullable();


            $table->integer('fees')->nullable();
            $table->longText('hash')->nullable();
            $table->boolean('double_spend')->nullable();
            $table->integer('confirmations')->nullable();
            $table->string('preference')->nullable();
            $table->timestamp('received')->nullable();

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
        Schema::dropIfExists('litecoin_transactions');
    }
}
