<?php

namespace App\Models\Clients;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AppInvoice extends Model
{
    use HasFactory;

    public $wallet;

    protected $fillable = [
        'user_id', 'wallet_id', 'category_id', 'description', 'type', 'value', 'due_at', 'repeat_when'
    ];

    public function categories()
    {
        return $this->belongsTo(AppCategory::class, 'category_id');
    }

    public function wallets()
    {
        return $this->belongsTo(AppWallet::class, 'wallet_id');
    }

    public function balance(User $user, $wallet = null): object
    {
        $balance = new \stdClass();
        $balance->income = 0;
        $balance->expense = 0;
        $balance->wallet = 0;
        $balance->balance = "positive";
        $balance->walletName = "Saldo Geral";

        if($wallet){
            $concat = "wallet_id = {$wallet} AND";
        }else {
            $concat = "";
        }
        

        $find = $this->selectRaw("
                (SELECT SUM(value) FROM app_invoices WHERE {$concat} user_id = {$user->id} AND status = 'paid' AND type = 'income' {$this->wallet}) AS income,
                (SELECT SUM(value) FROM app_invoices WHERE {$concat} user_id =  {$user->id} AND status = 'paid' AND type = 'expense' {$this->wallet}) AS expense
            ")->where('user_id', auth()->user()->id)
            ->where('status', 'paid')
            ->first();

        if ($find) {
            $balance->income = abs($find->income);
            $balance->expense = abs($find->expense);
            $balance->wallet = $balance->income - $balance->expense;
            $balance->balance = ($balance->wallet >= 1 ? "positive" : "negative");
            $balance->walletName = ($wallet != null)? AppWallet::find($wallet)->wallet : 'Saldo Geral';
        }

        return $balance;
    }


    public function balanceWallet(AppWallet $wallet): object
    {
        $user = Auth::user();
        $balance = new \stdClass();
        $balance->income = 0;
        $balance->expense = 0;
        $balance->wallet = 0;
        $balance->balance = "positive";

        $find = $this->selectRaw("
            (SELECT SUM(value) FROM app_invoices WHERE user_id = {$user->id} AND wallet_id = {$wallet->id} AND status = 'paid' AND type = 'income') AS income,
            (SELECT SUM(value) FROM app_invoices WHERE user_id = {$user->id} AND wallet_id = {$wallet->id} AND status = 'paid' AND type = 'expense') AS expense
        ")->where('user_id', auth()->user()->id)
            ->where('status', 'paid')
            ->first();

        if ($find) {
            $balance->income = abs($find->income);
            $balance->expense = abs($find->expense);
            $balance->wallet = $balance->income - $balance->expense;
            $balance->balance = ($balance->wallet >= 1 ? "positive" : "negative");
        }

        return $balance;
    }

    public function fixed($user, int $afterMonths = 1)
    {
        $fixed = $this->where('user_id', $user->id)
            ->where('status', 'paid')
            ->whereIn('type', ['fixed_income', 'fixed_expense'])
            ->get();

        if (!$fixed) {
            return;
        }

        foreach ($fixed as $fixedItem) {
            $invoice = $fixedItem->id;
            $start = new \DateTime($fixedItem->due_at);
            $end = new \DateTime("+{$afterMonths}month");

            if ($fixedItem->period == "month") {
                $interval = new \DateInterval("P1M");
            }

            if ($fixedItem->period == "year") {
                $interval = new \DateInterval("P1Y");
            }

            $period = new \DatePeriod($start, $interval, $end);
            foreach ($period as $item) {
                $getFixed = $this->where('user_id', $user->id)
                    ->where('invoice_of', $fixedItem->id)
                    ->whereYear('due_at', $item->format("Y"))
                    ->whereMonth('due_at', $item->format("m"))
                    ->first();

                if (!$getFixed) {
                    $newItem = new AppInvoice();
                    $newItem->user_id = $user->id;
                    $newItem->wallet_id = $fixedItem->wallet_id;
                    $newItem->category_id = $fixedItem->category_id;
                    $newItem->invoice_of = $invoice;
                    $newItem->description = $fixedItem->description;
                    $newItem->type = str_replace("fixed_", "", $fixedItem->type);
                    $newItem->value = $fixedItem->value;
                    $newItem->currency = $fixedItem->currency;
                    $newItem->due_at = $item->format("Y-m-d");
                    $newItem->repeat_when = $fixedItem->repeat_when;
                    $newItem->period = $fixedItem->period;
                    $newItem->enrollments = $fixedItem->enrollments;
                    $newItem->status = ($item->format("Y-m-d") <= date("Y-m-d") ? "paid" : "unpaid");
                    $newItem->save();
                }
            }
        }
    }
}
