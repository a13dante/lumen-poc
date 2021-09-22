<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_details', function (Blueprint $table) {
            $table->id();
            $table->string('organisation_name');
            $table->string('logo_url')->nullable();
            $table->string('db_name');
            $table->string('db_username');
            $table->string('db_password')->nullable();
            $table->string('db_master_host');
            $table->string('db_slave_host');
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
        Schema::dropIfExists('master_details');
    }
}
