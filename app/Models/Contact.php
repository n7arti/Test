<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;
    protected $table = 'contacts';
    protected $guarded = false;

    public function leads()
    {
        return $this->belongsToMany(Lead::class, 'contact_leads', 'contact_id', 'lead_id');
    }
}
