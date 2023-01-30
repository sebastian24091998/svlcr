<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denomination extends Model
{
    use HasFactory;

    //meodos para añadir informacion para un llenador masivo
    protected $fillable = ['type','value','image'];

    public function getImagenAttribute()
    {
        
        if($this->image != null)
            return(file_exists('storage/denominations/'.  $this->image)? 'denominations/'. $this->image: 'noimg.jpg');
        else
            return 'noimg.png';
        
    }
}
