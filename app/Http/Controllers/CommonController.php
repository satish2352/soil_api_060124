<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Area;
use App\Models\User;
use App\Models\UsersInfo;
use App\Models\Downloads;
use Response;
use App\Models\LoginDetail;

class CommonController extends Controller
{

    public function checkemailexist(Request $request)
    {
        $result=User::where('email',$request->email)->select('email')->first();
        if ($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Email Already Exist';
            $response['result'] = true;
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Email Not Exist';
            $response['result'] = false;
            return response()->json($response);
        }
    }

    public function statelist(Request $request)
    {
        $result = Area::where('location_type', '=', 1)->orderBy('name', 'ASC')->get();

        if (count($result) > 0)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'State List Get Successfully';
            $response['result'] = 'true';
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'State List Not Found';
            $response['result'] = 'false';
            return response()->json($response);
        }
    }
    
    public function districtlist(Request $request)
    {
    
        $result = Area::where('location_type', '=', 2)->where('parent_id', '=', $request->state_id)->orderBy('name', 'ASC')
            ->get();

        if (count($result) > 0)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'District List Get Successfully';
            $response['result'] = 'true';
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'District List Not Found';
            $response['result'] = 'false';
            return response()->json($response);
        }
           
    }

    public function talukalist(Request $request)
    {

        $result = Area::where('location_type', '=', 3)->where('parent_id', '=', $request->dist_id)->orderBy('name', 'ASC')
            ->get();

        if (count($result) > 0)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Taluka List Get Successfully';
            $response['result'] = 'true';
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Taluka List Not Found';
            $response['result'] = 'false';
            return response()->json($response);
        }
         
    }

    public function villagelist(Request $request)
    {

        $result = Area::where('location_type', '=', 4)->where('parent_id', '=', $request->taluka_id)->orderBy('name', 'ASC')
            ->get();

        if (count($result) > 0)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Village List Get Successfully';
            $response['result'] = 'true';
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Village List Not Found';
            $response['result'] = 'false';
            return response()->json($response);
        }
        
    }
    
    
    
    public function downloadcontentlist(Request $request)
    {

        // $result = Downloads::where('language', '=', $request->lang)->where('status', '=', 0)->where('content_type', '=', $request->content_type )->get();
        $result = Downloads::where('language', 'like', '%' . $request->lang . '%')->where('status', '=', 0)->where('content_type', '=', $request->content_type )->get();

        if ($result)
        {
            $response = array();
            $response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Download List Get Successfully';
            $response['result'] = 'true';
            return response()->json($response);
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Download List Not Found';
            $response['result'] = 'false';
            return response()->json($response);
        }
        
    }
    
    public function downloadcontent(Request $request)
    {

        $content = Downloads::where('id', '=', $request->content_id)->first();
        
        $path=DOWNLAOD_CONTENT_DOWNLAOD.$content->content_name;
        $filename=$content->content_name;
        //  $headers = array(
        //       'Content-Type: application/pdf',
        //     );

        //return Response::download(public_path()."/uploads/downloads/".$filename, $filename, $headers);
        
        
        //$path = public_path() . '/uploads/downloads/'.$filename;
    //$file->move($path, $file->getClientOriginalName());
    
    
        if($path)
        {
            $response = array();
            //$response['data'] = $result;
            $response['code'] = 200;
            $response['message'] = 'Content Downloaded Get Successfully';
            //$response['result'] = 'true';
            //return response()->json($response);
            return response()->json(compact('path'));
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Download Content Not Found';
            $response['result'] = 'false';
            return response()->json($response);
        }
        
    }
    
    public function getAreaNameById($id)
    {
        $result = Area::where('location_id', '=', $id)->first();
        return $result;
    }
    
    public function getFarmerNameById($id)
    {
        // dd($id);
        // die();
        $farmerdetails=UsersInfo::where('user_type', '=', 'farmer')
                                        ->where('user_id',$id)
                                        ->first();
        //dd($farmerdetails);
        return $farmerdetails;
    }
    
    public function getDistributorNameById($id)
    {
        //dd($id);
         $distributordetails=UsersInfo::whereIn('user_type',['fsc','bsc','dsc'])
                                        ->where('user_id', '=',$id)
                                        ->first();
                                         //dd($distributordetails);
        return $distributordetails;
    }
    
     public function getUserNameById($id)
    {
         $details=UsersInfo::where('user_id', '=',$id)->first();
        return $details;
    }
    
    public function validateToken($user_id)
    {
        // $loginDetail=LoginDetail::where(['status'=>0,'user_id'=>$user_id])->get();
        // foreach($loginDetail as $key=>$loginDetailall)
        // {
        //     // $logindatetime=Carbon::parse($loginDetailall->info);
        //     // $totalDuration = $finishTime->diffForHumans($startTime);
        //     // dd($totalDuration);
        //     // if($logindatetime>now())
        //     // {
        //     //     $loginDetail= LoginDetail::where('user_id',$user_id)->update(['status'=>'1']);
        //     // }
            
        // }
        
    }
    
}
