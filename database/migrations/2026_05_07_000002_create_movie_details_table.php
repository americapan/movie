<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMovieDetailsTable extends Migration
{
    public function up()
    {
        Schema::create('movie_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('movie_id')->unique()->comment('影视ID');
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->string('director', 500)->nullable()->comment('导演');
            $table->string('writers', 1000)->nullable()->comment('编剧');
            $table->string('casts', 1000)->nullable()->comment('演员');
            $table->string('genre', 500)->nullable()->comment('类型');
            $table->string('country', 500)->nullable()->comment('制片国家/地区');
            $table->string('language', 200)->nullable()->comment('语言');
            $table->string('release_date', 200)->nullable()->comment('上映日期');
            $table->string('runtime', 100)->nullable()->comment('片长');
            $table->string('imdb_id', 100)->nullable()->comment('IMDb ID');
            $table->text('synopsis')->nullable()->comment('剧情简介');
            $table->json('download_resources')->nullable()->comment('下载资源');
            $table->timestamp('collected_at')->nullable()->comment('采集时间');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('movie_details');
    }
}
