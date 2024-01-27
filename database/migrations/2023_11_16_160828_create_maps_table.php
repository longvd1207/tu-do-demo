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
        Schema::create('maps', function (Blueprint $table) {
            $table->string('id', 55)->unique();
            $table->string('ticket_type_id', 55)->nullable()->comment('id của loại vé');
            $table->string('type_id', 55)->nullable()->comment('id của khu vực hoặc dịch vụ...');
            $table->integer('type')->nullable()->comment('1 khu vực, 2 dịch vụ, 3 điểm vui chơi');
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
        Schema::dropIfExists('maps');
    }
};
