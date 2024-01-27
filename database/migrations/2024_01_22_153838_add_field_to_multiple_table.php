<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('areas', function (Blueprint $table) {
            $table->string('company_id')->nullable();
        });

        Schema::table('fun_spots', function (Blueprint $table) {
            $table->string('company_id')->nullable();
        });

        Schema::table('services', function (Blueprint $table) {
            $table->string('company_id')->nullable();
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->string('company_id')->nullable();
        });

        Schema::table('ticket_types', function (Blueprint $table) {
            $table->string('company_id')->nullable();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('company_id')->nullable();
        });

        Schema::table('device', function (Blueprint $table) {
            $table->string('company_id')->nullable();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
