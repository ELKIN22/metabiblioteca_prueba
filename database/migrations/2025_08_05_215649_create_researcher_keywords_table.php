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
        Schema::create('researcher_keywords', function (Blueprint $table) {
            $table->id();
            $table->foreignId('researcher_id')->constrained('researchers')->onDelete('cascade');
            $table->string('keyword')->comment('Keyword content from ORCID');
            $table->timestamps();
            
            $table->index(['researcher_id', 'keyword']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('researcher_keywords');
    }
};
