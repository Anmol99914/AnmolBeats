<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Beat extends Model
{
    protected $fillable = ['category_id', 'title', 'description', 'price', 'image', 'audio_file'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}