<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('search_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('keyword', 200)->comment('搜索关键词');
            $table->string('ip_address', 45)->nullable()->comment('IP地址');
            $table->timestamps();

            $table->index('keyword');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('search_logs');
    }
};
