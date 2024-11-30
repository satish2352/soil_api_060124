<?php

namespace App\Http\Controllers;
use App\Task;
use Illuminate\Http\Request;
use App\Models\UsersInfo;
use App\Models\User;
use App\Http\Controllers\CommonController As CommonController;
use DB;
use File;
use Image;
class FarmerController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->commonController=new CommonController();
    }
    
     public function processUpload(Request $request, $user, $file_name)
     {
         if ($request->hasFile($file_name)) {
          //$destinationPath      = "public/uploads/farmer/farmerphoto";
          $destinationPath      = FARMER_PHOTO_UPLOAD;
          $fileName = $user->id.'-'.$file_name.'.'.$request->$file_name->guessClientExtension();
          $request->file($file_name)->move($destinationPath, $fileName);
          return $fileName;
        }
     }

    public function deleteFile($user, $path, $is_array = FALSE)
    {
        $files = array();
        $files[] = $path.$user;
        File::delete($files);
    }


    public function farmerregistration_photo_update(Request $request)
    {
        try
        {

            $users ='';
            if ($request->farmerphoto)
            {
    
                $imagedataPath=FARMER_PHOTO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }

                $unlink_one_file_path=UsersInfo::where('user_id',$request->user_id)->first();;
                if($unlink_one_file_path->photo)
                {
                    unlink($imagedataPath.$unlink_one_file_path->photo);
                }
                
                $farmerPhotoName=$request->user_id."_farmerphoto"."_".rand(100000, 999999);
                if (!empty($request->farmerphoto))
                {     
                    $applpic_ext = $request->file('farmerphoto')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('farmerphoto'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$farmerPhotoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $users=UsersInfo::where('user_id',$request->user_id)->update(['photo'=>$farmerPhotoName.".".$applpic_ext]);
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
                    "result" => true,
                    "message" => 'Farmer Photo Updated Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmer Photo Not Updated'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
   
    public function farmerregistration(Request $request)
    {
        try
        {
            //dd($request->farmerphoto);
            $requestnew=json_decode($request->farmerData, true);
           
            $requestdata = (object)$requestnew;

            $user = new User();
            $user->name = $requestdata->fname." ".$requestdata->mname." ".$requestdata->lname." ";
            $user->email = $requestdata->email;
            $user->password = app('hash')->make('123456788');
            $user->visible_password ='123456788';
            $user->user_type ='farmer';
            $user->save();
            //$user->id;
    
            $users = new UsersInfo();
            $users->user_id = $user->id;
            $users->fname = $requestdata->fname;
            $users->mname = $requestdata->mname;
            $users->lname = $requestdata->lname;
            $users->aadharcard = $requestdata->aadharcard;
            $users->email = $requestdata->email;
            $users->phone = $requestdata->phone;
            $users->state = $requestdata->state;
            $users->district = $requestdata->district;
            $users->taluka = $requestdata->taluka;
            $users->city = $requestdata->city;
            $users->address = $requestdata->address;
            $users->pincode = $requestdata->pincode;
            $users->crop = $requestdata->crop;
            $users->acre = $requestdata->acre;
            //$users->photo = $farmerPhoto;
            $users->password = '123456789';//$requestdata->password;
            $users->user_type = 'farmer';
            //$users->photo = $farmerPhotoName;
            $users->is_deleted = 'no'; // 0 Means Active, 1 Means Delete
            $users->active = 'yes'; // 0 Means Active, 1 Means Inactive
            $users->added_by = 'superadmin'; // 0- from Superadmin 1- Distributor
            $users->remember_token = '';//$requestdata->token;
            $users->save();
            
            $file_name = 'farmerphoto';
            if ($request->farmerphoto)
            {
    
                $imagedataPath=FARMER_PHOTO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $farmerPhotoName=$user->id."_farmerphoto";
                if (!empty($request->farmerphoto))
                {     
                    $applpic_ext = $request->file('farmerphoto')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('farmerphoto'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$farmerPhotoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $user=UsersInfo::where('user_id',$user->id)->update(['photo'=>$farmerPhotoName.".".$applpic_ext]);
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
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    public function farmerlist(Request $request)
    {
        $result = UsersInfo::where('usersinfo.user_type', '=', 'farmer')
                            ->where('usersinfo.is_deleted', '=', 'no')
        
                
                // ->join('users','users.id','=','usersinfo.user_id')
                ->leftJoin('usersinfo AS sct_farmer','sct_farmer.user_id','=','usersinfo.user_id')
                ->leftJoin('usersinfo AS sct_dist','sct_dist.user_id','=','usersinfo.added_by')
                
                ->leftJoin('tbl_area as stateNew', function($join) {
                    $join->on('usersinfo.state', '=', 'stateNew.location_id');
                  })
                  
                  ->leftJoin('tbl_area as districtNew', function($join) {
                    $join->on('usersinfo.district', '=', 'districtNew.location_id');
                  })
                  
                  
                  ->leftJoin('tbl_area as talukaNew', function($join) {
                    $join->on('usersinfo.taluka', '=', 'talukaNew.location_id');
                  })
                  
                  ->leftJoin('tbl_area as cityNew', function($join) {
                    $join->on('usersinfo.city', '=', 'cityNew.location_id');
                  });

                //   if($request->added_by == 'superadmin') {
                //     info("Super Admin");
                //     $result =  $result->where('usersinfo.added_by','=', 'superadmin');

                // } else {
                //     info($request->added_by );
                //         info("in else Super Admin");
                //         $result = $result->where('usersinfo.added_by', '!=', 'speradmin');
                //         info('$result');
                //         info($result);

                // }


                if ($request->added_by == 'superadmin') {
                    $result = $result->where('usersinfo.added_by', '=', 'superadmin');
                } else {
                    $result = $result->whereNotIn('usersinfo.added_by', ['superadmin', 'dist']);
                }


                $result =  $result->when($request->get('state'), function($query) use ($request) {
                    $query->where('usersinfo.state',$request->state);
                  })
                  
                  ->when($request->get('district'), function($query) use ($request) {
                    $query->where('usersinfo.district',$request->district);
                  })
                  
                  ->when($request->get('taluka'), function($query) use ($request) {
                    $query->where('usersinfo.taluka',$request->taluka);
                  })
                  
                  ->when($request->get('city'), function($query) use ($request) {
                    $query->where('usersinfo.city',$request->city);
                  })
                  
                //   ->when($request->get('added_by'), function($query) use ($request) {
                //     $query->where('usersinfo.added_by',$request->added_by);
                //   })

                ->when($request->get('datefrom'), function($query) use ($request) {
                   $query->whereBetween('usersinfo.created_on', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                });
                
                
                
          
                $result =  $result->select('usersinfo.user_id',
             'sct_farmer.fname as sct_farmer_fname','sct_farmer.mname as as sct_farmer_mname','sct_farmer.lname as sct_farmer_lname',
             'sct_dist.fname as sct_dist_fname','sct_dist.mname as sct_dist_mname','sct_dist.lname as sct_dist_lname',
             'usersinfo.aadharcard','usersinfo.email','usersinfo.phone',
             'usersinfo.state','usersinfo.district','usersinfo.taluka','usersinfo.city',
             'usersinfo.address','usersinfo.pincode','usersinfo.crop','usersinfo.acre',
             'usersinfo.password','usersinfo.photo',
             'stateNew.name as state',
             'districtNew.name as district',
             'talukaNew.name as taluka',
             'cityNew.name as city'
             )->orderBy('usersinfo.id', 'DESC')
            ->get();

        if ($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Farmer List Get Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Farmer List Not Found';
            $response['result'] = false;
            return response()->json($response);
        }

    }

    
    
    
    // public function farmerList(Request $request)
    // {
    //     // Base query
    //     $result = UsersInfo::where('usersinfo.user_type', '=', 'farmer')
    //         ->where('usersinfo.is_deleted', '=', 'no')
    //         ->leftJoin('usersinfo AS sct_farmer', 'sct_farmer.user_id', '=', 'usersinfo.user_id')
    //         ->leftJoin('usersinfo AS sct_dist', 'sct_dist.user_id', '=', 'usersinfo.added_by')
    //         ->leftJoin('tbl_area as stateNew', 'usersinfo.state', '=', 'stateNew.location_id')
    //         ->leftJoin('tbl_area as districtNew', 'usersinfo.district', '=', 'districtNew.location_id')
    //         ->leftJoin('tbl_area as talukaNew', 'usersinfo.taluka', '=', 'talukaNew.location_id')
    //         ->leftJoin('tbl_area as cityNew', 'usersinfo.city', '=', 'cityNew.location_id');

    //     // Handling added_by filter
    //     if ($request->added_by == 'superadmin') {
    //         info("Super Admin");
    //         $result = $result->where('usersinfo.added_by', '=', 'superadmin');
    //     } else {
    //         info("Added by: " . $request->added_by);
    //         $result =$result->whereNotIn('usersinfo.added_by', ['superadmin', 'dist']);
            

    //     }

    //     // Dynamic filters
    //     $result =$result->when($request->get('state'), function ($query) use ($request) {
    //             $query->where('usersinfo.state', $request->state);
    //         })
    //         ->when($request->get('district'), function ($query) use ($request) {
    //             $query->where('usersinfo.district', $request->district);
    //         })
    //         ->when($request->get('taluka'), function ($query) use ($request) {
    //             $query->where('usersinfo.taluka', $request->taluka);
    //         })
    //         ->when($request->get('city'), function ($query) use ($request) {
    //             $query->where('usersinfo.city', $request->city);
    //         })
    //         ->when($request->get('added_by'), function ($query) use ($request) {
    //             $query->where('usersinfo.added_by', $request->added_by);
    //         })
    //         ->when($request->get('datefrom') && $request->get('dateto'), function ($query) use ($request) {
    //             $query->whereBetween('usersinfo.created_on', [$request->datefrom . ' 00:00:00', $request->dateto . ' 23:59:59']);
    //         });

    //     // Select statement
    //     $result = $result->select(
    //         'usersinfo.user_id',
    //         'sct_farmer.fname as sct_farmer_fname',
    //         'sct_farmer.mname as sct_farmer_mname',
    //         'sct_farmer.lname as sct_farmer_lname',
    //         'sct_dist.fname as sct_dist_fname',
    //         'sct_dist.mname as sct_dist_mname',
    //         'sct_dist.lname as sct_dist_lname',
    //         'usersinfo.aadharcard',
    //         'usersinfo.email',
    //         'usersinfo.phone',
    //         'usersinfo.state',
    //         'usersinfo.district',
    //         'usersinfo.taluka',
    //         'usersinfo.city',
    //         'usersinfo.address',
    //         'usersinfo.pincode',
    //         'usersinfo.crop',
    //         'usersinfo.acre',
    //         'usersinfo.password',
    //         'usersinfo.photo',
    //         'stateNew.name as state',
    //         'districtNew.name as district',
    //         'talukaNew.name as taluka',
    //         'cityNew.name as city'
    //     )
    //         ->orderBy('usersinfo.id', 'DESC')
    //         ->get();

    //     // Return response
    //     if ($result->isNotEmpty()) {
    //         return response()->json([
    //             'data' => $result,
    //             'code' => 200,
    //             'message' => 'Farmer List retrieved successfully',
    //             'result' => true,
    //         ]);
    //     } else {
    //         return response()->json([
    //             'code' => 400,
    //             'message' => 'Farmer List not found',
    //             'result' => false,
    //         ]);
    //     }
    // }

    public function farmerget(Request $request)
    {
        
         $result = UsersInfo::where('user_type', '=', 'farmer')->where('is_deleted', '=', 'no')->where('user_id', '=',$request->user_id )
             ->select('user_id','fname','mname','lname','aadharcard','email','phone','state','district','taluka','city','address','pincode','crop','acre','password','photo')
            ->get();
            
        foreach($result as $key=>$value)
        {
            $stateName=$this->commonController->getAreaNameById($value->state);
            $value->state_name=$stateName->name;
            
            $districtName=$this->commonController->getAreaNameById($value->district);
            $value->district_name=$districtName->name;
            
            $talukaName=$this->commonController->getAreaNameById($value->taluka);
            $value->taluka_name=$talukaName->name;
            
            $cityName=$this->commonController->getAreaNameById($value->city);
            $value->city_name=$cityName->name;
            
            // $value->photo_new=FARMER_PHOTO_VIEW.$value->photo;

            $value->photo = FARMER_PHOTO_VIEW.$value->photo;
        }
        //dd($result);
        if ($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Farmer List Get Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Farmer List Not Found';
            $response['result'] = false;
            return response()->json($response);
        }


    }
    
    public function farmerdetails(Request $request)
    {
         $result = UsersInfo::where('user_type', '=', 'farmer')->where('is_deleted', '=', 'no')->where('user_id', '=',$request->user_id )
             ->select('user_id','fname','mname','lname','aadharcard','email','phone','state','district','taluka','city','address','pincode','crop','acre','password','photo')
            ->get();
            
        foreach($result as $key=>$value)
        {
            $stateName=$this->commonController->getAreaNameById($value->state);
            $value->state=$stateName->name;
            
            $districtName=$this->commonController->getAreaNameById($value->district);
            $value->district=$districtName->name;
            
            $talukaName=$this->commonController->getAreaNameById($value->taluka);
            $value->taluka=$talukaName->name;
            
            $cityName=$this->commonController->getAreaNameById($value->city);
            $value->city=$cityName->name;
            
            $value->photo=FARMER_PHOTO_VIEW.$value->photo;
        }
        //dd($result);
        if ($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Farmer Details Get Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Farmer Not Found';
            $response['result'] = false;
            return response()->json($response);
        }


    }
    
    
    
    
    
    public function farmergetdetails(Request $request)
    {
        $result = UsersInfo::where('user_type', '=', 'farmer')
                            ->where('is_deleted', '=', 'no')
                            ->where('user_id', '=',$request->user_id )
                            ->select('user_id','fname','mname','lname','aadharcard','email','phone','state','district','taluka','city','address','pincode','crop','acre','password','photo')
                            ->get();
        
        foreach($result as $key=>$value)
        {
            $stateName=$this->commonController->getAreaNameById($value->state);
            $value->state=$stateName->name;
            
            $districtName=$this->commonController->getAreaNameById($value->district);
            $value->district=$districtName->name;
            
            $talukaName=$this->commonController->getAreaNameById($value->taluka);
            $value->taluka=$talukaName->name;
            
            $cityName=$this->commonController->getAreaNameById($value->city);
            $value->city=$cityName->name;
            
            $value->photo=FARMER_PHOTO_VIEW.$value->photo;
        }
        if (count($result) > 0)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Farmer List Get Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Farmer List Not Found';
            $response['result'] = false;
            return response()->json($response);
        }

    }
    
    public function farmerdelete(Request $request)
    {
        $farmerdelete = ['is_deleted' => 'yes'];
        $result = UsersInfo::where('user_id', '=',$request->user_id )->update($farmerdelete);

        if ($result)
        {
            // return response()->json([
            //     "data" => $result,
            //     "result" => true,
            //     "message" => 'Farmer Deleted Successfully'
            // ]);
            
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Farmer Deleted Successfully';
            $response['result'] = true;
            return response()->json($response);
            
        }
        else
        {
            return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Farmer Not Deleted'
            ]);
        
        }

    }

    public function farmerupdate(Request $request)
    {
        $farmerupdatedata = ['fname' => $request->fname, 'mname' => $request->mname, 'lname' => $request->lname, 'aadharcard' => $request->aadharcard, 'email' => $request->email, 'phone' => $request->phone, 'state' => $request->state, 'district' => $request->district, 'taluka' => $request->taluka, 'city' => $request->city, 'address' => $request->address, 'pincode' => $request->pincode, 'crop' => $request->crop, 'acre' => $request->acre];
        $result = UsersInfo::where('user_id', '=', $request->user_id)->update($farmerupdatedata);
        $farmerupdatedataNew = ['name' => $request->fname." ".$request->mname." ".$request->lname." "];
        $result = User::where('id', '=', $request->user_id)->update($farmerupdatedataNew);
        if ($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Farmer Updated Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Farmer Not Updated';
            $response['result'] = false;
            return response()->json($response);
        }

    }

    public function farmeractiveinactive(Request $request)
    {
        $value = $request->value;
        if ($value == 0)
        {
            $actInacValue = 'no';
        }
        else
        {
            $actInacValue = 'yes';
        }

        $farmeractiveinactive = ['activeinactive' => $actInacValue];
        $id = $request->id;
        $result = UsersInfo::where('id', '=', $id)->update($farmeractiveinactive);

        if (count($result) > 0 && $actInacValue == 0)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Farmer Activated Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        elseif (count($result) > 0 && $actInacValue == 1)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Farmer Inactivated Successfully';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Farmer Not Changed Any Status';
            $response['result'] = false;
            return response()->json($response);
        }
        
    }
   
}