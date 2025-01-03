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
            $table->float("active_power_1")->default(0);
            $table->float("active_power_2")->default(0);
            $table->float("active_power_3")->default(0);
            $table->float("active_power_4")->default(0);
            $table->float("active_power_5")->default(0);
            $table->float("active_power_6")->default(0);
            $table->float("active_power_7")->default(0);
            $table->float("active_power_8")->default(0);
            $table->float("active_power_9")->default(0);
            $table->float("active_power_10")->default(0);
            $table->float("active_power_11")->default(0);
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
