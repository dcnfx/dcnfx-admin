<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 50)->nullable()->comment('项目名称');
            $table->string('folder', 30)->nullable();
            $table->string('background', 128)->nullable()->comment('背景图');
            $table->string('logo', 128)->nullable()->comment('logo');
            $table->json('streamlist')->comment('视频列表');
            $table->json('filelist')->comment('文件列表');
            $table->string('download',100)->comment('下载地址');
            $table->unsignedInteger('created_at');
            $table->unsignedInteger('updated_at');
            $table->unsignedInteger('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admin_projects');
    }
}
