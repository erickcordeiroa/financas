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
    public function launch(Request $request)
    {
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

        if ($validate->fails()) {
            return redirect()->route('app.dash')
                ->withErrors($validate);
        }

        //Fazer a verificação da Wallet

        if (!empty($data['enrollments']) && ($data['enrollments'] < 2 || $data['enrollments'] > 420)) {
            $validate->errors()->add("message", "Ooops! Para lançar o número de parcelas deve ser entre 2 e 420.");
        }

        if (count($validate->errors()) > 0) {
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

        if ($invoice->repeat_when == "enrollment") {
            $invoiceOf = $invoice->id;
            for ($enrollment = 1; $enrollment < $invoice->enrollments; $enrollment++) {
                $this->enrollments($data, $invoiceOf, $enrollment);
            }
        }

        if ($invoice->type == 'income') {
            return redirect()->route('app.dash')->with('success', 'Receita lançada com sucesso. Use o filtro para controlar.');
        } else {
            return redirect()->route('app.dash')->with('success', 'Despesa lançada com sucesso. Use o filtro para controlar.');
        }
    }

    public function income(Request $request)
    {
        $categories = AppCategory::all();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();

        $income = AppInvoice::where('user_id', Auth::user()->id)
            ->where('type', 'income')
            ->whereMonth('due_at', date('m'))
            ->orderBy('due_at', 'ASC')
            ->paginate(25);

        $filters = [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t')
        ];

        return view('client.invoice', [
            'type' => "income",
            'filters' => $filters,
            'categories' =>  $categories,
            'wallets' => $wallets,
            'invoice' => $income
        ]);
    }

    public function expense(Request $request)
    {
        $categories = AppCategory::all();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();

        $expense = AppInvoice::where('user_id', Auth::user()->id)
            ->where('type', 'expense')
            ->whereMonth('due_at', date('m'))
            ->orderBy('due_at', 'ASC')
            ->paginate(25);

        $filters = [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t')
        ];

        return view('client.invoice', [
            'type' => "expense",
            'filters' => $filters,
            'categories' =>  $categories,
            'wallets' => $wallets,
            'invoice' => $expense
        ]);
    }

    public function fixed()
    {
        $categories = AppCategory::all();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();

        $invoices = AppInvoice::where('user_id', Auth::user()->id)
            ->whereIn('type', ['fixed_income', 'fixed_expense'])
            ->orderBy('due_at', 'ASC')
            ->paginate(25);

        return view('client.recurrences', [
            'type' => "fixed",
            'categories' =>  $categories,
            'wallets' => $wallets,
            'invoice' => $invoices
        ]);
    }

    public function search(Request $request)
    {
        $filters = $request->except('_token');

        $validate = Validator::make($filters, []);

        if ((!empty($filters['start']) && empty($filters['end'])) || (empty($filters['start']) && !empty($filters['end']))) {
            $validate->errors()->add("message", "Ooops! As datas precisam ser preenchidas!");
        }

        if ((!empty($filters['start']) && !empty($filters['end'])) && $filters['start'] > $filters['end']) {
            $validate->errors()->add("message", "Ooops! A Primeira data não pode estar maior que a segunda!");
        }

        if (count($validate->errors()) > 0) {
            return redirect()->route('app.' . $filters['type'])->withErrors($validate);
        }

        $invoice = $this->filter($filters);

        $categories = AppCategory::all();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();

        return view('client.invoice', [
            'type' => $filters['type'],
            'filters' => $filters,
            'categories' =>  $categories,
            'wallets' => $wallets,
            'invoice' => $invoice
        ]);
    }

    /**************************************
     ******* FUNCTIONS AUXILIARES ********
     ***************************************/
    public function enrollments($data, $invoiceOf, $enrollment)
    {
        $invoice = new AppInvoice();
        $invoice->user_id = Auth::user()->id;
        $invoice->wallet_id = $data["wallet"];
        $invoice->category_id = $data["category"];

        $invoice->invoice_of = $invoiceOf;
        $invoice->description = $data["description"];
        $invoice->type = ($data["repeat_when"] == "fixed" ? "fixed_{$data["type"]}" : $data["type"]);
        $invoice->value = str_replace([".", ","], ["", "."], $data["value"]);
        $invoice->currency = $data["currency"];

        $invoice->due_at = date("Y-m-d", strtotime($data["due_at"] . "+{$enrollment}month"));

        $invoice->repeat_when = $data["repeat_when"];
        $invoice->period = (!empty($data["period"]) ? $data["period"] : "month");
        $invoice->enrollments = (!empty($data["enrollments"]) ? $data["enrollments"] : 1);

        $invoice->status = (date($invoice->due_at) <= date("Y-m-d") ? "paid" : "unpaid");
        $invoice->enrollments_of = $enrollment + 1;
        $invoice->save();
    }

    public function filter($filters)
    {
        $invoice = AppInvoice::where('user_id', Auth::user()->id)
            ->where('type', $filters['type'])
            ->orderBy('due_at', 'ASC');

        if (
            !empty($filters['status']) && empty($filters['category'])
            && empty($filters['start']) && empty($filters['end'])
        ) {

            $invoice->where('status', $filters['status']);
        }

        //Category Only
        if (
            empty($filters['status']) && !empty($filters['category'])
            && empty($filters['start']) && empty($filters['end'])
        ) {

            $invoice->where('category_id', $filters['category']);
        }

        //Dates Only
        if (
            empty($filters['status']) && empty($filters['category'])
            && !empty($filters['start']) && !empty($filters['end'])
        ) {

            $invoice->whereBetween('due_at', [$filters['start'], $filters['end']]);
        }

        //Category and Status
        if (
            !empty($filters['status']) && !empty($filters['category'])
            && empty($filters['start']) && empty($filters['end'])
        ) {

            $invoice->where('status', $filters['status'])->where('category_id', $filters['category']);
        }

        //Status and Dates
        if (
            !empty($filters['status']) && empty($filters['category'])
            && !empty($filters['start']) && !empty($filters['end'])
        ) {

            $invoice->where('status', $filters['status'])->whereBetween('due_at', [$filters['start'], $filters['end']]);
        }

        //Category and Dates
        if (
            empty($filters['status']) && !empty($filters['category'])
            && !empty($filters['start']) && !empty($filters['end'])
        ) {

            $invoice->where('category_id', $filters['category'])->whereBetween('due_at', [$filters['start'], $filters['end']]);
        }

        //All
        if (
            !empty($filters['status']) && !empty($filters['category'])
            && !empty($filters['start']) && !empty($filters['end'])
        ) {

            $invoice->where('status', $filters['status'])
                ->where('category_id', $filters['category'])
                ->whereBetween('due_at', [$filters['start'], $filters['end']]);
        }



        return $invoice->paginate(25);
    }
}
