<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Client;

class AuthController extends Controller {
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        // Check if field is not empty
        if (empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all fields']);
        }
        $client = new Client();
        try 
        {

            $response =  $client->post(config('service.passport.login_endpoint'), [
                "form_params" => [
                    "client_secret" => config('service.passport.client_secret'),
                    "grant_type" => "password",
                    "client_id" => config('service.passport.client_id'),
                    "username" => $request->email,
                    "password" => $request->password
                ]
            ]);

            $result  = json_decode((string) $response->getBody(), true);
            
            $responseArray['token']         = $result['access_token'];
            $responseArray['refresh_token'] = $result['refresh_token'];

            $userRole = User::where('email','=',$email)->first();
            $responseArray['user_id']   = $userRole->id;

            return response()->json(['status' => 'success', 'message' => 'Token generated','data' => $responseArray,'status_code'=>200]);
       
        } catch (BadResponseException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

 

    public function register(Request $request)
    {
        $name = $request->name;
        $email = $request->email;
        $password = $request->password;
        // Check if field is not empty
        if (empty($name) or empty($email) or empty($password)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all the fields']);
        }

        // Check if email is valid
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['status' => 'error', 'message' => 'You must enter a valid email']);
        }
        if (strlen($password) < 6) {
            return response()->json(['status' => 'error', 'message' => 'Password should be min 6 character']);
        }
        if (User::where('email', '=', $email)->exists()) {
            return response()->json(['status' => 'error', 'message' => 'User already exists with this email']);
        }

        try {
            $user = new User();
            $user->name = $name;
            $user->email = $email;
            $user->password = app('hash')->make($password);
            if ($user->save()) {
                return response()->json(['status' => 'success', 'message' => 'User created']);
                //return $this->login($request);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->each(function ($token) {
                $token->delete();
            });
            return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

 

    public function refresh_token(Request $request)
    {
        $token = $request->token;
        if (empty($token)) {
            return response()->json(['status' => 'error', 'message' => 'You must fill all fields']);
        }
        $client = new Client();
        try 
        {
            return $client->post(config('service.passport.login_endpoint'), [
                "form_params" => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' =>$token,
                    'client_id' =>config('service.passport.client_id'),
                    'client_secret' => config('service.passport.client_secret'),
                    'scope' => '',
                ]
            ]);
        } catch (BadResponseException $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }
}