<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VnintoNibss extends Model
{
    // Explicit table name
    protected $table = 'send_vnin';

    // Optionally specify fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'bvn',
        'nin',
        'field',
        'status',
        'comment',
        'request_id',
        'submission_date',
        // Add any other relevant fields
    ];

    // Define statusHistory as an Eloquent relationship method
    public function statusHistory()
    {
        return $this->hasMany(StatusHistory::class, 'id');
    }
   
}
