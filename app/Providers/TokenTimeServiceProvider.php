<?php
namespace App\Providers;
use Carbon\Carbon;
use Laravel\Passport\Passport;
use Laravel\Passport\PassportServiceProvider;

class TokenTimeServiceProvider extends PassportServiceProvider

{

    /**

     * Boot the authentication services for the application.

     *

     * @return void

     */

    public function boot()

    {

        Passport::tokensExpireIn(Carbon::now()->addMinutes(360));

        Passport::refreshTokensExpireIn(Carbon::now()->addMinutes(361));

        Passport::personalAccessTokensExpireIn(Carbon::now()->addMinutes(360));

 

    }

}