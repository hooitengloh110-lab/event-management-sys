<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
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
        'start_datetime'
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

    public function getStartDatetimeAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Kuala_Lumpur');
    }

    public function getEndDatetimeAttribute($value)
    {
        return Carbon::parse($value)->timezone('Asia/Kuala_Lumpur');
    }

    public function setStartDatetimeAttribute($value)
    {
        $this->attributes['start_datetime'] = Carbon::parse($value, 'Asia/Kuala_Lumpur')->timezone('UTC');
    }

    public function setEndDatetimeAttribute($value)
    {
        $this->attributes['end_datetime'] = Carbon::parse($value, 'Asia/Kuala_Lumpur')->timezone('UTC');
    }

    public function scopeFilter(Builder $query, array $filters): Builder
    {
      return $query
      ->when($filters['keyword'] ?? false, function ($q, $value) {
            $q->where(function ($sub) use ($value) {
                $sub->where('title', 'like', "%{$value}%")
                    ->orWhere('description', 'like', "%{$value}%");
            });
      })->when($filters['category'] ?? false,
            fn($q, $value) => $q->where('category', 'like', "%{$value}%")
      )->when(
        $filters['organiser'] ?? false,
        fn($query, $value) => $query->where('organiser_id', $value)
      )->when(
        $filters['priceFrom'] ?? false,
        fn($query, $value) => $query->where('price', '>=', $value)
      )->when(
        $filters['priceTo'] ?? false,
        fn($query, $value) => $query->where('price', '<=', $value)
      )->when(
        $filters['capacity'] ?? false,
        fn($query, $value) => $query->where('capacity', '>=', $value)
      )->when($filters['freeOnly'] ?? false,
        fn($q) => $q->where('price', 0)
      )->when($filters['dateFrom'] ?? false,
        fn($q, $value) => $q->whereDate('start_datetime', '>=', $value)
      )->when($filters['dateTo'] ?? false,
        fn($q, $value) => $q->whereDate('start_datetime', '<=', $value)
      )->when(
        $filters['deleted'] ?? false,
        fn($query, $value) => $query->withTrashed()
      )->when(
        $filters['by'] ?? false,
        function ($q, $value) use ($filters) {
          if (!in_array($value, $this->sortable)) {
            return $q;
          }
          return $q->orderBy(
            $value,
            $filters['order'] ?? 'desc'
          );
        },
        fn($q) => $q->latest()
      );
    }

  public function scopePublicFilter(Builder $query, array $filters): Builder
  {
    return $query
      ->when($filters['keyword'] ?? false, function ($q, $value) {
        $q->where(function ($sub) use ($value) {
          $sub->where('title', 'like', "%{$value}%")
            ->orWhere('description', 'like', "%{$value}%")
            ->orWhere('location', 'like', '%' . "%{$value}%");
        });
      })->when(
        $filters['category'] ?? false,
        fn($q, $value) => $q->where('category', 'like', "%{$value}%")
      )->when(($filters['price'] ?? null) === 'free',
        fn($q) => $q->where('price', 0)
      )
      ->when(($filters['price'] ?? null) === 'paid',
        fn($q) => $q->where('price', '>', 0)
      )->when($filters['date'] ?? false, function ($q, $value) {
        switch ($value) {
          case 'today':
            $q->whereDate('start_datetime', today());
            break;
          case 'tomorrow':
            $q->whereDate('start_datetime', today()->addDay());
            break;
          case 'this_week':
            $q->whereBetween('start_datetime', [
              now(),
              now()->endOfWeek()
            ]);
            break;
          case 'this_month':
            $q->whereMonth('start_datetime', now()->month);
            break;
        }
      })->when($filters['sort'] ?? false, function ($q, $value) {
          switch ($value) {
            case 'date':
              $q->orderBy('start_datetime', 'asc');
              break;
            case 'popular':
              $q->orderBy('registrations_count', 'desc');
              break;
            case 'price_low':
              $q->orderBy('price', 'asc');
              break;
            case 'price_high':
              $q->orderBy('price', 'desc');
              break;
          }
        }, fn($q) => $q->orderBy('start_datetime', 'asc')
      )->when(
        $filters['priceFrom'] ?? false,
        fn($query, $value) => $query->where('price', '>=', $value)
      )->when(
        $filters['priceTo'] ?? false,
        fn($query, $value) => $query->where('price', '<=', $value)
      )->when(
        $filters['capacity'] ?? false,
        fn($query, $value) => $query->where('capacity', '>=', $value)
      )->when(
        $filters['freeOnly'] ?? false,
        fn($q) => $q->where('price', 0)
      )->when(
        $filters['dateFrom'] ?? false,
        fn($q, $value) => $q->whereDate('start_datetime', '>=', $value)
      )
      ->when(
        $filters['dateTo'] ?? false,
        fn($q, $value) => $q->whereDate('start_datetime', '<=', $value)
      )->when(
        $filters['deleted'] ?? false,
        fn($query, $value) => $query->withTrashed()
      )->when(
        $filters['by'] ?? false,
        function ($q, $value) use ($filters) {
          if (!in_array($value, $this->sortable)) {
            return $q;
          }
          return $q->orderBy(
            $value,
            $filters['order'] ?? 'desc'
          );
        },
        fn($q) => $q->latest()
      );
  }
}
