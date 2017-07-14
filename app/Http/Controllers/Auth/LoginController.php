<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.auth.login');
    }

    public function login(Request $request)
    {
        // Verifica se possui o usuario com o e-mail específicado.
        if ($user = User::where('email', $request->email)->where('status', 'Ativo')->first()) {
            //Verifica se as senhas estão iguais
            if (Hash::check($request->password, $user->password)) {
                //Se as senhas estão iguais, loga.
                Auth::login($user);
                return redirect('/');
            } else {
                //Senha incorreta
                return redirect()->back()->withErrors("Senha incorreta, verifique.")->withInput();
            }
        } else {
            // E-mail incorreto.
            return redirect()->back()->withErrors("E-mail incorreto, verifique.");
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
