<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->enum('payment_method', ['クレジットカード', 'コンビニ支払い']);
            $table->string('shipping_postal_code', 8);
            $table->string('shipping_address');
            $table->string('shipping_building')->nullable();
            $table->enum('status', ['支払い待ち', '支払い済み', '発送済み', 'キャンセル']);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};
