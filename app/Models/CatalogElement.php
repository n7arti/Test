<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogElement extends Model
{
    use HasFactory;
    protected $table = 'catalog_elements';
    protected $guarded = false;

    public function leads(){
        return $this->belongsTo(Lead::class, 'lead_id', 'id');
    }
}
