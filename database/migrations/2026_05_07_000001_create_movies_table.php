<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMoviesTable extends Migration
{
    public function up()
    {
        Schema::create('movies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title', 500)->comment('标题');
            $table->string('poster_url', 1000)->nullable()->comment('海报URL');
            $table->string('source_url', 500)->unique()->comment('源详情页URL');
            $table->date('publish_date')->nullable()->comment('发布日期');
            $table->decimal('douban_rating', 3, 1)->nullable()->comment('豆瓣评分');
            $table->decimal('imdb_rating', 3, 1)->nullable()->comment('IMDB评分');
            $table->text('description')->nullable()->comment('简介摘要');
            $table->timestamp('collected_at')->nullable()->comment('采集时间');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movies');
    }
}
