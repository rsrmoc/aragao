<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class ResetPasswordController extends Controller
{
    public function index()
    {
        return view('pages.request-password');
    }

    public function store(Request $request)
    {
        
        $request->validate(['email' => 'required|email']);

 
   
        try {
            $TOKEN=csrf_token(); 
            User::whereRaw("email='".$request['email']."'")->update(['remember_token'=>$TOKEN]);

            $conteudo='
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Untitled Document</title>
            </head>
            
            <body>
            
            <p>Alguém solicitou alteração nas suas credenciais do App Aragão. Se foi você, click no link abaixo para redefinir sua senha </p>
            <p>&nbsp;</p>
            <a href="https://app.aragao.app.br/alterar-senha/'.$TOKEN.'">Link para redefinir credenciais</a>
            </body>
            </html>'; 



            $mail = new PHPMailer;
            $mail->setLanguage('br');
            $mail->CharSet='UTF-8';
            $mail->SMTPDebug = 0;
            $mail->Debugoutput = 'html';

            $mail->IsSMTP(); // envia por SMTP
            $mail->SMTPAuth = true;		// Autenticaï¿½ï¿½o ativada
            $mail->Host = "ssl://smtp.gmail.com";
            $mail->Port = 465;  		// A porta 587 deverï¿½ estar aberta em seu servidor
            $mail->Username = 'info@rpsys.com.br'; // SMTP username
            $mail->Password = 'FyaMJRVk8c'; // SMTP password
            $mail->From = 'info@rpsys.com.br'; // From
            $mail->FromName = "Aragão"; // Nome de quem envia o email
            $mail->addAddress($request['email']);
            // $mail->AddBCC("carolina@santacasamontesclaros.com.br", 'ANA CAROLINA');
            $mail->WordWrap = 50; // Definir quebra de linha
            $mail->IsHTML(true); // Enviar como HTML
            $mail->AltBody = "This is the text-only body"; //PlainText, para caso quem receber o email no aceite o corpo HTML
   
            $mail->Subject = 'ARAGÃO - Acesso ao Sistema';
            $mail->Body    = $conteudo ;
  
            if( !$mail->send() ) {
                $staus=0; 
                $Nmstaus='Falha no enviado do email';
            } 
            else {
                $staus=1; 
                $Nmstaus='Email enviado com sucesso';
            }
 
 
            return view('pages.request-password', [
                'status' => $staus,
                'message' => __($Nmstaus)
            ]);

        } catch (Exception $e) {
             //return back()->with('error','Message could not be sent.');
             return view('pages.request-password', [
                'status' =>false,
                'message' => __($e)
            ]);
             
        }

         
    }

    public function edit(Request $request,$token)
    {
        
        return view('pages.reset-password');
    }

    public function update(Request $request)
    {
        $request->validate([
            'token' => 'required', 
            'password' => 'required|min:8|confirmed',
        ]);

        $Usuario=User::whereRaw("remember_token='".request()->token."'")->update(['password' => Hash::make($request['password']),'remember_token'=>null]);
 
        if($Usuario==true){
            return view('pages.updated-password', [
                'status' => $Usuario,
                'message' => __('Alterado com sucesso!')
            ]);
        }else{
            return view('pages.updated-password', [
                'status' => $Usuario,
                'message' => __('Erro ao alterar informação!')
            ]);
        }

    }
}
