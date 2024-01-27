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
        Schema::create('customers', function (Blueprint $table) {
            $table->string('id', 55)->unique();
            $table->string('name', 55)->nullable();
            $table->string('email', 55)->nullable();
            $table->string('phone', 12)->nullable();
            $table->tinyInteger('gender')->comment('1 nam, 2 nu, 3 khong xac dinh');
            $table->string('address', 255)->nullable();
            $table->boolean('is_delete')->default(0);
            $table->softDeletes();
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
        Schema::dropIfExists('customers');
    }
};
