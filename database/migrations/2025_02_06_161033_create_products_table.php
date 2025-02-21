<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            //id と timestamps は、データベースに格納される情報ですが、通常はデータの管理や追跡に使われ、ユーザー向けには表示しない場合が多いです
            $table->id(); //バックエンドではidによって情報が管理されており、管理Noのようなもの
            $table->integer('company_id');
            $table->string('product_name');
            $table->integer('price');
            $table->integer('stock');
            $table->text('comment')->nullable();
            $table->string('img_path')->nullable();
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
        Schema::dropIfExists('products');
    }
};
