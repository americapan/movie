<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitLogsTable extends Migration
{
    public function up()
    {
        Schema::create('visit_logs', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ip_address', 45)->nullable()->comment('访问者IP');
            $table->text('url')->comment('完整URL');
            $table->string('method', 10)->default('GET')->comment('请求方法');
            $table->text('user_agent')->nullable()->comment('浏览器UA');
            $table->text('referer')->nullable()->comment('来源页面');
            $table->string('language', 50)->nullable()->comment('浏览器语言');
            $table->string('device_type', 20)->nullable()->comment('设备类型:desktop/mobile/tablet/bot');
            $table->string('browser', 50)->nullable()->comment('浏览器名称');
            $table->string('browser_version', 20)->nullable()->comment('浏览器版本');
            $table->string('os', 50)->nullable()->comment('操作系统');
            $table->string('route_name', 100)->nullable()->comment('路由名称');
            $table->json('query_params')->nullable()->comment('GET参数');
            $table->string('session_id', 64)->nullable()->comment('会话ID');
            $table->integer('status_code')->nullable()->comment('HTTP状态码');
            $table->decimal('request_duration', 10, 2)->nullable()->comment('请求耗时(毫秒)');
            $table->string('country', 50)->nullable()->comment('IP归属国家');
            $table->string('region', 50)->nullable()->comment('IP归属地区');
            $table->string('city', 50)->nullable()->comment('IP归属城市');
            $table->timestamps();

            $table->index('ip_address');
            $table->index('session_id');
            $table->index('route_name');
            $table->index('device_type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('visit_logs');
    }
}
