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
    }
}
