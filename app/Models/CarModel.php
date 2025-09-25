<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarModel extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'car_brand_id',
    ];

    /**
     * @return BelongsTo<CarBrand, CarModel>
     */
    public function brand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class, 'car_brand_id');
    }
}
