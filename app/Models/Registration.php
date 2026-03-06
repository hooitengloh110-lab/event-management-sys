<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Registration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'event_id',
        'attendee_id',
        'status'
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function attendee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'attendee_id');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
      return $query
        ->when($filters['event'] ?? false, 
          fn($q, $eventId) => $q->where('event_id', $eventId)
        )->when($filters['attendee'] ?? false, 
          fn($q, $attendeeId) => $q->where('attendee_id', $attendeeId)
        )->when($filters['status'] ?? false, 
          fn($q, $status) => $q->where('status', $status)
        );
    }
}
