<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLimitIncreasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('limit_increases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('client_id')->unsigned();
            $table->integer('consumer_id')->unsigned();
            $table->string('item', 128);
            $table->decimal('amount_asked');
            $table->decimal('amount_increased');
            $table->timestamp('created_at');
            $table->timestamp('confirmed_at');
            $table->timestamp('aborted_at');

        });

        Schema::table('limit_increases', function (Blueprint $table) {
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
        Schema::dropIfExists('limit_increases');
    }
}
