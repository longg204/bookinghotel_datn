<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'room_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function cart() {
        return $this->belongsTo(Cart::class);
    }
}
