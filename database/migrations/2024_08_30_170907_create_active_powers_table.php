<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('active_powers', function (Blueprint $table) {
            $table->uuid('id');
            $table->float("active_power_1")->nullable();
            $table->float("active_power_2")->nullable();
            $table->float("active_power_3")->nullable();
            $table->float("active_power_4")->nullable();
            $table->float("active_power_5")->nullable();
            $table->float("active_power_6")->nullable();
            $table->float("active_power_7")->nullable();
            $table->float("active_power_8")->nullable();
            $table->float("active_power_9")->nullable();
            $table->float("active_power_10")->nullable();
            $table->float("active_power_11")->nullable();
            $table->float("active_power_12")->nullable();
            $table->float("active_power_13")->nullable();
            $table->dateTime("terminal_time")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('active_powers');
    }
};
