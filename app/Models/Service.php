<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $fillable = [
        'name' 
    ];

    public function modificationFields()
    {
        return $this->hasMany(ModificationField::class, 'services_id');
    }
}
