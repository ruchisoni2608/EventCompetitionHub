<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ranking extends Model
{
    use HasFactory;
    protected $table="ranking";
    protected $fillable = ['id','userid','name','blacklist','costume','skill','punctual','event_id','created_by',
    'updated_by'];

      public function participant()
      {
          return $this->belongsTo(Participate::class, 'userid', 'id');
      }

      public function event_id()
      {
          return $this->belongsTo(Event::class, 'event_id', 'id');
      }
    public function createdByUser()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
protected $casts = [
    'blacklist' => 'integer',
];

}