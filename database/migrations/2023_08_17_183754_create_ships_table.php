<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ships', function (Blueprint $table) {
            $table->id('sh_id');
            $table->string('sh_name', 50);
            $table->integer('sh_size');
            $table->integer('sh_count');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->default(DB::raw('NULL ON UPDATE CURRENT_TIMESTAMP'))->nullable();
        });

        DB::table("ships")->insert(
            [
                [
                    'sh_name' => 'Battleship',
                    'sh_size' => 5,
                    'sh_count' => 1
                ],
                [
                    'sh_name' => 'Destroyer',
                    'sh_size' => 4,
                    'sh_count' => 2
                ]
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ships');
    }
}
