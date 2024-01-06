<?php

namespace App\Http\Controllers;
use Exception;
use JWTAuth;
use App\Task;
use Illuminate\Http\Request;
use App\Model\UsersInfo;
use App\Model\UsersProfile;
use App\User;
use App\Model\FarmerMeeting;
use App\Model\DistributorMeeting;
use App\Model\TargetVideos;
use App\Model\TargetVideosToDistributor;
use App\Model\FarmerVistByDistributor;
use App\Model\SCTResult;
use App\Model\WebAgency;
use App\Model\OrderSummary;
use App\Model\OrderDetail;
use App\Model\Subscriber;
use App\Model\SubscriberTarget; 
use App\Model\FrontProduct; 
use DB;
use App\Model\ProductDetails;
use App\Http\Controllers\CommonController As CommonController;

class DistributorMobileAppController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->commonController=new CommonController();
        $this->user = JWTAuth::parseToken()->authenticate();
    }
    
   
     public function allproductlist_mobileapp(Request $request)
    {
        try
        {
            
              $result = ProductDetails::leftJoin('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
              ->leftJoin('front_product','tbl_product.id','=','front_product.product_id')
          ->distinct('tbl_product.title')
            ->where('tbl_product_details.is_deleted','no')
            ->where('tbl_product.is_deleted','no')
             ->distinct('tbl_product.title')
            ->select('tbl_product_details.*','tbl_product.*','front_product.*')
            ->orderBy('tbl_product.id', 'DESC')
            ->get();
            
        
            // $result = DB::table('tbl_product')
            //         ->select('title','photo_one','id')
            //         ->distinct('title')
            //         // ->groupBy('id')
            //         ->where('is_deleted','no')
            //         ->get();
            
               $result = ProductDetails::leftJoin('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
              ->leftJoin('front_product','tbl_product.id','=','front_product.product_id')
          ->distinct('tbl_product.title')
            ->where('tbl_product_details.is_deleted','no')
            ->where('tbl_product.is_deleted','no')
             ->distinct('tbl_product.title')
            ->select('tbl_product_details.*','tbl_product.*','front_product.*')
            ->orderBy('tbl_product.id', 'DESC')
            ->get();

                foreach ($result as $key => $value) {
                    $front_product_details = FrontProduct::where('product_id',$value->id)->select('short_description','long_description')->first();
                    info($front_product_details);
                    $value->product_id = $value->id;
                    $value->short_description = $front_product_details ? $front_product_details->short_description  : '';
                    $value->long_description = $front_product_details ? $front_product_details->long_description : '';
                    $value->photopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
                    $data_count = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
                                                    ->where('tbl_product_details.is_deleted','no')
                                                    ->where('tbl_product.title',$value->title)
                                                    ->where('tbl_product.is_deleted','no')
                                                    ->orderBy('tbl_product.id', 'DESC')
                                                    ->get();
                    
                    $value->product_details = $data_count;
                }
            
            
           
            
            foreach($result as $key=>$value)
            {
                $value->photopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
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
    
    public function allproductlist_mobileapp_first(Request $request)
    {
        try
        {
        
            // $result = DB::table('tbl_product')
            //         ->select('title','photo_one','id')
            //         ->distinct('title')
            //         ->where('is_deleted','no')
            //         ->get();
            //     foreach ($result as $key => $value) {
            //         $front_product_details = FrontProduct::where('product_id',$value->id)->select('short_description','long_description')->first();
            //         info($front_product_details);
            //         $value->product_id = $value->id;
            //         $value->short_description = $front_product_details ? $front_product_details->short_description  : '';
            //         $value->long_description = $front_product_details ? $front_product_details->long_description : '';
            //         $value->photopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
            //         $data_count = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
            //                                         ->where('tbl_product_details.is_deleted','no')
            //                                         ->where('tbl_product.title',$value->title)
            //                                         ->where('tbl_product.is_deleted','no')
            //                                         ->orderBy('tbl_product.id', 'DESC')
            //                                         ->get();
                    
            //         $value->product_details = $data_count;
            //     }
            
            
              $result = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
              ->leftJoin('front_product','tbl_product.id','=','front_product.product_id')
           
            ->where('tbl_product_details.is_deleted','no')
            ->where('tbl_product.is_deleted','no')
             ->distinct('tbl_product.title')
            ->select('tbl_product_details.*','tbl_product.*','front_product.*')
            ->orderBy('tbl_product.id', 'DESC')
            ->get();
            
            foreach($result as $key=>$value)
            {
                $value->photopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
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
    
    public function allproductlist_mobileapp_new(Request $request)
    {
        try
        {
          $result =  ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
                                                    ->where('tbl_product_details.is_deleted','no')
                                                    ->where('tbl_product.is_deleted','no')
                                                    ->orderBy('tbl_product.id', 'DESC')
                                                    ->get();

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
     
     //Order
    public function orderadd_mobileapp(Request $request)
    {
        try
        {  
            
            $date=date("Y-m-d");
            $time= time();
            $tempid=$date.$time;
            $order_no=str_replace("-","",$tempid);
            $requestdata = $request;
            $ordrsummary = new OrderSummary();
            $ordrsummary->order_no = $order_no;
            $ordrsummary->order_date = date('Y-m-d');
            $ordrsummary->order_created_by = $requestdata->order_created_by;
            $ordrsummary->entry_by = 'distributor';
            $ordrsummary->order_cerated_for = $requestdata->order_cerated_for;
            $ordrsummary->order_cerated_for_id = $requestdata->order_cerated_for_id;
            $ordrsummary->created_disctributor_id = $requestdata->created_disctributor_id;
            $ordrsummary->created_disctributor_amount = $requestdata->created_disctributor_amount;
            $ordrsummary->remark = $requestdata->remark;
            $ordrsummary->save();
            //dd($requestdata->order_created_by);
            //$requestdata = $request;
            $allproduct=$requestdata->all_product;
            $allproductNew=json_decode($allproduct,true);
            $fsccommission_sum = 0;
            $bsccommission_sum = 0;
            $dsccommission_sum = 0;
            
            foreach($allproductNew as $key=>$prod_details)
            {
                $prodId = $prod_details['prod_id'];
                $ordcretby = $requestdata->order_created_by;
                
                if($ordcretby == 'fsc')
                {
                   
                    $proddetails = DB::select("SELECT farmer_price,fsc_price FROM `tbl_product_details` where product_id='$prodId' " );
                    $fsccommission = $proddetails[0]->farmer_price - $proddetails[0]->fsc_price;
                    $fsccommission_sum+= $fsccommission;
                }
                elseif($ordcretby == 'bsc')
                {
                   
                    $proddetails = DB::select("SELECT farmer_price,bsc_price FROM `tbl_product_details` where product_id='$prodId' " );
                    $bsccommission = $proddetails[0]->farmer_price - $proddetails[0]->bsc_price;
                    $bsccommission_sum+= $bsccommission;
                }
                elseif($ordcretby == 'dsc')
                {
                    $proddetails = DB::select("SELECT farmer_price,dsc_price FROM `tbl_product_details` where product_id='$prodId' " );
                    $dsccommission = $proddetails[0]->farmer_price - $proddetails[0]->dsc_price;
                    $dsccommission_sum+= $dsccommission;
                }
                
                $orderdetails = new OrderDetail();
                $orderdetails->order_no =$order_no;
                $orderdetails->prod_id = $prod_details['prod_id'];
                $orderdetails->qty = $prod_details['qty'];
                $orderdetails->rate_of_prod = $prod_details['rate_of_prod'];
                $orderdetails->final_amt = $prod_details['qty']*$prod_details['rate_of_prod'];
                $orderdetails->save();
            }
            $ord_cr_by = $requestdata->order_created_by;
            if($ord_cr_by == 'fsc')
            {
                $data=['forwarded_fsc_amount'=> $fsccommission_sum];
                $orderdetails = OrderSummary::where('order_no',$order_no)->update($data);  
            }
            elseif($ord_cr_by == 'bsc')
            {
                $data=['forwarded_bsc_amount'=> $bsccommission_sum];
                $orderdetails = OrderSummary::where('order_no',$order_no)->update($data);  
            }
            elseif($ord_cr_by == 'dsc')
            {
                $data=['forwarded_dsc_amount'=> $dsccommission_sum];
                $orderdetails = OrderSummary::where('order_no',$order_no)->update($data);  
            }
            
                
            if ($orderdetails)
            {
                 return response()->json([
                    "data" => array(),
                    "result" => true,
                    "message" => 'Information Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function orderupdate_mobileapp(Request $request)
    {
        try
        {
            $requestdata =$request;
            
            $allproduct=$requestdata->all_product;
            // $allproductOld=json_encode($allproduct);
            // $allproductNew=json_decode($allproductOld,true);
             $allproductNew=json_decode($allproduct,true);
            foreach($allproductNew as $key=>$prod_details)
            {
                 $data=[
                    'prod_id'=> $prod_details['prod_id'],
                    'qty'=>$prod_details['qty'],
                    'rate_of_prod'=>$prod_details['rate_of_prod'],
                    'final_amt' =>$prod_details['qty']*$prod_details['rate_of_prod']
                ];
                $orderdetail = OrderDetail::where('order_no',$requestdata->order_no)->where('prod_id',$prod_details['prod_id'])->update($data);       
            }
            
            if ($orderdetail)
            {
                 return response()->json([
                    "data" => $orderdetail,
                    "result" => true,
                    "message" => 'Information Updated Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information Not Updated'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function orderget_mobileapp(Request $request)
    {
       try
        {
            $result = OrderSummary::where('order_no',$request->order_no)
            ->where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_order_summary.is_deleted','no')->get();
        
            foreach($result as $key=>$value)
            {
                $value->all_product = OrderDetail::where('order_no',$request->order_no)->get();       
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
    
    public function orderdelete_mobileapp(Request $request)
    {
        try
        {
            $data=[
                'is_deleted'=>'yes',
            ];
            
            $user = OrderSummary::where('order_no',$request->order_no)
                    ->where('created_disctributor_id',$request->created_disctributor_id)
                    ->update($data);
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
                    "result" => true,
                    "message" => 'Information Deleted Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Information Not Deleted'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function orderlist_mobileapp(Request $request)
    {
        try
        {
             $result = OrderSummary::where('is_deleted','no')
                        ->where('created_disctributor_id',$request->created_disctributor_id)
                        ->orderBy('id','DESC')
                        ->get();
            
            foreach($result as $key=>$resultnew)
            {
                if($resultnew->account_approved=='no' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Pending';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Verified';
                }elseif( $resultnew->order_dispatched=='yes'){
                    $resultnew->status = 'Order Dispatched From Warehouse';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='yes'){
                    $resultnew->status = 'Forwaded to warehouse';
                }
                try
                {
                    $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                    $resultnew->fname=$details->fname;
                    $resultnew->mname=$details->mname;
                    $resultnew->lname=$details->lname;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
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
    
    public function orderview_mobileapp(Request $request)
    {
        try
        {
            $result = OrderSummary::where('order_no',$request->order_no)
            ->where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_order_summary.is_deleted','no')->get();
        
            foreach($result as $key=>$value)
            {
                if($value->account_approved=='no' && $value->forward_to_warehouse=='no'){
                    $value->status = 'Pending';
                }elseif($value->account_approved=='yes' && $value->forward_to_warehouse=='no'){
                    $value->status = 'Verified';
                }elseif( $value->order_dispatched=='yes') {
                    $value->status = 'Order Dispatched From Warehouse';
                }elseif($value->account_approved=='yes' && $value->forward_to_warehouse=='yes'){
                    $value->status = 'Forwaded to warehouse';
                }
                //$value->all_product = OrderDetail::where('order_no',$request->order_no)->get();
                
                $value->all_product = OrderDetail::where('tbl_order_detail.order_no',$request->order_no)
                                    ->where('tbl_order_detail.is_deleted','no')
                                    ->join('tbl_product','tbl_product.id','=','tbl_order_detail.prod_id')
                                    ->get();
                try
                {
                    $details=$this->commonController->getUserNameById($value->created_disctributor_id);                        
                    $value->fname=$details->fname;
                    $value->mname=$details->mname;
                    $value->lname=$details->lname;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                    
                    }

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
    
     public function orderdetail_mobileapp(Request $request)
    {
        try
        {
             $result = OrderSummary::join('tbl_order_detail','tbl_order_detail.order_no','=','tbl_order_summary.order_no')
            ->where('tbl_order_summary.order_no',$request->order_no)
            ->where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_order_summary.is_deleted','no')->get();

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
    
    
    public function target_video_viewed_mobileapp(Request $request)
    {
        
        try
        {
            $data=[
                'is_watched'=>'yes',
            ];
            
            $videowatched = TargetVideosToDistributor::where('target_vedio_id',$request->target_vedio_id)->where('dist_id',$request->dist_id)->update($data);
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
    
    
    
    public function target_video_not_viewed_mobileapp(Request $request)
    {
        try
        {
            $videonotwatched = TargetVideosToDistributor::where('dist_id',$request->dist_id)->where('is_deleted','no')->where('active','yes')->where('is_watched','no')->get();
            if($videonotwatched)
            {
                date_default_timezone_set('Asia/Kolkata');
                //$today = date("Y-m-d");
                $today = '2021-03-12';
                $vdate = $videonotwatched[0]->date;
                $videodate = \Carbon\Carbon::createFromFormat('Y-m-d', $vdate);
                echo $different_days = $videodate->diffInDays($today);
                
                if($different_days < 30)
                {
                    return response()->json([
                    "data" => $videonotwatched,
                    "result" => true,
                    "message" => 'No warning.'
                    ]);
                }
                
                if($different_days > 30 && $different_days < 60)
                {
                    return response()->json([
                    "data" => 'warning',
                    "result" => true,
                    "message" => 'You have Not Watched Videos since last One Month. Please watch videos as soon as possible.'
                    ]);
                }
                
                else if($different_days > 60 && $different_days < 90)
                {
                    return response()->json([
                    "data" => 'warning',
                    "result" => true,
                    "message" => 'You have Not Watched Videos since last Two Months. Please watch videos as soon as possible.'
                    ]);
                }
                
                else if($different_days > 90)
                {
                    return response()->json([
                    "data" => 'block',
                    "result" => true,
                    "message" => 'Contact to Manager or Sales Manager. Contact by Mail or Contact Number.'
                    ]);
                }
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Video Watched.'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    

    //New Profile Update Distributor
    public function add_profile_data_mobileapp(Request $request)
    {
        try{
            $user = false;
            $imagedataPath=DISTRIBUTOR_PROFILE_UPLOADS;
        
            if ( !is_dir( $imagedataPath) ) 
            {
                mkdir( $imagedataPath );       
            }
            $profile_photo = UsersProfile::where('user_id',$request->user_id)->first();
            $photoName=$request->user_id."_profile_photo";
            $inputfilenametoupload='profile_photo';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                if($profile_photo) {
                    $deletefrontfilename=$profile_photo->profile_photo;
                    $unlink_front_file_path=$imagedataPath.$deletefrontfilename;
                    if(!empty($unlink_front_file_path))
                    {
                        unlink($unlink_front_file_path);
                    }
                }
            
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                if($profile_photo) {
                    $user = UsersProfile::where( ['user_id' =>  $request->user_id])->update(
                        ['profile_photo' => $filename,'about' =>  $request->about]
                    );
                } else {
                    $user = UsersProfile::insert(
                        [ 'profile_photo' => $filename,
                            'about' =>  $request->about,
                            'user_id' =>  $request->user_id
                        ]
                    );
                }

            } else {
                $user = UsersProfile::where( ['user_id' =>  $request->user_id])->update(
                    ['about' =>  $request->about]
                );
            }
            
            if ($user)
            {
                return response()->json([
                    "data" => $user,
                    "result" => true,
                    "message" => 'Profile Updated By Distributor'
                ]);
            }
            else
            {
                return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Profile Not Updated'
                ]);
                
            }

        }catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        
    }
    

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
    

    public function edit_profile_data_mobileapp(Request $request)
    {
        try{
          
            $profile_photo = UsersProfile::leftJoin('usersinfo', function($join) {
                $join->on('usersinfo.user_id', '=', 'tbl_user_profile.user_id');
              })
              ->where('tbl_user_profile.user_id',$request->user_id)
              ->select('tbl_user_profile.profile_photo',
                        'tbl_user_profile.about',
                        'usersinfo.fname',
                        'usersinfo.mname',
                        'usersinfo.lname',
                        'usersinfo.phone'

                // DB::raw("CONCAT('".DISTRIBUTOR_PROFILE_VIEW."','tbl_user_profile.profile_photo') AS profile_photo")
              )->first();
            $profile_photo->profile_photo = DISTRIBUTOR_PROFILE_VIEW.$profile_photo->profile_photo;
            if ($profile_photo)
            {
                return response()->json([
                    "data" => $profile_photo,
                    "result" => true,
                    "message" => 'Profile Details Get Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Profile Details Not Found'
                ]);
                
            }

        }catch(Exception $e) {
            echo 'Message: ' .$e->getMessage();
        }
        
    }

    public function update_profile_data_mobileapp(Request $request)
    {
        try{
            $user = false;
            $imagedataPath=DISTRIBUTOR_PROFILE_UPLOADS;
        
            if ( !is_dir( $imagedataPath) ) 
            {
                mkdir( $imagedataPath );       
            }
            $profile_photo = UsersProfile::where('user_id',$request->user_id)->first();
            $photoName=$request->user_id."_profile_photo";
            $inputfilenametoupload='profile_photo';
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                if($profile_photo) {
                    $deletefrontfilename=$profile_photo->profile_photo;
                    $unlink_front_file_path=$imagedataPath.$deletefrontfilename;
                    if(!empty($unlink_front_file_path))
                    {
                        unlink($unlink_front_file_path);
                    }
                }
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $submit_array = array();
                if (!empty($request->hasFile($inputfilenametoupload))) {
                    $submit_array['profile_photo']  = $filename;
                }

                if(isset($request->about)) {
                    $submit_array['about']  = $request->about;
                }
                // return $submit_array;
                if($profile_photo) {
                                       
                    $user = UsersProfile::where( ['user_id' =>  $request->user_id])->update(
                        $submit_array
                    );
                } else {
                    $user = UsersProfile::where([
                        'user_id' =>  $request->user_id] )
                        ->update(
                         $submit_array
                    );
                }

            } else {
                $user = UsersProfile::where( ['user_id' =>  $request->user_id])->update(
                    ['about' =>  $request->about]
                );
            }
            
        
            return response()->json([
                "data" => $user,
                "result" => true,
                "message" => 'Profile Updated By Distributor'
            ]);
        

        }catch(Exception $e) {
            return response()->json([
                "data" => '',
                "result" => false,
                "message" => $e->getMessage()
            ]);
        }
        
    }
    
   
}
