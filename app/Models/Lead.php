<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $table = 'leads';
    protected $guarded = false;


    public function contacts()
    {
        return $this->belongsToMany(Contact::class, 'contact_leads', 'lead_id', 'contact_id');
    }

    public function companies(){
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function elements()
    {
        return $this->hasMany(CatalogElement::class);
    }

    public function tags()
    {
        return $this->hasMany(Tag::class);
    }
}
