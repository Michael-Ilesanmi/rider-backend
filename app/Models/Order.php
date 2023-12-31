<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'metadata' => AsCollection::class,
    ];

    public function rider() : BelongsTo 
    {
        return $this->belongsTo(User::class, 'rider_id');
    }
    public function user() : BelongsTo 
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function pickup() : BelongsTo 
    {
        return $this->belongsTo(City::class, 'pickup');
    }
    public function delivery() : BelongsTo 
    {
        return $this->belongsTo(City::class, 'delivery');
    }
}
