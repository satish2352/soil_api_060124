<?php

namespace App\Http\Controllers;
use Exception;
use JWTAuth;
use App\Task;
use Illuminate\Http\Request;
use App\Models\ {
    UsersInfo,
    User,
    FarmerMeeting,
    DistributorMeeting,
    TargetVideos,
    TargetVideosToDistributor,
    FarmerVistByDistributor,
    SCTResult,
    WebAgency,
    Subscriber,
    SubscriberTarget,
    Messages,
    Complaint,
    WebBlog,
    OrderSummary,
    OrderDetail,
    SaleSummary,
    SaleDetail,
    Video,
    Language,
    Allvideo,
    ProductDetails,
    Downloads,
    WebVideos,
    VideoWatchHistory,
    Notification
};
use DB;
use Carbon\Carbon;

use App\Http\Controllers\CommonController As CommonController;

class DistributorControllerNandu extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->commonController=new CommonController();
    }
  
       
      
    // Target Farmers Count
    public function mytarget_farmercount_mobileapp(Request $request)
    {
        try
        {
            $count= UsersInfo::where('added_by',$request->added_by)
                ->where('user_type','farmer')
                ->where('is_deleted','no')
                ->get();
            $user_type = UsersInfo::where('user_id',$request->added_by)->first();
            $data = array();
            $data['records'] = sizeof($count);
            $data['user_type'] = $user_type->user_type;
        
            if ($data)
            {
                 return response()->json([
                    "data" => $data,
                    "result" => true,
                    "message" => 'Farmers Count Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Farmers Count Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    // Target Youtube Video Link Count 
    public function mytarget_youtubevideolinkcount_mobileapp(Request $request)
    {
        try
        {
             $count= VideoWatchHistory::where('user_id',$request->dist_id)
                   
                    ->get();
        
        $count=sizeof($count);
            
            if ($count)
            {
                 return response()->json([
                    "data" => $count,
                    "result" => true,
                    "message" => 'Video Link Count Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Video Link Count Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    // Subscriber List Count
    public function subscriber_count_mobileapp(Request $request)
    {
        try
        {
            $subscriber_listcount= Subscriber::where('created_by',$request->created_by)
                    ->where('is_deleted','no')
                    ->get();
            $countofsubscriber_list=sizeof($subscriber_listcount);
            
            
            if ($countofsubscriber_list)
            {
                 return response()->json([
                    "data" => $countofsubscriber_list,
                    "result" => true,
                    "message" => 'Subscriber List Count Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Subscriber List Count Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    // Subscribers Target Count 
    public function subscribers_target_count_mobileapp(Request $request)
    {
        try
        {
             $count= SubscriberTarget::where('target_to',$request->target_to)
                    ->where('is_deleted','no')
                    ->get();
        
        $count=sizeof($count);
            
            if ($count)
            {
                 return response()->json([
                    "data" => $count,
                    "result" => true,
                    "message" => 'Subscriber Target Count Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Subscriber Target Count Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    
    // Farmers Meeting Search
    public function farmer_meeting_search_mobileapp(Request $request)
    {
        try {
            $response = [];
            $farmerMeetingData = FarmerMeeting::
                leftJoin('tbl_farmer_meeting_details', 'tbl_farmer_meeting.id', '=', 'tbl_farmer_meeting_details.farmer_meeting_table_id')

                ->leftJoin('tbl_area as stateNew', function ($join) {
                    $join->on('tbl_farmer_meeting.state', '=', 'stateNew.location_id');
                })
                ->leftJoin('tbl_area as districtNew', function ($join) {
                    $join->on('tbl_farmer_meeting.district', '=', 'districtNew.location_id');
                })
                ->leftJoin('tbl_area as talukaNew', function ($join) {
                    $join->on('tbl_farmer_meeting.taluka', '=', 'talukaNew.location_id');
                })
                ->leftJoin('tbl_area as cityNew', function ($join) {
                    $join->on('tbl_farmer_meeting.city', '=', 'cityNew.location_id');
                })

                ->where('tbl_farmer_meeting.is_deleted', 'no')
                ->where('tbl_farmer_meeting.created_by', $request->user_id)
                ->whereBetween('tbl_farmer_meeting.date', [$request->fromdate,$request->todate])
                ->select('tbl_farmer_meeting.*', 'tbl_farmer_meeting_details.farmer_id', 'tbl_farmer_meeting_details.farmer_fname', 'tbl_farmer_meeting_details.farmer_mname', 'tbl_farmer_meeting_details.farmer_lname',
                'stateNew.name as state_name',
                'districtNew.name as district_name',
                'talukaNew.name as taluka_name',
                'cityNew.name as city_name')
                ->orderBy('tbl_farmer_meeting.id', 'DESC')
                ->get();

                foreach ($farmerMeetingData as $data) {
                    $meetingId = $data->id;
        
                    if (!isset($response[$meetingId])) {
                        $response[$meetingId] = [
                            'id' => $data->id,
                            'date' => $data->date,
                            'meeting_place' => $data->meeting_place,
                            'farmer_id' => $data->farmer_id,
                            'meeting_title' => $data->meeting_title,
                            'meeting_description' => $data->meeting_description,
                            'created_by' => $data->created_by,
                            'district_name' => $data->district_name,
                            'state_name' => $data->state_name,
                            'taluka_name' => $data->taluka_name,
                            'city_name' => $data->city_name,
                            'photo_one' => FARMER_MEETING_PHOTO_VIEW . $data->photo_one,
                            'photo_two' => FARMER_MEETING_PHOTO_VIEW . $data->photo_two,
                            'photo_three' => FARMER_MEETING_PHOTO_VIEW . $data->photo_three,
                            'photo_four' => FARMER_MEETING_PHOTO_VIEW . $data->photo_four,
                            'photo_five' => FARMER_MEETING_PHOTO_VIEW . $data->photo_five,
                            'presentFarmers' => []
                        ];
                    }
        
                    if ($data->farmer_id) {
                        $response[$meetingId]['presentFarmers'][] = [
                            'fname' => $data->farmer_fname,
                            'mname' => $data->farmer_mname,
                            'lname' => $data->farmer_lname
                        ];
                    }
                }
        
                // Convert associative array to indexed array
                $response = array_values($response);
        
                return response()->json([
                    "data" => $response,
                    "result" => true,
                    "message" => 'Farmer Meeting Get Successfully'
                ]);
        } catch (Exception $e) {
            return response()->json([
                "data" => [],
                "result" => false,
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }
    
    
    
    
    
    // Farmers Meeting Search by title
    public function farmer_meeting_title_search_mobileapp(Request $request)
    {
        try {
            $response = [];
            $farmerMeetingData = FarmerMeeting::leftJoin('tbl_farmer_meeting_details', 'tbl_farmer_meeting.id', '=', 'tbl_farmer_meeting_details.farmer_meeting_table_id')
                ->leftJoin('tbl_area as stateNew', function ($join) {
                    $join->on('tbl_farmer_meeting.state', '=', 'stateNew.location_id');
                })
                ->leftJoin('tbl_area as districtNew', function ($join) {
                    $join->on('tbl_farmer_meeting.district', '=', 'districtNew.location_id');
                })
                ->leftJoin('tbl_area as talukaNew', function ($join) {
                    $join->on('tbl_farmer_meeting.taluka', '=', 'talukaNew.location_id');
                })
                ->leftJoin('tbl_area as cityNew', function ($join) {
                    $join->on('tbl_farmer_meeting.city', '=', 'cityNew.location_id');
                })
                ->where('tbl_farmer_meeting.is_deleted', 'no')
                ->where('tbl_farmer_meeting.created_by', $request->user_id)
                // ->where('tbl_farmer_meeting.meeting_title', 'like', '%' . $request->search . '%')
                ->whereRaw('LOWER(tbl_farmer_meeting.meeting_title) LIKE ?', ['%' . strtolower($request->search) . '%'])

                ->orderBy('tbl_farmer_meeting.id', 'DESC')
                ->select('tbl_farmer_meeting.*', 'tbl_farmer_meeting_details.farmer_id', 'tbl_farmer_meeting_details.farmer_fname', 'tbl_farmer_meeting_details.farmer_mname', 'tbl_farmer_meeting_details.farmer_lname',
                'stateNew.name as state_name',
                'districtNew.name as district_name',
                'talukaNew.name as taluka_name',
                'cityNew.name as city_name')
                ->get();

                foreach ($farmerMeetingData as $data) {
                    $meetingId = $data->id;
        
                    if (!isset($response[$meetingId])) {
                        $response[$meetingId] = [
                            'id' => $data->id,
                            'date' => $data->date,
                            'meeting_place' => $data->meeting_place,
                            'farmer_id' => $data->farmer_id,
                            'meeting_title' => $data->meeting_title,
                            'meeting_description' => $data->meeting_description,
                            'created_by' => $data->created_by,
                            'district_name' => $data->district_name,
                            'state_name' => $data->state_name,
                            'taluka_name' => $data->taluka_name,
                            'city_name' => $data->city_name,
                            'photo_one' => FARMER_MEETING_PHOTO_VIEW . $data->photo_one,
                            'photo_two' => FARMER_MEETING_PHOTO_VIEW . $data->photo_two,
                            'photo_three' => FARMER_MEETING_PHOTO_VIEW . $data->photo_three,
                            'photo_four' => FARMER_MEETING_PHOTO_VIEW . $data->photo_four,
                            'photo_five' => FARMER_MEETING_PHOTO_VIEW . $data->photo_five,
                            'presentFarmers' => []
                        ];
                    }
        
                    if ($data->farmer_id) {
                        $response[$meetingId]['presentFarmers'][] = [
                            'fname' => $data->farmer_fname,
                            'mname' => $data->farmer_mname,
                            'lname' => $data->farmer_lname
                        ];
                    }
                }
        
                // Convert associative array to indexed array
                $response = array_values($response);
        
                return response()->json([
                    "data" => $response,
                    "result" => true,
                    "message" => 'Farmer Meeting Get Successfully'
                ]);
        } catch (Exception $e) {
            return response()->json([
                "data" => [],
                "result" => false,
                "error" => true,
                "message" => $e->getMessage()
            ]);
        }
    }
    
    
    
    // Meeting Delete
    public function farmer_meeting_delete(Request $request)
    {
        $id = $request->id;
        $farmer_meetingdelete = ['is_deleted' => 'yes'];
        $result = FarmerMeeting::where('id', '=', $id)->update($farmer_meetingdelete);

        if ($result)
        {
            return response()->json([
                "data" => $result,
                "result" => true,
                "message" => 'Farmer Meeting Deleted Successfully'
            ]);
        }
        else
        {
            return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Farmer Meeting Not Deleted'
            ]);
        
        }

    }
    
    
    // Distributor Meeting Search
    public function distributor_meeting_search_mobileapp(Request $request)
    {
        try
        {
             $distributor_meering_record= DistributorMeeting::where('is_deleted','no')
                    ->whereBetween('date', [$request->fromdate,$request->todate])
                    ->where('created_by',$request->created_by)
                    ->get();
            $distributor_meering_recordcount=sizeof($distributor_meering_record);
            if($distributor_meering_recordcount>0)
            
            {
                 return response()->json([
                    "data" => $distributor_meering_record,
                    "result" => true,
                    "message" => 'Distributor Meeting Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Distributor Meeting Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    // Distributor Meeting Search
    public function distributor_meeting_title_search_mobileapp(Request $request)
    {
        try
        {
             $distributor_meeting_title_record= DistributorMeeting::where('meeting_place', 'like', '%' . $request->search . '%')
                    ->where('is_deleted','no')
                    ->where('created_by',$request->created_by)
                    ->get();
            
            $distributor_meeting_title_recordcount=sizeof($distributor_meeting_title_record);
            if($distributor_meeting_title_recordcount>0)
            
            {
                 return response()->json([
                    "data" => $distributor_meeting_title_record,
                    "result" => true,
                    "message" => 'Distributor Meeting Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Distributor Meeting Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    // distributor_meeting Delete
    public function distributor_meeting_delete(Request $request)
    {
        $id = $request->id;
        $distributor_meetingdelete = ['is_deleted' => 'yes'];
        $result = DistributorMeeting::where('id', '=', $id)->update($distributor_meetingdelete);

        if ($result)
        {
            return response()->json([
                "data" => $result,
                "result" => true,
                "message" => 'Distributor Meeting Deleted Successfully'
            ]);
        }
        else
        {
            return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Distributor Meeting Not Deleted'
            ]);
        
        }

    }
    
    
    
    // SCT Result Search by Date
    public function sct_result_search_by_date_mobileapp(Request $request)
    {
        try
        {
             $sct_result_search_by_date= SCTResult::leftJoin('tbl_area as stateNew', function ($join) {
                $join->on('tbl_sct_result.state', '=', 'stateNew.location_id');
            })
            ->leftJoin('tbl_area as districtNew', function ($join) {
                $join->on('tbl_sct_result.district', '=', 'districtNew.location_id');
            })
            ->leftJoin('tbl_area as talukaNew', function ($join) {
                $join->on('tbl_sct_result.taluka', '=', 'talukaNew.location_id');
            })
            ->leftJoin('tbl_area as cityNew', function ($join) {
                $join->on('tbl_sct_result.city', '=', 'cityNew.location_id');
            })
            ->select('tbl_sct_result.*',
                DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_one) as photo_one"),
                DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_two) as photo_two"),
                DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_three) as photo_three"),
                DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_four) as photo_four"),
                DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_five) as photo_five"),
            'stateNew.name as state_name',
            'districtNew.name as district_name',
            'talukaNew.name as taluka_name',
            'cityNew.name as city_name')
             ->where('is_deleted','no')
                    ->whereBetween('date', [$request->fromdate,$request->todate])
                    ->where('created_by',$request->created_by)
                    ->get();
        
            $sct_result_search_by_datecount=sizeof($sct_result_search_by_date);
            if($sct_result_search_by_datecount>0)
            
            {
                 return response()->json([
                    "data" => $sct_result_search_by_date,
                    "result" => true,
                    "message" => 'SCT Result Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'SCT Result Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
   
    
    
    
    
    // Messages Added / Insert
    public function messageadd(Request $request)
    {
        $messages = new Messages();
        date_default_timezone_set('Asia/Kolkata');
        $today = date("d-m-Y");
        $messages->date = $today; 
        $messages->recipient_name = $request->recipient_name;
        $messages->subject = $request->subject;
        $messages->message = $request->message;  
        $messages->msg_read = 'n';  
        $messages->msg_status = 0;  
        // $messages->msg_repl = 'n';  
        $messages->message_by = $request->distributor_id;
        $messages->save();
        
        $imagedataPath=MESSAGE_UPLOADS;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );
        }
        
        $idLastInserted=$messages->id;
        $photoName=$idLastInserted."_message";
        $inputfilenametoupload='document';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $messages=Messages::where('id',$idLastInserted)->update(['document'=>$filename]);
           
        }
            
            
        if ($messages)
        {
             return response()->json([
                "data" => $messages,
                "result" => true,
                "message" => 'Message Added Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
               "message" => 'Message Not Added'
            ]);
            
        }
    }
    
    
    
    // Messages Update
    public function messageedit(Request $request)
    {
        $data=[
                // 'recipient_name' => $request->recipient_name,
                // 'subject' => $request->subject,
                // 'message' => $request->message,
                'msg' => $request->msg,
                'msg_status' => 2,
                'msg_read' => 'y',
              ];
        $messageupdate = Messages::where('id',$request->id)->update($data);
        $messageupdateData = Messages::where('id',$request->id)->first();
      
        $dataToInsert = [
            "distributor_id"=>$messageupdateData->message_by,
            "message"=>$request->msg,
            "is_read"=>"no",
        ];
        $notification = Notification::insert($dataToInsert);
        if ($messageupdate)
        {
             return response()->json([
                "data" => $messageupdate,
                "result" => true,
                "message" => 'Message Updated'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Message Not Updated'
            ]);
            
        }
    }
    
    
    
    // Message View
    public function messageview_perticular(Request $request)
    {
        try
        {

            $data=[
                'msg_status' => 1,
                'msg_read' => 'y',
              ];
            // Messages::where('id',$request->messageid)->update($data);

            // $messageview= Messages::where(['is_deleted' => 'no', 'id'=>$request->messageid])->orderBy('id', 'DESC')->get();
            

            Messages::where('id',$request->messageid)->update($data);

            $messageview= Messages::leftJoin('usersinfo as newuser_table', function($join) {
                $join->on('newuser_table.user_id', '=','tbl_messages.message_by');
            })->where(['tbl_messages.is_deleted' => 'no', 'tbl_messages.id'=>$request->messageid])
            
            ->select('tbl_messages.id',
            'tbl_messages.date',
            'tbl_messages.recipient_name',
            'tbl_messages.subject',
            'tbl_messages.message',
            'tbl_messages.document',
            'tbl_messages.message_by',
            'tbl_messages.msg_status',
            'tbl_messages.msg_read',
            // 'tbl_messages.msg_reply',
            'tbl_messages.msg',
            'tbl_messages.is_deleted',
            'tbl_messages.created_at',
            
             
            'newuser_table.fname',
            'newuser_table.mname',
            'newuser_table.lname',
            'newuser_table.phone',
            )
            ->first();
            if ($messageview)
            {
                $messageview->document=COMPLAINT_VIEW.$messageview->document;
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Message Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }

    public function messageview(Request $request)
    {
        try
        {
            $messageview= Messages::where('date', '<>','');
                                    if($request->msg_status) {
                                        if($request->msg_status == '1') {
                                            $messageview=  $messageview->whereIn('msg_status',array('0','1'));
                                        } else {
                                            $messageview=  $messageview->where('msg_status', $request->msg_status);
                                        } 
                                    }
                                    $messageview=  $messageview->orderBy('msg_status', 'asc')
                                    ->get();
            
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Message Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function messageviewbyid(Request $request)
    {
        try
        {
            $messageview= Messages::where(['is_deleted' =>'no','message_by' =>$request->dist_id])
                            // ->orderBy('id', 'DESC')
                            ->orderBy('id', 'DESC')
                            ->get();
            
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Message Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    
    
    // Message search by Subject
    public function messagesearch(Request $request)
    {
        try
        {
            $messagesearch = Messages::where('message_by', $request->dist_id)
                            ->where('is_deleted', 'no')
                            ->where(function ($query) use ($request) {
                                $query->where('subject', 'like', '%' . $request->search . '%')
                                    ->orWhere('recipient_name', 'like', '%' . $request->search . '%')
                                    ->orWhere('message', 'like', '%' . $request->search . '%')
                                    ->orWhere('msg', 'like', '%' . $request->search . '%');
                            })
                            ->groupBy('id')
                            ->get();
        
            
            $messagesearchcount=sizeof($messagesearch);
            if($messagesearchcount>0)
            
            {
                 return response()->json([
                    "data" => $messagesearch,
                    "result" => true,
                    "message" => 'Message Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Message Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    // Message search by date
    public function messagesearchbydate(Request $request)
    {
        try
        {
            
            $messagesearchbydate= Messages::where('message_by',$request->distributor_id)
                    ->where('is_deleted','no')
                    ->whereBetween('date', [$request->fromdate,$request->todate])
                    ->orderBy('id', 'DESC')
                    ->get();
                    
            $messagesearchbydatecount=sizeof($messagesearchbydate);
            if($messagesearchbydatecount>0)
            
            {
                 return response()->json([
                    "data" => $messagesearchbydate,
                    "result" => true,
                    "message" => 'Message Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Message Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    public function messagedelete(Request $request)
    {
        $id = $request->id;
        $messagedelete = ['is_deleted' => 'yes'];
        $result = Messages::where('id', '=', $id)->update($messagedelete);

        if ($result)
        {
            return response()->json([
                "data" => $result,
                "result" => true,
                "message" => 'Message Deleted Successfully'
            ]);
        }
        else
        {
            return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Message Not Deleted'
            ]);
        
        }

    }
    
    
    
    
    // Farmers Meeting Search
    public function myvisit_date_filter_mobileapp(Request $request)
    {
        try
        {
             $myvisit_date_filter= FarmerVistByDistributor::where('created_by',$request->created_by)
                    ->whereBetween('date', [$request->fromdate,$request->todate])
                     ->where('status',0)
                     ->orderBy('id', 'DESC')
                    ->get();
                    
            $myvisit_date_filtercount=sizeof($myvisit_date_filter);
            if($myvisit_date_filtercount>0)
            
            {
                 return response()->json([
                    "data" => $myvisit_date_filter,
                    "result" => true,
                    "message" => 'Visit Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Visit Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    // Complaint Added / Insert
    public function complaintadd(Request $request)
    {
        $complaint = new Complaint();
        date_default_timezone_set('Asia/Kolkata');
        $today = date("d-m-Y");
        $complaint->date = $today; 
        $complaint->recipient_name = $request->recipient_name;
        $complaint->subject = $request->subject;
        $complaint->complaint = $request->complaint;
        $complaint->complaint_by = $request->complaint_by;

        $complaint->msg_read = 'n';  
        $complaint->msg_status = 0;  
        $complaint->msg = null;  

        $complaint->save();
        
        $imagedataPath=COMPLAINT_UPLOADS;
        if ( !is_dir( $imagedataPath) ) 
        {
            mkdir( $imagedataPath );
        }
        
        $idLastInserted=$complaint->id;
        $photoName=$idLastInserted."_complaint_one";
        $inputfilenametoupload='complaint_one';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $complaint=Complaint::where('id',$idLastInserted)->update(['document_one'=>$filename]);
        }
        
        $photoName=$idLastInserted."_complaint_two";
        $inputfilenametoupload='complaint_two';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $complaint=Complaint::where('id',$idLastInserted)->update(['document_two'=>$filename]);
        }
        
        $photoName=$idLastInserted."_complaint_three";
        $inputfilenametoupload='complaint_three';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $complaint=Complaint::where('id',$idLastInserted)->update(['document_three'=>$filename]);
        }
        
        $photoName=$idLastInserted."_complaint_four";
        $inputfilenametoupload='complaint_four';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $complaint=Complaint::where('id',$idLastInserted)->update(['document_four'=>$filename]);
        }
        
        $photoName=$idLastInserted."_complaint_five";
        $inputfilenametoupload='complaint_five';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $complaint=Complaint::where('id',$idLastInserted)->update(['document_five'=>$filename]);
        }
        
        if ($complaint)
        {
             return response()->json([
                "data" => $complaint,
                "result" => true,
                "message" => 'Complaint Added Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
               "message" => 'Complaint Not Added'
            ]);
            
        }
    }
    
    
    
    // Complaint View
    public function complaintview(Request $request)
    {
        
        try
        {
            $complaintview= Complaint::where('is_deleted', 'no');
                                    if($request->msg_status) {
                                        if($request->msg_status == '1') {
                                            $complaintview=  $complaintview->whereIn('msg_status',array('0','1'));
                                        } else {
                                            $complaintview=  $complaintview->where('msg_status', $request->msg_status);
                                        } 
                                    }
            $complaintview=  $complaintview->orderBy('id', 'DESC')
                                    ->get();
            
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Complaint Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }

    public function complaintviewbyid(Request $request)
    {
        
        try
        {
            $complaintview= Complaint::where(['is_deleted' =>'no','complaint_by' =>$request->dist_id])->orderBy('id', 'DESC')->get();
            
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Complaint Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    

    public function complaintview_perticular(Request $request)
    {
        try
        {

            $data=[
                'msg_status' => 1,
                'msg_read' => 'y',
            ];
            Complaint::where('id',$request->messageid)->update($data);

            $messageview= Complaint::leftJoin('usersinfo as newuser_table', function($join) {
                $join->on('newuser_table.user_id', '=','tbl_complaint.complaint_by');
            })->where(['tbl_complaint.is_deleted' => 'no', 'tbl_complaint.id'=>$request->messageid])
            
            ->select('tbl_complaint.id',
            'tbl_complaint.date',
            'tbl_complaint.recipient_name',
            'tbl_complaint.subject',
            'tbl_complaint.complaint',
            'tbl_complaint.document_one',
            'tbl_complaint.document_two',
            'tbl_complaint.document_three',
            'tbl_complaint.document_four',
            'tbl_complaint.document_five',
            'tbl_complaint.complaint_by',
            'tbl_complaint.msg_status',
            'tbl_complaint.msg_read',
            'tbl_complaint.msg_reply',
            'tbl_complaint.msg',
            'tbl_complaint.is_deleted',
            'tbl_complaint.created_at',
            
             
            'newuser_table.fname',
            'newuser_table.mname',
            'newuser_table.lname',
            'newuser_table.phone',
            )
            ->first();
            if ($messageview)
            {
              
                $messageview->document_one=COMPLAINT_VIEW.$messageview->document_one;
                $messageview->document_two=COMPLAINT_VIEW.$messageview->document_two;
                $messageview->document_three=COMPLAINT_VIEW.$messageview->document_three;
                $messageview->document_four=COMPLAINT_VIEW.$messageview->document_four;
                $messageview->document_five=COMPLAINT_VIEW.$messageview->document_five;
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Message Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    
    
    // Complaint Update
    public function complaintedit(Request $request)
    {
        // $data=[
        //         'recipient_name' => $request->recipient_name,
        //         'subject' => $request->subject,
        //         'complaint' => $request->complaint
        //       ];
        // $complaintupdate = Complaint::where('id',$request->complaintid)->update($data);

        $data=[
            // 'recipient_name' => $request->recipient_name,
            // 'subject' => $request->subject,
            // 'message' => $request->message,
            'msg' => $request->msg,
            'msg_status' => 2,
            'msg_read' => 'y',
          ];
        $complaintupdate = Complaint::where('id',$request->id)->update($data);
        $messageupdateData = Complaint::where('id',$request->id)->first();
      
        $dataToInsert = [
            "distributor_id"=>$messageupdateData->complaint_by,
            "message"=>$request->msg,
            "is_read"=>"no",
        ];
        $notification = Notification::insert($dataToInsert);
        
      
        if ($complaintupdate)
        {
             return response()->json([
                "data" => $complaintupdate,
                "result" => true,
                "message" => 'Complaint Updated'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Complaint Not Updated'
            ]);
            
        }
    }
    
    
    
    
    
    
    
    // Complaint search by Subject
    public function complaintsearch(Request $request)
    {
        try
        {

            $complaintsearch = Complaint::where('complaint_by', $request->dist_id)
                    ->where('is_deleted', 'no')
                    ->where(function ($query) use ($request) {
                        $query->where('subject', 'like', '%' . $request->search . '%')
                            ->orWhere('recipient_name', 'like', '%' . $request->search . '%')
                            ->orWhere('complaint', 'like', '%' . $request->search . '%')
                            ->orWhere('msg', 'like', '%' . $request->search . '%');
                    })
                    ->groupBy('id')
                    ->get();

            $Complaintcount=sizeof($complaintsearch);
            if ($Complaintcount>0)
            {
                 return response()->json([
                    "data" => $complaintsearch,
                    "result" => true,
                    "message" => 'Complaint Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Complaint Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    // Complaint search by date
    public function complaintsearchbydate(Request $request)
    {
        try
        {
            
            $complaintsearchbydate= Complaint::where('is_deleted','no')
                    ->where('complaint_by',$request->distributor_id)
                    ->whereBetween('date', [$request->fromdate,$request->todate])
                    ->orderBy('id', 'DESC')
                    ->get();
                    
            $complaintsearchbydatecount=sizeof($complaintsearchbydate);
            if($complaintsearchbydatecount>0)
            
            {
                 return response()->json([
                    "data" => $complaintsearchbydate,
                    "result" => true,
                    "message" => 'Complaint Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Complaint Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    // Complaint Delete
    public function complaintdelete(Request $request)
    {
        $id = $request->id;
        $complaintdelete = ['is_deleted' => 'yes'];
        $result = Complaint::where('id', '=', $id)->update($complaintdelete);

        if ($result)
        {
            return response()->json([
                "data" => $result,
                "result" => true,
                "message" => 'Complaint Deleted Successfully'
            ]);
        }
        else
        {
            return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Complaint Not Deleted'
            ]);
        
        }

    }
    
    
    // Order List Search by Date
    public function orderlist_by_date_mobileapp(Request $request)
    {
        try
        {
             $orderlist_by_date_record= OrderSummary::where('created_disctributor_id',$request->disctributor_id)
                    ->whereBetween('order_date', [$request->fromdate,$request->todate])
                    ->where('is_deleted','no')
                    ->where('entry_by','distributor')
                    ->get();
                    
            $orderlist_by_date_recordcount=sizeof($orderlist_by_date_record);
            foreach($orderlist_by_date_record as $key=>$resultnew)
            {
                if($resultnew->account_approved=='no' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Pending';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Verified';
                }elseif($resultnew->order_dispatched=='yes'){
                    $resultnew->status = 'Order Dispatched From warehouse';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='yes'){
                    $resultnew->status = 'Forwaded to warehouse';
                }
            }
            if($orderlist_by_date_recordcount>0)
            
            {
                 return response()->json([
                    "data" => $orderlist_by_date_record,
                    "result" => true,
                    "message" => 'Order Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Order Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    // Videos search by Title
    public function videossearch_mobileapp(Request $request)
    {
        try
        {
            //  $videossearch= Video::where('title', 'like', '%' . $request->search . '%')
            $videossearch= WebVideos::where('status',0)
            ->when($request->get('datefrom'), function($query) use ($request) {
                $query->whereBetween('created_at', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
            }) 
            ->when($request->get('search'), function($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            }) 
                    ->select(
                        'id',
                        'title',
                        'description',
                        'url',
                        'status',
                        'created_at  as date' ,
                        'updated_at',
                    )
                    ->orderBy('id', 'DESC')
                    ->get();
            $videossearchcount=sizeof($videossearch);
            if ($videossearchcount>0)
            {
                 return response()->json([
                    "data" => $videossearch,
                    "result" => true,
                    "message" => 'Videos Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Videos Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    // Videos Search by Date
    public function video_search_by_date_mobileapp(Request $request)
    {
        try
        {
             $video_search_by_date_record= Video::where('status',0)
                    ->whereBetween('created_at', [$request->fromdate,$request->todate])
                    ->orderBy('id', 'DESC')
                    ->where('activeinactive',0)
                    ->get();
                    
            $video_search_by_date_recordcount=sizeof($video_search_by_date_record);
            if($video_search_by_date_recordcount>0)
            
            {
                 return response()->json([
                    "data" => $video_search_by_date_record,
                    "result" => true,
                    "message" => 'Video Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Video Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    // Blog List
    public function bloglist_distributorapp(Request $request)
    {
        try
        {
            $result = WebBlog::where(['status'=>0,'articleorschedule'=>'article'])->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=BLOG_CONTENT_VIEW.$value->photo_one;
            }
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Blog get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Blog not found'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }
    }
    
    
    
    
    // Videos search by Title
    public function sct_result_search_by_title_mobileapp(Request $request)
    {
        try
        {
             $sct_result_search__by_title= SCTResult::leftJoin('tbl_area as stateNew', function ($join) {
                $join->on('tbl_sct_result.state', '=', 'stateNew.location_id');
            })
            ->leftJoin('tbl_area as districtNew', function ($join) {
                $join->on('tbl_sct_result.district', '=', 'districtNew.location_id');
            })
            ->leftJoin('tbl_area as talukaNew', function ($join) {
                $join->on('tbl_sct_result.taluka', '=', 'talukaNew.location_id');
            })
            ->leftJoin('tbl_area as cityNew', function ($join) {
                $join->on('tbl_sct_result.city', '=', 'cityNew.location_id');
            })
            ->select('tbl_sct_result.*',
            DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_one) as photo_one"),
            DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_two) as photo_two"),
            DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_three) as photo_three"),
            DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_four) as photo_four"),
            DB::raw("CONCAT('" . SCT_RESULT_PHOTO_VIEW . "', tbl_sct_result.photo_five) as photo_five"),
            'stateNew.name as state_name',
            'districtNew.name as district_name',
            'talukaNew.name as taluka_name',
            'cityNew.name as city_name')
            //  ->where('title', 'like', '%' . $request->search . '%')
             ->whereRaw('LOWER(tbl_sct_result.title) LIKE ?', ['%' . strtolower($request->search) . '%'])
                    ->where('is_deleted','no')
                    ->where('created_by',$request->created_by)
                    ->get();
            $sct_result_search__by_title_count=sizeof($sct_result_search__by_title);
            if ($sct_result_search__by_title_count>0)
            {
                 return response()->json([
                    "data" => $sct_result_search__by_title,
                    "result" => true,
                    "message" => 'SCT Result Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'SCT Result Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    
    
    
    
    // Sale add
    public function saleadd_mobileapp(Request $request)
    {
        try
        {  
            //dd($request);
            $date=date("Y-m-d");
            $time= time();
            $tempid=$date.$time;
            $order_no=str_replace("-","",$tempid);
            $requestdata = $request;
            $ordrsummary = new SaleSummary();
            $ordrsummary->order_no = $order_no;
            $ordrsummary->order_date = date('Y-m-d');
            $ordrsummary->order_created_by = $requestdata->order_created_by;
            $ordrsummary->entry_by = 'distributor';
            $ordrsummary->created_disctributor_id = $requestdata->created_disctributor_id;
            $ordrsummary->order_created_for = $requestdata->order_created_for;
            $ordrsummary->created_disctributor_amount = $requestdata->created_disctributor_amount;
            $ordrsummary->save();
          

            $requestdata = $request;
            $allproduct=$requestdata->all_product;
            
            $allproductNew=json_decode($allproduct,true);
            foreach($allproductNew as $key=>$prod_details)
            {
                $orderdetails = new SaleDetail();
                $orderdetails->order_no =$order_no;
                $orderdetails->prod_id = $prod_details['prod_id'];
                $orderdetails->qty = $prod_details['qty'];
                $orderdetails->rate_of_prod = $prod_details['rate_of_prod'];
                $orderdetails->final_amt = $prod_details['qty']*$prod_details['rate_of_prod'];
                $orderdetails->save();
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Information Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    // Sale Get
    public function saleget_mobileapp(Request $request)
    {
       try
        {
            $result = SaleSummary::where('order_no',$request->order_no)
            ->where('tbl_sale_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_sale_summary.is_deleted','no')->get();
        
            foreach($result as $key=>$value)
            {
                $value->all_product = SaleDetail::where('order_no',$request->order_no)->get();       
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
    
    
    
    // Sale List Search by Date
    public function salelist_by_date_mobileapp(Request $request)
    {
        try
        {
             $salelist_by_date_record= SaleSummary::
                leftJoin('usersinfo as newuser_table_order_for', function($join) {
                    $join->on('newuser_table_order_for.user_id', '=','tbl_sale_summary.order_created_for');
                })
                ->where('tbl_sale_summary.created_disctributor_id',$request->disctributor_id)
                ->whereBetween('tbl_sale_summary.order_date', [$request->fromdate,$request->todate])
                ->where('tbl_sale_summary.is_deleted','no')
                ->where('tbl_sale_summary.entry_by','distributor')
                ->select(
                    'tbl_sale_summary.*',
                    'newuser_table_order_for.fname as order_for_fname',
                    'newuser_table_order_for.mname as order_for_mname',
                    'newuser_table_order_for.lname as order_for_lname',
                )
                ->get();
                    
            $salelist_by_date_recordcount=sizeof($salelist_by_date_record);
            foreach($salelist_by_date_record as $key=>$resultnew)
            {
                if($resultnew->account_approved=='no' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Pending';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Verified';
                }elseif($resultnew->order_dispatched=='yes'){
                    $resultnew->status = 'Order Dispatched From warehouse';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='yes'){
                    $resultnew->status = 'Forwaded to warehouse';
                }
            }
            if($salelist_by_date_recordcount>0)
            {
                 return response()->json([
                    "data" => $salelist_by_date_record,
                    "result" => true,
                    "message" => 'Sale Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Sale Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    public function salelist_mobileapp(Request $request)
    {
        try
        {
             $result = SaleSummary::
                        leftJoin('usersinfo as newuser_table', function($join) {
                            $join->on('newuser_table.user_id', '=','tbl_sale_summary.created_disctributor_id');
                        })
                        ->leftJoin('usersinfo as newuser_table_order_for', function($join) {
                            $join->on('newuser_table_order_for.user_id', '=','tbl_sale_summary.order_created_for');
                        })
                        
                        ->where('tbl_sale_summary.is_deleted','no')
                        ->where('tbl_sale_summary.created_disctributor_id',$request->created_disctributor_id)
                        ->select(
                            'tbl_sale_summary.id',
                            'tbl_sale_summary.order_no',
                            'tbl_sale_summary.order_date',
                            'tbl_sale_summary.order_created_by',
                            'tbl_sale_summary.order_created_for',
                            'tbl_sale_summary.created_disctributor_id',
                            'tbl_sale_summary.created_disctributor_amount',
                            'tbl_sale_summary.dispatched_to_created_disctributor_by_warehouse',
                            'tbl_sale_summary.forwarded_bsc_id',
                            'tbl_sale_summary.forwarded_bsc_amount',
                            'tbl_sale_summary.dispatched_to_forwarded_bsc_by_warehouse',
                            'tbl_sale_summary.forwarded_dsc_id',
                            'tbl_sale_summary.forwarded_dsc_amount',
                            'tbl_sale_summary.dispatched_to_forwarded_dsc_amount_by_warehouse',
                            'tbl_sale_summary.account_approved',
                            'tbl_sale_summary.forward_to_warehouse',
                            'tbl_sale_summary.entry_by',
                            'tbl_sale_summary.order_dispatched',
                            'tbl_sale_summary.order_dispatched_date',

                            'newuser_table_order_for.fname as order_for_fname',
                            'newuser_table_order_for.mname as order_for_mname',
                            'newuser_table_order_for.lname as order_for_lname',

                            'newuser_table.fname',
                            'newuser_table.mname',
                            'newuser_table.lname',
                            'newuser_table.phone',
                        )
                        ->orderBy('tbl_sale_summary.id','DESC')
                        ->get();
            
            foreach($result as $key=>$resultnew)
            {
                if($resultnew->account_approved=='no' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Pending';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Verified';
                }elseif($resultnew->order_dispatched=='yes'){
                    $resultnew->status = 'Order Dispatched From warehouse';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='yes'){
                    $resultnew->status = 'Forwaded to warehouse';
                }
                // try
                // {
                //     $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                //     $resultnew->fname=$details->fname;
                //     $resultnew->mname=$details->mname;
                //     $resultnew->lname=$details->lname;
                    
                // } catch(Exception $e) {
                //     return response()->json([
                //             "data" => array(),
                //             "result" => false,
                //             "error" => true,
                //             "message" =>$e->getMessage()." ".$e->getCode()
                //         ]);
                   
                //  }
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
    
    public function saleview_mobileapp(Request $request)
    {
        try
        {
            $result = SaleSummary::
            // leftJoin('tbl_sale_detail', function($join) {
            //     $join->on('tbl_sale_summary.order_no', '=', 'tbl_sale_detail.order_no');
            // })
          
            // ->leftJoin('tbl_product', function($join) {
            //     $join->on('tbl_sale_detail.prod_id', '=', 'tbl_product.id');
            // })

            leftJoin('usersinfo as newuser_table', function($join) {
                $join->on('newuser_table.user_id', '=','tbl_sale_summary.created_disctributor_id');
            })
            ->leftJoin('usersinfo as newuser_table_order_for', function($join) {
                $join->on('newuser_table_order_for.user_id', '=','tbl_sale_summary.order_created_for');
            })
            
            
            ->where('tbl_sale_summary.order_no',$request->order_no)
            ->where('tbl_sale_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_sale_summary.is_deleted','no')
            ->select(
                'tbl_sale_summary.*',
              
                'newuser_table.fname',
                'newuser_table.mname',
                'newuser_table.lname',
                'newuser_table.phone',

                'newuser_table_order_for.fname as order_for_fname',
                'newuser_table_order_for.mname as order_for_mname',
                'newuser_table_order_for.lname as order_for_lname',
                
            )
            ->get();
        
            foreach($result as $key=>$value)
            {
                //$value->all_product = OrderDetail::where('order_no',$request->order_no)->get();
                if($value->account_approved=='no' && $value->forward_to_warehouse=='no'){
                    $value->status = 'Pending';
                }elseif($value->account_approved=='yes' && $value->forward_to_warehouse=='no'){
                    $value->status = 'Verified';
                }elseif($value->order_dispatched=='yes'){
                    $value->status = 'Order Dispatched From warehouse';
                }elseif($value->account_approved=='yes' && $value->forward_to_warehouse=='yes'){
                    $value->status = 'Forwaded to warehouse';
                }
                // $value->all_product = SaleDetail::leftJoin('tbl_product','tbl_product.id','=','tbl_sale_detail.prod_id')
                //                     ->where('tbl_sale_detail.order_no',$request->order_no)
                //                     ->where('tbl_sale_detail.is_deleted','no')
                //                     ->get();


                $value->all_product = SaleDetail::where('tbl_sale_detail.order_no',$request->order_no)
                                            ->leftJoin('tbl_product_details', function($join) {
                                                $join->on('tbl_sale_detail.prod_id', '=', 'tbl_product_details.id');
                                            })
                                            ->where('tbl_sale_detail.is_deleted','no')
                                            ->join('tbl_product','tbl_product.id','=','tbl_sale_detail.prod_id')
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
    

    public function saleview_by_name_mobileapp(Request $request)
    {
        try
        {
            $result = SaleSummary::
            // leftJoin('tbl_sale_detail', function($join) {
            //     $join->on('tbl_sale_summary.order_no', '=', 'tbl_sale_detail.order_no');
            // })
          
            // ->leftJoin('tbl_product', function($join) {
            //     $join->on('tbl_sale_detail.prod_id', '=', 'tbl_product.id');
            // })

            leftJoin('usersinfo as newuser_table', function($join) {
                $join->on('newuser_table.user_id', '=','tbl_sale_summary.created_disctributor_id');
            })
            ->leftJoin('usersinfo as newuser_table_order_for', function($join) {
                $join->on('newuser_table_order_for.user_id', '=','tbl_sale_summary.order_created_for');
            })
            
            
            ->where('newuser_table_order_for.fname', 'like', '%' . $request->name_of_person . '%')
            ->orWhere('newuser_table_order_for.mname', 'like', '%' . $request->name_of_person . '%')
            ->orWhere('newuser_table_order_for.lname', 'like', '%' . $request->name_of_person . '%')
            ->where('tbl_sale_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_sale_summary.is_deleted','no')
            ->select(
                'tbl_sale_summary.*',
              
                'newuser_table.fname',
                'newuser_table.mname',
                'newuser_table.lname',
                'newuser_table.phone',

                'newuser_table_order_for.fname as order_for_fname',
                'newuser_table_order_for.mname as order_for_mname',
                'newuser_table_order_for.lname as order_for_lname',
                
            )
            ->get();
        
            foreach($result as $key=>$value)
            {
                //$value->all_product = OrderDetail::where('order_no',$request->order_no)->get();
                if($value->account_approved=='no' && $value->forward_to_warehouse=='no'){
                    $value->status = 'Pending';
                }elseif($value->account_approved=='yes' && $value->forward_to_warehouse=='no'){
                    $value->status = 'Verified';
                }elseif($value->order_dispatched=='yes'){
                    $value->status = 'Order Dispatched From warehouse';
                }elseif($value->account_approved=='yes' && $value->forward_to_warehouse=='yes'){
                    $value->status = 'Forwaded to warehouse';
                }
                // $value->all_product = SaleDetail::leftJoin('tbl_product','tbl_product.id','=','tbl_sale_detail.prod_id')
                //                     ->where('tbl_sale_detail.order_no',$request->order_no)
                //                     ->where('tbl_sale_detail.is_deleted','no')
                //                     ->get();


                $value->all_product = SaleDetail::where('tbl_sale_detail.order_no',$request->order_no)
                                            ->leftJoin('tbl_product_details', function($join) {
                                                $join->on('tbl_sale_detail.prod_id', '=', 'tbl_product_details.id');
                                            })
                                            ->where('tbl_sale_detail.is_deleted','no')
                                            ->join('tbl_product','tbl_product.id','=','tbl_sale_detail.prod_id')
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
    
    // Sale Update
    public function saleupdate_mobileapp(Request $request)
    {
        try
        {
            $requestdata =$request;
            
            $allproduct=$requestdata->all_product;
            
             $allproductNew=json_decode($allproduct,true);
            foreach($allproductNew as $key=>$prod_details)
            {
                 $data=[
                    'prod_id'=> $prod_details['prod_id'],
                    'qty'=>$prod_details['qty'],
                    'rate_of_prod'=>$prod_details['rate_of_prod'],
                    'final_amt' =>$prod_details['qty']*$prod_details['rate_of_prod']
                ];
                $orderdetail = SaleDetail::where('order_no',$requestdata->order_no)->where('prod_id',$prod_details['prod_id'])->update($data);       
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Information Not Updated'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    
    
    // Sale Delete
    public function saledelete_mobileapp(Request $request)
    {
        try
        {
            $data=[
                'is_deleted'=>'yes',
            ];
            
            $user = SaleSummary::where('order_no',$request->order_no)
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
                    "data" => array(),
                    "result" => false,
                    "message" => 'Information Not Deleted'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    
    
    
    public function allsaleproductlist_mobileapp(Request $request)
    {
        try
        {
            $result = SaleDetail::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
                ->where('tbl_product_details.is_deleted','no')
                ->select('tbl_product_details.*','tbl_product.title','tbl_product.content','tbl_product.link')
                ->get();

            //dd($result);
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
    
    
    // public function allsaleproductlist_by_distributor_mobileapp(Request $request)
    // {
    //     try
    //     {
    //         // $result = SaleDetail::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
    //         //     ->where('tbl_product_details.is_deleted','no')
    //         //     ->select('tbl_product_details.*','tbl_product.title','tbl_product.content','tbl_product.link')
    //         //     ->get();
                
    //         $result = SaleSummary::join('tbl_sale_detail','tbl_sale_detail.order_no','=','tbl_sale_summary.order_no')
    //         ->where('tbl_sale_summary.created_disctributor_id',$request->disctributor_id)
    //         ->where('tbl_sale_summary.is_deleted','no')->get();

    //         //dd($result);
    //         // foreach($result as $key=>$value)
    //         // {
    //         //     $value->photopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
    //         // }
    //         if ($result)
    //         {
    //              return response()->json([
    //                 "data" => $result,
    //                 "result" => true,
    //                 "message" => 'Information get Successfully'
    //             ]);
    //         }
    //         else
    //         {
    //              return response()->json([
    //                 "data" => array(),
    //                 "result" => false,
    //                 "message" => 'Information not found'
    //             ]);
                
    //         }
    //     }
    //     catch(Exception $e) {
    //       return  'Message: ' .$e->getMessage();
    //     }
        
        
    // }
    
    
    public function allorderproductlist_by_distributor_mobileapp(Request $request)
    {
        try
        {
            // $result = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
            //     ->where('tbl_product.is_deleted','no')
            //     ->where('tbl_product_details.is_deleted','no')
            //     ->where('tbl_product.created_by',$request->created_by)
            //     ->select('tbl_product_details.*','tbl_product.title','tbl_product.content','tbl_product.link')
            //     ->get();
                
            $result = OrderSummary::join('tbl_order_detail','tbl_order_detail.order_no','=','tbl_order_summary.order_no')
            ->join('tbl_product','tbl_product.id','=','tbl_order_detail.prod_id')
                ->where('tbl_order_detail.is_deleted','no')
                ->where('tbl_order_summary.is_deleted','no')
                ->where('tbl_order_summary.order_dispatched','no')
                ->where('tbl_order_summary.created_disctributor_id',$request->created_by)
                ->select('tbl_order_detail.*','tbl_order_summary.*','tbl_product.title')
                ->get();

            //dd($result);
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
    
    
    // public function allorderproductlist_by_distributor_mobileapp(Request $request)
    // {
    //     try
    //     {
    //         $result = OrderSummary::join('tbl_order_detail','tbl_order_detail.order_no','=','tbl_order_summary.order_no')
    //         ->join('tbl_product','tbl_product.id','=','tbl_order_detail.prod_id')
    //             ->where('tbl_order_detail.is_deleted','no')
    //             ->where('tbl_order_summary.is_deleted','no')
    //             ->where('tbl_order_summary.order_dispatched','no')
    //             ->where('tbl_order_summary.created_disctributor_id',$request->created_by)
    //             ->select('tbl_order_detail.*','tbl_order_summary.*','tbl_product.title')
    //             ->get();

    //         //dd($result);
    //         // foreach($result as $key=>$value)
    //         // {
    //         //     $value->photopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
    //         // }
    //         if ($result)
    //         {
    //              return response()->json([
    //                 "data" => $result,
    //                 "result" => true,
    //                 "message" => 'Information get Successfully'
    //             ]);
    //         }
    //         else
    //         {
    //              return response()->json([
    //                 "data" => array(),
    //                 "result" => false,
    //                 "message" => 'Information not found'
    //             ]);
                
    //         }
    //     }
    //     catch(Exception $e) {
    //       return  'Message: ' .$e->getMessage();
    //     }
        
        
    // }
    
    // Language View
    public function languageview(Request $request)
    {
        
        try
        {
            $languageview= Language::where('status',0)->where('activeinactive',0)->get();
            
            if ($languageview)
            {
                 return response()->json([
                    "data" => $languageview,
                    "result" => true,
                    "message" => 'Language Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Language Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    public function allvideo(Request $request)
    {
        $allvideo = WebVideos::where('status',0)->orderBy('id', 'DESC')->get();
      
        if ($allvideo)
        {
             return response()->json([
                "data" => $allvideo,
                "result" => true,
                "message" => 'Videos Get Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Videos Not Found'
            ]);
            
        }
    }
    
    
    public function allvideoadd(Request $request)
    {
        $allvideo = new Allvideo();
        $allvideo->title = $request->title;
        $allvideo->description = $request->description;
        $allvideo->language = $request->language;
        $allvideo->url = $request->url;
        $allvideo->is_deleted = 'no';
        $allvideo->active = 'yes';
        $allvideo->save();
       
        if ($allvideo)
        {
             return response()->json([
                "data" => $allvideo,
                "result" => true,
                "message" => 'Video Added Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Web Video Not Added'
            ]);
            
        }
    }
    
    public function allvideoupdate(Request $request)
    {
        $data=[
                'title'=>$request->title,
                'description' => $request->description,
                'language' => $request->language,
                'url' => $request->url,
            ];
        $allvideo = Allvideo::where('id',$request->id)->update($data);
      
        if ($allvideo)
        {
             return response()->json([
                "data" => $allvideo,
                "result" => true,
                "message" => 'Video Updated Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Video Not Updated'
            ]);
            
        }
    }
    
    public function allvideoget(Request $request)
    {
        $allvideo = Allvideo::where('id',$request->id)->get();
      
        if ($allvideo)
        {
             return response()->json([
                "data" => $allvideo,
                "result" => true,
                "message" => 'Video Get Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Video Not Found'
            ]);
            
        }
    }
    
    public function allvideodelete(Request $request)
    {
        $allvideo = Allvideo::where('id',$request->id)->update(['is_deleted'=>'yes']);
      
        if ($allvideo)
        {
             return response()->json([
                "data" => $allvideo,
                "result" => true,
                "message" => 'Video Deleted Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => array(),
                "result" => false,
                "message" => 'Video Not Found'
            ]);
            
        }
    }
    // Farmer List under Distributor
    public function farmer_under_distributor_mobileapp(Request $request)
    {
        try
        {
             $farmerlist_record= UsersInfo::
             leftJoin('tbl_area as stateNew', function ($join) {
                $join->on('usersinfo.state', '=', 'stateNew.location_id');
            })

            ->leftJoin('tbl_area as districtNew', function ($join) {
                $join->on('usersinfo.district', '=', 'districtNew.location_id');
            })


            ->leftJoin('tbl_area as talukaNew', function ($join) {
                $join->on('usersinfo.taluka', '=', 'talukaNew.location_id');
            })

            ->leftJoin('tbl_area as cityNew', function ($join) {
                $join->on('usersinfo.city', '=', 'cityNew.location_id');
            })
             
                ->where('added_by',$request->disctributor_id)
                    // ->when($request->get('farmer_name'), function($query) use ($request) {
                    //     $query->where('fname', 'like', '%' . $request->farmer_name . '%')
                    //           ->orWhere('mname', 'like', '%' . $request->farmer_name . '%')
                    //           ->orWhere('lname', 'like', '%' . $request->farmer_name . '%');
                    // }) 
                 

                    ->when($request->get('farmer_name'), function($queryNew) use ($request) {
                        $queryNew->where(function ($query) use ($request) {
                            $query->where('fname', 'like', '%' . $request->farmer_name . '%')
                                  ->orWhere('mname', 'like', '%' . $request->farmer_name . '%')
                                  ->orWhere('lname', 'like', '%' . $request->farmer_name . '%');
                        });
                    })

                    
                    ->where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','farmer')
                    ->select(
                        'usersinfo.*',
                        'stateNew.name as state_name',
                        'districtNew.name as district_name',
                        'talukaNew.name as taluka_name',
                        'cityNew.name as city_name'
                        )
                    ->orderBy('id', 'DESC')
                    ->get();
                    
            $farmerlist_recorddcount=sizeof($farmerlist_record);
            if($farmerlist_recorddcount>0)
            
            {

                foreach ($farmerlist_record as $key => $value) {
                    $value->photo = FARMER_PHOTO_VIEW.$value->photo;
                }

                
                 return response()->json([
                    "data" => $farmerlist_record,
                    "result" => true,
                    "message" => 'Farmer Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Farmer Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    // Distributor List under Distributor
    public function distributor_under_distributor_mobileapp(Request $request)
    {
        try
        {

            $farmerlist_record_user_type= UsersInfo::where('user_id',$request->disctributor_id)->select('user_type')->first();
         

            $farmerlist_record= UsersInfo::where('added_by',$request->disctributor_id)
                    ->where('is_deleted','no')
                    ->where('active','yes');

            if($farmerlist_record_user_type->user_type == 'dsc') {
                $farmerlist_record = $farmerlist_record->where('user_type','bsc');
            } else if($farmerlist_record_user_type->user_type == 'bsc')  {
                $farmerlist_record = $farmerlist_record->where('user_type','fsc');
            }
            
            $farmerlist_record = $farmerlist_record->get();
                    
            $farmerlist_recorddcount=sizeof($farmerlist_record);
            if($farmerlist_recorddcount>0)
            
            {
                 return response()->json([
                    "data" => $farmerlist_record,
                    "result" => true,
                    "message" => 'Distributor Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Distributor Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    
    public function allproductlistofdistributor_mobileapp(Request $request)
    {
        try
        {
            $result = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
                ->where('tbl_product.is_deleted','no')
                ->where('tbl_product_details.is_deleted','no')
                ->where('tbl_product.created_by',$request->created_by)
                ->select('tbl_product_details.*','tbl_product.title','tbl_product.content','tbl_product.link')
                ->get();

            //dd($result);
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
    
    
    
    
    
    // My Visit Search by Visit No.
    public function myvisit_search_by_visitno_mobileapp(Request $request)
    {
        try
        {
             $myvisit_search_by_visitno= FarmerVistByDistributor::where(['id'=>$request->id,"created_by"=>$request->created_by ])
                     ->where('status',0)
                     ->orderBy('id', 'DESC')
                    ->get();
                    
            $myvisit_search_by_visitnocount=sizeof($myvisit_search_by_visitno);
            if($myvisit_search_by_visitnocount>0)
            
            {
                 return response()->json([
                    "data" => $myvisit_search_by_visitno,
                    "result" => true,
                    "message" => 'Visit Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => array(),
                    "result" => false,
                    "message" => 'Visit Record Not Found'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    
    
    // Purchase Search
    public function ordersearch_by_orderno_mobileapp(Request $request)
    {
        try
        {
            // $temp = [];
            // $result = OrderSummary::where('tbl_order_summary.order_no','like','%'.$request->order_no.'%')
            // ->where('tbl_order_summary.created_disctributor_id',$request->disctributor_id)
            // ->where('tbl_order_summary.is_deleted','no')->first();
            // $data=[];
            // $data['id'] = $result['id'];
            // $data['order_no'] = $result['order_no'];
            // $data['order_date'] = $result['order_date'];
            // $data['created_disctributor_amount'] = $result['created_disctributor_amount'];
            // $data['total_items'] = OrderDetail::where('order_no','like','%'.$result['order_no'].'%')->count();
            // array_push($temp,$data);

            $result = OrderSummary::where('is_deleted','no')
                        ->where('tbl_order_summary.order_no','like','%'.$request->order_no.'%')
                        ->where('created_disctributor_id',$request->disctributor_id)
                        ->get();
            
            foreach($result as $key=>$resultnew)
            {
                if($resultnew->account_approved=='no' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Pending';
                }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='no'){
                    $resultnew->status = 'Verified';
                }elseif($resultnew->order_dispatched=='yes'){
                    $resultnew->status = 'Order Dispatched From warehouse';
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
                            "data" => array(),
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }


            if (!empty($result))
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
    
    
    
    
    // Sale Search
    public function salesearch_by_orderno_mobileapp(Request $request)
    {
        try
        {
            $result = SaleSummary::
            leftJoin('usersinfo as newuser_table_order_for', function($join) {
                $join->on('newuser_table_order_for.user_id', '=','tbl_sale_summary.order_created_for');
            })
            ->where('tbl_sale_summary.is_deleted','no')
            ->where('tbl_sale_summary.order_no','like','%'.$request->order_no.'%')
            ->where('tbl_sale_summary.created_disctributor_id',$request->disctributor_id)
            ->select(
                        'tbl_sale_summary.*',
                        'newuser_table_order_for.fname as order_for_fname',
                        'newuser_table_order_for.mname as order_for_mname',
                        'newuser_table_order_for.lname as order_for_lname')
            ->get();
                foreach($result as $key=>$resultnew)
                {
                    if($resultnew->account_approved=='no' && $resultnew->forward_to_warehouse=='no'){
                        $resultnew->status = 'Pending';
                    }elseif($resultnew->account_approved=='yes' && $resultnew->forward_to_warehouse=='no'){
                        $resultnew->status = 'Verified';
                    }elseif($resultnew->order_dispatched=='yes'){
                     $resultnew->status = 'Order Dispatched From warehouse';
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
                                "data" => array(),
                                "result" => false,
                                "error" => true,
                                "message" =>$e->getMessage()." ".$e->getCode()
                            ]);
                    
                    }
                }


    
            if (!empty($result))
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
    
    
    
    // Download Language Brochure Search
    public function language_brochure_search(Request $request)
    {
        // $result = Downloads::where('title', 'like', '%' . $request->title . '%')->where('language', '=',$request->lang)->where('status', '=', 0)->where('content_type', '=', $request->content_type )->get();
        $lang = strtolower($request->lang);
        $contentType = strtolower($request->content_type);
        $title = strtolower($request->title);

        // Check if lang and content_type are provided
        $query = Downloads::where('status', 0);

        if ($title) {
            // $query->where('title', 'like', '%' . $title . '%');
            $query->whereRaw('LOWER(title) LIKE ?', ['%' . $title . '%']);
        }

        if ($lang) {
            $query->where('language', 'like', '%' . $lang . '%');
        }


        if ($contentType) {
            $query->where('content_type', $contentType);
        }

        $result = $query->get();

        if (count($result) > 0)
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


    public function video_watch_view_add(Request $request)
    {
        try
        {
            $video_history_watch= VideoWatchHistory::where(['user_id' =>$request->dist_id,'video_id' =>$request->video_id])->first();

            if ($video_history_watch)
            {
                $video_total_duration_watch  = $video_history_watch->video_total_duration_watch;
                $video_total_duration_watch_final  = (int)$video_total_duration_watch + (int)$request->watch_seconds;
                $video_history_watch_update= VideoWatchHistory::where([
                                                    'user_id' =>$request->dist_id,
                                                    'video_id' =>$request->video_id
                                                    ])
                                                    ->update([
                                                        'video_total_duration_watch'=>$video_total_duration_watch_final
                                                    ]);


                 return response()->json([
                    "data" => $video_history_watch_update,
                    "result" => true,
                    "message" => 'Record Updated Successfully'
                ]);
            }
            else
            {

                $video_history_watch_update= VideoWatchHistory::insert([
                                                    'user_id'  => $request->dist_id,
                                                    'video_id' => $request->video_id,
                                                    'video_total_duration_watch'=> $request->watch_seconds,
                                                    'video_total_duration'=> $request->video_duration
                                                ]);


                return response()->json([
                    "data" => $video_history_watch_update,
                    "result" => true,
                    "message" => 'Record Updated Successfully'
                ]);
                
            }
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    function secondsToHoursMinutes($seconds) {
        // Calculate hours
        $hours = floor($seconds / 3600);
        
        // Calculate remaining minutes
        $minutes = floor(($seconds % 3600) / 60);
        
        // Return the result as an array
        return ['hours' => $hours, 'minutes' => $minutes];
    }
    

    

    public function target_video_watch_record_view_admin(Request $request)
    {
        try
        {

            $targetvideo = VideoWatchHistory::
                                    leftJoin('tbl_target_videos_to_distributor', function($join) {
                                        $join->on('video_watch_history.video_id','=','tbl_target_videos_to_distributor.id');
                                    })
                                    ->leftJoin('tbl_target_videos', function($join) {
                                        $join->on('tbl_target_videos_to_distributor.target_vedio_id','=','tbl_target_videos.id');
                                    })
                                    ->leftJoin('usersinfo', function($join) {
                                        $join->on('video_watch_history.user_id','=','usersinfo.user_id');
                                    });

            if(isset($request->dist_id) && $request->get('dist_id') !='') {
                $targetvideo = $targetvideo->where('video_watch_history.user_id', $request->dist_id);
            }
            $targetvideo = $targetvideo->select(   'video_watch_history.*',
                                                'usersinfo.fname',
                                                'usersinfo.mname',
                                                'usersinfo.lname',
                                                'usersinfo.email',
                                                'usersinfo.phone',
                                                'usersinfo.user_type',
                                                'tbl_target_videos.title',
                                                'tbl_target_videos.description',
                                                'tbl_target_videos.url',
                                            )
                                    ->get();
            
           

            return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Record get Successfully'
            ]);
        
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    

  


    public function target_video_watch_record_view(Request $request)
    {
        try
        {
    
            $targetvideo = VideoWatchHistory::
                // leftJoin('tbl_target_videos_to_distributor', function($join) {
                //     $join->on('video_watch_history.video_id', '=', 'tbl_target_videos_to_distributor.target_vedio_id');
                // })
                leftJoin('tbl_target_videos', function($join) {
                    $join->on('video_watch_history.video_id', '=', 'tbl_target_videos.id');
                })
                ->leftJoin('usersinfo', function($join) {
                    $join->on('video_watch_history.user_id', '=', 'usersinfo.user_id');
                })
                ->where('video_watch_history.user_id', $request->dist_id)
                ->select(   
                    'video_watch_history.id',
                    'video_watch_history.user_id',
                    'video_watch_history.video_id',
                    'video_watch_history.video_total_duration',
                    'video_watch_history.video_total_duration_watch',
                    'video_watch_history.created_at',
                    'video_watch_history.updated_at',
                    'usersinfo.fname',
                    'usersinfo.mname',
                    'usersinfo.lname',
                    'usersinfo.email',
                    'usersinfo.phone',
                    'usersinfo.user_type',
                    'tbl_target_videos.title',
                    'tbl_target_videos.description',
                    'tbl_target_videos.url',
                )
                ->get();
           
               // Convert created_at and updated_at to Asia/Kolkata time zone
            $targetvideo->transform(function($item) {
                $item->created_at = Carbon::parse($item->created_at)->setTimezone('Asia/Kolkata')->toDateTimeString();
                $item->updated_at = Carbon::parse($item->updated_at)->setTimezone('Asia/Kolkata')->toDateTimeString();
                $item->description = strip_tags($item->description); // Remove HTML tags

                return $item;
            });
            return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Record get Successfully'
            ]);
        
        } catch(Exception $e) {
            return response()->json([
                    "data" => array(),
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
     
    
}
