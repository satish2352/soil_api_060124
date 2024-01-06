<?php

namespace App\Http\Controllers;

use JWTAuth;
use App\User;
use App\Model\LoginDetail;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    public $loginAfterSignUp = true;

    public function login(Request $request)
    {
        // $credentials = $request->only("email", "password");
        // $token = null;

        // if (!$token = JWTAuth::attempt($credentials)) {
        //     return response()->json([
        //         "status" => false,
        //         "message" => "Unauthorized"
        //     ]);
        // }
        // else
        // {
        //     $userinfo=User::where(['email'=>$request->email,'visible_password'=>$request->password])->first();
        //     return response()->json([
        //     "status" => true,
        //     "token" => $token,
        //     "data" => $userinfo
        //     ]);
            
        // }
        $userinfo=User::where(['email'=>$request->email,'visible_password'=>$request->password])->whereIn('user_type',['superadmin','accountant','sales','receptionist','dispatcher','expert','farmer','warehouse'])->first();
        if($userinfo)
        {
            $credentials = $request->only("email", "password");
            $token = null;
    
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    "status" => false,
                    "message" => "Unauthorized"
                ]);
            }
            else
            {
                $userinfo=User::where(['email'=>$request->email,'visible_password'=>$request->password])->first();
                
                $loginDetail= new LoginDetail();
                $loginDetail->user_id=$userinfo->id;
                $loginDetail->token=$token;
                $loginDetail->info=now();
                $loginDetail->status='0';
                $loginDetail->save();
                
                return response()->json([
                "status" => true,
                "token" => $token,
                "data" => $userinfo
                ]);
                
                
            }
        }
        else
        {
             return response()->json([
                    "status" => false,
                    "message" => "Unauthorized"
                ]);
            
        }

        
    }
    
    
    public function login_mobileapp(Request $request)
    {   
        $userinfo=User::where(['email'=>$request->email,'visible_password'=>$request->password,'is_deleted'=>'no'])->whereIn('user_type',['fsc','bsc','dsc'])->first();
       
        if($userinfo)
        {
            $credentials = $request->only("email", "password");
            
            $token = null;
            
            if (!$token = JWTAuth::attempt($credentials)) {
                
                
                return response()->json([
                    "status" => false,
                    "message" => "Unauthorized"
                ]);
            }
            else
            {
                
                $userinfo=User::where(['email'=>$request->email,'visible_password'=>$request->password])->first();
                
                if($userinfo['is_approved']=='yes')
                {
                    
                    if($userinfo['is_block']=='no')
                    {
                    
                        $userinfoNew=User::where(['email'=>$request->email,'visible_password'=>$request->password])->update(['app_token'=>$request->app_token]);
                        
                        return response()->json([
                        "status" => true,
                        "token" => $token,
                        "data" => $userinfo
                        ]);
                        
                    }
                    else
                    {
                        return response()->json([
                        "status" => false,
                        "message" => "Your account has Blocked."
                    ]);
                    }
                }
                else
                {
                    return response()->json([
                        "status" => false,
                        "message" => "Still Your account not Approved by Admin. Please try later."
                    ]);
                }
                
            }
        }
        else
        {
             return response()->json([
                    "status" => false,
                    "message" => "Unauthorized"
                ]);
            
        }
        

        
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            "name" => "required|string",
            "email" => "required|email|unique:users",
            "password" => "required|string|min:6|max:10"
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        if ($this->loginAfterSignUp) {
            return $this->login($request);
        }

        return response()->json([
            "status" => true,
            "user" => $user
        ]);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            "token" => "required"
        ]);
        //dd($request->token);
        try {
            JWTAuth::invalidate($request->token);
            $loginDetail= LoginDetail::where('token',$request->token)->update(['status'=>'1']);


            return response()->json([
                "status" => true,
                "message" => "User logged out successfully ".$request->token
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                "status" => false,
                "message" => "Ops, the user can not be logged out"
            ]);
        }
    }
}
