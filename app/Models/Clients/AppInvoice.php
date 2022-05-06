<?php

namespace App\Models\Clients;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'wallet_id', 'category_id', 'description', 'type', 'value', 'due_at', 'repeat_when'
    ];

    public function categories()
    {
        return $this->belongsTo(AppCategory::class, 'category_id');
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
                    $newItem->value = str_replace([".", ","], ["", "."], $fixedItem->value);
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
