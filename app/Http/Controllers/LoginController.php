<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth,DB;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        try{
            //dados dologin do pronto
            $dadosLoginPronto = $this->loginPronto($request->email,$request->password);
            //dd($dadosLoginPronto);

            // adicionar um usuário através do login do pronto
            User::create(['name' => 'medico@teste','email'=>$dadosLoginPronto[0]->LoginCodigo,'password' => $request->password]);

        }catch(\Exception $e){
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return redirect()->intended('home');
            }
        }

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect()->intended('home');
        }
    }

    public function loginPronto($email,$password)
    {
        //dd($email,$password);

        $query = DB::connection('sqlsrv')->select(
        "SELECT LoginId,LoginCodigo,LoginSenha
            FROM Login
            WHERE LoginCodigo  = :email
            AND LoginSenha  = CONVERT(VARCHAR(32), HASHBYTES('MD5', '$password'), 2)",
            ['email' => $email]
        );

        if (count($query) > 0) {
            return $query;
        } else {
            return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }

}
