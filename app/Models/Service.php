<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    public function modificationFields()
    {
        return $this->hasMany(ModificationField::class);
    }

    public function servicePrices()
    {
        return $this->hasMany(ServicePrice::class);
    }
}
