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
        Schema::create('current_loads', function (Blueprint $table) {
            $table->uuid('id');
            $table->float("1_1")->default(0);
            $table->float("1_2")->default(0);
            $table->float("1_3")->default(0);
            $table->float("2_1")->default(0);
            $table->float("2_2")->default(0);
            $table->float("2_3")->default(0);
            $table->float("3_1")->default(0);
            $table->float("3_2")->default(0);
            $table->float("3_3")->default(0);
            $table->float("4_1")->default(0);
            $table->float("4_2")->default(0);
            $table->float("4_3")->default(0);
            $table->float("5_1")->default(0);
            $table->float("5_2")->default(0);
            $table->float("5_3")->default(0);
            $table->float("6_1")->default(0);
            $table->float("6_2")->default(0);
            $table->float("6_3")->default(0);
            $table->float("7_1")->default(0);
            $table->float("7_2")->default(0);
            $table->float("7_3")->default(0);
            $table->float("8_1")->default(0);
            $table->float("8_2")->default(0);
            $table->float("8_3")->default(0);
            $table->float("9_1")->default(0);
            $table->float("9_2")->default(0);
            $table->float("9_3")->default(0);
            $table->float("10_1")->default(0);
            $table->float("10_2")->default(0);
            $table->float("10_3")->default(0);
            $table->float("11_1")->default(0);
            $table->float("11_2")->default(0);
            $table->float("11_3")->default(0);
            $table->dateTime("terminal_time")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('current_load');
    }
};
