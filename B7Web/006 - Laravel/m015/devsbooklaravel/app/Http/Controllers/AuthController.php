<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', [
            'except'=>[
                'login',
                'create',
                'unauthorized',
            ]
        ]);
    }

    public function create(Request $request)
    {
        $array = ['error'=>''];

        $name = $request->input('name');
        $email = $request->input('email');
        $password = $request->input('password');
        $birthdate = $request->input('birthdate');

        if($name && $email && $password && $birthdate) {
            // VALIDANDO DATA DE NASCIMENTO
            if(strtotime($birthdate) === false) {
                $array['error'] = 'Data de nascimento inválida!';
                return $array;
            }
            // VERIFICANDO EXISTÊNCIA DO E-MAIL
            $emailExists = User::where('email', $email)->count();
            if($emailExists === 0) {
                $hash = password_hash($password, PASSWORD_DEFAULT);

                $newUser = new User();
                $newUser->name = $name;
                $newUser->email = $email;
                $newUser->password = $hash;
                $newUser->birthdate = $birthdate;
                $newUser->save();

                $token = auth()->attempt([
                    'email' => $email,
                    'password' => $password
                ]);
                if(!$token) {
                    $array['error'] = 'Ocorreu um erro!';
                    return $array;
                }
                
                $array['token'] = $token;
            } else {
                $array['error'] = 'E-mail já cadastrado!';
                return $array;
            }
        } else {
            $array['error'] = 'Não enviou todos os dados!';
            return $array;
        }

        return $array;
    }
}
// $array = ['error'=>''];



// return $array;
