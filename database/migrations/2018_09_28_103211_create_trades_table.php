<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTradesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trades', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');

            $table->enum('type', ['buy', 'sell']);
            $table->string('coin');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->integer('partner_id')->unsigned();
            $table->foreign('partner_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->integer('offer_id')->unsigned()->nullable();
            $table->foreign('offer_id')->references('id')
                ->on('offers')->onDelete('set null');

            $table->string('currency');

            $table->double('fee');
            $table->integer('amount')->default(0);
            $table->double('rate');

            $table->longText('offer_terms')->nullable();
            $table->longText('instruction')->nullable();
            $table->string('label')->nullable();

            $table->string('dispute_by')->nullable();
            $table->longText('dispute_comment')->nullable();
            $table->boolean('confirmed')->default(false);
            $table->integer('deadline');

            $table->enum('status', [
                'active', 'successful', 'cancelled', 'dispute', 'pending'
            ])->default('active');

            $table->string('payment_method');

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
        Schema::dropIfExists('trades');
    }
}
