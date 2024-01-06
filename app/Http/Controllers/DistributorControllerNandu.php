<?php

namespace App\Http\Controllers;
use Exception;
use JWTAuth;
use App\Task;
use Illuminate\Http\Request;
use App\Model\UsersInfo;
use App\User;
use App\Model\FarmerMeeting;
use App\Model\DistributorMeeting;
use App\Model\TargetVideos;
use App\Model\TargetVideosToDistributor;
use App\Model\FarmerVistByDistributor;
use App\Model\SCTResult;
use App\Model\WebAgency;
use App\Model\Subscriber;
use App\Model\SubscriberTarget;
use App\Model\Messages;
use App\Model\Complaint;
use App\Model\WebBlog;
use App\Model\OrderSummary;
use App\Model\OrderDetail;
use App\Model\SaleSummary;
use App\Model\SaleDetail;
use App\Model\Video;
use App\Model\Language;
use App\Model\Allvideo;
use App\Model\ProductDetails;
use App\Model\Downloads;
use App\Model\WebVideos;
use DB;
use App\Http\Controllers\CommonController As CommonController;

class DistributorControllerNandu extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->commonController=new CommonController();
        $this->user = JWTAuth::parseToken()->authenticate();
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
        
        $count=sizeof($count);
            
            if ($count)
            {
                 return response()->json([
                    "data" => $count,
                    "result" => true,
                    "message" => 'Farmers Count Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmers Count Not Found'
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
    
    // Target Youtube Video Link Count 
    public function mytarget_youtubevideolinkcount_mobileapp(Request $request)
    {
        try
        {
             $count= TargetVideosToDistributor::where('target_vedio_id',$request->target_vedio_id)
                    ->where('is_deleted','no')
                    ->where('active','yes')
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Video Link Count Not Found'
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Subscriber List Count Not Found'
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Subscriber Target Count Not Found'
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
    
    
    
    
    
    // Farmers Meeting Search
    public function farmer_meeting_search_mobileapp(Request $request)
    {
        try
        {
            
             $farmer_meering_record= FarmerMeeting::where('created_by',$request->created_by)
                    ->whereBetween('date', [$request->fromdate,$request->todate])
                    ->where('is_deleted','no')
                    ->get();
                    
            $farmer_meering_recordcount=sizeof($farmer_meering_record);
            if($farmer_meering_recordcount>0)
            
            {
                 return response()->json([
                    "data" => $farmer_meering_record,
                    "result" => true,
                    "message" => 'Farmers Meeting Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmers Meeting Record Not Found'
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
    
    
    
    
    
    // Farmers Meeting Search by title
    public function farmer_meeting_title_search_mobileapp(Request $request)
    {
        try
        {
             $farmer_meering_title_record= FarmerMeeting::where('meeting_title', 'like', '%' . $request->search . '%')
                    ->where('created_by',$request->created_by)
                    ->where('is_deleted','no')
                    ->get();

            $farmer_meering_title_recordcount=sizeof($farmer_meering_title_record);
            if($farmer_meering_title_recordcount>0)
            
            {
                 return response()->json([
                    "data" => $farmer_meering_title_record,
                    "result" => true,
                    "message" => 'Farmers Meeting Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmers Meeting Record Not Found'
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
                "data" => '',
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Meeting Record Not Found'
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Meeting Record Not Found'
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
                "data" => '',
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
             $sct_result_search_by_date= SCTResult::where('is_deleted','no')
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
                    "data" => '',
                    "result" => false,
                    "message" => 'SCT Result Record Not Found'
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
                "data" => '',
                "result" => false,
               "message" => 'Message Not Added'
            ]);
            
        }
    }
    
    
    
    // Messages Update
    public function messageedit(Request $request)
    {
        $data=[
                'recipient_name' => $request->recipient_name,
                'subject' => $request->subject,
                'message' => $request->message
              ];
        $messageupdate = Messages::where('id',$request->messageid)->update($data);
      
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
                "data" => '',
                "result" => false,
                "message" => 'Message Not Updated'
            ]);
            
        }
    }
    
    
    
    // Message View
    public function messageview(Request $request)
    {
        try
        {
            $messageview= Messages::where('is_deleted', 'no')->orderBy('id', 'DESC')->get();
            
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
    
    
    
    
    
    
    
    // Message search by Subject
    public function messagesearch(Request $request)
    {
        try
        {
             $messagesearch= Messages::where('subject', 'like', '%' . $request->search . '%')
                    ->where('is_deleted','no')
                    ->orderBy('id', 'DESC')
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
                "data" => '',
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Visit Record Not Found'
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
                "data" => '',
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
            $complaintview= Complaint::where('is_deleted', 'no')->orderBy('id', 'DESC')->get();
            
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
    
    
    
    
    
    
    
    // Complaint Update
    public function complaintedit(Request $request)
    {
        $data=[
                'recipient_name' => $request->recipient_name,
                'subject' => $request->subject,
                'complaint' => $request->complaint
              ];
        $complaintupdate = Complaint::where('id',$request->complaintid)->update($data);
      
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
                "data" => '',
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
             $complaintsearch= Complaint::where('subject', 'like', '%' . $request->search . '%')
                    ->where('is_deleted','no')
                    ->orderBy('id', 'DESC')
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
                "data" => '',
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Order Record Not Found'
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
    
    
    
    // Videos search by Title
    public function videossearch_mobileapp(Request $request)
    {
        try
        {
             $videossearch= Video::where('title', 'like', '%' . $request->search . '%')
                    ->where('status',0)
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Videos Record Not Found'
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Video Record Not Found'
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
                    "data" => '',
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
             $sct_result_search__by_title= SCTResult::where('title', 'like', '%' . $request->search . '%')
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
                    "data" => '',
                    "result" => false,
                    "message" => 'SCT Result Record Not Found'
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
    
    
    
    // Sale List Search by Date
    public function salelist_by_date_mobileapp(Request $request)
    {
        try
        {
             $salelist_by_date_record= SaleSummary::where('created_disctributor_id',$request->disctributor_id)
                    ->whereBetween('order_date', [$request->fromdate,$request->todate])
                    ->where('is_deleted','no')
                    ->where('entry_by','distributor')
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Sale Record Not Found'
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
    
    
    
    public function salelist_mobileapp(Request $request)
    {
        try
        {
             $result = SaleSummary::where('is_deleted','no')
                        ->where('created_disctributor_id',$request->created_disctributor_id)
                        ->orderBy('id','DESC')
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
    
    public function saleview_mobileapp(Request $request)
    {
        try
        {
            $result = SaleSummary::where('order_no',$request->order_no)
            ->where('tbl_sale_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_sale_summary.is_deleted','no')->get();
        
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
                $value->all_product = SaleDetail::leftJoin('tbl_product','tbl_product.id','=','tbl_sale_detail.prod_id')
                                    ->where('tbl_sale_detail.order_no',$request->order_no)
                                    ->where('tbl_sale_detail.is_deleted','no')
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
    //                 "data" => '',
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
    //                 "data" => '',
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Language Record Not Found'
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
                "data" => '',
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
                "data" => '',
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
                "data" => '',
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
                "data" => '',
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
                "data" => '',
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
             $farmerlist_record= UsersInfo::where('added_by',$request->disctributor_id)
                    ->where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','farmer')
                    ->orderBy('id', 'DESC')
                    ->get();
                    
            $farmerlist_recorddcount=sizeof($farmerlist_record);
            if($farmerlist_recorddcount>0)
            
            {
                 return response()->json([
                    "data" => $farmerlist_record,
                    "result" => true,
                    "message" => 'Farmer Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Farmer Record Not Found'
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
    
    
    
    // Distributor List under Distributor
    public function distributor_under_distributor_mobileapp(Request $request)
    {
        try
        {
             $farmerlist_record= UsersInfo::where('added_by',$request->disctributor_id)
                    ->where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','fsc')
                    ->get();
                    
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor Record Not Found'
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
    
    
    
    
    
    // My Visit Search by Visit No.
    public function myvisit_search_by_visitno_mobileapp(Request $request)
    {
        try
        {
             $myvisit_search_by_visitno= FarmerVistByDistributor::where('visit_no',$request->visit_no)
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
                    "data" => '',
                    "result" => false,
                    "message" => 'Visit Record Not Found'
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
                            "data" => '',
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
    
    
    
    
    // Sale Search
    public function salesearch_by_orderno_mobileapp(Request $request)
    {
        try
        {
            $result = SaleSummary::where('is_deleted','no')
            ->where('tbl_sale_summary.order_no','like','%'.$request->order_no.'%')
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
                                "data" => '',
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
    
    
    
    // Download Language Brochure Search
    public function language_brochure_search(Request $request)
    {
        $result = Downloads::where('title', 'like', '%' . $request->title . '%')->where('language', '=',$request->lang)->where('status', '=', 0)->where('content_type', '=', $request->content_type )->get();

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
    
    
     
    
}
