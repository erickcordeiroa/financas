<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\AppCategory;
use App\Models\Clients\AppInvoice;
use App\Models\Clients\AppWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = AppCategory::all();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();
        $expense = AppInvoice::where('user_id', Auth::user()->id)
            ->where('type', 'expense')
            ->where('status', 'unpaid')
            ->limit(5)
            ->orderBy('due_at', 'DESC')->get();


        $income = AppInvoice::where('user_id', Auth::user()->id)
            ->where('status', 'unpaid')
            ->where('type', 'income')
            ->limit(5)
            ->orderBy('due_at', 'DESC')->get();

        $unpaid = 2000;
        $paid = 3000;

        return view("client.dashboard", [
            'unpaid'=> $unpaid,
            'paid' => $paid,
            'bg' => ($unpaid > $paid)? 'danger' : 'success',
            'categories' => $categories,
            'wallets' => $wallets,
            'expense' => $expense,
            'income' => $income,
        ]);
    }
}
