<?php

/**
 * Flash Helper
 * @param  string|null  $title
 * @param  string|null  $text
 * @return void
 */
use App\Model\Notification;

function getsettingfront($keyName)
{
    $result=App\FrontSetting::where('keyname', 'LIKE', '%'.$keyName.'%')->get();
    if(count($result)>0)
    {
        $result=$result[0]->value;    
    }
    else
    {
        $result='';
    }
    
    return $result;
}

function testimonialsAll()
{
    $testimonials=App\FrontTestimonials::all();
    return $testimonials;
}


function getUserSubsribedToCourse($courseId)
{
    $userCount=\App\Payment::where('plan_type','=','lms')
                            ->where('item_id','=',$courseId)
                            ->where('payment_status','=','success')
                            ->get();
    $userCount=$userCount->count();
    return $userCount;
}


function getUserSubsribedToCourseDetails($courseId)
{
    $userDetails=\App\Payment::join('users','users.id','=','payments.user_id')
                            ->where('plan_type','=','lms')
                            ->where('item_id','=',$courseId)
                            ->where('payments.payment_status','=','success')
                            ->where('payments.end_date','>',date('Y-m-d'))
                            ->get();
    return $userDetails;
}

//Code Added By Nilesh

function getUserSubsribedToNiftyServiceCount($serviceId)
{
    $userCount=\App\Payment::where('plan_type','=','nifty')
                            ->where('item_id','=',$serviceId)
                            ->where('payment_status','=','success')
                            ->where('end_date','>',date('Y-m-d'))
                            ->get();
    $userCount=$userCount->count();
    return $userCount;
}

function getUserSubsribedToNiftyServiceDetails($serviceId)
{
    //dd($serviceId);
    $userDetails=\App\Payment::join('users','users.id','=','payments.user_id')
                             
                            ->where('payments.plan_type','=','nifty')
                            ->where('payments.item_id','=',$serviceId)
                            ->where('payments.payment_status','=','success')
                            ->where('payments.end_date','>',date('Y-m-d'))
                            ->get();
   
    return $userDetails;
}


function getUserSubsribedToCallServiceCount($serviceId)
{
    $userCount=\App\Payment::where('plan_type','=','callingservice')
                            ->where('item_id','=',$serviceId)
                            ->where('payment_status','=','success')
                             ->where('end_date','>',date('Y-m-d'))
                            ->get();
    $userCount=$userCount->count();
    return $userCount;
}

function getUserSubsribedToCallServiceDetails($serviceId)
{
    $userDetails=\App\Payment::join('users','users.id','=','payments.user_id')
                             
                            ->where('payments.plan_type','=','callingservice')
                            ->where('payments.item_id','=',$serviceId)
                            ->where('payments.payment_status','=','success')
                            ->where('payments.end_date','>',date('Y-m-d'))
                            ->get();
    return $userDetails;
}

//End Code By Nilesh

 function getPercentageCount($item_id,$userIdNew='')
{
    DB::enableQueryLog();
        if($userIdNew=='')
        {
            $userId=Auth::user()->id;
        }
        else
        {
            $userId=$userIdNew;
        }
        
        $paymentInfo=\App\Payment::where('user_id','=',$userId)
                                    ->where('plan_type','=','lms')
                                    ->where('item_id','=',$item_id)
                                    ->where('payment_status','=','success')
                                    ->first();
        //dd($paymentInfo);
        if($paymentInfo)
        {
            $lmsContentAll=DB::table('lmsseries_data')
                   ->join('lmscontents', 'lmscontents.id', '=', 'lmscontent_id')
                     ->where('lmsseries_id', '=', $paymentInfo->item_id)
                     ->where('content_type', '=', 'iframe')
                     ->get();
            //$lmsContentAll=\App\LmsSeriesData::where('lmsseries_id','=',$paymentInfo->item_id)->get(); //Old Code
            $lmsContentCount=$lmsContentAll->count();
            $allrecoredofuserlmsview=\App\UserCourceCompletionStage::where('user_id','=',$userId)
                                                                    ->where('lmsseriesid', '=', $paymentInfo->item_id)
                                                                    ->where('percentage_cover', '>', 75)
                                                                    ->get(); 
          
            //dd($allrecoredofuserlmsview);
            $allrecoredofuserlmsCount=$allrecoredofuserlmsview->count();
            $percentage=$allrecoredofuserlmsCount/$lmsContentCount*100;
            $totaltimespent=\App\UserCourceCompletionStage::where('user_id','=',$userId)->where('lmsseriesid', '=', $paymentInfo->item_id)->sum('time_spent');
            //dd($totaltimespent);
            //dd(DB::getQueryLog());
            $hr=floor($totaltimespent/3600);
            $min=floor(($totaltimespent/60)%60);
            $sec=$totaltimespent%60;
            
            $data=array();
            $data['hr']=$hr;
            $data['min']=$min;
            $data['sec']=$sec;
            $data['per']=$percentage;
            
            return $data;
            
        }
        else
        {
           $data=array();
            $data['hr']=0;
            $data['min']=0;
            $data['sec']=0;
            $data['per']=0;
            return $data;
        }
        
    }
function flash($title = null, $text = null, $type='info')
{
    $flash = app('App\Http\Flash');

    if (func_num_args() == 0) {
        return $flash;
    }
    return $flash->$type($title, $text);
}

/**
 * Language Helper
 * @param  string|null  $phrase
 * @return string
 */
function getPhrase($key = null)
{
  
    $phrase = app('App\Language');

    if (func_num_args() == 0) {
        return '';
    }

    return  $phrase::getPhrase($key); 
}

/**
 * This method fetches the specified key in the type of setting
 * @param  [type] $key          [description]
 * @param  [type] $setting_type [description]
 * @return [type]               [description]
 */
function getSetting($key, $setting_type)
{
    return App\Settings::getSetting($key, $setting_type);
}

/**
 * This method fetches the specified key in the type of setting
 * @param  [type] $key          [description]
 * @param  [type] $setting_type [description]
 * @return [type]               [description]
 */
function getThemeSetting($key, $setting_type)
{
    return App\SiteTheme::getSetting($key, $setting_type);
}

/**
 * Language Helper
 * @param  string|null  $phrase
 * @return string
 */
function isActive($active_class = '', $value = '')
{
    $value = isset($active_class) ? ($active_class == $value) ? 'active' : '' : '';
    if($value)
        return "class = ".$value;
    return $value; 
}

/**
 * This method returns the path of the user image based on the type
 * It verifies wether the image is exists or not, 
 * if not available it returns the default image based on type
 * @param  string $image [Image name present in DB]
 * @param  string $type  [Type of the image, the type may be thumb or profile, 
 *                       by default it is thumb]
 * @return [string]      [returns the full qualified path of the image]
 */
function getProfilePath($image = '', $type = 'thumb')
{
    $obj = app('App\ImageSettings');
    $path = '';
    
    if($image=='') {
        if($type=='profile')
            return PREFIX.$obj->getDefaultProfilePicPath();
        return PREFIX.$obj->getDefaultprofilePicsThumbnailpath();
    }
  

    if($type == 'profile')
        $path = $obj->getProfilePicsPath();
    else
        $path = $obj->getProfilePicsThumbnailpath();
    $imageFile = $path.$image;

    if (File::exists($imageFile)) {
        return PREFIX.$imageFile;
    }

    if($type=='profile')
        return PREFIX.$obj->getDefaultProfilePicPath();
    return PREFIX.$obj->getDefaultprofilePicsThumbnailpath();

}

/**
 * This method returns the standard date format set by admin
 * @return [string] [description]
 */
function getDateFormat()
{
    $obj = app('App\GeneralSettings');
    return $obj->getDateFormat(); 
}


function getBloodGroups()
{
    return array(
            'A +ve'    => 'A +ve',
            'A -ve'    => 'A -ve',
            'B +ve'    => 'B +ve',
            'B -ve'    => 'B -ve',
            'O +ve'    => 'O +ve',
            'O -ve'    => 'O -ve',
            'AB +ve'   => 'AB +ve',
            'AB -ve'   => 'AB -ve',
        );
}

function getAge($date)
{
    

    // return Carbon::createFromDate(1984, 7, 17)->diff(Carbon::now())->format('%y years, %m months and %d days');
}

function getLibrarySettings()
{
    return json_decode((new App\LibrarySettings())->getSettings());
}

function getExamSettings()
{
    return json_decode((new App\ExamSettings())->getSettings());
}

/**
 * This method is used to generate the formatted number based 
 * on requirement with the follwoing formatting options
 * @param  [type]  $sno    [description]
 * @param  integer $length [description]
 * @param  string  $token  [description]
 * @param  string  $type   [description]
 * @return [type]          [description]
 */
function makeNumber($sno, $length=2, $token = '0',$type='left')
{
    if($type=='right')
        return str_pad($sno, $length, $token, STR_PAD_RIGHT);
    
    return str_pad($sno, $length, $token, STR_PAD_LEFT);
    
}
 
/**
 * This method returns the settings for the selected key
 * @param  string $type [description]
 * @return [type]       [description]
 */
function getSettings($type='')
{
    if($type=='lms')
        return json_decode((new App\LmsSettings())->getSettings());
    
    if($type=='subscription')
        return json_decode((new App\SubscriptionSettings())->getSettings());
    
    if($type=='general')
        return json_decode((new App\GeneralSettings())->getSettings());

    if($type=='email'){

        $dta = json_decode((new App\EmailSettings())->getSettings());
        return $dta;
      }
   
   if($type=='attendance')
        return json_decode((new App\AttendanceSettings())->getSettings());

}

/**
 * This method returns the role of the currently logged in user
 * @return [type] [description]
 */

 function send_notification($sendmsg,$send_to)
 {
    // dd($send_to);
     $userToken = App\User::where(['id'=>$send_to,'is_block'=>'no','is_approved'=>'yes','is_deleted'=>'no', ])->pluck('app_token')->toArray();
     $userId = App\User::where(['id'=>$send_to,'is_block'=>'no','is_approved'=>'yes','is_deleted'=>'no', ])->pluck('id')->toArray();

     $fcm_server_keyFinal='AAAAog8TE8Y:APA91bFVPjkXqCY_Mube2butwlOz3x5RaVaJv5JYDXHV9AtJK96kFPrKZp3LCKqgG7PlZcWDqywPGlDUTkWBajmmoqqtxOAJkCmBbTyki8r6axzrF2i67oY3muMujYkaav3AYIKPwQJG';
     $title='Soil Charge Technology';
     $icon='';
     
     $message = [
             'title' => $title,
             'body' => $sendmsg,
             'icon' => $icon ,
             'actions'=>'',
             
         ];
     
    //  dd($userToken);
    
     $fields = array(
         'registration_ids' => $userToken,
         //'registration_ids' =>['eF1zFcNQS0SstgXFONd_qq:APA91bHNPw6Ir_KaJsEw2riTRNHLIXd4KBkHeNUznDMa_StyYXgIKb4uVIrFvpw8k2Z0C9YLXakLOf6_dx18cLFDyU7F8-R6CWRc-VT7rOtWkCS4ofmIwv1BRVwLyZXzakDMHoYLZELl'],
         'notification' => $message, //note: body & title fileds must be specified for the message or your only get just the vibration but the notification
     );
     $headers = array(
         'Authorization: key=' .$fcm_server_keyFinal, //  FIREBASE_API_KEY_FOR_ANDROID_NOTIFICATION
         'Content-Type: application/json'
     );
     // dd($headers);
     //Open connection
     $ch = curl_init();
     //Set the url, number of POST vars, POST data
     curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
     curl_setopt($ch, CURLOPT_POST, true);
     curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
     // Disabling SSL Certificate support temporarly
     curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
     $result = curl_exec($ch);
    //  dd($result);
     if ($result === false) {
         die('Curl failed:' . curl_errno($ch));
        
     }
     // Close connection
     curl_close($ch);
     

     foreach($userId as $key=>$userIdNew)
     {
         $Notificationdetails = new \App\Model\Notification();
         $Notificationdetails->distributor_id = $userIdNew;
         $Notificationdetails->message =$sendmsg;
         $Notificationdetails->save();
     }
 }
 function getRole($user_id = 0)
 {
     if($user_id)
        return getUserRecord($user_id)->roles()->first()->name;
     return Auth::user()->roles()->first()->name;
 }

/**
 * This is a common method to send emails based on the requirement
 * The template is the key for template which is available in db
 * The data part contains the key=>value pairs 
 * That would be replaced in the extracted content from db
 * @param  [type] $template [description]
 * @param  [type] $data     [description]
 * @return [type]           [description]
 */
 function sendEmail($template, $data)
 {
    return (new App\EmailTemplate())->sendEmail($template, $data);
 }

/**
 * This method returns the formatted by appending the 0's
 * @param  [type] $number [description]
 * @return [type]         [description]
 */
 function formatPercentage($number)
 {
     return sprintf('%.2f',$number).' %';
 }


/**
 * This method returns the user based on the sent userId, 
 * If no userId is passed returns the current logged in user
 * @param  [type] $user_id [description]
 * @return [type]          [description]
 */
 function getUserRecord($user_id = 0)
 {
    if($user_id)
     return (new App\User())->where('id','=',$user_id)->first();
    return Auth::user();
 }

/**
 * Returns the user record with the matching slug.
 * If slug is empty, it will return the currently logged in user
 * @param  string $slug [description]
 * @return [type]       [description]
 */
function getUserWithSlug($slug='')
{
    if($slug)
     return App\User::where('slug', $slug)->get()->first();
    return Auth::user();
}

/**
 * This method identifies if the url contains the specific string
 * @param  [type] $str [description]
 * @return [type]      [description]
 */
 function urlHasString($str)
 {
    $url = $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
     if (strpos($url, $str)) 
        return TRUE;
    return FALSE;
                
 }

 function checkRole($roles)
 {
     if(Entrust::hasRole($roles))
        return TRUE;
    return FALSE;
 }

 function getUserGrade($grade = 5)
 {
     switch ($grade) {
         case 1:
             return ['owner'];
             break; 
        case 2:
             return ['owner', 'admin'];
             break;
        case 3:
             return ['owner', 'admin', 'staff'];
             break;
        case 4:
             return ['owner', 'admin', 'parent'];
             break;
        case 5:
             return ['student'];
             break;
        case 6:
             return ['admin'];
             break; 
        case 7:
             return ['parent'];
             break; 
         
     }
 }
 /**
  * Returns the appropriate layout based on the user logged in
  * @return [type] [description]
  */
 function getLayout()
 {
    $layout = 'layouts.student.studentlayout';
    if(checkRole(getUserGrade(2)))
        $layout             = 'layouts.admin.adminlayout';
    if(checkRole(['parent']))
        $layout             = 'layouts.parent.parentlayout';

    return $layout;
 }

 function validateUser($slug)
 {
    if($slug == Auth::user()->slug)
        return TRUE;
    return FALSE;
 }

 /**
  * Common method to send user restriction message for invalid attempt 
  * @return [type] [description]
  */
 function prepareBlockUserMessage()
 {
    flash('Ooops..!', 'you_have_no_permission_to_access', 'error');
     return '';
 }

 /**
  * Common method to send user restriction message for invalid attempt 
  * @return [type] [description]
  */
 function pageNotFound()
 {
    flash('Ooops..!', 'page_not_found', 'error');
     return '';
 }
 

 function isEligible($slug)
 {
     if(!checkRole(getUserGrade(2)))
     {
        if(!validateUser($slug)) 
        {
            if(!checkRole(['parent']) || !isActualParent($slug))
            {
               prepareBlockUserMessage();
               return FALSE;
            }
        }
     }
     return TRUE;
 }

 /**
  * This method checks wether the student belongs to the currently loggedin parent or not
  * And returns the boolean value
  * @param  [type]  $slug [description]
  * @return boolean       [description]
  */
 function isActualParent($slug)
 {
     return (new App\User())
              ->isChildBelongsToThisParent(
                                    getUserWithSlug($slug)->id, 
                                    Auth::user()->id
                                    );

 }

/**
 * This method returns the role name or role ID based on the type of parameter passed
 * It returns ID if role name is supplied
 * It returns Name if ID is passed
 * @param  [type] $type [description]
 * @return [type]       [description]
 */
 function getRoleData($type)
 {
    
     if(is_numeric($type))
     {
        /**
         * Return the Role Name as the type is numeric
         */
        return App\Role::where('id','=',$type)->first()->name;
        
     }

     //Return Role Id as the type is role name
     return App\Role::where('name','=',$type)->first()->id;

 }

 /**
  * Checks the subscription details and returns the boolean value
  * @param  string  $type [this is the of package]
  * @return boolean       [description]
  */
 function isSubscribed($type = 'main',$user_slug='')
 {
    $user = getUserWithSlug();
    if($user_slug)
        $user = getUserWithSlug($user_slug);
    
    if($user->subscribed($type))
      return TRUE;
    return FALSE;
 }

/**
 * This method will send the random color to use in graph
 * The random color generation is based on the number parameter 
 * As the border and bgcolor need to be same, 
 * We are maintainig number parameter to send the same value for bgcolor and background color
 * @param  string  $type   [description]
 * @param  integer $number [description]
 * @return [type]          [description]
 */
 function getColor($type = 'background',$number = 777) {

    $hash = md5('color'.$number); // modify 'color' to get a different palette
    $color = array(
        hexdec(substr($hash, 0, 2)), // r
        hexdec(substr($hash, 2, 2)), // g
        hexdec(substr($hash, 4, 2))); //b
    if($type=='border')
    return 'rgba('.$color[0].','.$color[1].','.$color[2].',1)';
    return 'rgba('.$color[0].','.$color[1].','.$color[2].',0.2)';
}


function pushNotification($channels = ['owner','admin'], $event = 'newUser',  $options)
{

     $pusher = \Illuminate\Support\Facades\App::make('pusher');

         $pusher->trigger( $channels,
                      $event, 
                      $options
                     );

         

}

/**
 * This method is used to return the default validation messages
 * @param  string $key [description]
 * @return [type]      [description]
 */
function getValidationMessage($key='required')
{
    $message = '<p ng-message="required">'.getPhrase('this_field_is_required').'</p>';    
    
    if($key === 'required')
        return $message;

        switch($key)
        {
          case 'minlength' : $message = '<p ng-message="minlength">'
                                        .getPhrase('the_text_is_too_short')
                                        .'</p>';
                                        break;
          case 'maxlength' : $message = '<p ng-message="maxlength">'
                                        .getPhrase('the_text_is_too_long')
                                        .'</p>';
                                        break;
          case 'pattern' : $message   = '<p ng-message="pattern">'
                                        .getPhrase('invalid_input')
                                        .'</p>';
                                        break;
            case 'image' : $message   = '<p ng-message="validImage">'
                                        .getPhrase('please_upload_valid_image_type')
                                        .'</p>';
                                        break;
          case 'email' : $message   = '<p ng-message="email">'
                                        .getPhrase('please_enter_valid_email')
                                        .'</p>';
                                        break;
                                       
          case 'number' : $message   = '<p ng-message="number">'
                                        .getPhrase('please_enter_valid_number')
                                        .'</p>';
                                        break;

          case 'confirmPassword' : $message   = '<p ng-message="compareTo">'
                                        .getPhrase('password_and_confirm_password_does_not_match')
                                        .'</p>';
                                        break;
           case 'password' : $message   = '<p ng-message="minlength">'
                                        .getPhrase('the_password_is_too_short')
                                        .'</p>';
                                        break;
           case 'phone' : $message   = '<p ng-message="minlength">'
                                        .getPhrase('please_enter_valid_phone_number')
                                        .'</p>';
                                        break;
        }
    return $message;
}

/**
 * Returns the predefined Regular Expressions for validation purpose
 * @param  string $key [description]
 * @return [type]      [description]
 */
function getRegexPattern($key='name')
{
    $phone_regx = getSetting('phone_number_expression', 'site_settings');
    $pattern = array(
                    'name' => '/(^[A-Za-z0-9-. ]+$)+/',
                    'email' => '/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',
                    'phone'=>$phone_regx
                    );
    return $pattern[$key];
}

function getPhoneNumberLength()
{
  return getSetting('site_favicon', 'site_settings');
}


function getArrayFromJson($jsonData)
{
    $result = array();
    if($jsonData)
    {
        foreach(json_decode($jsonData) as $key=>$value)
            $result[$key] = $value;
    }
    return $result;
}


function prepareArrayFromString($string='', $delimeter = '|')
{
  
    return explode($delimeter, $string);
}

/**
 * Returns the random hash unique code
 * @return [type] [description]
 */
function getHashCode()
{
  return bin2hex(openssl_random_pseudo_bytes(20));
}

/**
 * Sends the default Currency set for the project
 * @return [type] [description]
 */
function getCurrencyCode()
{
  return getSetting('currency_code','site_settings') ;
}

/**
 * Returns the max records per page
 * @return [type] [description]
 */
function getRecordsPerPage()
{
  return RECORDS_PER_PAGE;
}

/**
 * Checks wether the user is eligible to use the current item
 * @param  [type]  $item_id   [description]
 * @param  [type]  $item_type [description]
 * @return boolean            [description]
 */
function isItemPurchased($item_id, $item_type, $user_id = '')
{
  return App\Payment::isItemPurchased($item_id, $item_type, $user_id);
}

function humanizeDate($target_date)
{
   $created = new \Carbon\Carbon($target_date);
   $now = \Carbon\Carbon::now();
   $difference = ($created->diff($now)->days < 1) ? getPhrase('today') 
                                : $created->diffForHumans($now);
    return $difference;
}


function getTimeFromSeconds($seconds)
{
    return gmdate("H:i:s",$seconds);
}

function getRazorKey()
{
return env('RAZORPAY_APIKEY', 'rzp_test_A7YYdxPOae6Dpn');
}

function getRazorSecret()
{
return env('RAZORPAY_SECRET','j1ikm980d6Lxs4ZNceOv44Sz');     
}

function getTheme()
{ 
  
  $theme_name  = 'default';

  $current_theme  = App\SiteTheme::where('is_active',1)->first();

  if($current_theme){
    $theme_name = $current_theme->theme_title_key;
  }

  Theme::set($theme_name);
  return Theme::current();
}

function getDefaultTheme()
{
    $current_theme  = App\SiteTheme::where('is_active',1)->first();
   
    if($current_theme){

       $theme_name = $current_theme->theme_title_key;
       return $theme_name;
    }
    return FALSE;
} 


function getThemeColor(){

  $current_theme  = App\SiteTheme::where('is_active',1)->first();
  
  return $current_theme->theme_color; 
  
}

function getLangugesOptions(){

  $languages_data = array();
  $languages_data['Afrikanns'] = 'Afrikanns';
  $languages_data['Albanian'] = 'Albanian';
  $languages_data['Arabic'] = 'Arabic';
  $languages_data['Armenian'] = 'Armenian';
  $languages_data['Basque'] = 'Basque';
  $languages_data['Bengali'] = 'Bengali';
  $languages_data['Bulgarian'] = 'Bulgarian';
  $languages_data['Catalan'] = 'Catalan';
  $languages_data['Cambodian'] = 'Cambodian';
  $languages_data['Chinese'] = 'Chinese';
  $languages_data['Croation'] = 'Croation';
  $languages_data['Czech'] = 'Czech';
  $languages_data['Danish'] = 'Danish';
  $languages_data['Dutch'] = 'Dutch';
  $languages_data['Estonian'] = 'Estonian';
  $languages_data['French'] = 'French';
  $languages_data['German'] = 'German';
  $languages_data['Greek'] = 'Greek';
  $languages_data['Gujarati'] = 'Gujarati';
  $languages_data['Hebrew'] = 'Hebrew';
  $languages_data['Hindi'] = 'Hindi';
  $languages_data['Hungarian'] = 'Hungarian';
  $languages_data['Italian'] = 'Italian';
  $languages_data['Japanese'] = 'Japanese';
  $languages_data['Malayalam'] = 'Malayalam';
  $languages_data['Marathi'] = 'Marathi';
  $languages_data['Nepali'] = 'Nepali';
  $languages_data['Romanian'] = 'Romanian';
  $languages_data['Russian'] = 'Russian';
  $languages_data['Spanish'] = 'Spanish';
  $languages_data['Tamil'] = 'Tamil';
  $languages_data['Telugu'] = 'Telugu';
  $languages_data['Turkish'] = 'Turkish';
  $languages_data['Urdu'] = 'Urdu';
  $languages_data['Vietnamese'] = 'Vietnamese';
    
    return $languages_data;
}
