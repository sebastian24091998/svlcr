<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    //meodos para añadir informacion para un llenador masivo
    protected $fillable = ['name','image'];
    //relacion que tiene con categoria 
    public function products()
    {
        return $this->hasMany(Product::class);

    }

    //recuperar la img funcion
    public function getImagenAttribute()
    {
        
        if(file_exists('storage/categories/'. $this->image))
            return $this->image;
        else
            return 'noimg.png';
        
    }
}
