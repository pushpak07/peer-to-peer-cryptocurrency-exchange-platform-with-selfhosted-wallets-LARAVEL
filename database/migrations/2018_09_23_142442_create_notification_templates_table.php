<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTemplatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->enum('level', ['info', 'success', 'error'])
                ->default('info');

            $table->longText('subject')->nullable();
            $table->longText('intro_line')->nullable();
            $table->longText('action')->nullable();
            $table->longText('channels')->nullable();
            $table->longText('outro_line')->nullable();
            $table->longText('message')->nullable();

            $table->boolean('action_editable')->default(false);

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
        Schema::dropIfExists('notification_templates');
    }
}
