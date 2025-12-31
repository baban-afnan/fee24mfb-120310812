<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModificationField extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'field_name',
        'field_code',
        'description',
        'base_price',
        'is_active',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function servicePrices()
    {
        return $this->hasMany(ServicePrice::class);
    }
}
