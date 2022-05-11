<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Clients\AppInvoice;
use App\Models\Clients\AppWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class WalletController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $wallets = AppWallet::where('user_id', Auth::user()->id)->get();
        return view('client.wallet', ['wallets' => $wallets]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->only(['wallet']);
        $validate = Validator::make($data, ['wallet'=> 'required|string|min:5|max:100']);

        if($validate->fails()){
            return redirect()->route('app.wallets')
                ->withErrors($validate);
        }

        $wallet = new AppWallet();
        $wallet->user_id = Auth::user()->id;
        $wallet->wallet = $data['wallet'];
        $wallet->save();

        return redirect()->route('app.wallets')
            ->with('success', 'A sua nova carteira foi criada com sucesso!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wallet = AppWallet::find($id);

        if(!$wallet){
            return redirect()->route('app.wallets')
                ->with('error', 'A carteira que tentou excluir não existe!');
        }

        $invoice = AppInvoice::where("wallet_id", $wallet->id)->get();

        if($invoice){
            foreach($invoice as $item){
                $item->delete();
            }
        }

        $wallet->delete();

        (new AppWallet())->start(Auth::user());

        return redirect()->route('app.wallets')
                ->with('success', 'A sua carteira foi excluida com sucesso!');
    }
}
