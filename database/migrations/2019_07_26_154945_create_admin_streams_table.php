<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminStreamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_streams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 128)->comment('标题');
            $table->string('folder', 50)->comment('存储路径');
            $table->string('type',50);
            $table->string('url', 100)->comment('视频流地址');
            $table->string('frame', 100)->comment('视频流截图');
            $table->json('cam_to_world');
            $table->json('intrinsics');
            $table->string('remarks', 200);
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('admin_streams');
    }
}
