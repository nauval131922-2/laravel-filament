<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    //

    use SoftDeletes;

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    // boot
    protected static function boot()
    {
        parent::boot();
        static::forceDeleted(function ($product) {
            Storage::disk('public')->delete($product->photo);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
