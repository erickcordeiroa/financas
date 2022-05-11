<?php

namespace App\Models\Clients;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppWallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'wallet'
    ];

    public function start(User $user): AppWallet
    {
        if(!$this->where('user_id', $user->id)->count()){
            $this->user_id = $user->id;
            $this->wallet = "Minha Carteira";
            $this->free = true;
            $this->save();
        }

        return $this;
    }

    public function balance()
    {
        return (new AppInvoice())->balanceWallet($this);
    }
}
