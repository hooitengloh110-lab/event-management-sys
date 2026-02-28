<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventImage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['filename','is_cover'];
    protected $appends = ['src'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function getSrcAttribute()
    {
        return asset("storage/{$this->filename}");
    }
}
