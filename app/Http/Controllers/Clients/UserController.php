<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show()
    {
        $id = intval(Auth::id());
        $user = User::find($id);

        if (!$user) {
            return redirect()->route('app.dash');
        }

        return view('client.profile', ['user' => $user]);
    }

    public function update(Request $request)
    {

        $id = intval(Auth::id());
        $user = User::find($id);

        //Validando de o Usuário existe no banco de dados
        if (!$user) {
            return redirect()->route('app.dash');
        }

        $data = $request->only([
            'name', 'email', 'password', 'password_confirmation'
        ]);

        $validator = Validator::make($data, [
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'string', 'max:100', 'email']
        ]);

        $user->name = $data['name'];

        //Verify changed email
        if ($user->email != $data['email']) {
            $hasEmail = User::where('email', $data['email'])->get();

            if (count($hasEmail) !== 0) {
                $validator->errors()->add('email', __('validation.unique', [
                    "attribute" => 'email'
                ]));
            } else {
                $user->email = $data["email"];
            }
        }

        if(!empty($data['password'])){
            if (strlen($data['password']) < 8) {
                $validator->errors()->add('password', __('validation.min.string', [
                    "attribute" => 'password',
                    "min" => 8
                ]));
            } elseif ($data['password'] !== $data['password_confirmation']) {
                $validator->errors()->add('password', "O campo SENHA e CONFIRMAR SENHA estão diferentes, verifique por favor!");
            } else {
                $user->password = Hash::make($data["password"]);
            }
        }

        if (count($validator->errors()) > 0) {
            return redirect()->route('app.profile', ["user" => $id])
                ->withErrors($validator);
        }

        $user->save();

        return redirect()->route("app.profile")
            ->with('success', 'Suas informações foram alteradas com sucesso!');
    }
}
