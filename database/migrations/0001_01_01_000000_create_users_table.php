<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();

            // Campos adicionales
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('shipping_address')->nullable();
            $table->string('billing_address')->nullable();

            // Imagen por defecto asignada en la migración
            $table->string('profile_image')->default('/storage/profile-images/default/default_image.jpg');

            // Control de roles
            $table->boolean('is_admin')->default(false);

            $table->string('password');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        // El resto de tablas (password_reset_tokens y sessions) déjalas como vienen por defecto
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};