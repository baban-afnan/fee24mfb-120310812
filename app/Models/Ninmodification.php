<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ninmodification extends Model
{
    // Explicit table name
    protected $table = 'nin_modifications';

    
    protected $fillable = [
        'user_id',
        'reference',
        'modifcation_field_id',
        'service_id',
        'nin',
        'description',
        'status',
        'transaction_id',
        'created_at',
        'updated_at',
        'comment',
        'request_id',
        'submission_date',
        
    ];

    // Define statusHistory as an Eloquent relationship method
    public function statusHistory()
    {
        return $this->hasMany(StatusHistory::class, 'id');
    }
      

 public function modificationField()
{
    return $this->belongsTo(ModificationField::class);
}


   
}
