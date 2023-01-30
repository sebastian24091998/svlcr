<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    //meodos para añadir informacion para un llenador masivo
    protected $fillable = ['name','barcode','cost','price','stock','alerts','image','category_id'];
    //relacion que tiene con categoria 
    public function category()
    {
        return $this->belongsTo(Category::class);

    }
    public function getImagenAttribute()
    {
        
        if(file_exists('storage/products/'. $this->image))
            return $this->image;
        else
            return 'noimg.png';
        
    }
}
