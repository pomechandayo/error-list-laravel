<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArticleTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {           if(Schema::hasTable('article_tags')){
        return;
    }
            Schema::create('article_tags', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->timestamps();
                $table->unsignedInteger('tag_id');
                $table->unsignedInteger('article_id')->nullable();
                $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
                $table->foreign('article_id')->references('id')->on('articles')->onDelete('cascade');
            });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_tags');
    }
}
