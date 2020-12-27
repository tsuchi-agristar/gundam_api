<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->increments('game_id');
            $table->timestamp('game_start_at')->nullable(true);
            $table->timestamp('game_end_at')->nullable(true);
            $table->integer('winner')->nullable(true);
            $table->integer('total_participants')->nullable(true);
            $table->integer('total_launches')->nullable(true);
            $table->integer('japan')->nullable(true);
            $table->integer('korea')->nullable(true);
            $table->integer('english')->nullable(true);
            $table->integer('chinese')->nullable(true);
            $table->integer('a_participants')->nullable(true);
            $table->integer('a_launches')->nullable(true);
            $table->double('a_score', 8, 2)->nullable(true);
            $table->integer('b_participants')->nullable(true);
            $table->integer('b_launches')->nullable(true);
            $table->double('b_score', 8, 2)->nullable(true);
            $table->double('difficulty', 8, 2)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('games');
    }
}
