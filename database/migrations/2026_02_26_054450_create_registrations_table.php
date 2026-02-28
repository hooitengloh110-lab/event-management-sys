<?php

use App\Models\Event;
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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->softDeletes();
            
            $table->enum('status', ['pending', 'confirmed', 'cancelled'])->default('pending');
            $table->foreignIdFor(Event::class)->constrained('events');
            $table->foreignIdFor(User::class, 'attendee_id')->constrained('users');
            
            $table->index('event_id');
            $table->index('attendee_id');
            $table->index('status');

            $table->unique(['event_id', 'attendee_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
