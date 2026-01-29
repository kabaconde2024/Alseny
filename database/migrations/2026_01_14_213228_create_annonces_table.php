<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('annonces', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->text('contenu');
            
            // On place image_path ici (au lieu d'utiliser ->after('contenu'))
            $table->string('image_path')->nullable(); 

            $table->boolean('is_published')->default(true);
            
            // On place published_at ici (au lieu d'utiliser ->after('is_published'))
            $table->timestamp('published_at')->nullable();

            $table->boolean('is_pinned')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            // Index pour optimiser les performances
            $table->index(['is_published', 'is_pinned', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annonces');
    }
};