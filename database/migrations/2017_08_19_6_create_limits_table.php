<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLimitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limits', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('limit', 15,4)->unsigned();
            $table->integer('period')->unsigned()->default(1)->nullable();
            $table->decimal('current_value', 15,4);
            $table->boolean('active');
            $table->integer('client_id')->unsigned();
            $table->integer('limitable_id')->unsigned();
            $table->string('limitable_type', 64)->nullable();
            $table->integer('cron_last_update')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::table('limits', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('limits');
    }
}
