<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    use HasFactory;
    protected $table = 'product_attribute';
    
    public function field_details(){
        return $this->belongsTo(CustomField::class, 'custom_field_id', 'id');
    }

    public function custom_values(){
        return $this->hasMany(ProductAttribute::class, 'ancestor_id', 'id');
    }
}
