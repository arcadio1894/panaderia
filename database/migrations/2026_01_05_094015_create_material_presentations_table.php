<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaterialPresentationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('material_presentations', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable(); // Ej: "Docena", "1/4 Ciento"
            $table->foreignId('material_id')->nullable()->constrained('materials');
            $table->integer('quantity')->nullable();
            $table->decimal('price', 9, 2)->nullable();
            $table->boolean('active')->default(true);
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
        Schema::dropIfExists('material_presentations');
    }
}
