<?php

namespace App\Http\Controllers;

use App\Enums\Status;
use App\Mail\SendLinkForgotPassword;
use App\Models\ForgotPassword;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use stdClass;

class Login extends Controller
{

    private $user;
    private $company;

    public function index()
    {

        if ((Auth::check())) {
            return redirect()->route('home');
        }

        return Inertia::render('Auth/Login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'O campo email é obrigatório',
            'password.required' => 'O campo email é obrigatório',
        ]);

        $credentials = $request->only('email', 'password');

        $user = DB::table("users")
            ->where("email", $credentials['email'])
            ->where('status', '1')
            ->first(['empresa']);


        if (!$user) {
            return redirect("login")->withErrors('Usuário não localizado.');
        }
   

        $this->user = $user;
        
        $this->setSessionData();

        if (Auth::attempt($credentials)) {
            return redirect()->route('home');
        }

        return redirect("login")->withErrors('As credenciais informadas estão inválidas!');
    }

    public function setSessionData()
    {				
        $MinhasEmpresas = explode(',',$this->user->empresa);
        $EmpresaFinal = '';
        $Empresa = DB::table("companies")->where('deleted','0')->where('status','1')->whereIn("id",$MinhasEmpresas)->get();
        foreach($Empresa as $valid){
            $EmpresaFinal .= $valid->id.',';
        }
        $EmpresaFinal = substr($EmpresaFinal,0,-1);
        session(['empresa' => $EmpresaFinal]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    public function replaceTempPasswordView()
    {
        return Inertia::render('Auth/TempPassword');
    }

    public function replaceTempPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|confirmed|max:255',
        ]);

        $user = User::find(auth()->user()->id);
        $user->password = bcrypt($request->password);
        $user->temp_password = false;
        $user->save();

        return redirect()->route('home');
    }

    public function forgotPassword()
    {
        return Inertia::render('Auth/ForgotPassword');
    }

    public function forgotPasswordSend(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'O campo email é obrigatório',
            'email.email' => 'O campo email foi digitado incorretamente',
            'email.exists' => 'O campo email foi digitado incorretamente',
        ]);

        $mail = $request->email;

        $user = User::whereEmail($mail)
            ->first();

        $code = str()->random(40);

        ForgotPassword::create([
            'user_id' => $user->id,
            'code' => $code
        ]);

        $to = $mail;
        $name = $user->name;

        Mail::to($to)->send(new SendLinkForgotPassword($name, $code));

        return Inertia::render('Auth/ForgotPasswordSuccess');
    }

    public function recoveryPassword()
    {
        $code = 0;
        return Inertia::render('Auth/RecoveryPassword', ['code' => $code]);
    }

    public function recoveryPasswordSend(Request $request)
    {
      
        $mail = $request->email;

        $Dados = DB::table("users")->where('email',$mail)->where('status','1')->first();

        if($Dados){
        
        $novaSenha = rand(0,999999);
        $save = new stdClass;   
        $save->password = bcrypt($novaSenha);
       
        $save = collect($save)->toArray();        
        DB::table("users")
        ->where("id", $Dados->id)
        ->update($save);


        $Mensagem = 'Olá <b>'.$Dados->name.'</b>, tudo bem?
        <p>Conforme solicitado, segue sua nova senha: <br><b>'.$novaSenha.'</b> <br>
        Caso não tenha solicitado esse token entre em contato com o suporte.</p>
        <br><br>							
        <b>- Obrigado, Suporte.</b>';

        $SMTP = new SMTP;
        $SMTP->EnviaEmail('Recuperação de Senha',$Mensagem,$mail);
     
        } else{
           
        }

        return redirect()->route('home');
    }
}
