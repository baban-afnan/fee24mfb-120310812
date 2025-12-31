<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServicePrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'modification_field_id',
        'user_type',
        'price',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function modificationField()
    {
        return $this->belongsTo(ModificationField::class);
    }
}
