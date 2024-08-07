<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->engine = 'InnoDB';

            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('order_id')->nullable();
            $table->enum('status',['1','2','3','4','5'])->default(1);
            $table->softDeletes();
            $table->timestamps();

            $table->text('title')->nullable();
            $table->text('content')->nullable();
            $table->text('content_short')->nullable();
            $table->float('price')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reports');
    }
}
