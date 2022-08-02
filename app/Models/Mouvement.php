<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    use HasFactory;
    public function produit () {
        return $this->belongsTo(Produit::class);
    }
    public function type(){
        return $this->belongsTo(Type::class);
    }
}
