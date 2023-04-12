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
        Schema::create('containables', function (Blueprint $table) {
            $table->bigInteger('project_id');
            $table->bigInteger('containable_id');
            $table->string('containable_type');
            $table->integer('privilege');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('containables');
    }
};
