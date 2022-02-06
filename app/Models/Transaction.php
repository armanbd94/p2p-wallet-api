<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_user_id','to_user_id','transfered_amount','converted_amount','from_currency_id','to_currency_id',
    ];

    public function from_user()
    {
        return $this->belongsTo(User::class,'from_user_id','id');
    }
    public function to_user()
    {
        return $this->belongsTo(User::class,'to_user_id','id');
    }
    public function from_currency()
    {
        return $this->belongsTo(Currency::class,'from_currency_id','id');
    }
    public function to_currency()
    {
        return $this->belongsTo(Currency::class,'to_currency_id','id');
    }
}
