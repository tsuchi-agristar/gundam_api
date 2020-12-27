<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->integer('id');
            $table->double('difficulty', 8, 2)->nullable(true);
            $table->double('superior_attack_rate', 8, 2)->nullable(true);
            $table->integer('judge_seconds')->nullable(true);
            $table->integer('fixed_attacks_percent')->nullable(true);
            $table->integer('fixed_attacks_down_percent')->nullable(true);
            $table->integer('attack_start_time')->nullable(true);
            $table->integer('attack_end_time')->nullable(true);
            $table->integer('result_time')->nullable(true);

            $table->primary('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
}
