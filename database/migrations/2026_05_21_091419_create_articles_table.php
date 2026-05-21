<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('old_price', 10, 2)->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('on_sale')->default(false);

            // Campo JSON para guardar un array de hasta 5 imágenes
            $table->json('images')->nullable();

            $table->integer('item_state')->nullable(); // Ej: 1=Nuevo, 2=Segunda mano

            // Clave foránea a tu tabla categories
            $table->foreignId('category_id')->constrained()->onDelete('cascade');

            $table->integer('stock')->default(0);

            // Estadísticas
            $table->decimal('product_avg', 3, 2)->nullable()->default(0); // Nota de reseñas (0 a 5)
            $table->integer('product_count')->nullable()->default(0); // Cantidad de reseñas
            $table->integer('sell_count')->nullable()->default(0); // Cantidad de ventas

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('articles');
    }
};