<?php

namespace App\Http\Controllers;
use Exception;
use App\Task;
use Illuminate\Http\Request;
use App\Models\ {
    FrontProduct,
    UsersInfoForStructures,
    SubscriberTarget,
    UsersInfo,
    UsersProfile,
    User,
    FarmerMeeting,
    DistributorMeeting,
    TargetVideos,
    TargetVideosToDistributor,
    FarmerVistByDistributor,
    SCTResult,
    WebAgency,
    OrderSummary,
    OrderDetail,
    Subscriber,
    ProductDetails
}; 
use DB;
use App\Http\Controllers\CommonController As CommonController;

class DistributorMobileAppController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->commonController=new CommonController();
    }
    
   
     public function allproductlist_mobileapp(Request $request)
    {
        try
        {
            
        //       $result = ProductDetails::leftJoin('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
        //       ->leftJoin('front_product','tbl_product.id','=','front_product.product_id')
        //   ->distinct('tbl_product.title')
        //     ->where('tbl_product_details.is_deleted','no')
        //     ->where('tbl_product.is_deleted','no')
        //      ->distinct('tbl_product.title')
        //     ->select('tbl_product_details.*','tbl_product.*','front_product.*')
        //     ->orderBy('tbl_product.id', 'DESC')
        //     ->get();
            
        
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
                    // info($front_product_details);
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

            $forwarded_bsc_id = 0;
            $forwarded_dsc_id = 0;
            $is_order_confirm_from_dist = 'no';
            if($request->order_created_by =='fsc') {
                // To find added by 
                $forwarded_bsc_id_data = UsersInfoForStructures::where([
                                        'user_id'=>$request->created_disctributor_id,
                                        // 'user_type'=>'fsc',
                                    ])->select('added_by','user_type')->first(); 


                if($forwarded_bsc_id_data) {
                    $forwarded_bsc_id = $forwarded_bsc_id_data->added_by;
                } else {
                    $forwarded_bsc_id = 0;
                }


                $forwarded_dsc_id = UsersInfoForStructures::where([
                                    'user_id'=>$forwarded_bsc_id,
                                    // 'user_type'=>'bsc',
                                ])->select('added_by','user_type')->first() ; 
                if($forwarded_dsc_id) {
                    $forwarded_dsc_id = $forwarded_dsc_id->added_by;
                } else {
                    $forwarded_dsc_id = 0;
                }
               
            } elseif($request->order_created_by =='bsc')  {
                $forwarded_dsc_id = UsersInfoForStructures::where([
                                        'user_id'=>$request->created_disctributor_id,
                                        // 'user_type'=>'bsc',
                                    ])->select('added_by','user_type')->first() ; 
                if($forwarded_dsc_id) {
                    $forwarded_dsc_id = $forwarded_dsc_id->added_by;

                } else {
                    $forwarded_dsc_id = 0;

                }
            } 

            if($forwarded_dsc_id == '0' && $forwarded_bsc_id == '0') {
                $is_order_confirm_from_dist = 'yes';
            } else {
                $is_order_confirm_from_dist = 'no';
            }


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
            $ordrsummary->forwarded_bsc_id = $forwarded_bsc_id;
            $ordrsummary->forwarded_dsc_id = $forwarded_dsc_id;
            $ordrsummary->order_cerated_for_id = $requestdata->order_cerated_for_id;
            $ordrsummary->created_disctributor_id = $requestdata->created_disctributor_id;
            $ordrsummary->created_disctributor_amount = $requestdata->created_disctributor_amount;
            $ordrsummary->remark = isset($requestdata->remark) ? $requestdata->remark :'NA';
            $ordrsummary->is_order_confirm_from_dist = $is_order_confirm_from_dist;
            $ordrsummary->payment_mode = $requestdata->payment_mode;
            $ordrsummary->address_one = $requestdata->address_one;
            $ordrsummary->address_two = $requestdata->address_two;
            if($request->order_created_by == 'dsc')  {
                $ordrsummary->is_order_confirm_from_dsc = 'yes';
                $ordrsummary->is_order_final_confirm = 'yes';
                $ordrsummary->is_order_confirm_from_dist = 'yes';
            } else if($request->order_created_by == 'bsc')  {
                $ordrsummary->is_order_confirm_from_bsc = 'yes';
            }
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
          return response()->json([
            "data" => '',
            "result" => false,
            "message" => 'Message: ' .$e->getMessage()
        ]);
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


    public function order_dist_confirm_mobileapp(Request $request)
    {
       try
        {
            
            $result = OrderSummary::where('order_no',$request->order_no);
            if($request->user_type == 'bsc') {
                $result = $result->update([
                    'is_order_confirm_from_bsc' => 'yes',
                ]);
            } else  if($request->user_type =='dsc') {
                $result = $result->update([
                                'is_order_confirm_from_dsc' => 'yes',
                                'is_order_final_confirm' => 'yes'
                            ]);
            }
            
            if ($result)
            {
                 return response()->json([
                    "data" => array(),
                    "result" => true,
                    "message" => 'Information updated Successfully'
                ]);
            }
            else
            {
                return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Information not updated'
                ]);
                
            }
        }
        catch(Exception $e) {

          return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Message: ' .$e->getMessage()
            ]);
        
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

                        ->when($request->get('created_disctributor_id'), function($query) use ($request) {
                            $query->where('created_disctributor_id', $request->created_disctributor_id);
                        }) 
                        ->when($request->get('datefrom'), function($query) use ($request) {
                            $query->whereBetween('created_at', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                        }) 

                        ->when($request->get('order_no'), function($query) use ($request) {
                            $query->where('order_no',  'LIKE %'.$request->order_no.'%');
                        }) 
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

    

    
    public function orderlist_from_other_dist_mobileapp(Request $request)
    {
        try
        {

            $userinfo = UsersInfo::where('user_id',$request->dist_id)->first();
             $result = OrderSummary::
             
                            leftJoin('usersinfo', function($join) {
                                $join->on('usersinfo.user_id', '=', 'tbl_order_summary.forwarded_bsc_id');
                            })
                            
                            ->leftJoin('usersinfo as newuser_table', function($join) {
                                $join->on('newuser_table.user_id', '=', 'tbl_order_summary.forwarded_dsc_id');
                            })
                            
                            ->when($request->dist_id, function($query) use ($request) {
                                $query->where('tbl_order_summary.forwarded_bsc_id',$request->dist_id)
                                ->orWhere('tbl_order_summary.forwarded_dsc_id',$request->dist_id);
                            }) 
                            ->when($request->fromdate, function($query) use ($request) {
                                $query->where('tbl_order_summary.created_at','>=',$request->fromdate." 00:00:00")
                              ->where('tbl_order_summary.created_at','<=',$request->todate." 23:59:59");
                            }) 

                            ->when($request->order_no, function($query) use ($request) {
                                $query->where('tbl_order_summary.order_no','like', '%' . $request->order_no . '%' );
                            });
                            

                            if($userinfo->user_type =='dsc') {
                                $result = $result->where('tbl_order_summary.is_order_confirm_from_bsc','yes');
                            }

                            $result = $result->where('tbl_order_summary.is_order_confirm_from_dist','no')
                            ->where('tbl_order_summary.is_deleted','no')
                           
                            
                            ->select(
                                'usersinfo.fname as fname_new',
                                'usersinfo.mname as mname_new',
                                'usersinfo.lname as lname_new',
                                'usersinfo.phone as phone_new',

                                'newuser_table.fname',
                                'newuser_table.mname',
                                'newuser_table.lname',
                                'newuser_table.phone',
                                'tbl_order_summary.*'
                            )
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
            $result = OrderSummary::
            leftJoin('tbl_order_detail', function($join) {
                $join->on('tbl_order_summary.order_no', '=', 'tbl_order_detail.order_no');
            })
          
            ->leftJoin('tbl_product', function($join) {
                $join->on('tbl_order_detail.prod_id', '=', 'tbl_product.id');
            })
            ->leftJoin('usersinfo as newuser_table', function($join) {
                $join->on('newuser_table.user_id', '=','tbl_order_summary.created_disctributor_id');
            })
            ->where('tbl_order_summary.order_no',$request->order_no)
            // ->where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_order_summary.is_deleted','no')
            ->select(
                'tbl_order_summary.id',
                'tbl_order_summary.order_no',
                'tbl_order_summary.order_date',
                'tbl_order_summary.order_created_by',
                'tbl_order_summary.created_disctributor_id',
                'tbl_order_summary.created_disctributor_amount',
                'tbl_order_summary.dispatched_to_created_disctributor_by_warehouse',
                'tbl_order_summary.forwarded_bsc_id',
                'tbl_order_summary.forwarded_bsc_amount',
                'tbl_order_summary.dispatched_to_forwarded_bsc_by_warehouse',
                'tbl_order_summary.forwarded_dsc_id',
                'tbl_order_summary.forwarded_dsc_amount',
                'tbl_order_summary.dispatched_to_forwarded_dsc_amount_by_warehouse',
                'tbl_order_summary.account_approved',
                'tbl_order_summary.forward_to_warehouse',
                'tbl_order_summary.entry_by',
                'tbl_order_summary.order_dispatched',
                'tbl_order_summary.order_dispatched_date',
                'tbl_order_summary.is_deleted',
                'tbl_order_summary.created_at',
                'tbl_order_summary.updated_at',
                'tbl_order_summary.is_order_confirm_from_bsc',
                'tbl_order_summary.date_confirm_from_bsc',
                'tbl_order_summary.is_order_confirm_from_dsc',
                'tbl_order_summary.date_confirm_from_dsc',
                'tbl_order_summary.is_order_confirm_from_dist',
                'tbl_order_summary.date_confirm_from_dist',
                'tbl_order_summary.payment_mode',
                'newuser_table.fname',
                'newuser_table.mname',
                'newuser_table.lname',
                'newuser_table.phone',

                
                
            )
            ->get();
        
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
                                    ->leftJoin('tbl_product_details', function($join) {
                                        $join->on('tbl_order_detail.prod_id', '=', 'tbl_product_details.id');
                                    })
                                    ->where('tbl_order_detail.is_deleted','no')
                                    ->join('tbl_product','tbl_product.id','=','tbl_order_detail.prod_id')
                                    ->get();
              

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
                    "data" => array(),
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

    

    public function distributor_added_by_me(Request $request)
    {
        $result = UsersInfoForStructures::join('usersinfo', function($join) {
            $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
        })
        ->leftJoin('tbl_area as shopState', function ($join) {
            $join->on('usersinfo.shop_state', '=', 'shopState.location_id');
        })
        ->leftJoin('tbl_area as shopDistrict', function ($join) {
            $join->on('usersinfo.shop_district', '=', 'shopDistrict.location_id');
        })
        ->leftJoin('tbl_area as shopTaluka', function ($join) {
            $join->on('usersinfo.shop_taluka', '=', 'shopTaluka.location_id');
        })
        ->leftJoin('tbl_area as shopCity', function ($join) {
            $join->on('usersinfo.shop_village', '=', 'shopCity.location_id');
        })
      
           
            ->join('tbl_area as stateNew', function($join) {
                $join->on('usersinfo.state', '=', 'stateNew.location_id');
            })
          ->join('tbl_area as districtNew', function($join) {
            $join->on('usersinfo.district', '=', 'districtNew.location_id');
          })
          
          
          ->join('tbl_area as talukaNew', function($join) {
            $join->on('usersinfo.taluka', '=', 'talukaNew.location_id');
          })
          
          ->join('tbl_area as cityNew', function($join) {
            $join->on('usersinfo.city', '=', 'cityNew.location_id');
          })
          ->join('users','users.id','=','usersinfo.user_id')
         
          ->where('users_info_for_structures.added_by',$request->dist_id)
         ->select('stateNew.name as state',
         'districtNew.name as district',
         'talukaNew.name as taluka',
         'cityNew.name as city',
         'usersinfo.id as id',
        'usersinfo.user_id',
        'usersinfo.name',
        'usersinfo.fname',
        'usersinfo.mname',
        'usersinfo.lname',
        'usersinfo.email',
        'usersinfo.phone',
        'usersinfo.aadharcard',
        // 'usersinfo.state',
        // 'usersinfo.district',
        // 'usersinfo.taluka',
        // 'usersinfo.city',
        'usersinfo.address',
        'usersinfo.pincode',
        'usersinfo.crop',
        'usersinfo.acre',
        'usersinfo.password',
        'usersinfo.visible_password',
        'usersinfo.photo',
        'usersinfo.is_sms_send',
        'usersinfo.notification',
        'usersinfo.user_type',
        'usersinfo.shop_name',
        'usersinfo.total_area',
        'usersinfo.other_bussiness',
        'usersinfo.is_deleted',
        'usersinfo.active',
        'usersinfo.remember_token',
        'usersinfo.otp',
        'usersinfo.is_verified',
        'usersinfo.occupation',
        'usersinfo.education',
        'usersinfo.exp_in_agricultural',
        'usersinfo.other_distributorship',
        'usersinfo.reference_from',
        'usersinfo.shop_location',
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.aadhar_card_image_front) as aadhar_card_image_front"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.aadhar_card_image_back) as aadhar_card_image_back"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.pan_card) as pan_card"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.light_bill) as light_bill"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.shop_act_image) as shop_act_image"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.product_purchase_bill) as product_purchase_bill"),
        // 'usersinfo.aadhar_card_image_front',
        // 'usersinfo.aadhar_card_image_back',
        // 'usersinfo.pan_card',
        // 'usersinfo.light_bill',
        // 'usersinfo.shop_act_image',
        // 'usersinfo.product_purchase_bill',
        'usersinfo.geolocation',
        'usersinfo.added_by',
        'usersinfo.devicetoken',
        'usersinfo.devicetype',
        'usersinfo.devicename',
        'usersinfo.deviceid',
        'usersinfo.logintime',
        'usersinfo.created_by',
        'usersinfo.created_on',

        'usersinfo.shop_address',
        'usersinfo.shop_state',
        'shopState.name as shop_state',
        'usersinfo.shop_district',
        'shopDistrict.name as shop_district',
        'usersinfo.shop_taluka',
        'shopTaluka.name as shop_taluka',
        'usersinfo.shop_village',
        'shopCity.name as shop_city',
              
         )
          ->get();
      
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
            $response['data'] = array();
            $response['code'] = 400;
            $response['message'] = 'Distributor List Not Found';
            $response['result'] = false;
            return response()->json($response);
        }

    }


    public function distributor_added_by_me_get(Request $request)
    {

       
        $result = UsersInfoForStructures::
            join('usersinfo', function($join) {
                $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
            })
            ->join('tbl_area as stateNew', function($join) {
                $join->on('usersinfo.state', '=', 'stateNew.location_id');
            })
          
          ->join('tbl_area as districtNew', function($join) {
            $join->on('usersinfo.district', '=', 'districtNew.location_id');
          })
          
          
          ->join('tbl_area as talukaNew', function($join) {
            $join->on('usersinfo.taluka', '=', 'talukaNew.location_id');
          })
          
          ->join('tbl_area as cityNew', function($join) {
            $join->on('usersinfo.city', '=', 'cityNew.location_id');
          })
          ->leftJoin('tbl_area as shopState', function ($join) {
            $join->on('users_info_for_structures.shop_state', '=', 'shopState.location_id');
        })
        ->leftJoin('tbl_area as shopDistrict', function ($join) {
            $join->on('users_info_for_structures.shop_district', '=', 'shopDistrict.location_id');
        })
        ->leftJoin('tbl_area as shopTaluka', function ($join) {
            $join->on('users_info_for_structures.shop_taluka', '=', 'shopTaluka.location_id');
        })
        ->leftJoin('tbl_area as shopCity', function ($join) {
            $join->on('users_info_for_structures.shop_village', '=', 'shopCity.location_id');
        })
          ->join('users','users.id','=','usersinfo.user_id')
          ->where('users_info_for_structures.added_by',$request->dist_id)
          ->where('users_info_for_structures.user_id',$request->user_id)
         ->select('stateNew.name as state',
         'districtNew.name as district',
         'talukaNew.name as taluka',
         'cityNew.name as city',
         'usersinfo.id as id',
        'usersinfo.user_id',
        'usersinfo.name',
        'usersinfo.fname',
        'usersinfo.mname',
        'usersinfo.lname',
        'usersinfo.email',
        'usersinfo.phone',
        'usersinfo.aadharcard',
        'usersinfo.state',
        'usersinfo.district',
        'usersinfo.taluka',
        'usersinfo.city',
        'usersinfo.address',
        'usersinfo.pincode',
        'usersinfo.crop',
        'usersinfo.acre',
        'usersinfo.password',
        'usersinfo.visible_password',
        'usersinfo.photo',
        'usersinfo.is_sms_send',
        'usersinfo.notification',
        'usersinfo.user_type',
        'usersinfo.shop_name',
        'usersinfo.total_area',
        'usersinfo.other_bussiness',
        'usersinfo.is_deleted',
        'usersinfo.active',
        'usersinfo.remember_token',
        'usersinfo.otp',
        'usersinfo.is_verified',
        'usersinfo.occupation',
        'usersinfo.education',
        'usersinfo.exp_in_agricultural',
        'usersinfo.other_distributorship',
        'usersinfo.reference_from',
        'usersinfo.shop_location',
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.aadhar_card_image_front) as aadhar_card_image_front"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.aadhar_card_image_back) as aadhar_card_image_back"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.pan_card) as pan_card"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.light_bill) as light_bill"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.shop_act_image) as shop_act_image"),
        DB::raw("CONCAT('" . DISTRIBUTOR_OWN_DOCUMENTS_VIEW . "', usersinfo.product_purchase_bill) as product_purchase_bill"),
        // 'usersinfo.aadhar_card_image_front',
        // 'usersinfo.aadhar_card_image_back',
        // 'usersinfo.pan_card',
        // 'usersinfo.light_bill',
        // 'usersinfo.shop_act_image',
        // 'usersinfo.product_purchase_bill',
        'usersinfo.geolocation',
        'usersinfo.added_by',
        'usersinfo.devicetoken',
        'usersinfo.devicetype',
        'usersinfo.devicename',
        'usersinfo.deviceid',
        'usersinfo.logintime',
        'usersinfo.created_by',
        'usersinfo.created_on',
        'shopState.name as shop_state',
         'shopDistrict.name as shop_district',
         'shopTaluka.name as shop_taluka',
         'shopCity.name as shop_city',
         )
          ->get();


          foreach( $result as $key => $value ) {


            $imagedataPath=DISTRIBUTOR_OWN_DOCUMENTS;
            
            $value->aadhar_card_image_front_path = $imagedataPath.$value->aadhar_card_image_front;
            $value->aadhar_card_image_back_path = $imagedataPath.$value->aadhar_card_image_back;
            $value->pan_card_path = $imagedataPath.$value->pan_card;
            $value->light_bill_path = $imagedataPath.$value->light_bill;
            $value->shop_act_image_path = $imagedataPath.$value->shop_act_image;
            $value->product_purchase_bill_path = $imagedataPath.$value->product_purchase_bill;

              
          }
      
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
            $response['data'] = array();
            $response['code'] = 400;
            $response['message'] = 'Distributor List Not Found';
            $response['result'] = false;
            return response()->json($response);
        }

    }
    
    
    public function distributor_update_new(Request $request)
    {
        try
        {
            $user = User::where(['id'=> $request->user_id]);
            $user->name = ucwords($request->fname)." ".ucwords($request->mname)." ".ucwords($request->lname)." ";
            $user->email = $request->email;
            $user->save();
            
            $users =  UsersInfo::where(['user_id'=> $request->user_id]);
            $users->fname = $request->fname;
            $users->mname = $request->mname;
            $users->lname = $request->lname;
            $users->aadharcard = $request->aadharcard;
            $users->pincode = $request->pincode;
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
            $users->reference_from = $request->created_by;
            $users->shop_location = $request->shop_location;
            $users->save();      
             
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
                        "message" => "Some data missing or duplicate 183"
                    ]);
               
        }

    }
    
   
}
