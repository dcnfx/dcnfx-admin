<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminMaterialsCompressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_materials_compress', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('material_id')->unsigned();
            $table->string('filename', 128)->nullable()->comment('原文件名');
            $table->string('suffix', 10)->nullable()->comment('文件后缀');
            $table->string('type', 20)->nullable()->comment('文件类型，texture，还是model');
            $table->string('path', 255)->nullable();
            $table->bigInteger('size');
            $table->string('desc', 50)->nullable();
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
        Schema::dropIfExists('admin_materials_compress');
    }
}
