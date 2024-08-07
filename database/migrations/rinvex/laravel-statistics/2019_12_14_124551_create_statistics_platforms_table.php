<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticsPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create((string)config('rinvex.statistics.tables.platforms'), function (Blueprint $table) {
            // Columns
            $table->increments('id');
            $table->string('family');
            $table->string('version')->nullable();
            $table->integer('count')->unsigned()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists((string)config('rinvex.statistics.tables.platforms'));
    }
}
