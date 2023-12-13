<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
// use Vinkla\Hashids\Facades\Hashids;
// use Hashids\Hashids;
use App\Traits\Hashidable;
use Vinkla\Hashids\Facades\Hashids;


class Participate extends Model
{
    use HasFactory;
    use Hashidable;
    protected $table="participates";
    protected $fillable = ['id','name','image','phone','address','dob'];

   
     public function ranking():HasOne
    {
        return $this->hasOne(Ranking::class);
    }
 
 public function getHashIdAttribute()
    {
        return Hashids::encode($this->getKey());
    }

}