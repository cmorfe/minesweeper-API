<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSquareTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('squares', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('board_id');
            $table->integer('x');
            $table->integer('y');
            $table->enum('mark', ['NONE', 'FLAG', 'QUESTION'])->default('NONE');
            $table->boolean('mined')->default(false);
            $table->boolean('open')->default(false);

            $table->timestamps();

            $table->foreign(
                'board_id'
            )->references('id')
                ->on('boards');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('square');
    }
}
