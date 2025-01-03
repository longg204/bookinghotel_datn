<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $table = 'cart';

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function room(): BelongsTo {
        return $this->belongsTo(Room::class, 'room_id');
    }

    public function coupon(): BelongsTo {
        return $this->belongsTo(Coupon::class);
    }
}
