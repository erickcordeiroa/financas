<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\AppCategory;
use App\Models\Clients\AppInvoice;
use App\Models\Clients\AppWallet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $walletSelected = $request->input('wallet');

        (new AppInvoice())->fixed(Auth::user(), 3);
        (new AppWallet())->start(Auth::user());

        $categories = AppCategory::where('user_id', auth()->user()->id)->get();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();
        $expense = AppInvoice::where('user_id', Auth::user()->id)
            ->where('type', 'expense')
            ->where('status', 'unpaid')
            ->when($walletSelected, function($query) use($walletSelected){
                return $query->where('wallet_id', $walletSelected);
            })
            ->whereMonth('due_at', date('m'))
            ->limit(5)
            ->orderBy('due_at', 'DESC')->get();

        $income = AppInvoice::where('user_id', Auth::user()->id)
            ->where('status', 'unpaid')
            ->where('type', 'income')
            ->when($walletSelected, function($query) use($walletSelected){
                return $query->where('wallet_id', $walletSelected);
            })
            ->whereMonth('due_at', date('m'))
            ->limit(5)
            ->orderBy('due_at', 'DESC')->get();


        if($walletSelected) {
            $chart = $this->chartData(Auth::user(), $walletSelected);
            $wallet = (new AppInvoice())->balance(Auth::user(), $walletSelected);
        } else {
            $chart = $this->chartData(Auth::user());
            $wallet = (new AppInvoice())->balance(Auth::user());
        }

        return view("client.dashboard", [
            'wallet' => $wallet,
            'chart' => $chart,
            'categories' => $categories,
            'wallets' => $wallets,
            'walletSelected' => $walletSelected,
            'expense' => $expense,
            'income' => $income,
        ]);
    }

    public function chartData(User $user, $wallet = null): object
    {
        $dateChart = [];
        for ($month = -4; $month <= 0; $month++) {
            $dateChart[] = date("m/Y", strtotime("{$month}month"));
        }

        $chartData = new \stdClass();
        $chartData->categories = "'" . implode("','", $dateChart) . "'";
        $chartData->expense = "0,0,0,0,0";
        $chartData->income = "0,0,0,0,0";

        if($wallet){
            $concat = "wallet_id = {$wallet} AND";
        }else {
            $concat = "";
        }

        $chart = AppInvoice::selectRaw("
                    due_at,
                    year(due_at) AS due_year,
                    month(due_at) AS due_month,
                    DATE_FORMAT(due_at, '%m/%Y') AS due_date,
                    (SELECT SUM(value) FROM app_invoices WHERE {$concat} user_id = {$user->id} AND status = 'paid' AND type = 'income' AND year(due_at) = due_year AND month(due_at) = due_month) AS income,
                    (SELECT SUM(value) FROM app_invoices WHERE {$concat} user_id = {$user->id} AND status = 'paid' AND type = 'expense' AND year(due_at) = due_year AND month(due_at) = due_month) AS expense")
            ->where('user_id', $user->id)
            ->where('status', 'paid')
            ->when($wallet, function($query) use($wallet){
                return $query->where('wallet_id', $wallet);
            })
            ->where('due_at', '>=', date('d/m/Y', strtotime('+4 month')))
            ->groupBy('due_year')
            ->groupBy('due_month')
            ->limit(5)
            ->get();

        if ($chart) {
            $chartCategories = [];
            $chartExpense = [];
            $chartIncome = [];

            foreach ($chart as $chartItem) {
                $chartCategories[] = $chartItem->due_date;
                $chartExpense[] = $chartItem->expense;
                $chartIncome[] = $chartItem->income;
            }

            $chartData->categories = "'" . implode("','", $chartCategories) . "'";
            $chartData->expense = implode(",", array_map("abs", $chartExpense));
            $chartData->income = implode(",", array_map("abs", $chartIncome));
        }

        return $chartData;
    }
}
