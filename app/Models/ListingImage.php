<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ListingImage extends Model
{
    use HasFactory;

    protected $fillable = ['listing_id', 'filename', 'sort_order'];

    protected static function booted(): void
    {
        static::deleting(function (ListingImage $image): void {
            if (! empty($image->filename)) {
                Storage::disk('public')->delete($image->filename);
            }
        });
    }

    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}
