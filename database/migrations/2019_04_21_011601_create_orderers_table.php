<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orderers', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('orderer_id');
            $table->bigInteger('people_id')->nullable();

            $table->softDeletes();
            $table->timestamps();

            $table->text('value')->nullable();
            $table->text('label')->nullable();
            $table->text('photo')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orderers');
    }
}
