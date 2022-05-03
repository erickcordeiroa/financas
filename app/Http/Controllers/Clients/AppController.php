<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\AppCategory;
use App\Models\Clients\AppInvoice;
use App\Models\Clients\AppWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AppController extends Controller
{
    public function launch(Request $request){
        $data = $request->only([
            'type', 'currency', 'description', 'value', 'due_at', 'wallet', 'category', 'repeat_when', 'period', 'enrollments'
        ]);

        $validate = Validator::make($data, [
            'type' => ['required', 'string'],
            'description' => ['required', 'string', 'max:100'],
            'value' => ['required', 'between:0,99.99'],
            'due_at' => ['required', 'date', 'date_format:Y-m-d'],
            'wallet' => ['required', 'integer'],
            'category' => ['required', 'integer'],
            'repeat_when' => ['required']
        ]);

        if($validate->fails()){
            return redirect()->route('app.dash')
                ->withErrors($validate);
        }

        //Fazer a verificação da Wallet

        if(!empty($data['enrollments']) && ($data['enrollments'] < 2 || $data['enrollments'] > 420)){
            $validate->errors()->add("message", "Ooops! Para lançar o número de parcelas deve ser entre 2 e 420.");
        }

        if(count($validate->errors()) > 0){
            return redirect()->route('app.dash')->withErrors($validate);
        }

        $status = (date($data['due_at']) <= date('Y-m-d') ? "paid" : "unpaid");

        $invoice = new AppInvoice();
        $invoice->user_id = Auth::user()->id;
        $invoice->wallet_id = $data["wallet"];
        $invoice->category_id = $data["category"];
        $invoice->invoice_of = null;
        $invoice->description = $data["description"];
        $invoice->type = ($data["repeat_when"] == "fixed" ? "fixed_{$data["type"]}" : $data["type"]);
        $invoice->value = str_replace([".", ","], ["", "."], $data["value"]);
        $invoice->currency = $data["currency"];
        $invoice->due_at = $data["due_at"];
        $invoice->repeat_when = $data["repeat_when"];
        $invoice->period = (!empty($data["period"]) ? $data["period"] : "month");
        $invoice->enrollments = (!empty($data["enrollments"]) ? $data["enrollments"] : 1);
        $invoice->enrollments_of = 1;
        $invoice->status = ($data["repeat_when"] == "fixed" ? "paid" : $status);
        $invoice->save();

        if($invoice->repeat_when == "enrollment"){
            $invoiceOf = $invoice->id;
            for($enrollment = 1; $enrollment < $invoice->enrollments; $enrollment++){
                $invoice->id = null;
                $invoice->invoice_of = $invoiceOf;
                $invoice->due_at = date("Y-m-d", strtotime($data["due_at"] . "+{$enrollment}month"));
                $invoice->status = (date($invoice->due_at) <= date("Y-m-d") ? "paid" : "unpaid");
                $invoice->enrollments_of = $enrollment + 1;
                $invoice->save();
            }
        }

        if($invoice->type == 'income'){
            return redirect()->route('app.dash')->with('success', 'Receita lançada com sucesso. Use o filtro para controlar.');
        } else {
            return redirect()->route('app.dash')->with('success', 'Despesa lançada com sucesso. Use o filtro para controlar.');
        }
    }

    public function income(Request $request){
        $categories = AppCategory::all();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();

        $income = AppInvoice::where('user_id', Auth::user()->id)
            ->where('type', 'income')
            ->get();

        return view('client.invoice', [
            'type' => "income",
            'categories' =>  $categories,
            'wallets' => $wallets,
            'income' => $income
        ]);
    }

    public function expense(Request $request){
        $categories = AppCategory::all();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();

        $expense = AppInvoice::where('user_id', Auth::user()->id)
        ->where('type', 'expense')
        ->get();

        return view('client.invoice', [
            'type' => "expense",
            'categories' =>  $categories,
            'wallets' => $wallets,
            'expense' => $expense
        ]);
    }
}
