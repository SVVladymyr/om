<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid', 36)->nullable();
            $table->integer('client_id')->unsigned();
            $table->decimal('sum', 15,4)->nullable();
            $table->integer('status_id')->default(1)->unsigned();
            $table->json('sublevel_comfirm')->nullable();
            $table->date('expected_delivery_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->integer('customer_id')->unsigned()->nullable();
            $table->timestamps();
        });

        Schema::create('order_product', function (Blueprint $table) {
            $table->integer('order_id');
            $table->integer('product_id');
            $table->integer('amount')->unsigned();
            $table->decimal('price', 15,4)->nullable();
            $table->decimal('total', 15,4)->nullable();//count by trigger
            $table->primary('order_id', 'product_id');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreign('client_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('status_id')->references('id')->on('statuses');
            $table->foreign('customer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('oc_product_orders');
    }
}
