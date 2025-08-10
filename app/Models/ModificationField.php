<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Service;
use App\Models\ServicePrice;

class ModificationField extends Model
{
    use HasFactory;

    protected $fillable = [
        'services_id', 
        'field_name',
        'field_code',
        'description',
        'base_price',
        'is_active',
    ];

  
    public function service()
    {
        return $this->belongsTo(Service::class, 'services_id');
    }

    public function prices()
    {
        return $this->hasMany(ServicePrice::class);
    }

    /**
     * Get price for user type (role), fallback to base price
     *
     * @param string $userType
     * @return float
     */
    public function getPriceForUserType($userType)
    {
        $matched = $this->relationLoaded('prices')
            ? $this->prices->firstWhere('user_type', $userType)
            : $this->prices()->where('user_type', $userType)->first();

        return $matched?->price ?? $this->base_price;
    }

    /**
     * Get field name with related service name
     *
     * @return string
     */
    public function getFieldWithServiceName()
    {
        return $this->field_name . ' (' . ($this->service->name ?? 'No Service') . ')';
    }
}
