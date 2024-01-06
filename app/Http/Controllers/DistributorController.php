<?php

namespace App\Http\Controllers;
use Exception;
use JWTAuth;
use App\Task;
use Illuminate\Http\Request;
use App\User;
use App\Model\ {
    Dist_Promotion_Demotion,
    FrontProduct,ProductDetails,
    UsersInfo,
    FarmerMeeting,
    DistributorMeeting,
    TargetVideos,
    TargetVideosToDistributor,
    FarmerVistByDistributor,
    SCTResult,
    WebAgency,
    Subscriber,
    Downloads,
    Language,
    Product,
    SubscriberTarget,
    WebBlog,
    Messages,
    Complaint,
    Notification,
    Address
};
use DB;
use Validator;
use App\Http\Controllers\CommonController As CommonController;

class DistributorController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->commonController=new CommonController();
        $this->user = JWTAuth::parseToken()->authenticate();
        
    }
    
    // public function distributorlogin(Request $request)
    // {
    //     $credentials = $request->only("email", "password");
    //     $token = null;

    //     if (!$token = JWTAuth::attempt($credentials)) {
    //         return response()->json([
    //             "status" => false,
    //             "message" => "Unauthorized"
    //         ]);
    //     }

    //     return response()->json([
    //         "status" => true,
    //         "token" => $token
    //     ]);
    // }
    
    // public function register(Request $request)
    // {
    //     $this->validate($request, [
    //         "name" => "required|string",
    //         "email" => "required|email|unique:users",
    //         "password" => "required|string|min:6|max:10"
    //     ]);

    //     $user = new User();
    //     $user->name = $request->fname." ".$request->mname." ".$request->lname." ";
    //     $user->email = $request->email;
    //     $user->password = bcrypt($request->password);
    //     $user->user_type ='7';
    //     $user->save();

    //     // if ($this->loginAfterSignUp) {
    //     //     return $this->login($request);
    //     // }

    //     return response()->json([
    //         "status" => true,
    //         "user" => $user
    //     ]);
    // }

    public function processUpload(Request $request, $inputfilenametoupload,$imagedataPath,$photoName)
    {
         if ($request->hasFile($inputfilenametoupload)) 
         {
            $applpic_ext = $request->file($inputfilenametoupload)->getClientOriginalExtension();
            $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file($inputfilenametoupload))); 
            $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
            $path2 = $imagedataPath.$photoName.".".$applpic_ext;
            file_put_contents($path2, $applicantAttachmentOne);  
            return $photoName.".".$applpic_ext;
        }
    }
    
    
    public function distributorregistration(Request $request)
    {
        try
        {
            
            $user = new User();
            $user->name = ucwords($request->fname)." ".ucwords($request->mname)." ".ucwords($request->lname)." ";
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->visible_password =$request->password;
            $user->user_type ='fsc';
            $user->save();
            $user->id;
            
            $users = new UsersInfo();
            $users->user_id = $user->id;
            $users->fname = $request->fname;
            $users->mname = $request->mname;
            $users->lname = $request->lname;
            $users->aadharcard = $request->aadharcard;
            $users->pincode = $request->pincode;
            $users->password = $request->password;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->state = $request->state;
            $users->district = $request->district;
            $users->taluka = $request->taluka;
            $users->city = $request->city;
            $users->address = $request->address;
            $users->occupation = $request->occupation;
            $users->education = $request->education;
            $users->exp_in_agricultural = $request->exp_in_agricultural;
            $users->other_distributorship = $request->other_distributorship;
            $users->reference_from = $request->reference_from;
            $users->shop_location = $request->shop_location;
            $users->geolocation = $request->geolocation;
            // $users->shop_act_image = $request->shop_act_image;
            // $users->shop_image = $request->shop_image;
            // $users->aadhar_card_image = $request->aadhar_card_image;
            $users->user_type = 'fsc';
            $users->is_deleted = 'no'; // 0 Means Active, 1 Means Delete
            $users->active = 'yes'; // 0 Means Active, 1 Means Inactive
            $users->added_by =  ($request->created_by) ? $request->created_by: 'superadmin'; // 0- from Superadmin 1- Distributor
            //$users->added_by =  '130'; // 0- from Superadmin 1- Distributor
            $users->save();
                
            if($request->created_by){
                $this->checkLevelofDistributor($request->created_by);
            }
            
             
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
                    "result" => true,
                    "message" => 'Distributor Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        // "message" =>$e->getMessage()." ".$e->getCode()
                        "message" => "Some data missing or duplicate"
                    ]);
               
        }

    }
    
    
    
    public function distributorregistrationspecific(Request $request)
    {
        try
        {
            
            $user = new User();
            //$user->name = $request->fname." ".$request->mname." ".$request->lname." ";
            $user->name = ucwords($request->fname)." ".ucwords($request->mname)." ".ucwords($request->lname)." ";
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->visible_password =$request->password;
            $user->user_type =$request->user_type;
            $user->save();
            $user->id;
            
            $users = new UsersInfo();
            $users->user_id = $user->id;
            $users->fname = ucwords($request->fname);
            $users->mname = ucwords($request->mname);
            $users->lname = ucwords($request->lname);
            $users->aadharcard = $request->aadharcard;
            $users->pincode = $request->pincode;
            $users->password = $request->password;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->state = $request->state;
            $users->district = $request->district;
            $users->taluka = $request->taluka;
            $users->city = $request->city;
            $users->address = $request->address;
            $users->occupation = $request->occupation;
            $users->education = $request->education;
            $users->exp_in_agricultural = $request->exp_in_agricultural;
            $users->other_distributorship = $request->other_distributorship;
            $users->reference_from = $request->reference_from;
            $users->shop_location = $request->shop_location;
            $users->geolocation = $request->geolocation;
            
            $users->user_type = $request->user_type;
            $users->is_deleted = 'no'; // 0 Means Active, 1 Means Delete
            $users->active = 'yes'; // 0 Means Active, 1 Means Inactive
            //$users->added_by =  ($request->created_by) ? $request->created_by: 'superadmin'; // 0- from Superadmin 1- Distributor
            $users->added_by =  $request->created_by; // 0- from Superadmin 1- Distributor
            
            $users->save();
                
            // if($request->created_by){
            //     $this->checkLevelofDistributor($request->created_by);
            // }
            
             
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
                    "result" => true,
                    "message" => 'Distributor Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }

    }
    
    public function distributorregistration_by_distributor(Request $request)
    {
        try
        {
            $user = new User();
            $user->name = ucwords($request->fname)." ".ucwords($request->mname)." ".ucwords($request->lname)." ";
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->visible_password =$request->password;
            $user->user_type ='fsc';
            $user->is_approved =  'no';
            $user->save();
            $user->id;
            
            $users = new UsersInfo();
            $users->user_id = $user->id;
            $users->fname = ucwords($request->fname);
            $users->mname = ucwords($request->mname);
            $users->lname = ucwords($request->lname);
            //$users->aadharcard = $request->aadharcard;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->state = $request->state;
            $users->district = $request->district;
            $users->taluka = $request->taluka;
            $users->city = $request->city;
            $users->address = $request->address;
            $users->occupation = $request->occupation;
            $users->education = $request->education;
            $users->exp_in_agricultural = $request->exp_in_agricultural;
            $users->other_distributorship = $request->other_distributorship;
            $users->reference_from = $request->reference_from;
            $users->shop_location = $request->shop_location;
            $users->geolocation = $request->geolocation;
            // $users->shop_act_image = $request->shop_act_image;
            // $users->shop_image = $request->shop_image;
            // $users->aadhar_card_image = $request->aadhar_card_image;
            $users->user_type = 'fsc';
            $users->is_deleted = 'no'; // 0 Means Active, 1 Means Delete
            $users->active = 'yes'; // 0 Means Active, 1 Means Inactive
            $users->added_by =  ($request->created_by) ? $request->created_by: 'superadmin'; // 0- from Superadmin 1- Distributor
            $users->save();
                
            if($request->created_by){
                $this->checkLevelofDistributor($request->created_by);
            }
            
             
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
                    "result" => true,
                    "message" => 'Distributor Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }

    }
    
    
    
    
    
    
    public function distributorregistration_images(Request $request)
    {
        $users = new UsersInfo();
        $imagedataPath=DISTRIBUTOR_OWN_DOCUMENTS;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        $idLastInserted=$request->user_id;
        
        $photoName=$idLastInserted."_aadhar_card_image_front";
        $inputfilenametoupload='aadhar_card_image_front';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=UsersInfo::where('user_id',$request->user_id)->update(['aadhar_card_image_front'=>$filename]);
        }
        
        $photoName=$idLastInserted."_aadhar_card_image_back";
        $inputfilenametoupload='aadhar_card_image_back';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=UsersInfo::where('user_id',$request->user_id)->update(['aadhar_card_image_back'=>$filename]);
        }
        
        $photoName=$idLastInserted."_pan_card";
        $inputfilenametoupload='pan_card';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=UsersInfo::where('user_id',$request->user_id)->update(['pan_card'=>$filename]);
        }
        
        
        $photoName=$idLastInserted."_light_bill";
        $inputfilenametoupload='light_bill';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=UsersInfo::where('user_id',$request->user_id)->update(['light_bill'=>$filename]);
        }
        
        
        $photoName=$idLastInserted."_shop_act_image";
        $inputfilenametoupload='shop_act_image';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
           
            $users = UsersInfo::where('user_id',$request->user_id)->update(['shop_act_image'=>$filename]);
           
        }
        
        $photoName=$idLastInserted."_product_purchase_bill";
        $inputfilenametoupload='product_purchase_bill';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=UsersInfo::where('user_id',$request->user_id)->update(['product_purchase_bill'=>$filename]);
           
        }
        
        
        if ($users)
        {
             return response()->json([
                "data" => $users,
                "result" => true,
                "message" => 'Distributor Photo Added By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Photo Not Added'
            ]);
            
        }
    }
    
    
    public function distributorregistration_images_update(Request $request)
    {
        $users = new UsersInfo();
        $imagedataPath=DISTRIBUTOR_OWN_DOCUMENTS;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        $idLastInserted=$request->user_id;
        $distributorregistration_images_list = UsersInfo::where('user_id',$request->user_id)->get();
        if(!empty($distributorregistration_images_list))
        {
            $photoName=$idLastInserted."_aadhar_card_image_front";
            $inputfilenametoupload='aadhar_card_image_front';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                $deletefrontfilename=$distributorregistration_images_list[0]['aadhar_card_image_front'];
                $unlink_front_file_path=$imagedataPath.$deletefrontfilename;
                if(!empty($unlink_front_file_path))
                {
                    unlink($unlink_front_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $users=UsersInfo::where('user_id',$request->user_id)->update(['aadhar_card_image_front'=>$filename]);
            }
            
            $photoName=$idLastInserted."_aadhar_card_image_back";
            $inputfilenametoupload='aadhar_card_image_back';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                $deletebackfilename=$distributorregistration_images_list[0]['aadhar_card_image_back'];
                $unlink_back_file_path=$imagedataPath.$deletebackfilename;
                if(!empty($unlink_back_file_path))
                {
                    unlink($unlink_back_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $users=UsersInfo::where('user_id',$request->user_id)->update(['aadhar_card_image_back'=>$filename]);
            }
            
            $photoName=$idLastInserted."_pan_card";
            $inputfilenametoupload='pan_card';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                $deletepanfilename=$distributorregistration_images_list[0]['pan_card'];
                $unlink_pan_file_path=$imagedataPath.$deletepanfilename;
                if(!empty($unlink_pan_file_path))
                {
                    unlink($unlink_pan_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $users=UsersInfo::where('user_id',$request->user_id)->update(['pan_card'=>$filename]);
            }
            
            
            $photoName=$idLastInserted."_light_bill";
            $inputfilenametoupload='light_bill';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                $deletebillfilename=$distributorregistration_images_list[0]['light_bill'];
                $unlink_bill_file_path=$imagedataPath.$deletebillfilename;
                if(!empty($unlink_bill_file_path))
                {
                    unlink($unlink_bill_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $users=UsersInfo::where('user_id',$request->user_id)->update(['light_bill'=>$filename]);
            }
            
            
            $photoName=$idLastInserted."_shop_act_image";
            $inputfilenametoupload='shop_act_image';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                $deleteshopfilename=$distributorregistration_images_list[0]['shop_act_image'];
                $unlink_shop_file_path=$imagedataPath.$deleteshopfilename;
                if(!empty($unlink_shop_file_path))
                {
                    unlink($unlink_shop_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $users = UsersInfo::where('user_id',$request->user_id)->update(['shop_act_image'=>$filename]);
               
            }
            
            $photoName=$idLastInserted."_product_purchase_bill";
            $inputfilenametoupload='product_purchase_bill';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                $deletepurchasefilename=$distributorregistration_images_list[0]['product_purchase_bill'];
                $unlink_purchase_file_path=$imagedataPath.$deletepurchasefilename;
                if(!empty($unlink_purchase_file_path))
                {
                    unlink($unlink_purchase_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $users=UsersInfo::where('user_id',$request->user_id)->update(['product_purchase_bill'=>$filename]);
               
            }
        }
        
        if ($users)
        {
             return response()->json([
                "data" => $users,
                "result" => true,
                "message" => 'Distributor Photo Updated By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Photo Not Updated'
            ]);
            
        }
    }
    
    
    
    
    public function distributorinfo(Request $request)
    {

        $userinfo = UsersInfo::where('user_id',$request->id)->first();
        
        if ($userinfo)
        {
             return response()->json([
                "data" => $userinfo,
                "result" => true,
                "message" => 'Distributor info get successfully',
               
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor not found '
            ]);
            
        }

    }
    
    public function distributordetails(Request $request)
    {
        
        $userinfo = UsersInfo::where('user_id',$request->id)->first();
        
        if ($userinfo)
        {
             return response()->json([
                "data" => $userinfo,
                "result" => true,
                "message" => 'Distributor Details get successfully',
               
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor not found '
            ]);
            
        }

    }
    
    
    public function distributorlist(Request $request)
    {
        $result = UsersInfo::whereIn('usersinfo.user_type',['fsc','bsc','dsc'])->where('usersinfo.is_deleted', '=', 'no')->join('users','users.id','=','usersinfo.user_id')->orderBy('users.id', 'DESC')->get();
        foreach($result as $key=>$value)
        {
            $promo_demo = Dist_Promotion_Demotion::where('user_id',$value->user_id)->where('is_updated','n')->first();
            
            if(!empty($promo_demo))
            {
                $value->new_user_type=$promo_demo->user_type;
            }
            else
            {
                $value->new_user_type='';
            }

            $stateName=$this->commonController->getAreaNameById($value->state);
            $value->state=$stateName->name;
            
            $districtName=$this->commonController->getAreaNameById($value->district);
            $value->district=$districtName->name;
            
            $talukaName=$this->commonController->getAreaNameById($value->taluka);
            $value->taluka=$talukaName->name;
            
            $cityName=$this->commonController->getAreaNameById($value->city);
            
            // $value->city=isset($cityName->name)?$cityName:'-';
            $value->city=isset($cityName->name)?$cityName->name:'-';
            // $value->city=$cityName->name?$cityName:'-';
        }
        // dd($result);
        if (count($result) > 0)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Distributor List Get Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Distributor List Not Found';
            $response['result'] = false;
            return response()->json($response);
        }

    }

    public function distributordelete(Request $request)
    {
        
        $distdelete = ['is_deleted' => 'yes'];
        //$distdeletes = ['is_deleted' => 'yes'];

        $id = $request->id;
        $result = UsersInfo::where('user_id', '=', $id)->update($distdelete);
        //$result = Users::where('id', '=', $id)->update($distdeletes);

        if($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Distributor Deleted Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Distributor Not Deleted';
            $response['result'] = false;
            return response()->json($response);
        }
    }

    public function distributorupdate(Request $request)
    {
        $farmerupdatedata = ['fname' => ucwords($request->fname), 'mname' => ucwords($request->mname), 'lname' => ucwords($request->lname), 'aadharcard' => $request->aadharcard, 'email' => $request->email, 'phone' => $request->phone, 'state' => $request->state, 'district' => $request->district, 'taluka' => $request->taluka, 'city' => $request->city, 'address' => $request->address, 'pincode' => $request->pincode, 'crop' => $request->crop, 'acre' => $request->acre, 'photo' => $request->photo, 'password' => $request->password, 'remember_token' => $request->token];

        $id = $request->user_id;
        $result = UsersInfo::where('user_id', '=', $id)->update($farmerupdatedata);

        if($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Distributor Updated Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Distributor Not Updated';
            $response['result'] = false;
            return response()->json($response);
        }

    }
    
    
    public function distributorupdatenew(Request $request)
    {
       
        $farmerupdatedata = ['fname' => ucwords($request->fname), 'mname' => ucwords($request->mname), 'lname' => ucwords($request->lname), 'aadharcard' => $request->aadharcard, 'email' => $request->email, 'phone' => $request->phone, 'state' => $request->state, 'district' => $request->district, 'taluka' => $request->taluka, 'city' => $request->city, 'address' => $request->address, 'pincode' => $request->pincode, 'crop' => $request->crop, 'user_type' => $request->user_type, 'added_by' => $request->created_by, 'acre' => $request->acre, 'photo' => $request->photo, 'password' => $request->password, 'remember_token' => $request->token];
        $farmerupdatedatauser = ['user_type' => $request->user_type];

        $id = $request->user_id;
        $result = UsersInfo::where('user_id', '=', $id)->update($farmerupdatedata);
        $result = Users::where('id', '=', $id)->update($farmerupdatedatauser);

        if($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Distributor Updated Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Distributor Not Updated';
            $response['result'] = false;
            return response()->json($response);
        }

    }

    public function distributoractiveinactive(Request $request)
    {
        $value = $request->value;
        if ($value == 0)
        {
            $actInacValue = '1';
        }
        else
        {
            $actInacValue = '0';
        }

        $farmeractiveinactive = ['activeinactive' => $actInacValue];
        $id = $request->id;
        $result = UsersInfo::where('id', '=', $id)->update($farmeractiveinactive);

        if (count($result) > 0 && $actInacValue == 0)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Distributor Activated Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        elseif (count($result) > 0 && $actInacValue == 1)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Distributor Inactivated Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Distributor Not Changed Any Status';
            $response['result'] = false;
            return response()->json($response);
        }
        
    }
    
      public function farmer_registration_distributorapp(Request $request)
    {
        try {
        $validator = Validator::make($request->all(), [
            'fname' => 'required',
            // 'mname' => 'required',
            // 'lname' => 'required',
            'email' => 'required',
            'password' => 'required',
            'aadharcard' => 'required',
            'phone' => 'required',
            'state' => 'required',
            'district' => 'required',
            'taluka' => 'required',
            'city' => 'required',
            'address' => 'required',
            'pincode' => 'required',
            'crop' => 'required',
            'acre' => 'required',
            'created_by' => 'required',
            'token' => 'required',
        ]);

        if ($validator->fails()) 
        {
            return $validator->errors()->all();
        }

        $user = new User();
        $user->name = $request->fname;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->visible_password =$request->password;
        $user->user_type ='farmer';
        $user->save();
        $user->id;
        
        
        $users = new UsersInfo();
        $users->user_id =$user->id;
        $users->fname = $request->fname;
        $users->mname = $request->fname;
        $users->lname =$request->fname;
        $users->aadharcard = $request->aadharcard;
        $users->email = $request->email;
        $users->phone = $request->phone;
        $users->state = $request->state;
        $users->district = $request->district;
        $users->taluka = $request->taluka;
        $users->city = $request->city;
        $users->address = $request->address;
        $users->pincode = $request->pincode;
        $users->crop = $request->crop;
        $users->acre = $request->acre;
        //$users->photo = $farmerPhoto;
        $users->password = $request->password;
        $users->user_type = 'farmer';
        //$users->photo = $request->photo;
        $users->is_deleted = 'no'; // 0 Means Active, 1 Means Delete
        $users->active= 'yes'; // 0 Means Active, 1 Means Inactive
        $users->added_by = $request->created_by; // 0- from Superadmin 1- Distributor
        $users->remember_token = $request->token;
        $users->save();
        
        $imagedataPath=DISTRIBUTOR_OWN_DOCUMENTS;
        // dd($imagedataPath);
        if (!$imagedataPath) 
        {
            mkdir( $imagedataPath );
        }
        
        $idLastInserted=$user->id;
        $photoName=$idLastInserted."_photo";
        $inputfilenametoupload='photo';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=UsersInfo::where('user_id',$idLastInserted)->update(['photo'=>$filename]);
           
        }
        
        if ($users)
        {
             return response()->json([
                "data" => $users,
                "result" => true,
                "message" => 'Farmer Added Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Farmer Not Added'
            ]);
            
        }
        
        }
        catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        // "message" =>$e->getMessage()." ".$e->getCode()
                        "message" => "Some data missing or duplicate"
                    ]);
               
        }

    }
    
    
    
    
    
    
    public function farmerunder_distributor(Request $request)
    {
        $farmerlist = UsersInfo::where('added_by',$request->user_id)->whereIn('user_type',['farmer'])->where('is_deleted','no')->get();
        if ($farmerlist)
        {
             return response()->json([
                "data" => $farmerlist,
                "result" => true,
                "message" => 'All Farmer List Added By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Farmer Not Found'
            ]);
            
        }
    }
    
    
    public function distributorlistunder_distributor(Request $request)
    {
        try
        {
            $distrlist = UsersInfo::where('added_by',$request->created_by)->whereIn('user_type',['fsc','bsc','dsc'])->where('is_deleted','no')->get();
            if ($distrlist)
            {
                 return response()->json([
                    "data" => $distrlist,
                    "result" => true,
                    "message" => 'All Distributor List Added By Distributor'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Not Found'
                ]);
                
            }

        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
    
    
    
    public function farmermeetingadd_distributorapp(Request $request)
    {
        $farmer = new FarmerMeeting();
        $farmer->date = $request->date;
        $farmer->meeting_place = $request->meeting_place;
        $farmer->farmer_id = $request->farmer_id;
        $farmer->meeting_title = $request->meeting_title;
        $farmer->meeting_description = $request->meeting_description;
        
        $farmer->created_by = $request->created_by;
        $farmer->longitude = $request->longitude;
        $farmer->latitude = $request->latitude;
        $farmer->save();
        
        
        if ($farmer)
        {
             return response()->json([
                "data" => $farmer,
                "result" => true,
                "message" => 'Farmer Meeting Added By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Farmer Meeting Not Added'
            ]);
            
        }
    }
    
    public function farmermeetingadd_images_distributorapp(Request $request)
    {
        $farmer = new FarmerMeeting();
        $imagedataPath=FARMER_MEETING_PHOTO_UPLOAD;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        $idLastInserted=$request->id;
        
        $photoName=$idLastInserted."_meetingphoto_one";
        $inputfilenametoupload='photo_one';
        
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $photo_one_lat_long=explode("_",$request->lat_long_string);
            $photo_one_lat = $photo_one_lat_long[0];
            $photo_one_long = $photo_one_lat_long[1];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer = FarmerMeeting::where('id',$idLastInserted)->update(['photo_one'=>$filename,'photo_one_lat'=>$photo_one_lat,'photo_one_long'=>$photo_one_long]);
           
        }
        
        $photoName=$idLastInserted."_meetingphoto_two";
        $inputfilenametoupload='photo_two';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $photo_two_lat_long=explode("_",$request->lat_long_string);
            $photo_two_lat = $photo_two_lat_long[2];
            $photo_two_long = $photo_two_lat_long[3];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerMeeting::where('id',$idLastInserted)->update(['photo_two'=>$filename,'photo_two_lat'=>$photo_two_lat,'photo_two_long'=>$photo_two_long]);
           
        }
        
        
        $photoName=$idLastInserted."_meetingphoto_three";
        $inputfilenametoupload='photo_three';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_three_lat_long=explode("_",$request->lat_long_string);
            $photo_three_lat = $photo_three_lat_long[4];
            $photo_three_long = $photo_three_lat_long[5];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerMeeting::where('id',$idLastInserted)->update(['photo_three'=>$filename,'photo_three_lat'=>$photo_three_lat,'photo_three_long'=>$photo_three_long]);
           
        }
        
        
        $photoName=$idLastInserted."_meetingphoto_four";
        $inputfilenametoupload='photo_four';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_four_lat_long=explode("_",$request->lat_long_string);
            $photo_four_lat = $photo_four_lat_long[6];
            $photo_four_long = $photo_four_lat_long[7];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerMeeting::where('id',$idLastInserted)->update(['photo_four'=>$filename,'photo_four_lat'=>$photo_four_lat,'photo_four_long'=>$photo_four_long]);
           
        }
        
        $photoName=$idLastInserted."_meetingphoto_five";
        $inputfilenametoupload='photo_five';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_five_lat_long=explode("_",$request->lat_long_string);
            $photo_five_lat = $photo_five_lat_long[8];
            $photo_five_long = $photo_five_lat_long[9];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerMeeting::where('id',$idLastInserted)->update(['photo_five'=>$filename,'photo_five_lat'=>$photo_five_lat,'photo_five_long'=>$photo_five_long]);
           
        }
        
        
        
        if ($farmer)
        {
             return response()->json([
                "data" => $farmer,
                "result" => true,
                "message" => 'Farmer Meeting Photo Added By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Farmer Meeting Photo Not Added'
            ]);
            
        }
    }
    
    
    public function farmermeetingadd_images_update_distributorapp(Request $request)
    {
        $farmer = new FarmerMeeting();
        $imagedataPath=FARMER_MEETING_PHOTO_UPLOAD;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        $idLastInserted=$request->id;
        
        $farmermeeting_images_list = FarmerMeeting::where('id',$request->id)->get();
        if(!empty($farmermeeting_images_list))
        {
        $photoName=$idLastInserted."_meetingphoto_one";
        $inputfilenametoupload='photo_one';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $deleteonefilename=$farmermeeting_images_list[0]['photo_one'];
            $unlink_one_file_path=$imagedataPath.$deleteonefilename;
            if(!empty($unlink_one_file_path))
            {
                unlink($unlink_one_file_path);
            }
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer = FarmerMeeting::where('id',$request->id)->update(['photo_one'=>$filename]);
        }
        
        $photoName=$idLastInserted."_meetingphoto_two";
        $inputfilenametoupload='photo_two';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $deletetwofilename=$farmermeeting_images_list[0]['photo_two'];
            $unlink_two_file_path=$imagedataPath.$deletetwofilename;
            if(!empty($unlink_two_file_path))
            {
                unlink($unlink_two_file_path);
            }
            
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerMeeting::where('id',$request->id)->update(['photo_two'=>$filename]);
        }
        
        
        $photoName=$idLastInserted."_meetingphoto_three";
        $inputfilenametoupload='photo_three';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $deletethreefilename=$farmermeeting_images_list[0]['photo_three'];
            $unlink_three_file_path=$imagedataPath.$deletethreefilename;
            if(!empty($unlink_three_file_path))
            {
                unlink($unlink_three_file_path);
            }
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerMeeting::where('id',$request->id)->update(['photo_three'=>$filename]);
        }
        
        
        $photoName=$idLastInserted."_meetingphoto_four";
        $inputfilenametoupload='photo_four';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $deletefourfilename=$farmermeeting_images_list[0]['photo_four'];
            $unlink_four_file_path=$imagedataPath.$deletefourfilename;
            if(!empty($unlink_four_file_path))
            {
                unlink($unlink_four_file_path);
            }
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerMeeting::where('id',$request->id)->update(['photo_four'=>$filename]);
        }
        
        $photoName=$idLastInserted."_meetingphoto_five";
        $inputfilenametoupload='photo_five';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $deletefivefilename=$farmermeeting_images_list[0]['photo_five'];
            $unlink_five_file_path=$imagedataPath.$deletefivefilename;
            if(!empty($unlink_five_file_path))
            {
                unlink($unlink_five_file_path);
            }
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerMeeting::where('id',$request->id)->update(['photo_five'=>$filename]);
        }
        
        }
        
        if ($farmer)
        {
             return response()->json([
                "data" => $farmer,
                "result" => true,
                "message" => 'Farmer Meeting Photo Updated By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Farmer Meeting Photo Not Updated'
            ]);
            
        }
    }
    
    
    
    
    public function farmermeetingupdate_distributorapp(Request $request)
    {
        
        $data=[
                'date'=>$request->date,
                'meeting_place' => $request->meeting_place,
                'farmer_id' => $request->farmer_id,
                'meeting_title' => $request->meeting_title,
                'meeting_description' => $request->meeting_description,
                'created_by' => $request->created_by,
                'longitude' => $request->longitude,
                'latitude' => $request->latitude
            ];
        $FarmerMeeting = FarmerMeeting::where('id',$request->meeting_id)->update($data);
        $FarmerMeeting_count=sizeof($FarmerMeeting);
        if ($FarmerMeeting_count)
        {
             return response()->json([
                "data" => $FarmerMeeting,
                "result" => true,
                "message" => 'Farmer Meeting Updated'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Farmer Meeting Not Updated'
            ]);
            
        }
    }
    
    
    
    
    public function farmermeetinglist_distributorweb(Request $request)
    {
        try{
            $presentFarmerFormeeting='';
            $farmerMeetingData =FarmerMeeting::where('tbl_farmer_meeting.is_deleted','no')
                ->leftJoin('usersinfo','tbl_farmer_meeting.created_by','=','usersinfo.user_id')
            
            
            ->when($request->get('state'), function($farmerMeetingData) use ($request) {
                $farmerMeetingData->where('usersinfo.state',$request->state);
              })
              
              ->when($request->get('district'), function($farmerMeetingData) use ($request) {
                $farmerMeetingData->where('usersinfo.district',$request->district);
              })
              
              ->when($request->get('taluka'), function($farmerMeetingData) use ($request) {
                $farmerMeetingData->where('usersinfo.taluka',$request->taluka);
              })
              
              ->when($request->get('city'), function($farmerMeetingData) use ($request) {
                $farmerMeetingData->where('usersinfo.city',$request->city);
              })
              ->select(
                  'tbl_farmer_meeting.id',
                'tbl_farmer_meeting.id as tbl_farmer_meeting_id', 'tbl_farmer_meeting.date', 'tbl_farmer_meeting.meeting_place', 'tbl_farmer_meeting.farmer_id', 'tbl_farmer_meeting.meeting_title', 'tbl_farmer_meeting.meeting_description', 
                'tbl_farmer_meeting.created_by', 'tbl_farmer_meeting.photo_one', 'tbl_farmer_meeting.photo_one_lat', 'tbl_farmer_meeting.photo_one_long', 'tbl_farmer_meeting.photo_two', 'tbl_farmer_meeting.photo_two_lat', 
                'tbl_farmer_meeting.photo_two_long', 'tbl_farmer_meeting.photo_three', 'tbl_farmer_meeting.photo_three_lat', 'tbl_farmer_meeting.photo_three_long', 'tbl_farmer_meeting.photo_four', 
                'tbl_farmer_meeting.photo_four_lat', 'tbl_farmer_meeting.photo_four_long', 'tbl_farmer_meeting.photo_five', 'tbl_farmer_meeting.photo_five_lat', 
                'tbl_farmer_meeting.photo_five_long', 'tbl_farmer_meeting.latitude', 'tbl_farmer_meeting.longitude', 'tbl_farmer_meeting.is_deleted', 'tbl_farmer_meeting.created_at', 'tbl_farmer_meeting.updated_at',
                'usersinfo.fname as dfname',
                'usersinfo.mname as dmname',
                'usersinfo.lname as dlname'
                
              )
              ->orderBy('id','desc')
              ->get();
           info($farmerMeetingData);
            foreach($farmerMeetingData as $key=>$farmermeeting)
            {
                try
                {
                     $presentFarmer = null;
                     $presentFarmerFormeeting = null;
                     $presentFarmer=explode(",",$farmermeeting->farmer_id);
                    //  if (str_contains(",", $farmermeeting->farmer_id)) {
                        
                    // } else {
                    //     $presentFarmer= array($farmermeeting->farmer_id);
                    // }
                    
                    $presentFarmer= array_unique($presentFarmer);
                    info($presentFarmer);
                    // $distributordetails=$this->commonController->getDistributorNameById($farmermeeting->created_by);  
                    
                    // $farmermeeting->dfname=$distributordetails->fname;
                    // $farmermeeting->dmname=$distributordetails->mname;
                    // $farmermeeting->dlname=$distributordetails->lname;

                    $farmermeeting->photo_one=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_one;
                    $farmermeeting->photo_two=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_two;
                    $farmermeeting->photo_three=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_three;
                    $farmermeeting->photo_four=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_four;
                    
                    
                  
                    foreach($presentFarmer as $key=>$farmermeetingPresentDist)
                    {
                        if($farmermeetingPresentDist!=''|| $farmermeetingPresentDist!=NULL || $farmermeetingPresentDist!=null)
                        {
                            $farmerdetails=$this->commonController->getFarmerNameById($farmermeetingPresentDist); 
                              
                           if(!$farmerdetails) {
                                throw new Exception("Farmer Details Not Found");
                            }
                            $presentFarmerFormeeting .=++$key.")".$farmerdetails->fname." ".$farmerdetails->mname." ".$farmerdetails->lname;
                            $presentFarmerFormeeting .=",";
                            
                        }
                         //$farmermeetingPresentDist->presentFarmerFormeeting= 
                    }
                   $farmermeeting->presentFarmerFormeeting = rtrim($presentFarmerFormeeting, ',');
                   

                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }

            if (sizeof($farmerMeetingData))
            {
                 return response()->json([
                    "data" => $farmerMeetingData,
                    "result" => true,
                    "message" => 'Farmer Meeting Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmer Meeting Not Found'
                ]);
                
            }
        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
    
    public function farmermeetinglist_distributorapp(Request $request)
    {
        try
        {
            //dd($request->user_id);
            $presentFarmerFormeeting='';
            $farmerMeetingData =FarmerMeeting::where('is_deleted','no')->where('created_by',$request->user_id)->orderBy('id', 'DESC')->get();
            //$farmerMeetingData =FarmerMeeting::all();
            
            foreach($farmerMeetingData as $key=>$farmermeeting)
            {
                try
                {
                    
                    $distributordetails=$this->commonController->getDistributorNameById($farmermeeting->created_by);
                    
                    $farmermeeting->dfname=$distributordetails->fname;
                    $farmermeeting->dmname=$distributordetails->mname;
                    $farmermeeting->dlname=$distributordetails->lname;
                    //dd($farmermeeting);
                    $presentFarmer=explode(",",$farmermeeting->farmer_id);
                    //dd($presentFarmer);
                    foreach($presentFarmer as $key=>$farmermeetingPresentDist)
                    {
                        if($farmermeetingPresentDist!=''|| $farmermeetingPresentDist!=NULL || $farmermeetingPresentDist!=null)
                        {
                            $farmermeeting->photopathone=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_one;
                            $farmermeeting->photopathtwo=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_two;
                            $farmermeeting->photopaththree=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_three;
                            $farmermeeting->photopathfour=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_four;
                            $farmermeeting->photopathfive=FARMER_MEETING_PHOTO_VIEW.$farmermeeting->photo_five;
                        
                        
                            $farmerdetails=$this->commonController->getFarmerNameById($farmermeetingPresentDist);
                            $presentFarmerFormeeting .=++$key.")".$farmerdetails->fname." ".$farmerdetails->mname." ".$farmerdetails->lname;
                            $presentFarmerFormeeting .=",";
                            
                        }
                        
                    }
                    $farmermeeting->presentFarmerFormeeting=$presentFarmerFormeeting;
                    
                } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
                 }
            }
            
            if ($farmerMeetingData)
            {
                 return response()->json([
                    "data" => $farmerMeetingData,
                    "result" => true,
                    "message" => 'Farmer Meeting Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmer Meeting Not Found'
                ]);
                
            }
        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
    
    
    
    public function distributormeetingadd_distributorapp(Request $request)
    {
        $distributor = new DistributorMeeting();
        $distributor->date = $request->date;
        $distributor->meeting_place = $request->meeting_place;
        $distributor->distributor_id = $request->distributor_id;
        $distributor->points_discuss = $request->points_discuss;
        $distributor->created_by = $request->created_by;
        $distributor->longitude = $request->longitude;
        $distributor->latitude = $request->latitude;
        $distributor->save();
        
        if ($distributor)
        {
             return response()->json([
                "data" => $distributor,
                "result" => true,
                "message" => 'Distributor Meeting Added By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Meeting Not Added'
            ]);
            
        }
    }
    
    
    public function distributormeetingadd_images_distributorapp(Request $request)
    {
       
        $distributor = new DistributorMeeting();
        
        $imagedataPath=DISTRIBUTOR_MEETING_PHOTO_UPLOAD;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        
        $idLastInserted=$request->id;
        $photoName=$idLastInserted."_meetingphoto_one";
        $inputfilenametoupload='photo_one';
        
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $photo_one_lat_long=explode("_",$request->lat_long_string);
            $photo_one_lat = $photo_one_lat_long[0];
            $photo_one_long = $photo_one_lat_long[1];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_one'=>$filename,'photo_one_lat'=>$photo_one_lat,'photo_one_long'=>$photo_one_long]);
        }
       
        $photoName=$idLastInserted."_meetingphoto_two";
        $inputfilenametoupload='photo_two';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $photo_two_lat_long=explode("_",$request->lat_long_string);
            $photo_two_lat = $photo_two_lat_long[2];
            $photo_two_long = $photo_two_lat_long[3];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_two'=>$filename,'photo_two_lat'=>$photo_two_lat,'photo_two_long'=>$photo_two_long]);
           
        }
        
        
        $photoName=$idLastInserted."_meetingphoto_three";
        $inputfilenametoupload='photo_three';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_three_lat_long=explode("_",$request->lat_long_string);
            $photo_three_lat = $photo_three_lat_long[4];
            $photo_three_long = $photo_three_lat_long[5];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_three'=>$filename,'photo_three_lat'=>$photo_three_lat,'photo_three_long'=>$photo_three_long]);
           
        }
        
        
        $photoName=$idLastInserted."_meetingphoto_four";
        $inputfilenametoupload='photo_four';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $photo_four_lat_long=explode("_",$request->lat_long_string);
            $photo_four_lat = $photo_four_lat_long[6];
            $photo_four_long = $photo_four_lat_long[7];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_four'=>$filename,'photo_four_lat'=>$photo_four_lat,'photo_four_long'=>$photo_four_long]);
           
        }
        
        $photoName=$idLastInserted."_meetingphoto_five";
        $inputfilenametoupload='photo_five';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_five_lat_long=explode("_",$request->lat_long_string);
            $photo_five_lat = $photo_five_lat_long[8];
            $photo_five_long = $photo_five_lat_long[9];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_five'=>$filename,'photo_five_lat'=>$photo_five_lat,'photo_five_long'=>$photo_five_long]);
           
        }
        
        
        if ($distributor)
        {
             return response()->json([
                "data" => $distributor,
                "result" => true,
                "message" => 'Distributor Meeting Photo Added By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Meeting Photo Not Added'
            ]);
            
        }
    }
    
    
    public function distributormeetingadd_images_update_distributorapp(Request $request)
    {
        $distributor = new DistributorMeeting();
        
        $imagedataPath=DISTRIBUTOR_MEETING_PHOTO_UPLOAD;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        
        $idLastInserted=$request->id;
        $distributormeeting_images_list = DistributorMeeting::where('id',$request->id)->get();
        if(!empty($distributormeeting_images_list))
        {
            $photoName=$idLastInserted."_meetingphoto_one";
            $inputfilenametoupload='photo_one';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                $deleteonefilename=$distributormeeting_images_list[0]['photo_one'];
                $unlink_one_file_path=$imagedataPath.$deleteonefilename;
                if(!empty($unlink_one_file_path))
                {
                    unlink($unlink_one_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_one'=>$filename]);
               
            }
            
            $photoName=$idLastInserted."_meetingphoto_two";
            $inputfilenametoupload='photo_two';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {     
                $deletetwofilename=$distributormeeting_images_list[0]['photo_two'];
                $unlink_two_file_path=$imagedataPath.$deletetwofilename;
                if(!empty($unlink_two_file_path))
                {
                    unlink($unlink_two_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_two'=>$filename]);
               
            }
            
            
            $photoName=$idLastInserted."_meetingphoto_three";
            $inputfilenametoupload='photo_three';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {     
                $deletethreefilename=$distributormeeting_images_list[0]['photo_three'];
                $unlink_three_file_path=$imagedataPath.$deletethreefilename;
                if(!empty($unlink_three_file_path))
                {
                    unlink($unlink_three_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_three'=>$filename]);
               
            }
            
            
            $photoName=$idLastInserted."_meetingphoto_four";
            $inputfilenametoupload='photo_four';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {     
                $deletefourfilename=$distributormeeting_images_list[0]['photo_four'];
                $unlink_four_file_path=$imagedataPath.$deletefourfilename;
                if(!empty($unlink_four_file_path))
                {
                    unlink($unlink_four_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_four'=>$filename]);
               
            }
            
            $photoName=$idLastInserted."_meetingphoto_five";
            $inputfilenametoupload='photo_five';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {     
                $deletefivefilename=$distributormeeting_images_list[0]['photo_five'];
                $unlink_five_file_path=$imagedataPath.$deletefivefilename;
                if(!empty($unlink_five_file_path))
                {
                    unlink($unlink_five_file_path);
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $user=DistributorMeeting::where('id',$idLastInserted)->update(['photo_five'=>$filename]);
               
            }
        }
        
        if ($distributor)
        {
             return response()->json([
                "data" => $distributor,
                "result" => true,
                "message" => 'Distributor Meeting Photo Updated By Distributor'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Meeting Photo Not Updated'
            ]);
            
        }
    }
    
    
    public function distributormeetinglist_distributorweb(Request $request)
    {
        try{
            $presentFarmerFormeeting='';
            $distributorMeetingData =DistributorMeeting::where('tbl_distributor_meeting.is_deleted','no')
            
            ->join('usersinfo','tbl_distributor_meeting.created_by','=','usersinfo.user_id')
            ->when($request->get('state'), function($farmerMeetingData) use ($request) {
                $farmerMeetingData->where('usersinfo.state',$request->state);
              })
              
              ->when($request->get('district'), function($farmerMeetingData) use ($request) {
                $farmerMeetingData->where('usersinfo.district',$request->district);
              })
              
              ->when($request->get('taluka'), function($farmerMeetingData) use ($request) {
                $farmerMeetingData->where('usersinfo.taluka',$request->taluka);
              })
              
              ->when($request->get('city'), function($farmerMeetingData) use ($request) {
                $farmerMeetingData->where('usersinfo.city',$request->city);
              })

              ->select(
                'tbl_distributor_meeting.id', 'tbl_distributor_meeting.date', 'tbl_distributor_meeting.meeting_place', 'tbl_distributor_meeting.distributor_id', 'tbl_distributor_meeting.points_discuss', 
                'tbl_distributor_meeting.photo_one', 'tbl_distributor_meeting.photo_one_lat', 'tbl_distributor_meeting.photo_one_long', 'tbl_distributor_meeting.photo_two', 'tbl_distributor_meeting.photo_two_lat', 
                'tbl_distributor_meeting.photo_two_long', 'tbl_distributor_meeting.photo_three', 'tbl_distributor_meeting.photo_three_lat', 'tbl_distributor_meeting.photo_three_long', 'tbl_distributor_meeting.photo_four', 
                'tbl_distributor_meeting.photo_four_lat', 'tbl_distributor_meeting.photo_four_long', 'tbl_distributor_meeting.photo_five', 'tbl_distributor_meeting.photo_five_lat', 'tbl_distributor_meeting.photo_five_long',
                'tbl_distributor_meeting.latitude', 'tbl_distributor_meeting.longitude', 'tbl_distributor_meeting.created_by', 'tbl_distributor_meeting.is_deleted', 'tbl_distributor_meeting.created_at', 'tbl_distributor_meeting.updated_at'
              )
            ->get();
            foreach($distributorMeetingData as $key=>$distributormeeting)
            {
                try
                {
                    //dd($distributormeeting->created_by);
                    $distributordetails=$this->commonController->getDistributorNameById($distributormeeting->created_by);   
                    
                    $distributormeeting->dfname=$distributordetails->fname;
                    $distributormeeting->dmname=$distributordetails->mname;
                    $distributormeeting->dlname=$distributordetails->lname;


                    $distributormeeting->photopathone=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_one;
                    $distributormeeting->photopathtwo=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_two;
                    $distributormeeting->photopaththree=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_three;
                    $distributormeeting->photopathfour=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_four;
                    $distributormeeting->photopathfive=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_five;
                    
                    
                    $presentDistributor=explode(",",$distributormeeting->distributor_id);
                   //dd($presentDistributor);
                    foreach($presentDistributor as $key=>$distributormeetingPresentDist)
                    {
                        if($distributormeetingPresentDist!=''|| $distributormeetingPresentDist!=NULL || $distributormeetingPresentDist!=null)
                        {
                            //dd($distributormeetingPresentDist);
                            $distributordetails=$this->commonController->getDistributorNameById($distributormeetingPresentDist); 
                             
                           if(!$distributordetails) {
                                throw new Exception("Distributor Details Not Found");
                            }
                            
                            $presentFarmerFormeeting .=++$key.")".$distributordetails->fname." ".$distributordetails->mname." ".$distributordetails->lname;
                            $presentFarmerFormeeting .=",";
                            //dd($presentFarmerFormeeting);
                        }
                        
                    }
                    $distributormeeting->presentFarmerFormeeting=$presentFarmerFormeeting;
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            
            if ($distributorMeetingData)
            {
                 return response()->json([
                    "data" => $distributorMeetingData,
                    "result" => true,
                    "message" => 'Distributor Meeting Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Meeting Not Found'
                ]);
                
            }
        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
    
    
    
    
    
    
    
    public function distributormeetinglist_distributorapp(Request $request)
    {
        try
        {
            $presentDistributorFormeeting='';
            
            $distributor =DistributorMeeting::where('is_deleted','no')->where('created_by',$request->user_id)->get();
            
            foreach($distributor as $key=>$distributormeeting)
            {
                try
                {
                    
                    $distributordetails=$this->commonController->getDistributorNameById($distributormeeting->created_by);
                    if(!$distributordetails) {
                            throw new Exception("No Distributor Information Found");
                        }
                    $distributormeeting->dfname=$distributordetails->fname;
                    $distributormeeting->dmname=$distributordetails->mname;
                    $distributormeeting->dlname=$distributordetails->lname;
                    
                    $presentDistributor=explode(",",$distributormeeting->distributor_id);
                    
                    foreach($presentDistributor as $key=>$distributormeetingPresentDist)
                    {
                        //dd($distributormeetingPresentDist);
                        $distributordetails=$this->commonController->getDistributorNameById($distributormeetingPresentDist);
                        if(!$distributordetails) {
                            throw new Exception("No Distributor Information Found");
                        }
                        
                        $distributormeeting->photopathone=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_one;
                        $distributormeeting->photopathtwo=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_two;
                        $distributormeeting->photopaththree=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_three;
                        $distributormeeting->photopathfour=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_four;
                        $distributormeeting->photopathfive=DISTRIBUTOR_MEETING_PHOTO_VIEW.$distributormeeting->photo_five;
                    
                    
                        $presentDistributorFormeeting .=++$key.")".$distributordetails->fname." ".$distributordetails->mname." ".$distributordetails->lname;
                        $presentDistributorFormeeting .=",";
                    }
                    $distributormeeting->presentDistributorFormeeting=$presentDistributorFormeeting;
                } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
                }
            }
            if ($distributor)
            {
                 return response()->json([
                    "data" => $distributor,
                    "result" => true,
                    "message" => 'Distributor Meeting Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Meeting Not Found'
                ]);
                
            }
        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
    
    public function distributormeetingperticularget_distributorapp(Request $request)
    {
        $distributor =DistributorMeeting::where('is_deleted','no')->where('created_by',$request->user_id)->where('id',$request->meeting_id)->get();
        
        if ($distributor)
        {
             return response()->json([
                "data" => $distributor,
                "result" => true,
                "message" => 'Distributor Meeting Get Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Meeting Not Found'
            ]);
            
        }
    }
    
    public function distributormeetingupdate_distributorapp(Request $request)
    {
        $data=[
                'date'=>$request->date,
                'meeting_place' => $request->meeting_place,
                'distributor_id' => $request->distributor_id,
                'points_discuss' => $request->points_discuss
            ];
        $distributor = DistributorMeeting::where('created_by',$request->user_id)->where('id',$request->meeting_id)->update($data);
      
        if ($distributor)
        {
             return response()->json([
                "data" => $distributor,
                "result" => true,
                "message" => 'Distributor Meeting Updated'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Meeting Not Updated'
            ]);
            
        }
    }
    
    public function distributorvisittofarmerlist_distributorweb(Request $request)
    // {
    //     try 
    //     {
    //         $farmerVistByDistributor = FarmerVistByDistributor::where('status',0)->get();
            
    //         if(!$farmerVistByDistributor) {
    //             throw new Exception(api_error(1006), 1006);
    //         }
    //         foreach($farmerVistByDistributor as $key=>$farmerVistByDistributorNew)
    //         {
    //             try
    //             { 
                    
    //                 $farmerdetails=$this->commonController->getFarmerNameById($farmerVistByDistributorNew->farmer_id);
    //                 if(!$farmerdetails) {
    //                     throw new Exception('unable to get farmer details');
    //                 }
                    
    //                 $farmerVistByDistributorNew->ffname=$farmerdetails->fname;
    //                 $farmerVistByDistributorNew->fmname=$farmerdetails->mname;
    //                 $farmerVistByDistributorNew->flname=$farmerdetails->lname;
    //                 //dd($farmerdetails->created_by);
    //                 $farmerdetails=$this->commonController->getDistributorNameById($farmerVistByDistributorNew->created_by);
    //                 $farmerVistByDistributorNew->dfname=$farmerdetails->fname;
    //                 $farmerVistByDistributorNew->dmname=$farmerdetails->mname;
    //                 $farmerVistByDistributorNew->dlname=$farmerdetails->lname;
    
    //                 $stateName=$this->commonController->getAreaNameById($farmerdetails->state);
    //                 $farmerVistByDistributorNew->state=$stateName->name;
                    
    //                 $districtName=$this->commonController->getAreaNameById($farmerdetails->district);
    //                 $farmerVistByDistributorNew->district=$districtName->name;
                    
    //                 $talukaName=$this->commonController->getAreaNameById($farmerdetails->taluka);
    //                 $farmerVistByDistributorNew->taluka=$talukaName->name;
                    
    //                 $cityName=$this->commonController->getAreaNameById($farmerdetails->city);
    //                 $farmerVistByDistributorNew->city=$cityName->name;
    //             } catch(Exception $e) {
    //             return response()->json([
    //                     "data" => '',
    //                     "result" => false,
    //                     "error" => true,
    //                     "message" =>$e->getMessage()." ".$e->getCode()
    //                 ]);
               
    //             }
        
    //         }
          
    //         if ($farmerVistByDistributor)
    //         {
    //              return response()->json([
    //                 "data" => $farmerVistByDistributor,
    //                 "result" => true,
    //                 "message" => 'Distributor Vist Towards Farmer Get Successfully'
    //             ]);
    //         }
    //         else
    //         {
    //              return response()->json([
    //                 "data" => '',
    //                 "result" => false,
    //                 "message" => 'Distributor Vist Towards Farmer Not Found'
    //             ]);
                
    //         }
    //     } catch(Exception $e) {
    //         return response()->json([
    //                 "data" => '',
    //                 "result" => false,
    //                 "error" => true,
    //                 "message" =>$e->getMessage()." ".$e->getCode()
    //             ]);
           
    //     }
    // }
    
    
    
    {
         try 
        {
            
            $farmerVistByDistributor = FarmerVistByDistributor::
                leftJoin('usersinfo as dist_name', function($join) {
                    $join->on('tbl_farmer_vist_by_distributor.created_by', '=', 'dist_name.user_id');
                  })
                  
                  
                  ->leftJoin('usersinfo AS farm_name', function($join) {
                    $join->on('tbl_farmer_vist_by_distributor.farmer_id', '=', 'farm_name.user_id');
                  })
                  
                  ->leftJoin('tbl_area as stateNew', function($join) {
                    $join->on('dist_name.state', '=', 'stateNew.location_id');
                  })
                  
                  ->leftJoin('tbl_area as districtNew', function($join) {
                    $join->on('dist_name.district', '=', 'districtNew.location_id');
                  })
                  
                  
                  ->leftJoin('tbl_area as talukaNew', function($join) {
                    $join->on('dist_name.taluka', '=', 'talukaNew.location_id');
                  })
                  
                  ->leftJoin('tbl_area as cityNew', function($join) {
                    $join->on('dist_name.city', '=', 'cityNew.location_id');
                  })
          
                  
                  ->select( 'tbl_farmer_vist_by_distributor.id as tbl_farmer_vist_by_distributor_id',
                  'tbl_farmer_vist_by_distributor.farmer_id',
                  'tbl_farmer_vist_by_distributor.crop',
                  'tbl_farmer_vist_by_distributor.created_at',
                  'tbl_farmer_vist_by_distributor.photo_one',
                  'tbl_farmer_vist_by_distributor.photo_two',
                  'tbl_farmer_vist_by_distributor.photo_three',
                  'tbl_farmer_vist_by_distributor.photo_four',
                  'tbl_farmer_vist_by_distributor.photo_five',
                  'tbl_farmer_vist_by_distributor.acer',
                  'tbl_farmer_vist_by_distributor.description_about_visit',
                  'tbl_farmer_vist_by_distributor.about_visit',
                  
                 'farm_name.fname as ffname',
                 'farm_name.mname as fmname',
                 'farm_name.lname as flname',
                 
                 'dist_name.fname as dfname',
                 'dist_name.mname as dmname',
                 'dist_name.lname as dlname',
                 
                'stateNew.name as state',
                'districtNew.name as district',
                'talukaNew.name as taluka',
                'cityNew.name as city'
        
                )
                ->orderBy('tbl_farmer_vist_by_distributor.id','desc')
            // ->where('tbl_farmer_vist_by_distributor.status',0)
            // ->select('tbl_farmer_vist_by_distributor.*','usersinfo.*')
            // ->orderBy('tbl_farmer_vist_by_distributor.id', 'DESC')
           
            ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('usersinfo.state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('usersinfo.district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('usersinfo.taluka',$request->taluka);
                })
                
                ->when($request->get('usersinfo.city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                
                ->when($request->get('created_by'), function($query) use ($request) {
                  $query->where('usersinfo.created_by',$request->added_by);
                })
                
                ->get();
// dd($farmerVistByDistributor);
            if(!$farmerVistByDistributor) {
                throw new Exception(api_error(1006), 1006);
            }
            foreach($farmerVistByDistributor as $key=>$farmerVistByDistributorNew)
            {

                $farmerVistByDistributorNew->photopathone=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_one;
                $farmerVistByDistributorNew->photopathtwo=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_two;
                $farmerVistByDistributorNew->photopaththree=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_three;
                $farmerVistByDistributorNew->photopathfour=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_four;
                $farmerVistByDistributorNew->photopathfive=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_five;
                
                // $farmerdetails=$this->commonController->getFarmerNameById($farmerVistByDistributorNew->farmer_id);
                // if(!$farmerdetails) {
                //     throw new Exception('unable to get farmer details');
                // }
                // $farmerVistByDistributorNew->ffname=$farmerdetails->fname;
                // $farmerVistByDistributorNew->fmname=$farmerdetails->mname;
                // $farmerVistByDistributorNew->flname=$farmerdetails->lname;
                
                // $farmerdetails=$this->commonController->getDistributorNameById($farmerVistByDistributorNew->created_by);
                // $farmerVistByDistributorNew->dfname=$farmerdetails->fname;
                // $farmerVistByDistributorNew->dmname=$farmerdetails->mname;
                // $farmerVistByDistributorNew->dlname=$farmerdetails->lname;

                // $stateName=$this->commonController->getAreaNameById($farmerdetails->state);
                // $farmerVistByDistributorNew->state=$stateName->name;
                
                // $districtName=$this->commonController->getAreaNameById($farmerdetails->district);
                // $farmerVistByDistributorNew->district=$districtName->name;
                
                // $talukaName=$this->commonController->getAreaNameById($farmerdetails->taluka);
                // $farmerVistByDistributorNew->taluka=$talukaName->name;
                
                // $cityName=$this->commonController->getAreaNameById($farmerdetails->city);
                // $farmerVistByDistributorNew->city=$cityName->name;
        
            }
          
            if ($farmerVistByDistributor)
            {
                 return response()->json([
                    "data" => $farmerVistByDistributor,
                    "result" => true,
                    "message" => 'Plot Vist Towards Farmer Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Plot Vist Towards Farmer Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributorvisittofarmerlist_distributorapp(Request $request)
    {
        try 
        {
            $farmerVistByDistributor = FarmerVistByDistributor::where('created_by',$request->created_by)->where('status','0')->orderBy('id', 'DESC')->get();
            if(!$farmerVistByDistributor) {
                throw new Exception(api_error(1006), 1006);
            }
            foreach($farmerVistByDistributor as $key=>$farmerVistByDistributorNew)
            {
                try
                {
                    $farmerdetails=$this->commonController->getFarmerNameById($farmerVistByDistributorNew->farmer_id);
                    //dd($farmerdetails);
                    if(!$farmerdetails) {
                        throw new Exception('unable to get farmer details');
                    }
                    
                    $farmerVistByDistributorNew->photopathone=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_one;
                    $farmerVistByDistributorNew->photopathtwo=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_two;
                    $farmerVistByDistributorNew->photopaththree=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_three;
                    $farmerVistByDistributorNew->photopathfour=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_four;
                    $farmerVistByDistributorNew->photopathfive=FARMER_VISIT_PHOTO_VIEW.$farmerVistByDistributorNew->photo_five;
                    
                    $farmerVistByDistributorNew->ffname=$farmerdetails->fname;
                    $farmerVistByDistributorNew->fmname=$farmerdetails->mname;
                    $farmerVistByDistributorNew->flname=$farmerdetails->lname;
                    //dd($farmerVistByDistributorNew->created_by);
                    $distributordetails=$this->commonController->getDistributorNameById($farmerVistByDistributorNew->created_by);                        
                    $farmerVistByDistributorNew->dfname=$distributordetails->fname;
                    $farmerVistByDistributorNew->dmname=$distributordetails->mname;
                    $farmerVistByDistributorNew->dlname=$distributordetails->lname;
                } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
                }
            }
          
            if ($farmerVistByDistributor)
            {
                 return response()->json([
                    "data" => $farmerVistByDistributor,
                    "result" => true,
                    "message" => 'Distributor Vist Towards Farmer Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Vist Towards Farmer Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributorvisittofarmerdelete_distributorapp(Request $request)
    {
        $farmerVistByDistributor = FarmerVistByDistributor::where('id',$request->visit_id)->update(['status'=>1]);
      
        if ($farmerVistByDistributor)
        {
             return response()->json([
                "data" => $farmerVistByDistributor,
                "result" => true,
                "message" => 'Distributor Vist Towards Farmer Deleted Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Vist Towards Farmer Not Found'
            ]);
            
        }
    }
    
    public function distributorvisittofarmerget_distributorapp(Request $request)
    {
        $farmerVistByDistributor = FarmerVistByDistributor::where('id',$request->visit_id)->where('status',0)->get();
        
        if ($farmerVistByDistributor)
        {
             return response()->json([
                "data" => $farmerVistByDistributor,
                "result" => true,
                "message" => 'Distributor Vist Towards Farmer Get Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Vist Towards Farmer Not Found'
            ]);
            
        }
    }
    
    public function distributorvisittofarmeradd_distributorapp(Request $request)
    {
        $farmerVistByDistributor = new FarmerVistByDistributor();
        $farmerVistByDistributor->date = $request->date;
        $farmerVistByDistributor->visit_no = $request->visit_no;
        $farmerVistByDistributor->farmer_id = $request->farmer_id;
        $farmerVistByDistributor->crop = $request->crop;
        $farmerVistByDistributor->acer = $request->acer;
        $farmerVistByDistributor->description_about_visit = $request->description_about_visit;
        $farmerVistByDistributor->about_visit = $request->about_visit;
        $farmerVistByDistributor->created_by = $request->created_by;
        $farmerVistByDistributor->longitude = $request->longitude;
        $farmerVistByDistributor->latitude = $request->latitude;
        $farmerVistByDistributor->status = '0';
        $farmerVistByDistributor->save();
        
        if ($farmerVistByDistributor)
        {
             return response()->json([
                "data" => $farmerVistByDistributor,
                "result" => true,
                "message" => 'Distributor Vist Towards Farmer Added Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Meeting Not Added'
            ]);
            
        }
    }
    
    
    
    
    
    
    public function distributorvisittofarmeradd_images_distributorapp(Request $request)
    {
        
        $farmermeeting = new FarmerVistByDistributor();
        $imagedataPath=FARMER_VISIT_PHOTO_UPLOAD;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        
        
        $idLastInserted=$request->id;
        $photoName=$idLastInserted."_farmervisitphoto_one";
        $inputfilenametoupload='photo_one';
        
        if (!empty($request->hasFile($inputfilenametoupload)))
        {
                $photo_one_lat_long=explode("_",$request->lat_long_string);
                $photo_one_lat = $photo_one_lat_long[0];
                $photo_one_long = $photo_one_lat_long[1];
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $farmermeeting=FarmerVistByDistributor::where('id',$idLastInserted)->update(['photo_one'=>$filename,'photo_one_lat'=>$photo_one_lat,'photo_one_long'=>$photo_one_long]);
        }
        
        $photoName=$idLastInserted."_farmervisitphoto_two";
        $inputfilenametoupload='photo_two';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_two_lat_long=explode("_",$request->lat_long_string);
            $photo_two_lat = $photo_two_lat_long[2];
            $photo_two_long = $photo_two_lat_long[3];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmermeeting=FarmerVistByDistributor::where('id',$idLastInserted)->update(['photo_two'=>$filename,'photo_two_lat'=>$photo_two_lat,'photo_two_long'=>$photo_two_long]);
            
        }
        
        
        $photoName=$idLastInserted."_farmervisitphoto_three";
        $inputfilenametoupload='photo_three';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_three_lat_long=explode("_",$request->lat_long_string);
            $photo_three_lat = $photo_three_lat_long[4];
            $photo_three_long = $photo_three_lat_long[5];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmermeeting=FarmerVistByDistributor::where('id',$idLastInserted)->update(['photo_three'=>$filename,'photo_three_lat'=>$photo_three_lat,'photo_three_long'=>$photo_three_long]);
            
        }
        
        
        $photoName=$idLastInserted."_farmervisitphoto_four";
        $inputfilenametoupload='photo_four';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_four_lat_long=explode("_",$request->lat_long_string);
            $photo_four_lat = $photo_four_lat_long[6];
            $photo_four_long = $photo_four_lat_long[7];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmermeeting=FarmerVistByDistributor::where('id',$idLastInserted)->update(['photo_four'=>$filename,'photo_four_lat'=>$photo_four_lat,'photo_four_long'=>$photo_four_long]);
            
        }
        
        $photoName=$idLastInserted."_farmervisitphoto_five";
        $inputfilenametoupload='photo_five';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_five_lat_long=explode("_",$request->lat_long_string);
            $photo_five_lat = $photo_five_lat_long[8];
            $photo_five_long = $photo_five_lat_long[9];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmermeeting=FarmerVistByDistributor::where('id',$idLastInserted)->update(['photo_five'=>$filename,'photo_five_lat'=>$photo_five_lat,'photo_five_long'=>$photo_five_long]);
            
        }
        
        
        if ($farmermeeting)
        {
             return response()->json([
                "data" => $farmermeeting,
                "result" => true,
                "message" => 'Farm Visit Photo Added.'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Farm Visit Photo Not Added'
            ]);
            
        }
    }
    
    
    
    public function distributorvisittofarmeradd_images_update_distributorapp(Request $request)
    {
        $farmer = new FarmerVistByDistributor();
        $imagedataPath=FARMER_VISIT_PHOTO_UPLOAD;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        $idLastInserted=$request->id;
        
        $farmermeeting_images_list = FarmerVistByDistributor::where('id',$request->id)->get();
        if(!empty($farmermeeting_images_list))
        {
        $photoName=$idLastInserted."_farmervisitphoto_one";
        $inputfilenametoupload='photo_one';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $deleteonefilename=$farmermeeting_images_list[0]['photo_one'];
            $unlink_one_file_path=$imagedataPath.$deleteonefilename;
            if(!empty($unlink_one_file_path))
            {
                unlink($unlink_one_file_path);
            }
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer = FarmerVistByDistributor::where('id',$request->id)->update(['photo_one'=>$filename]);
        }
        
        $photoName=$idLastInserted."_farmervisitphoto_two";
        $inputfilenametoupload='photo_two';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $deletetwofilename=$farmermeeting_images_list[0]['photo_two'];
            $unlink_two_file_path=$imagedataPath.$deletetwofilename;
            if(!empty($unlink_two_file_path))
            {
                unlink($unlink_two_file_path);
            }
            
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerVistByDistributor::where('id',$request->id)->update(['photo_two'=>$filename]);
        }
        
        
        $photoName=$idLastInserted."_farmervisitphoto_three";
        $inputfilenametoupload='photo_three';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $deletethreefilename=$farmermeeting_images_list[0]['photo_three'];
            $unlink_three_file_path=$imagedataPath.$deletethreefilename;
            if(!empty($unlink_three_file_path))
            {
                unlink($unlink_three_file_path);
            }
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerVistByDistributor::where('id',$request->id)->update(['photo_three'=>$filename]);
        }
        
        
        $photoName=$idLastInserted."_farmervisitphoto_four";
        $inputfilenametoupload='photo_four';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $deletefourfilename=$farmermeeting_images_list[0]['photo_four'];
            $unlink_four_file_path=$imagedataPath.$deletefourfilename;
            if(!empty($unlink_four_file_path))
            {
                unlink($unlink_four_file_path);
            }
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerVistByDistributor::where('id',$request->id)->update(['photo_four'=>$filename]);
        }
        
        $photoName=$idLastInserted."_farmervisitphoto_five";
        $inputfilenametoupload='photo_five';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $deletefivefilename=$farmermeeting_images_list[0]['photo_five'];
            $unlink_five_file_path=$imagedataPath.$deletefivefilename;
            if(!empty($unlink_five_file_path))
            {
                unlink($unlink_five_file_path);
            }
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $farmer=FarmerVistByDistributor::where('id',$request->id)->update(['photo_five'=>$filename]);
        }
        
        }
        
            return response()->json([
                "data" => $farmer,
                "result" => true,
                "message" => 'Farm Visit Photo Updated.'
            ]);
        
    }
    
    
    
    
    
    public function distributorvisittofarmerupdate_distributorapp(Request $request)
    {
        $data=[
                'farmer_id'=>$request->farmer_id,
                'crop' => $request->crop,
                'acer' => $request->acer,
                'description_about_visit' => $request->description_about_visit,
                'about_visit' => $request->about_visit
            ];
        $farmerVistByDistributor = FarmerVistByDistributor::where('created_by',$request->created_by)->where('id',$request->visit_id)->update($data);
      
        if ($farmerVistByDistributor)
        {
             return response()->json([
                "data" => $farmerVistByDistributor,
                "result" => true,
                "message" => 'Distributor Vist Towards Farmer Updated Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Vist Towards Farmer Not Successfully'
            ]);
            
        }
    }
    
    ////////////////////////////////////////////////////////////////////////////////////
    
    public function getvideodetailsdistributorall(Request $request)
    {
        try
        {
            
            $vediodetails=TargetVideos::where('is_deleted', '=','no')
                        ->where('active', '=','yes')->get();
            if(!$vediodetails) {
                throw new Exception('unable to video details');
            }
            if ($vediodetails)
                {
                     return response()->json([
                        "data" => $vediodetails,
                        "result" => true,
                        "message" => 'Videos  Get Successfully'
                    ]);
                }
                else
                {
                     return response()->json([
                        "data" => '',
                        "result" => false,
                        "message" => 'Videos Not Found'
                    ]);
                    
                }
        
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
        
    }
    public function distributortargetvideolist_distributorweb(Request $request)
    {
        try 
        {
            // $targetvideo = TargetVideosToDistributor::
            //                 leftJoin('tbl_target_videos', function($join) {
            //                     $join->on('tbl_target_videos_to_distributor.target_vedio_id','=','tbl_target_videos.id');
            //                 })
            //                 ->where(['tbl_target_videos.is_deleted'=>'no'])
            //                 ->select( 'tbl_target_videos.id as id','tbl_target_videos_to_distributor.target_vedio_id','tbl_target_videos_to_distributor.dist_id','tbl_target_videos_to_distributor.date','tbl_target_videos_to_distributor.is_deleted','tbl_target_videos_to_distributor.active','tbl_target_videos_to_distributor.is_watched',
            //                  'tbl_target_videos.title as title','tbl_target_videos.description as description','tbl_target_videos.language as language','tbl_target_videos.url as url','tbl_target_videos.to_whom_show as to_whom_show')
            //                 ->orderBy('tbl_target_videos_to_distributor.id', 'desc')
            //                 ->get();
                            
                            
                      $targetvideo = TargetVideos:: leftJoin('tbl_target_videos_to_distributor', function($join) {
                                $join->on('tbl_target_videos.id','=','tbl_target_videos_to_distributor.target_vedio_id');
                                
                            })
                            ->where(['tbl_target_videos.is_deleted'=>'no'])
                            ->select( 'tbl_target_videos.id as id','tbl_target_videos_to_distributor.target_vedio_id','tbl_target_videos_to_distributor.dist_id','tbl_target_videos_to_distributor.date','tbl_target_videos_to_distributor.is_deleted','tbl_target_videos_to_distributor.active','tbl_target_videos_to_distributor.is_watched',
                             'tbl_target_videos.title as title','tbl_target_videos.description as description','tbl_target_videos.language as language','tbl_target_videos.url as url','tbl_target_videos.to_whom_show as to_whom_show')
                            ->orderBy('tbl_target_videos_to_distributor.id', 'desc')
                            
                            ->get();
          
            if(!$targetvideo) {
                throw new Exception(api_error(1006), 1006);
            }
            foreach($targetvideo as $key=>$targetvideoNew)
            {
                try
                {
                    
                    // $vediodetails=TargetVideos::where('id', '=', $targetvideoNew->target_vedio_id)->first();
                    
                    // $targetvideoNew->title=$vediodetails->title;
                    // $targetvideoNew->description=$vediodetails->description;
                    // $targetvideoNew->url=$vediodetails->url;
                    
                    $distributordetails=$this->commonController->getUserNameById($targetvideoNew->dist_id);
                    // if(!$distributordetails) {
                    //     throw new Exception('unable to get ditributor details');
                    // }
                    
                        $targetvideoNew->dfname=$distributordetails ? $distributordetails->fname : '' ;
                    
                    
                    $targetvideoNew->dmname=$distributordetails ? $distributordetails->mname : '' ;
                    $targetvideoNew->dlname=$distributordetails ? $distributordetails->lname : '' ;
                    
                } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
                 }
            }
          
            if ($targetvideo)
            {
                 return response()->json([
                    "data" => $targetvideo,
                    "result" => true,
                    "message" => 'Target Videos To Distributor Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Target Videos To Distributor Farmer Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributortargetvideolist_distributorapp(Request $request)
    {
        try 
        {
            $targetvideo = TargetVideosToDistributor::where('dist_id',$request->user_id)->where('is_deleted','no')->orderBy('id', 'DESC')->get();
            
            if(!$targetvideo) {
                throw new Exception(api_error(1006), 1006);
            }
            foreach($targetvideo as $key=>$targetvideoNew)
            {
                try
                {
                    
                    $vediodetails=TargetVideos::where('id', '=', $targetvideoNew->target_vedio_id)->first();
                    
                                            
                    // if(!$vediodetails) {
                    //     throw new Exception('unable to video details');
                    // }
                    $targetvideoNew->title=$vediodetails->title;
                    $targetvideoNew->description=$vediodetails->description;
                    $targetvideoNew->url=$vediodetails->url;
                    
                } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
                }
            }
          
            if ($targetvideo)
            {
                 return response()->json([
                    "data" => $targetvideo,
                    "result" => true,
                    "message" => 'Target Videos To Distributor Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Target Videos To Distributor Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributortargetvideodelete_distributorweb(Request $request)
    {
        info($request->video_id);
        $targetvideo = TargetVideos::where('id',$request->video_id)->update(['is_deleted'=>'yes']);
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Target Videos To Distributor Deleted Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Target Videos To Distributor Not Found'
            ]);
            
        }
    }
    
    public function distributortargetvideoget_distributorweb(Request $request)
    {
        $targetvideo = TargetVideosToDistributor::where('id',$request->video_id)->where('is_deleted','no')->get();
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Target Videos To Distributor Get Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Target Videos To Distributor Not Found'
            ]);
            
        }
    }
    
    public function distributortargetvideoadd_distributorweb(Request $request)
    {
        $alldist=User::where('user_type',$request->to_whom_show)->where('is_deleted','no')->get();
        foreach($alldist as $key=>$alldistId)
        {
            $targetvideo = new TargetVideosToDistributor();
            $targetvideo->target_vedio_id = $request->target_vedio_id;
            $targetvideo->dist_id = $alldistId->id;
            $targetvideo->date = $request->date;
            // $targetvideo->status = 0;
            // $targetvideo->activeinactive = 0;
            $targetvideo->save();
        }
       
       
        if ($alldist)
        {
             return response()->json([
                "data" => $alldist,
                "result" => true,
                "message" => 'Target Videos To Distributor Added Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Target Videos To Distributor Not Added'
            ]);
            
        }
    }
    
    public function distributortargetvideoupdate_distributorweb(Request $request)
    {
        $data=[
                'target_vedio_id'=>$request->target_vedio_id,
                'dist_id' => $request->dist_id,
                'date' => $request->date
            ];
        $targetvideo = TargetVideosToDistributor::where('id',$request->video_id)->update($data);
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Target Videos To Distributor Updated Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Target Videos To Distributor Not Updated'
            ]);
            
        }
    }
    
    
    //SCT Result
    public function sct_resultadd_distributorapp(Request $request)
    {
        try
        {
            $sctresult = new SCTResult();
            $sctresult->date = $request->date;
            $sctresult->title = $request->title;
            $sctresult->area = $request->area;
            $sctresult->description = $request->description;
            $sctresult->created_by = $request->created_by;
            $sctresult->longitude = $request->longitude;
            $sctresult->latitude = $request->latitude;
            $sctresult->save();
            
            if ($sctresult)
            {
                 return response()->json([
                    "data" => $sctresult,
                    "result" => true,
                    "message" => 'SCT Result Added By Distributor'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'SCT Result Not Added'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    // public function sct_resultadd_images_distributorapp(Request $request)
    // {
    //     try
    //     {
            
    //         $sctresult = new SCTResult();
    //         $imagedataPath=SCT_RESULT_PHOTO_UPLOAD;
    //         if ( !is_dir( $imagedataPath) ) 
    //         {
    //             mkdir( $imagedataPath );       
    //         }
    //         $idLastInserted=$request->id;
    //         $photoName=$idLastInserted."_photo_one";
    //         $inputfilenametoupload='photo_one';
    //         if (!empty($request->hasFile($inputfilenametoupload)))
    //         {  
    //             $photo_one_lat_long=explode("_",$request->lat_long_string);
    //             $photo_one_lat = $photo_one_lat_long[0];
    //             $photo_one_long = $photo_one_lat_long[1];
                
    //             $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
    //             $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_one'=>$filename,'photo_one_lat'=>$photo_one_lat,'photo_one_long'=>$photo_one_long]);
                
    //         }
            
    //         $photoName=$idLastInserted."_photo_two";
    //         $inputfilenametoupload='photo_two';
    //         if (!empty($request->hasFile($inputfilenametoupload)))
    //         {   
    //             $photo_two_lat_long=explode("_",$request->lat_long_string);
    //             $photo_two_lat = $photo_two_lat_long[2];
    //             $photo_two_long = $photo_two_lat_long[3];
    //             $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
    //             $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_two'=>$filename,'photo_two_lat'=>$photo_two_lat,'photo_two_long'=>$photo_two_long]);
               
    //         }
            
            
    //         $photoName=$idLastInserted."_photo_three";
    //         $inputfilenametoupload='photo_three';
    //         if (!empty($request->hasFile($inputfilenametoupload)))
    //         {   
    //             $photo_three_lat_long=explode("_",$request->lat_long_string);
    //             $photo_three_lat = $photo_three_lat_long[4];
    //             $photo_three_long = $photo_three_lat_long[5];
    //             $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
    //             $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_three'=>$filename,'photo_three_lat'=>$photo_three_lat,'photo_three_long'=>$photo_three_long]);
               
    //         }
            
            
    //         $photoName=$idLastInserted."_photo_four";
    //         $inputfilenametoupload='photo_four';
    //         if (!empty($request->hasFile($inputfilenametoupload)))
    //         {   
    //             $photo_four_lat_long=explode("_",$request->lat_long_string);
    //             $photo_four_lat = $photo_four_lat_long[6];
    //             $photo_four_long = $photo_four_lat_long[7];
    //             $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
    //             $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_four'=>$filename,'photo_four_lat'=>$photo_four_lat,'photo_four_long'=>$photo_four_long]);
               
    //         }
            
    //         $photoName=$idLastInserted."_photo_five";
    //         $inputfilenametoupload='photo_five';
    //         if (!empty($request->hasFile($inputfilenametoupload)))
    //         {   
    //             $photo_five_lat_long=explode("_",$request->lat_long_string);
    //             $photo_five_lat = $photo_five_lat_long[8];
    //             $photo_five_long = $photo_five_lat_long[9];
    //             $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
    //             $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_five'=>$filename,'photo_five_lat'=>$photo_five_lat,'photo_five_long'=>$photo_five_long]);
               
    //         }
            

    //         if ($sctresult)
    //         {
    //              return response()->json([
    //                 "data" => $sctresult,
    //                 "result" => true,
    //                 "message" => 'SCT Result Photo Added By Distributor'
    //             ]);
    //         }
    //         else
    //         {
    //              return response()->json([
    //                 "data" => '',
    //                 "result" => false,
    //                 "message" => 'SCT Result Photo Not Added'
    //             ]);
                
    //         }
    //     } catch(Exception $e) {
    //         return response()->json([
    //                 "data" => '',
    //                 "result" => false,
    //                 "error" => true,
    //                 "message" =>$e->getMessage()." ".$e->getCode()
    //             ]);
           
    //     }
    // }
    
    
    public function sct_resultadd_images_distributorapp(Request $request)
    {
       $sctresult = new SCTResult();
       $imagedataPath=SCT_RESULT_PHOTO_UPLOAD;
        
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );       
        }
        
        $idLastInserted=$request->id;
        $photoName=$idLastInserted."_photo_one";
        $inputfilenametoupload='photo_one';
        
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $photo_one_lat_long=explode("_",$request->lat_long_string);
            $photo_one_lat = $photo_one_lat_long[0];
            $photo_one_long = $photo_one_lat_long[1];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_one'=>$filename,'photo_one_lat'=>$photo_one_lat,'photo_one_long'=>$photo_one_long]);
        }
       
        $photoName=$idLastInserted."_photo_two";
        $inputfilenametoupload='photo_two';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $photo_two_lat_long=explode("_",$request->lat_long_string);
            $photo_two_lat = $photo_two_lat_long[2];
            $photo_two_long = $photo_two_lat_long[3];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_two'=>$filename,'photo_two_lat'=>$photo_two_lat,'photo_two_long'=>$photo_two_long]);
           
        }
        
        
        $photoName=$idLastInserted."_photo_three";
        $inputfilenametoupload='photo_three';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_three_lat_long=explode("_",$request->lat_long_string);
            $photo_three_lat = $photo_three_lat_long[4];
            $photo_three_long = $photo_three_lat_long[5];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_three'=>$filename,'photo_three_lat'=>$photo_three_lat,'photo_three_long'=>$photo_three_long]);
           
        }
        
        
        $photoName=$idLastInserted."_photo_four";
        $inputfilenametoupload='photo_four';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {   
            $photo_four_lat_long=explode("_",$request->lat_long_string);
            $photo_four_lat = $photo_four_lat_long[6];
            $photo_four_long = $photo_four_lat_long[7];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_four'=>$filename,'photo_four_lat'=>$photo_four_lat,'photo_four_long'=>$photo_four_long]);
           
        }
        
        $photoName=$idLastInserted."_photo_five";
        $inputfilenametoupload='photo_five';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $photo_five_lat_long=explode("_",$request->lat_long_string);
            $photo_five_lat = $photo_five_lat_long[8];
            $photo_five_long = $photo_five_lat_long[9];
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_five'=>$filename,'photo_five_lat'=>$photo_five_lat,'photo_five_long'=>$photo_five_long]);
           
        }
        
        
        if ($sctresult)
        {
             return response()->json([
                "data" => $sctresult,
                "result" => true,
                "message" => 'SCT Photo Added'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'SCT Photo Not Added'
            ]);
            
        }
    }
    
    
    
    public function sct_resultadd_images_update_distributorapp(Request $request)
    {
        try
        {
            $sctresult = new SCTResult();
            $imagedataPath=SCT_RESULT_PHOTO_UPLOAD;
            if ( !is_dir( $imagedataPath) ) 
            {
                mkdir( $imagedataPath );       
            }
            $idLastInserted=$request->id;
            $SCTResult_images_list = SCTResult::where('id',$request->id)->get();
        if(!empty($SCTResult_images_list))
        {
                $photoName=$idLastInserted."_photo_one";
                $inputfilenametoupload='photo_one';
                if (!empty($request->hasFile($inputfilenametoupload)))
                {     
                    $deleteonefilename=$SCTResult_images_list[0]['photo_one'];
                    $unlink_one_file_path=$imagedataPath.$deleteonefilename;
                    if(!empty($unlink_one_file_path))
                    {
                    unlink($unlink_one_file_path);
                    }
                    $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                    $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_one'=>$filename]);
                   
                }
                
                $photoName=$idLastInserted."_photo_two";
                $inputfilenametoupload='photo_two';
                if (!empty($request->hasFile($inputfilenametoupload)))
                {     
                    $deletetwofilename=$SCTResult_images_list[0]['photo_two'];
                    $unlink_two_file_path=$imagedataPath.$deletetwofilename;
                    if(!empty($unlink_two_file_path))
                    {
                        unlink($unlink_two_file_path);
                    }
                    $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                    $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_two'=>$filename]);
                   
                }
                
                
                $photoName=$idLastInserted."_photo_three";
                $inputfilenametoupload='photo_three';
                if (!empty($request->hasFile($inputfilenametoupload)))
                {     
                    $deletethreefilename=$SCTResult_images_list[0]['photo_three'];
                $unlink_three_file_path=$imagedataPath.$deletethreefilename;
                if(!empty($unlink_three_file_path))
                    {
                        unlink($unlink_three_file_path);
                    }
                    $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                    $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_three'=>$filename]);
                   
                }
                
                
                $photoName=$idLastInserted."_photo_four";
                $inputfilenametoupload='photo_four';
                if (!empty($request->hasFile($inputfilenametoupload)))
                {     
                    $deletefourfilename=$SCTResult_images_list[0]['photo_four'];
                $unlink_four_file_path=$imagedataPath.$deletefourfilename;
                if(!empty($unlink_four_file_path))
                    {
                        unlink($unlink_four_file_path);
                    }
                    $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                    $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_four'=>$filename]);
                   
                }
                
                $photoName=$idLastInserted."_photo_five";
                $inputfilenametoupload='photo_five';
                if (!empty($request->hasFile($inputfilenametoupload)))
                {     
                    $deletefivefilename=$SCTResult_images_list[0]['photo_five'];
                $unlink_five_file_path=$imagedataPath.$deletefivefilename;
                if(!empty($unlink_five_file_path))
                    {
                        unlink($unlink_five_file_path);
                    }
                    $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                    $sctresult=SCTResult::where('id',$idLastInserted)->update(['photo_five'=>$filename]);
                   
                }
            }            

            if ($sctresult)
            {
                 return response()->json([
                    "data" => $sctresult,
                    "result" => true,
                    "message" => 'SCT Result Photo Updated By Distributor'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'SCT Result Photo Not Updated'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    public function sct_resultlist_distributorapp(Request $request)
    {
        try
        {
            $sctresult =SCTResult::where('is_deleted','no')->where('created_by',$request->created_by)->get();
            
            foreach($sctresult as $key=>$sctresults)
            {
                    $sctresults->photopathone=SCT_RESULT_PHOTO_VIEW.$sctresults->photo_one;
                    $sctresults->photopathtwo=SCT_RESULT_PHOTO_VIEW.$sctresults->photo_two;
                    $sctresults->photopaththree=SCT_RESULT_PHOTO_VIEW.$sctresults->photo_three;
                    $sctresults->photopathfour=SCT_RESULT_PHOTO_VIEW.$sctresults->photo_four;
                    $sctresults->photopathfive=SCT_RESULT_PHOTO_VIEW.$sctresults->photo_five;
            }
                            
            if ($sctresult)
            {
                 return response()->json([
                    "data" => $sctresult,
                    "result" => true,
                    "message" => 'SCT Result Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'SCT Result Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    public function suscriberadd_distributorapp(Request $request)
    {
        try
        {
            $modelName = new Subscriber();
            $modelName->name = $request->name;
            $modelName->email = $request->email;
            $modelName->mobile = $request->mobile;
            $modelName->address = $request->address;
            $modelName->created_by = $request->created_by;
            $modelName->save();
            
            if ($modelName)
            {
                 return response()->json([
                    "data" => $modelName,
                    "result" => true,
                    "message" => 'Subscriber Added By Distributor'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Subscriber Not Added'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }


    public function suscriberlist_distributorapp(Request $request)
    {
        try
        {
            $modelName = Subscriber::where('is_deleted','no')->orderBy('id','DESC')->get();
            
            if ($modelName)
            {
                 return response()->json([
                    "data" => $modelName,
                    "result" => true,
                    "message" => 'Subscriber Added By Distributor'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Subscriber Not Added'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function subscribertargetget_distributorapp(Request $request)
    {
        try
        {
            $result =SubscriberTarget::where('status',0)->where('target_to',$request->target_to)
                                        ->where('from_date',$request->from_date)
                                        ->where('to_date	',$request->to_date	)
                                        ->get();
            
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Subscriber Target Get'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Subscriber Target Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
     public function checkLevelofDistributor($distributorId)
    {
        
        $details = User::where('id',$distributorId)->where('is_deleted','no')->first();
        
        if($details->user_type=='fsc')
        {
            $fsclist = UsersInfo::where('added_by',$distributorId)->where('user_type','fsc')->where('is_deleted','no')->get(); 
            
            if(count($fsclist)>=4)
            {
                $data=[
                        'user_type'=>'bsc',
                    ];
                //dd($data);
                //$dataNew=User::where('id',$distributorId)->update($data);
                $dataNew=Dist_Promotion_Demotion::insert(array('user_id'=>$distributorId,'user_type'=>'bsc'));
                
            }
            
        }
        elseif($details->user_type=='bsc')
        {
            $fsclist = UsersInfo::where('added_by',$distributorId)->where('user_type','bsc')->where('is_deleted','no')->get();   
            if(count($fsclist)>=4)
            {
                $data=[
                        'user_type'=>'dsc',
                    ];
                // $dataNew=User::where('id',$distributorId)->update($data);
                // $dataNew=UsersInfo::where('user_id',$distributorId)->update($data);
                $dataNew=Dist_Promotion_Demotion::insert(array('user_id'=>$distributorId,'user_type'=>'dsc'));
            }
            
        }
        
    }
    
   /////New API Satish 06.03.2021
   
    public function distributortargetvideolistdatefilter_distributorapp(Request $request)
    {
        try 
        {
            $targetvideo = TargetVideosToDistributor::
                leftJoin('tbl_target_videos', function($join) {
                    $join->on('tbl_target_videos_to_distributor.target_vedio_id', '=', 'tbl_target_videos.id');
                  })
                  ->where('tbl_target_videos_to_distributor.dist_id',$request->dist_id)
                    ->whereBetween('tbl_target_videos_to_distributor.date', [$request->fromdate,$request->todate])
                    ->where('tbl_target_videos_to_distributor.is_deleted','no')
                    ->select(
                        'tbl_target_videos.title',
                        'tbl_target_videos.description',
                        'tbl_target_videos.url',
                        
                        "tbl_target_videos_to_distributor.id",
                        "tbl_target_videos_to_distributor.target_vedio_id",
                        "tbl_target_videos_to_distributor.dist_id",
                        "tbl_target_videos_to_distributor.date",
                        "tbl_target_videos_to_distributor.is_watched"
                    )
                    ->orderBy('tbl_target_videos.id', 'DESC')
                    ->get();
            if(!$targetvideo) {
                throw new Exception(api_error(1006), 1006);
            }
           
          
            if ($targetvideo)
            {
                 return response()->json([
                    "data" => $targetvideo,
                    "result" => true,
                    "message" => 'Target Videos To Distributor Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Target Videos To Distributor Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributortargetvideosearch_distributorapp(Request $request)
    {
        try 
        {
            DB::enableQueryLog(); // Enable query log

            $targetvideo = DB::table('tbl_target_videos_to_distributor')->leftJoin('tbl_target_videos', 'tbl_target_videos_to_distributor.dist_id', '=','tbl_target_videos.id' )
           
            ->where('tbl_target_videos.id', '=', $request->dist_id)
            
            ->get();
            
            // TargetVideosToDistributor::where('tbl_target_videos_to_distributor.dist_id','=',$request->dist_id)
            //                     ->join('tbl_target_videos','tbl_target_videos.id','=','tbl_target_videos_to_distributor.target_vedio_id')
                    
            //               // -> where('tbl_target_videos.title','like', '%' . $request->text_to_search . '%' )
            //                 ->where('tbl_target_videos_to_distributor.is_deleted','=','no');
            //               // ->get();
                            
                            // TargetVideosToDistributor::where('providers.id' , $request->id)
                            // ->leftJoin('provider_cards', 'providers.id','=','provider_cards.provider_id')
                            // ->where('provider_cards.id', $this->loginProvider->provider_card_id)
                            // ->where('provider_cards.is_default' , DEFAULT_TRUE);
                            
            dd(DB::getQueryLog()); // Show results of log

            //dd($targetvideo);
            if(!$targetvideo) {
                throw new Exception(api_error(1006), 1006);
            }
            // foreach($targetvideo as $key=>$targetvideoNew)
            // {
            //     try
            //     {
            //         $vediodetails=TargetVideos::where('id', '=', $targetvideoNew->target_vedio_id)
            //                                 ->first();
            //         // if(!$vediodetails) {
            //         //     throw new Exception('unable to video details');
            //         // }
            //         $targetvideoNew->title=$vediodetails->title;
            //         $targetvideoNew->description=$vediodetails->description;
            //         $targetvideoNew->url=$vediodetails->url;
            //     } catch(Exception $e) {
            //     return response()->json([
            //             "data" => '',
            //             "result" => false,
            //             "error" => true,
            //             "message" =>$e->getMessage()." ".$e->getCode()
            //         ]);
               
            //     }
            // }
          
            if ($targetvideo)
            {
                 return response()->json([
                    "data" => $targetvideo,
                    "result" => true,
                    "message" => 'Target Videos To Distributor Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Target Videos To Distributor Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributorsearchbrochure_distributorapp(Request $request)
    {
        try 
        {
            $result = Downloads::where('title','like', '%' . $request->text_to_search . '%' )
                                ->get();
            if(!$result) {
                throw new Exception(api_error(1006), 1006);
            }
           
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Information Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributorsearchlanguage_distributorapp(Request $request)
    {
        try 
        {
            $result = Language::where('language','like', '%' . $request->text_to_search . '%' )
                                ->get();
            if(!$result) {
                throw new Exception(api_error(1006), 1006);
            }
           
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Information Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributortargetvideosearchfromall_distributorapp(Request $request)
    {
        try 
        {
            
            $video = TargetVideos::
                leftJoin('tbl_target_videos_to_distributor', function($join) {
                    $join->on( 'tbl_target_videos.id' , '=',  'tbl_target_videos_to_distributor.target_vedio_id');
                  })
                  ->where('tbl_target_videos_to_distributor.dist_id',$request->dist_id)
                    ->where('tbl_target_videos_to_distributor.is_deleted','no')
                    ->where('tbl_target_videos.is_deleted','=','no')
                    ->where('tbl_target_videos.title','like', '%' . $request->text_to_search . '%' )
                    ->select(
                        'tbl_target_videos.title',
                        'tbl_target_videos.description',
                        'tbl_target_videos.url',
                        
                        "tbl_target_videos_to_distributor.id",
                        "tbl_target_videos_to_distributor.target_vedio_id",
                        "tbl_target_videos_to_distributor.dist_id",
                        "tbl_target_videos_to_distributor.date",
                        "tbl_target_videos_to_distributor.is_watched"
                    )
                    ->orderBy('tbl_target_videos.id', 'DESC')
                    ->get();
                    
      
            if(!$video) {
                throw new Exception(api_error(1006), 1006);
            }
            
            if (count($video))
            {
                 return response()->json([
                    "data" => $video,
                    "result" => true,
                    "message" => 'Videos Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => ' Videos Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributorproductsearch_distributorapp_old(Request $request)
    {
        try 
        {
            $details = Product::where('title','like', '%' . $request->text_to_search . '%' )
                            ->where('is_deleted','=','no')
                            ->get();
                            
            $temp =[];
            if (count($details)>0)
            {
                foreach ($details as $key => $value) 
                {
            
                    $data                      = [];
                    $data['id']                = $value->id;
                    $data['title']                = $value->title;
                    $data['content']                = $value->content;
                    $data['long_description']       = $value->content;
                    $data['short_description']       = $value->content;
                    $data['photo_one']       = $value->photo_one;
                    $data['photo_two']       = $value->photo_two;
                    $data['photo_three']       = $value->photo_three;
                    $data['photo_four']       = $value->photo_four;
                    $data['photo_five']       = $value->photo_five;
                    $data['link']       = $value->link;
                    $data['photopath'] = '//finalapi.soilchargertechnology.com/public/uploads/web/product/'.$value->photo_one;
                   
                    $data['product_details']     = \DB::table('tbl_product_details')
                                                ->where(['product_id'=>$value->id])->get();
                    array_push($temp, $data);  
                }
                 return response()->json([
                    "data" => $temp,
                    "result" => true,
                    "message" => 'Information Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => ' Information Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function distributorproductsearch_distributorapp(Request $request)
    {
        try
        {
         
            $result = DB::table('tbl_product')
                    ->select('title','photo_one','id')
                    ->where('title','like', '%' . $request->text_to_search . '%' )
                    ->distinct('title')
                    ->get();

            
                foreach ($result as $key => $value) {

                    $front_product_details = FrontProduct::where('product_id',$value->id)->select('short_description','long_description')->first();

                    $value->product_id = $value->id;
                    $value->short_description = $front_product_details ? $front_product_details->short_description  : '';
                    $value->long_description = $front_product_details ? $front_product_details->long_description : '';
                    $value->photopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
                    $data_count = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
                                                    ->where('tbl_product_details.is_deleted','no')
                                                    ->where('tbl_product.title',$value->title)
                                                    ->where('tbl_product.is_deleted','no')
                                                    ->orderBy('tbl_product.id', 'DESC')
                                                    //->select('tbl_product_details.*','tbl_product.title','tbl_product.content','tbl_product.link','tbl_product.photo_one')
                                                    ->get();
                                                    // ->count();
                    
                    $value->product_details = $data_count;
                }

          
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Information get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information not found'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }
        
        //  try
        // {
        //     $result = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
        //         ->where('tbl_product_details.is_deleted','no')
        //         ->select('tbl_product_details.*','tbl_product.title','tbl_product.content','tbl_product.link')
        //         ->get();

        //     //dd($result);
        //     foreach($result as $key=>$value)
        //     {
        //         $value->photopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
        //     }
        //     if ($result)
        //     {
        //          return response()->json([
        //             "data" => $result,
        //             "result" => true,
        //             "message" => 'Information get Successfully'
        //         ]);
        //     }
        //     else
        //     {
        //          return response()->json([
        //             "data" => '',
        //             "result" => false,
        //             "message" => 'Information not found'
        //         ]);
                
        //     }
        // }
        // catch(Exception $e) {
        //   return  'Message: ' .$e->getMessage();
        // }
    }
    
    public function distributorlistundercount_distributor(Request $request)
    {
        try
        {
            $distributorlist = UsersInfo::where('added_by',$request->created_by)->whereIn('user_type',['fsc','bsc','dsc'])->where('is_deleted','no')->get();
            if ($distributorlist)
            {
                 return response()->json([
                    "data" => count($distributorlist),
                    "result" => true,
                    "message" => 'All Distributor Count Added By Distributor'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Not Found'
                ]);
                
            }

        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
    
    
    public function articleblogbydatefilter_distributorapp(Request $request)
    {
        try
        {
            $result = WebBlog::where(['status'=>'0','articleorschedule' =>'article'])->whereBetween('created_at', [$request->fromdate,$request->todate])->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=BLOG_CONTENT_VIEW.$value->photo_one;
            }
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Information get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information not found'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }
    }
    
    
    public function articleblogsearch_distributorapp(Request $request)
    {
        try
        {
            $result = WebBlog::where(['status'=>'0','articleorschedule' =>'article'])->where('title','like', '%' . $request->text_to_search . '%' )->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=BLOG_CONTENT_VIEW.$value->photo_one;
            }
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Information get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information not found'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }
    }
    
    public function scheduleblogbydatefilter_distributorapp(Request $request)
    {
        try
        {
            $result = WebBlog::where(['status'=>'0','articleorschedule' =>'schedule'])->whereBetween('created_at', [$request->fromdate,$request->todate])->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=BLOG_CONTENT_VIEW.$value->photo_one;
            }
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Information get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information not found'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }
    }
    
    public function scheduleblogsearch_distributorapp(Request $request)
    {
        try
        {
            $result = WebBlog::where(['status'=>'0','articleorschedule' =>'schedule'])->where('title','like', '%' . $request->text_to_search . '%' )->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=BLOG_CONTENT_VIEW.$value->photo_one;
            }
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Information get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information not found'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }
    }
    
     public function distributorvisittofarmercount_distributorapp(Request $request)
    {
        try 
        {
            $farmerVistByDistributor = FarmerVistByDistributor::where('created_by',$request->created_by)->where('status','0')->get();
            if(!$farmerVistByDistributor) {
                throw new Exception(api_error(1006), 1006);
            }
           
            if ($farmerVistByDistributor)
            {
                 return response()->json([
                    "data" => count($farmerVistByDistributor),
                    "result" => true,
                    "message" => 'Distributor Vist Towards Farmer Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Vist Towards Farmer Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function farmermeetingcount_distributorapp(Request $request)
    {
        try
        {
            $farmerMeetingData =FarmerMeeting::where('is_deleted','no')->where('created_by',$request->created_by)->get();

            if ($farmerMeetingData)
            {
                 return response()->json([
                    "data" => count($farmerMeetingData),
                    "result" => true,
                    "message" => 'Farmer Meeting Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmer Meeting Not Found'
                ]);
                
            }
        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
    
    public function sct_resultcount_distributorapp(Request $request)
    {
        try
        {
            $sctresult =SCTResult::where('is_deleted','no')->where('created_by',$request->created_by)->get();
            if ($sctresult)
            {
                 return response()->json([
                    "data" => count($sctresult),
                    "result" => true,
                    "message" => 'SCT Result Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'SCT Result Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    public function distributortargetvideodatefilter_distributorapp(Request $request)
    {
        try 
        {
            $video = TargetVideosToDistributor::where('dist_id',$request->dist_id)->whereBetween('created_at', [$request->fromdate,$request->todate])
                            ->where('is_deleted','=','no')
                            ->get();
                            
            if(!$video) {
                throw new Exception(api_error(1006), 1006);
            }
            
            if ($video)
            {
                 return response()->json([
                    "data" => $video,
                    "result" => true,
                    "message" => 'Videos Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => ' Videos Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
      public function subscribertargetgetlist_distributorapp(Request $request)
    {
        try
        {
            $result =Subscriber::where('is_deleted','no')->where('created_by',$request->created_by)
                                        ->get();
            
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Subscriber List Get'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Subscriber  Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    public function subscribertargetcount_distributorapp(Request $request)
    {
        try
        {
            $farmer_visit =FarmerVistByDistributor::where('status',0)->where('created_by',$request->created_by)->get();
            $farmer_meeting =FarmerMeeting::where('is_deleted','no')->where('created_by',$request->created_by)->get();
            $farmers_added =UsersInfo::where('is_deleted','no')->where('active','yes')->where('added_by',$request->added_by)->get();
            
            $farmer_visit_count=sizeof($farmer_visit);
            $farmer_meeting_count=sizeof($farmer_meeting);
            $farmers_added_count=sizeof($farmers_added);
            
            // if ($farmer_visit_count>0)
            // {
                 return response()->json([
                    "visitdata" => $farmer_visit_count,
                    "meetingdata" => $farmer_meeting_count,
                    "farmeraddeddata" => $farmers_added_count,
                    "result" => true,
                    "message" => 'Count Get'
                ]);
            // }
            // else
            // {
            //      return response()->json([
            //         "data" => '',
            //         "result" => false,
            //         "message" => 'Not Found'
            //     ]);
            // }
            
            
            
            
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    // Nandkishor
    // Message View for Admin
    public function messageview_by_distributor(Request $request)
    {
        try
        {
            $messageview= Messages::where('is_deleted', 'no')->where('message_by',$request->distributor_id)->get();
            
            foreach($messageview as $key=>$distributormeeting)
            {
                $distributormeeting->document=MESSAGE_UPLOADS_VIEW.$distributormeeting->document;
            }
            
            if ($messageview)
            {
                 return response()->json([
                    "data" => $messageview,
                    "result" => true,
                    "message" => 'Message Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Message Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    // Complaint View for Admin
    public function complaintview_by_distributor(Request $request)
    {
        try
        {
            $complaintview= Complaint::where('is_deleted', 'no')->where('complaint_by',$request->distributor_id)->get();
            foreach($complaintview as $key=>$distributormeeting) { 
                $distributormeeting->document_one=COMPLAINT_VIEW.$distributormeeting->document_one; 
                $distributormeeting->document_two=COMPLAINT_VIEW.$distributormeeting->document_two; 
                $distributormeeting->document_three=COMPLAINT_VIEW.$distributormeeting->document_three; 
                $distributormeeting->document_four=COMPLAINT_VIEW.$distributormeeting->document_four; 
                $distributormeeting->document_five=COMPLAINT_VIEW.$distributormeeting->document_five; 
                
            }
            
            if ($complaintview)
            {
                 return response()->json([
                    "data" => $complaintview,
                    "result" => true,
                    "message" => 'Complaint Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Complaint Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    // Block Distributor Delete
    public function block_distributor(Request $request)
    {
        $id = $request->id;
        $block_distributor = ['is_block' => 'yes'];
        $result = User::where('id', '=', $id)->update($block_distributor);

        if ($result)
        {
            return response()->json([
                "data" => $result,
                "result" => true,
                "message" => 'Distributor Blocked Successfully'
            ]);
        }
        else
        {
            return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Not Blocked'
            ]);
        
        }

    }
    
    
    // UnBlock Distributor Delete
    public function unblock_distributor(Request $request)
    {
        $id = $request->id;
        $unblock_distributor = ['is_block' => 'no'];
        $result = User::where('id', '=', $id)->update($unblock_distributor);

        if ($result)
        {
            return response()->json([
                "data" => $result,
                "result" => true,
                "message" => 'Distributor Unblocked Successfully'
            ]);
        }
        else
        {
            return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Distributor Not Unblocked'
            ]);
        
        }
    }
    
    
    // Send Notification
    public function send_notofication(Request $request)
    {
       
        $request=$request['all_notification'];
        $request=json_decode($request,true);
        $send_to=$request['send_to'];
        $userToken = User::where(['user_type'=>$send_to,'is_block'=>'no','is_approved'=>'yes','is_deleted'=>'no', ])->pluck('app_token')->toArray();
        $userId = User::where(['user_type'=>$send_to,'is_block'=>'no','is_approved'=>'yes','is_deleted'=>'no', ])->pluck('id')->toArray();

        $fcm_server_keyFinal='AAAANa2pzJY:APA91bHa0b1JRFJY2OJHo7odte-QB6rh0HXmuxqACsv0Q3duY7uzDrEcHYkWLDksH1HxACaNsGM35YO92GFU-YtnXIG6VzKb6fTa7v1c2DBS5YsSptEQqzl0bWKMP0UqCVujvoI_AZNL';
        $title='Soil Charge Technology';
        $icon='';
        
    	$message = [
    			'title' => $title,
    			'body' => $request['message'],
    			'icon' => $icon ,
    			'actions'=>'',
    			
    		];
        
       
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
        info($result);
        if ($result === false) {
             //dd("fail");
            die('Curl failed:' . curl_errno($ch));
           
        }
        // Close connection
        curl_close($ch);
        

        foreach($userId as $key=>$userIdNew)
        {

            $Notificationdetails = new Notification();
            $Notificationdetails->distributor_id = $userIdNew;
            $Notificationdetails->message =$request['message'];
            $Notificationdetails->save();
        }
        
            
        
        if($userId)
        {
             return response()->json([
                "data" => $Notificationdetails,
                "result" => true,
                "message" => 'Notification Sent Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
               "message" => 'Notification Not Sent'
            ]);
            
        }
    }
    
    
    
    // Read Notification
    public function read_notification(Request $request)
    {
        $id = $request->id;
        $read_notofication = ['is_read' => 'yes'];
        $result = Notification::where('id', '=', $id)->update($read_notofication);
        if ($result)
        {
            return response()->json([
                "data" => $result,
                "result" => true,
                "message" => 'Read Notification Successfully'
            ]);
        }
        else
        {
            return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Not Read Notification'
            ]);
        
        }
    }
    
    
    
    
    // List Notification
    public function list_notification(Request $request)
    {
        $result= Notification::where('distributor_id', '=', $request->distributor_id)->where('is_read','no')->orderBy('id','DESC')->get();
        foreach ($result as $key=>$distInfo)
        {
            //dd($distInfo->distributor_id);
            $distributordetails=$this->commonController->getDistributorNameById($distInfo->distributor_id);
                    
                    if(!$distributordetails) {
                        throw new Exception('unable to get ditributor details');
                    }
                    //dd($distributordetails->fname);
                    $distributordetails->dfname=$distributordetails->fname;
                    $distributordetails->dmname=$distributordetails->mname;
                    $distributordetails->dlname=$distributordetails->lname;
        }
        if ($result)
        {
            return response()->json([
                "data" => $result,
                "result" => true,
                "message" => 'Notification List Get Successfully'
            ]);
        }
        else
        {
            return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'No List Notification'
            ]);
        
        }
    }
    
    
    public function distributormeetingdetails_distributorweb(Request $request)
    {
        try{
            $presentFarmerFormeeting='';
            $distributorMeetingData =DistributorMeeting::where('id',$request->id)->where('is_deleted','no')->get();
            foreach($distributorMeetingData as $key=>$distributormeeting)
            {
                try
                {
                    $distributormeeting->photopathone=DISTRIBUTOR_MEETING_PHOTO_UPLOAD.$distributormeeting->photo_one;
                    $distributormeeting->photopathtwo=DISTRIBUTOR_MEETING_PHOTO_UPLOAD.$distributormeeting->photo_two;
                    $distributormeeting->photopaththree=DISTRIBUTOR_MEETING_PHOTO_UPLOAD.$distributormeeting->photo_three;
                    $distributormeeting->photopathfour=DISTRIBUTOR_MEETING_PHOTO_UPLOAD.$distributormeeting->photo_four;
                    $distributormeeting->photopathfive=DISTRIBUTOR_MEETING_PHOTO_UPLOAD.$distributormeeting->photo_five;
                
                
                    $distributordetails=$this->commonController->getDistributorNameById($distributormeeting->created_by);                        
                    $distributormeeting->dfname=$distributordetails->fname;
                    $distributormeeting->dmname=$distributordetails->mname;
                    $distributormeeting->dlname=$distributordetails->lname;
                    
                    $presentDistributor=explode(",",$distributormeeting->distributor_id);
                   // dd($presentFarmer);
                    foreach($presentDistributor as $key=>$distributormeetingPresentDist)
                    {
                        if($distributormeetingPresentDist!=''|| $distributormeetingPresentDist!=NULL || $distributormeetingPresentDist!=null)
                        {
                            $distributordetails=$this->commonController->getDistributorNameById($distributormeetingPresentDist); 
                           if(!$distributordetails) {
                                throw new Exception("Distributor Details Not Found");
                            }
                            $presentFarmerFormeeting .=++$key.")".$distributordetails->fname." ".$distributordetails->mname." ".$distributordetails->lname;
                            $presentFarmerFormeeting .=",";
                            
                        }
                        
                    }
                    $distributormeeting->presentFarmerFormeeting=$presentFarmerFormeeting;
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            if ($distributorMeetingData)
            {
                 return response()->json([
                    "data" => $distributorMeetingData,
                    "result" => true,
                    "message" => 'Distributor Meeting Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Meeting Not Found'
                ]);
                
            }
        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
   
   
   
   
   
   
   
   public function farmermeetingdetails_distributorweb(Request $request)
    {
        try{
            $presentFarmerFormeeting='';
            $farmerMeetingData =FarmerMeeting::where('id',$request->id)->where('is_deleted','no')->get();
            foreach($farmerMeetingData as $key=>$farmermeeting)
            {
                try
                {
                    $farmermeeting->photopathone=FARMER_MEETING_PHOTO_UPLOAD.$farmermeeting->photo_one;
                    $farmermeeting->photopathtwo=FARMER_MEETING_PHOTO_UPLOAD.$farmermeeting->photo_two;
                    $farmermeeting->photopaththree=FARMER_MEETING_PHOTO_UPLOAD.$farmermeeting->photo_three;
                    $farmermeeting->photopathfour=FARMER_MEETING_PHOTO_UPLOAD.$farmermeeting->photo_four;
                    $farmermeeting->photopathfive=FARMER_MEETING_PHOTO_UPLOAD.$farmermeeting->photo_five;
                    
                    
                    $distributordetails=$this->commonController->getDistributorNameById($farmermeeting->created_by);                        
                    $farmermeeting->dfname=$distributordetails->fname;
                    $farmermeeting->dmname=$distributordetails->mname;
                    $farmermeeting->dlname=$distributordetails->lname;
                    
                    $presentFarmer=explode(",",$farmermeeting->farmer_id);
                   // dd($presentFarmer);
                    foreach($presentFarmer as $key=>$farmermeetingPresentDist)
                    {
                        if($farmermeetingPresentDist!=''|| $farmermeetingPresentDist!=NULL || $farmermeetingPresentDist!=null)
                        {
                            $farmerdetails=$this->commonController->getFarmerNameById($farmermeetingPresentDist); 
                           if(!$farmerdetails) {
                                throw new Exception("Farmer Details Not Found");
                            }
                            $presentFarmerFormeeting .=++$key.")".$farmerdetails->fname." ".$farmerdetails->mname." ".$farmerdetails->lname;
                            $presentFarmerFormeeting .=",";
                            
                        }
                        
                    }
                    $farmermeeting->presentFarmerFormeeting=$presentFarmerFormeeting;
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            if ($farmerMeetingData)
            {
                 return response()->json([
                    "data" => $farmerMeetingData,
                    "result" => true,
                    "message" => 'Farmer Meeting Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmer Meeting Not Found'
                ]);
                
            }
        } catch(Exception $e) {
                return response()->json([
                        "data" => '',
                        "result" => false,
                        "error" => true,
                        "message" =>$e->getMessage()." ".$e->getCode()
                    ]);
               
        }
    }
    
    
     public function address_list(Request $request)
    {
        try
        {
            $result =Address::get();
            
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Address List Get'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Address Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }

    public function target_video_viewed_admin(Request $request)
    {
        try
        {
            
            $videowatched = TargetVideosToDistributor::
            leftJoin('usersinfo', function($join) {
                $join->on('tbl_target_videos_to_distributor.dist_id','=','usersinfo.user_id');
            })
            ->leftJoin('tbl_target_videos', function($join) {
                $join->on('tbl_target_videos_to_distributor.target_vedio_id','=','tbl_target_videos.id');
            })
            ->where(
                ['tbl_target_videos_to_distributor.is_watched'=>'yes'])
            ->get();

            if ($videowatched)
            {
                 return response()->json([
                    "data" => $videowatched,
                    "result" => true,
                    "message" => 'Video Watched Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Video Not Watched'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function target_video_watched_by_dist(Request $request)
    {
        try
        {
            
            $videowatched = TargetVideosToDistributor::where('id',$request->target_videos_to_distributor_id)
                                            ->update(['tbl_target_videos_to_distributor.is_watched'=>'yes']);

            if ($videowatched)
            {
                 return response()->json([
                    "data" => $videowatched,
                    "result" => true,
                    "message" => 'Video Watched Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Video Not Watched'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
   
}
