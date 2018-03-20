<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('guid', 36)->nullable();
            $table->integer('one_c_id')->nullable();
            $table->string('name', 255);
            $table->string('code', 128)->nullable();
            $table->integer('manager_id')->unsigned()->nullable();
            $table->integer('master_id')->unsigned()->nullable();
            $table->integer('ancestor_id')->unsigned()->nullable();
            $table->integer('root_id')->unsigned();
            $table->integer('specification_id')->unsigned()->nullable();
            $table->string('phone_number');
            $table->string('address', 255);
            $table->string('main_contractor', 255)->nullable();
            $table->string('organization', 255)->nullable();
            $table->timestamps();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->foreign('manager_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('master_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('ancestor_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('root_id')->references('id')->on('clients')->onDelete('cascade');
            $table->foreign('specification_id')->references('id')->on('specifications')->onDelete('set null');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->foreign('employer_id')->references('id')->on('clients')->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
