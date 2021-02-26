<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBoardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->integer('width')->default(2);
            $table->integer('height')->default(2);
            $table->integer('mines')->default(1);
            $table->integer('time')->default(0);
            $table->enum('game_state', ['ON', 'WON', 'LOST'])->default('ON');

            $table->timestamps();

            $table->foreign(
                'user_id'
            )->references('id')
                ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('boards');
    }
}
