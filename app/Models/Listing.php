<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'marka',
        'modelis',
        'gads',
        'nobraukums',
        'cena',
        'degviela',
        'parnesumkarba',
        'apraksts',
    ];

    // Automātiski ielādē galerijas bildes, ja vajadzīgs
    protected $with = ['galleryImages'];

    /**
     * Lietotājs, kas ievietoja sludinājumu
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sludinājuma bildes (vienam sludinājumam var būt vairākas bildes)
     */
    public function galleryImages()
    {
        return $this->hasMany(ListingImage::class);
    }
}
