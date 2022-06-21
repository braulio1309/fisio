<?php
namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\PasswordReset;
use App\Models\User;
use App\Models\UserVerify;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class AuthController extends BaseController
{
    //--------------- Function Login ----------------\\

    public function customerRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firstname' => 'required',
            'lastname' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'phone' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        // Register
            $input = $request->all();
            $input['password']  = bcrypt($input['password']);
            $input['type']      = "customer";
            $input['role_id']   = 2;
            $input['statut']    = 1;
            $user = User::create($input);
            $token =  Str::random(128);

            // Crate register validator
                UserVerify::create([
                    'user_id' => $user->id,
                    'token' => $token
                ]);

            // Password backup
                PasswordReset::create([
                    'email' => $user->email,
                    'token' => $token,
                    'created_at' => Carbon::now()->format("Y-m-d H:i:s"),
                    'updated_at' => Carbon::now()->format("Y-m-d H:i:s")
                ]);

            // Send email
                $emailDetails = [
                    'title' => 'Confirmar Registro!',
                    'url'   => \Request::root().'/confirm-register-customer/'.$token,
                    'user' => $user->firstname.' '.$user->lastname,
                    'email' => $user->email,
                    'token' => $token
                ];

                Mail::send('emails.confirmRegister', $emailDetails, function($message) use ($emailDetails) {
                    $message->from('evmoya.89@gmail.com', 'Efecto Granel');
                    $message->to($emailDetails['email']);
                    $message->subject('Confirmar Registro - Efecto Granel');
                });

            return $this->sendResponse(200, '¡Successfully registered customer!');
    }

    //---------------- Validate register ------------\\
    public function validateRegister($token)
    {
        $verifyUser = UserVerify::where('token', $token)->first();

        if(!is_null($verifyUser) ) {
            $user = $verifyUser->user;

            if($user->email_verified_at == null) {
                $verifyUser->user->email_verified_at = Carbon::now()->format("Y-m-d H:i:s");
                $verifyUser->user->save();

                    $userStatus = $user->statut;

                    if ($userStatus === 0) {
                        return response()->json([
                            'message' => 'This user not active',
                            'status' => 'NotActive',
                        ]);
                    }

            }
            else {
                return response()->json([
                    'message' => 'Su cuenta se encuentra activa.',
                    'status' => true,
                ]);
            }

            $tokenResult = $user->createToken('Access Token');
            $token = $tokenResult->token;
            $this->setCookie('Stocky_token', $tokenResult->accessToken);

            return response()->json([
                'Stocky_token' => $tokenResult->accessToken,
                'username' => $user,
                'avatar' => $user->avatar, 'status' => true,
            ]);

        }
        else {
            return response()->json([
                'message' => 'No posee cuenta registrada en nuestra plataforma.',
                'status' => 'NotActive',
            ]);
        }

    }


    //--------------- Function Login ----------------\\

    public function getAccessToken(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = request(['email', 'password']);

        if (Auth::attempt($credentials)) {
            $userStatus = Auth::User()->statut;
            if ($userStatus === 0) {
                return response()->json([
                    'message' => 'Usuario no activo.',
                    'status' => 'NotActive',
                ]);
            }

            if (Auth::User()->email_verified_at == null) {
                return response()->json([
                    'message' => 'Debe validar su cuenta antes de loguearse',
                    'status' => 'NotValidate',
                ]);
            }

        }
        else {
            return response()->json([
                'message' => 'Incorrect Login',
                'status' => false,
            ]);
        }

        $user = auth()->user();
        $tokenResult = $user->createToken('Access Token');
        $token = $tokenResult->token;
        $this->setCookie('Stocky_token', $tokenResult->accessToken);

        return response()->json([
            'Stocky_token' => $tokenResult->accessToken,
            'username' => Auth::User(),
            'avatar' => Auth::User()->avatar, 'status' => true,
        ]);
    }

    //--------------- Function Logout ----------------\\

    public function logout()
    {
        if (Auth::check()) {
            $user = Auth::user()->token();
            $user->revoke();
            $this->destroyCookie('Stocky_token');
            return response()->json('success');
        }

    }

}
