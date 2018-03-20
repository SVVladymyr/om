<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSpecificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('specifications', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 128);
            $table->integer('main_id')->unsigned()->default(null);
            $table->integer('manager_id')->unsigned()->default(null);
            $table->integer('order_begin')->unsigned()->default(null);
            $table->integer('order_end')->unsigned()->default(null);
            $table->timestamps();
        });

        Schema::create('product_specification', function (Blueprint $table) {
            $table->integer('specification_id');
            $table->integer('product_id');
            $table->decimal('price', 15,4);
            $table->integer('cost_item_id')->unsigned()->default(null);
            $table->decimal('limit', 15,4)->unsigned()->default(null);
            $table->integer('period')->unsigned()->nullable();
            $table->primary('specification_id', 'product_id')->unique();
        });

        Schema::table('specifications', function (Blueprint $table) {
            $table->foreign('main_id')->references('id')->on('specifications')->onDelete('cascade');            
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('specifications');
        Schema::dropIfExists('product_specification');
    }
}
