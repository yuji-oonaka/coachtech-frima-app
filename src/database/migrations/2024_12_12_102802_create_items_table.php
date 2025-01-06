<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('brand_name')->nullable();
            $table->string('img_url', 255);
            $table->text('description');
            $table->unsignedInteger('price');
            $table->enum('condition', ['新品', '未使用', '目立った傷や汚れなし', '傷や汚れあり', '全体的に状態が悪い']);
            $table->enum('status', ['出品中', '売却済み', '出品停止']);
            $table->timestamps();
            $table->softDeletes();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
