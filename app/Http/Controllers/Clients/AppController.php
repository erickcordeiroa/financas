<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\AppCategory;
use App\Models\Clients\AppInvoice;
use App\Models\Clients\AppWallet;
use App\Models\User;
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

        $income = AppInvoice::with('categories')->where('user_id', Auth::user()->id)
            ->where('type', 'income')
            ->whereMonth('due_at', date('m'))
            ->orderBy('due_at', 'ASC')
            ->paginate(25);

        $filters = [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t')
        ];

        return view('client.invoices', [
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
        $wallets = AppWallet::where('user_id', auth()->user()->id)->get();

        $expense = AppInvoice::with('categories')->where('user_id', Auth::user()->id)
            ->where('type', 'expense')
            ->whereMonth('due_at', date('m'))
            ->orderBy('due_at', 'ASC')
            ->paginate(25);

        $filters = [
            'start' => date('Y-m-01'),
            'end' => date('Y-m-t')
        ];

        return view('client.invoices', [
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

        $invoices = AppInvoice::with('categories')->where('user_id', Auth::user()->id)
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

    public function invoice($id)
    {
        $categories = AppCategory::all();
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();

        $invoices = AppInvoice::where('user_id', Auth::user()->id)
            ->where('id', $id)
            ->first();

        return view('client.invoice', [
            'categories' =>  $categories,
            'wallets' => $wallets,
            'invoice' => $invoices
        ]);
    }

    public function updateInvoice($id, Request $request)
    {
        $data = $request->only([
            'type', 'currency', 'description', 'value', 'due_day', 'wallet', 'category', 'status'
        ]);

        $validate = Validator::make($data, [
            'type' => ['required', 'string'],
            'description' => ['required', 'string', 'max:100'],
            'value' => ['required', 'between:0,99.99'],
            'due_day' => ['required', 'integer', 'max:31', 'min:1'],
            'wallet' => ['required', 'integer'],
            'category' => ['required', 'integer'],
        ]);

        if ($validate->fails()) {
            return redirect()->route('app.invoice', ["id" => $id])
                ->withErrors($validate);
        }


        $invoice = AppInvoice::find($id);

        if ($data["due_day"] < 1 || $data["due_day"] > $dayOfMonth = date("t", strtotime($invoice->due_at))) {
            $validate->errors()->add("message", "O vencimento deve ser entre dia 1 e dia {$dayOfMonth} para este mês.");
        }

        if (count($validate->errors()) > 0) {
            return redirect()->route('app.invoice', ["id" => $id])->withErrors($validate);
        }

        //Fazer a verificação da Wallet

        $due_day = date("Y-m", strtotime($invoice->due_at)) . "-" . $data["due_day"];

        $invoice->wallet_id = $data["wallet"];
        $invoice->category_id = $data["category"];
        $invoice->description = $data["description"];
        $invoice->value = str_replace([".", ","], ["", "."], $data["value"]);
        $invoice->currency = $data["currency"];
        $invoice->due_at = date("Y-m-d", strtotime($due_day));
        $invoice->status = $data["status"];
        $invoice->save();

        $invoiceOf = AppInvoice::where("user_id", Auth::user()->id)
            ->where("invoice_of", $invoice->id)->get();

        if (!empty($invoiceOf) && in_array($invoice->type, ["fixed_income", "fixed_expense"])) {
            foreach ($invoiceOf as $invoiceItem) {
                if ($data["status"] == "unpaid" && $invoiceItem->status == "unpaid") {
                    $invoiceItem->delete();
                } else {
                    $due_day = date("Y-m", strtotime($invoiceItem->due_at)) . "-" . $data["due_day"];
                    $invoiceItem->category_id = $data["category"];
                    $invoiceItem->description = $data["description"];
                    $invoiceItem->wallet_id = $data["wallet"];

                    if ($invoiceItem->status == "unpaid") {
                        $invoiceItem->value = str_replace([".", ","], ["", "."], $data["value"]);
                        $invoiceItem->due_at = date("Y-m-d", strtotime($due_day));
                    }

                    $invoiceItem->save();
                }
            }

            (new AppInvoice())->fixed(Auth::user(), 3);
        }

        $type = ($data['type'] == 'income') ? "Receita" : "Despesa";
        return redirect()->route('app.invoice', ["id" => $id])
            ->with('success', "Sua {$type} foi atualizada com sucesso!");
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

        return view('client.invoices', [
            'type' => $filters['type'],
            'filters' => $filters,
            'categories' =>  $categories,
            'wallets' => $wallets,
            'invoice' => $invoice
        ]);
    }

    //Excluir Invoice
    public function destroy($id)
    {
        $invoice = AppInvoice::find($id);

        if (!$invoice) {
            return redirect()->route('app.dash')
                ->with('error', 'Registro que tentou excluir não existe, verifique!');
        }

        $invoice->delete();

        return redirect()->route('app.dash')
                ->with('success', 'O seu registro foi excluido com sucesso!');
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
