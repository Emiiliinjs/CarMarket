<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarModel extends Model {
    protected $fillable = ['brand_id','title'];

    public function brand() {
        return $this->belongsTo(CarBrand::class, 'brand_id');
    }
}
