<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('firstname')->nullable();
            $table->string('surname')->nullable();
            $table->string('patronymic')->nullable();
            $table->enum('gender',['male', 'female'])->default('male');
            $table->enum('family_status',['unmarried', 'married', 'divorced'])->default('unmarried');
            $table->string('avatar')->default('default.png');
            $table->string('phone1')->nullable();
            $table->string('phone2')->nullable();
            $table->string('phone3')->nullable();
            $table->string('phone4')->nullable();
            $table->string('skype')->nullable();
            $table->text('address1')->nullable();
            $table->text('address2')->nullable();
            $table->date('birthday')->nullable();
            $table->string('email')->unique();
            $table->string('email1')->nullable();
            $table->string('email2')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
