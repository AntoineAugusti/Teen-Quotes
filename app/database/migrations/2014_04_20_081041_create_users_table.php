<?php

/*
 * This file is part of the Teen Quotes website.
 *
 * (c) Antoine Augusti <antoine.augusti@teen-quotes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::dropIfExists('users');

        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('login');
            $table->string('password');
            $table->string('email');
            $table->tinyInteger('security_level')->unsigned()->default(0);
            $table->string('ip');
            $table->date('birthdate')->nullable()->default(null);
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->string('country')->references('id')->on('countries')->onDelete('cascade')->nullable()->default(null);
            $table->string('city')->nullable();
            $table->string('avatar')->nullable()->default(null);
            $table->string('about_me', 500)->nullable();
            $table->tinyInteger('hide_profile')->unsigned()->default(0);
            $table->tinyInteger('notification_comment_quote')->unsigned()->default(1);
            $table->dateTime('last_visit')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('users');
    }
}
