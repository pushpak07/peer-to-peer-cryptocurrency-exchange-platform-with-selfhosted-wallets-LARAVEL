<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOffersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('token');

            $table->enum('type', ['buy', 'sell']);
            $table->string('coin');
            $table->string('currency');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')
                ->on('users')->onDelete('cascade');

            $table->boolean('status')->default(true);
            $table->double('min_amount');
            $table->double('max_amount');
            $table->double('profit_margin');

            $table->string('payment_method');

            $table->longText('tags')->nullable();
            $table->longText('trade_instruction')->nullable();
            $table->longText('terms')->nullable();
            $table->string('label')->nullable();

            $table->boolean('phone_verification')->default(false);
            $table->boolean('email_verification')->default(false);
            $table->boolean('trusted_offer')->default(false);

            $table->integer('deadline');

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
        Schema::dropIfExists('offers');
    }
}
