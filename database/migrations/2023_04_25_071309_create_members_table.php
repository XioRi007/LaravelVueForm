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
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string('firstName', 30);;
            $table->string('lastName', 30);;
            $table->date('birthdate');;
            $table->string('reportSubject', 255);;
            $table->string('country', 50);
            $table->string('phone', 16);
            $table->string('email', 50)->unique();
            $table->text('photo')->nullable();
            $table->text('about')->nullable();
            $table->string('company', 50)->nullable();
            $table->string('position', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
