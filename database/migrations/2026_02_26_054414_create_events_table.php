<?php

use App\Models\User;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            $table->string('title');
            $table->text('description');
            $table->string('location');
            $table->string('category', 100);
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->unsignedSmallInteger('capacity');

            $table->unsignedInteger('price')->default(0);
            $table->enum('status', ['draft', 'published', 'cancelled'])->default('draft');
            $table->foreignIdFor(User::class, 'organiser_id')->constrained('users');

            $table->index('title');
            $table->index('category');
            $table->index('location');
            $table->index('end_datetime');
            $table->index('status');
            $table->index('organiser_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
