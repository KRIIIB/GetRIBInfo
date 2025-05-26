<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    use HasFactory;

    protected $fillable = ['label', 'rib_id', 'date', 'amount'];

    public function rib()
{
    return $this->belongsTo(Rib::class);
}

}
