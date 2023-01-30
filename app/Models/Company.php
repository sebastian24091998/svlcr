<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    //meodos para añadir informacion para un llenador masivo
    protected $fillable = ['name','address','phone','taxpayer_id'];
    
}
