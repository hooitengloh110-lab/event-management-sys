<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'location',
        'category',
        'start_datetime',
        'end_datetime',
        'capacity',
        'price',
        'status',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
    ];

    protected $sortable = [
        'price',
        'created_at',
    ];

    public function organiser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organiser_id');
    }

    public function images(): HasMany
    {
        return $this->hasMany(EventImage::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function scopeMostRecent(Builder $query): Builder
    {
        return $this->orderByDesc('created_at');
    }

    public function scopeEventStart(Builder $query): Builder
    {
        return $this->orderBy('start_datetime', 'asc');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
      return $query->when(
        $filters['priceFrom'] ?? false,
        fn($query, $value) => $query->where('price', '>=', $value)
      )->when(
        $filters['priceTo'] ?? false,
        fn($query, $value) => $query->where('priceTo', '<=', $value)
      )->when(
        $filters['capacity'] ?? false,
        fn($query, $value) => $query->where('capacity', '>=', $value)
      )->when(
        $filters['deleted'] ?? false,
        fn($query, $value) => $query->withTrashed()
      )->when(
        $filters['by'] ?? false,
          fn($query, $value) =>
          !in_array($value, $this->sortable)
            ? $query :
            $query->orderBy($value, $filters['order'] ?? 'desc')
      );
    }

}
