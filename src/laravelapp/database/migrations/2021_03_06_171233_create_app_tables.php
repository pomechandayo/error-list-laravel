<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {   
        if(Schema::hasTable('users')){
            return;
        }
        if(Schema::hasTable('articles')){
            return;
        }
        if(Schema::hasTable('tags')){
            return;
        }
        if(Schema::hasTable('likes')){
            return;
        }

        Schema::create('likes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
        });

        Schema::create('tags',function
        (Blueprint $table){
            $table->bigIncrements('id');
            $table->timestamps();
        });
        Schema::create('articles',function
        (Blueprint $table){
            $table->bigIncrements('id');
            $table->timestamps();
        });
        Schema::create('users',function
        (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('name',255);
            $table->string('email',255);
            $table->string('password',15);
            $table->string('avatar_file_name')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('likes');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('users');
    }
}
