<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens,Authenticatable, Authorizable, HasFactory;

    // public function __construct(Request $req)
    // {
    //     \Log::info($req);
    // }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];


    // public function findForPassport($username) {
    //     $request   = Request();
    //     $loginType = $request->header('login-type');
    //     $test      = $this->where('email', $username)
    //                     ->where('is_active', true);
    //     if ($loginType == 1) {
    //         $test = $test->whereIn('role_id',[1,2,5,6,7,8])->first();
    //     } else if ($loginType == 2) {
    //         $test = $test->where('role_id',4)->first();
    //     } else if ($loginType == 3) {
    //         $test = $test->where('role_id',3)->first();
    //     } 
    //     return $test;
    // }
      

    
      // Owerride password here
      // public function validateForPassportPasswordGrant($password)
      // {
      //   $request = Request();
      //   $loginType=$request->header('login-type');
      //   if($loginType==3)
      //   {
      //     return true;
      //   }
      //   else
      //   { 
      //     \Log::info("Pass");
      //     \Log::info($this->password);
      //     return Hash::check($password, $this->password);
      //   }
      // }
}
