<?php

namespace App\Models\Clients;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'wallet_id', 'category_id', 'description', 'type', 'value', 'due_at', 'repeat_when'
    ];

    public function categories(){
        return $this->belongsTo(AppCategory::class, 'category_id');
    }
}
