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
        Schema::create('researchers', function (Blueprint $table) {
            $table->id();
            $table->string('orcid')->unique()->comment('ORCID identifier');
            $table->string('given_names')->nullable()->comment('Given names from ORCID');
            $table->string('family_name')->nullable()->comment('Family name from ORCID');
            $table->string('primary_email')->nullable()->comment('Primary email from ORCID');
            $table->timestamps();
            
            $table->index('orcid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('researchers');
    }
};
