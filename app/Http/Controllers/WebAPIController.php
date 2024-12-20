<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;
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
                WebCompanyProfile,
                WebAboutUs,
                WebCoverPhoto,
                WebGallaryPhoto,
                WebVisionMission,
                WebVideos,
                WebBlog,
                Career,
                Marquee,
                Enquiry,
                WebTestiminials,
                FrontUsers,
                WebAudio,
                Product,
                FrontProduct,
                ProductReview,
               BlogReply,
               WebAgency,
               ProductDetails,
               Notification,
               SCTResult,
               Subscriber,
               Dist_Promotion_Demotion,
               WebClientLogo,
               Counter,
               WebInternship,
               WebJobPosting,

            SaleSummary,
            SaleDetail,
            OrderSummary,
            OrderDetail,
            Downloads,
            Address,
            Principles,

        };
use App\Models\{
    Crops,
    UsersInfoForStructures
};
use File;
use Carbon\Carbon;
use DB;
use Rap2hpoutre\FastExcel\FastExcel;

use App\Http\Controllers\CommonController As CommonController;

class WebAPIController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->commonController=new CommonController();
        
        // try 
        // {
        
        //     if (! $user = JWTAuth::parseToken()->authenticate()) {
        //             return response()->json(['user_not_found'], 404);
        //     }
            
        //     } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            
        //     return response()->json(['token_expired'], $e->getStatusCode());
            
        //     } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            
        //     return response()->json(['token_invalid'], $e->getStatusCode());
            
        //     } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            
        //     return response()->json(['token_absent'], $e->getStatusCode());
        
        // }

        // return response()->json(compact('user'));
                    
       
       // $this->commonController->validateToken($this->user);
    }
    
    
     public function career_list_web(Request $request)
    {
        try
        {
            $result = Career::where('is_deleted','no')->orderBy('id', 'ASC')->get();
            foreach($result as $key=>$value)
            {
                $value->internshipmenuphoto=WEB_CAREER_VIEW.$value->internshipmenuphoto;
                $value->dsitmenuphotoview=WEB_CAREER_VIEW.$value->dsitmenuphotoview;
                $value->jobmenuphotoview=WEB_CAREER_VIEW.$value->jobmenuphotoview;
                $value->certificatephotoview=WEB_CAREER_VIEW.$value->certificatephotoview;
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
    
 
    public function plotvisitlist_web(Request $request)
    {
        try 
        {
            
            $farmerVistByDistributor = FarmerVistByDistributor::leftJoin('usersinfo','usersinfo.user_id','=','tbl_farmer_vist_by_distributor.farmer_id')
            ->where('tbl_farmer_vist_by_distributor.status',0)
            ->select('tbl_farmer_vist_by_distributor.*','usersinfo.fname','usersinfo.mname','usersinfo.lname','usersinfo.state','usersinfo.district','usersinfo.taluka','usersinfo.city','usersinfo.user_id')
            ->orderBy('tbl_farmer_vist_by_distributor.id', 'DESC')
            
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
            if(!$farmerVistByDistributor) {
                throw new Exception(api_error(1006), 1006);
            }
            foreach($farmerVistByDistributor as $key=>$farmerVistByDistributorNew)
            {
                $farmerdetails=$this->commonController->getFarmerNameById($farmerVistByDistributorNew->farmer_id);
                if(!$farmerdetails) {
                    throw new Exception('unable to get farmer details');
                }
                $farmerVistByDistributorNew->ffname=$farmerdetails->fname;
                $farmerVistByDistributorNew->fmname=$farmerdetails->mname;
                $farmerVistByDistributorNew->flname=$farmerdetails->lname;
                
                // $farmerdetails=$this->commonController->getDistributorNameById($farmerVistByDistributorNew->created_by);
                // $farmerVistByDistributorNew->dfname=$farmerdetails->fname;
                // $farmerVistByDistributorNew->dmname=$farmerdetails->mname;
                // $farmerVistByDistributorNew->dlname=$farmerdetails->lname;

                $stateName=$this->commonController->getAreaNameById($farmerdetails->state);
                $farmerVistByDistributorNew->state=$stateName->name;
                
                $districtName=$this->commonController->getAreaNameById($farmerdetails->district);
                $farmerVistByDistributorNew->district=$districtName->name;
                
                $talukaName=$this->commonController->getAreaNameById($farmerdetails->taluka);
                $farmerVistByDistributorNew->taluka=$talukaName->name;
                
                $cityName=$this->commonController->getAreaNameById($farmerdetails->city);
                $farmerVistByDistributorNew->city=$cityName->name;
        
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
    
    public function plotvisitdelete_web(Request $request)
    {
        try 
        {
            $farmerVistByDistributor = FarmerVistByDistributor::where('id',$request->visit_id)->update(['status'=>1]);
          
            if ($farmerVistByDistributor)
            {
                 return response()->json([
                    "data" => $farmerVistByDistributor,
                    "result" => true,
                    "message" => 'Plot Vist Towards Farmer Deleted Successfully'
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
    
    public function plotvisitget_web(Request $request)
    {
        try 
        {
            $farmerVistByDistributor = FarmerVistByDistributor::where('id',$request->visit_id)->where('status',0)->get();
            
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
    
    
    
    
    
    public function farmervisitdetails(Request $request)
    {
        try 
        {
            // $result = FarmerVistByDistributor::where('id',$request->id)->get();
            $result = FarmerVistByDistributor::join('usersinfo','usersinfo.user_id','=','tbl_farmer_vist_by_distributor.created_by')
            ->where('tbl_farmer_vist_by_distributor.status',0)
            ->where('tbl_farmer_vist_by_distributor.id',$request->id)
            ->select('tbl_farmer_vist_by_distributor.*','usersinfo.fname','usersinfo.mname','usersinfo.lname','usersinfo.state','usersinfo.district','usersinfo.taluka','usersinfo.city','usersinfo.user_id')
            ->orderBy('tbl_farmer_vist_by_distributor.id', 'DESC')
            ->get();
            foreach($result as $key=>$value)
            {
                $farmerdetails=$this->commonController->getFarmerNameById($value->farmer_id);
                if(!$farmerdetails) {
                    throw new Exception('unable to get farmer details');
                }
                $value->ffname=$farmerdetails->fname;
                $value->fmname=$farmerdetails->mname;
                $value->flname=$farmerdetails->lname;
                
                $value->photopathone=FARMER_VISIT_PHOTO_VIEW.$value->photo_one;
                $value->photopathtwo=FARMER_VISIT_PHOTO_VIEW.$value->photo_two;
                $value->photopaththree=FARMER_VISIT_PHOTO_VIEW.$value->photo_three;
                $value->photopathfour=FARMER_VISIT_PHOTO_VIEW.$value->photo_four;
                $value->photopathfive=FARMER_VISIT_PHOTO_VIEW.$value->photo_five;
            }
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
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
    
    public function plotvisitadd_web(Request $request)
    {
        try 
        {
            $farmerVistByDistributor = new FarmerVistByDistributor();
            $farmerVistByDistributor->date = $request->date;
            $farmerVistByDistributor->visit_no = $request->visit_no;
            $farmerVistByDistributor->farmer_id = $request->farmer_id;
            $farmerVistByDistributor->crop = $request->crop;
            $farmerVistByDistributor->acer = $request->acer;
            $farmerVistByDistributor->description_about_visit = $request->description_about_visit;
            $farmerVistByDistributor->about_visit = $request->about_visit;
            $farmerVistByDistributor->created_by = 1;
            $farmerVistByDistributor->status = 0;
            $farmerVistByDistributor->save();
           
            if ($farmerVistByDistributor)
            {
                 return response()->json([
                    "data" => $farmerVistByDistributor,
                    "result" => true,
                    "message" => 'Plot Vist Towards Farmer Added Successfully'
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
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    public function plotvisitupdate_web(Request $request)
    {
        try 
        {
            $data=[
                    'farmer_id'=>$request->farmer_id,
                    'crop' => $request->crop,
                    'acer' => $request->acer,
                    'description_about_visit' => $request->description_about_visit,
                    'about_visit' => $request->about_visit
                ];
            //$farmerVistByDistributor = FarmerVistByDistributor::where('created_by',0)->where('id',$request->visit_id)->update($data);
            $farmerVistByDistributor = FarmerVistByDistributor::where('id',$request->visit_id)->update($data);
          
            if ($farmerVistByDistributor)
            {
                 return response()->json([
                    "data" => $farmerVistByDistributor,
                    "result" => true,
                    "message" => 'Plot Vist Towards Farmer Updated Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Plot Vist Towards Farmer Not Updated'
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
    
    //Company Profile
    public function deleteFile($user, $path)
    {
        $files = array();
        $files[] = $path.$user;
        File::delete($files);
    }
    public function companyprofileadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebCompanyProfile();
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=WEB_COMPANY_PROFILE_PHOTO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_companyprofile";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);  
                   
                }
                $users=WebCompanyProfile::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
                    "result" => true,
                    "message" => 'Company Profile Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Company Profile Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function companyprofileupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=WEB_COMPANY_PROFILE_PHOTO_UPLOAD;
                $userId = WebCompanyProfile::where('id',$requestdata->id)->first();
                //dd($userId);
                $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_companyprofile";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebCompanyProfile::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }
 
            $data=[
                'title'=> $requestdata->title,
                'content'=> $requestdata->content
            ];
            
            $user = WebCompanyProfile::where('id',$requestdata->id)->update($data);

            if ($user)
            {
                 return response()->json([
                    "data" => $user,
                    "result" => true,
                    "message" => 'Company Profile Updated Successfully'
                ]);
            }
            elseif($request->hasFile('photo_one'))
            {
                return response()->json([
                "data" => $user,
                "result" => true,
                "message" => 'Company Profile Image Updated Successfully'
                ]);
            }
            else
            {
        
                
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Company Profile Not Updated'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function companyprofileget(Request $request)
    {
        try
        {
            $result = WebCompanyProfile::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_COMPANY_PROFILE_PHOTO_VIEW.$value->photo_one;
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
    
    public function companyprofiledelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebCompanyProfile::where('id',$request->id)->update($data);
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
                    "result" => true,
                    "message" => 'Company Profile Deleted Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Company Profile Not Deleted'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function companyprofilelist(Request $request)
    {
        try
        {
            $result = WebCompanyProfile::where('status','0')->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_COMPANY_PROFILE_PHOTO_VIEW.$value->photo_one;
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
    
    
    //About Us
    public function webaboutusadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebAboutUs();
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=ABOUT_US_PHOTO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_companyprofile";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $users=WebAboutUs::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
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
    
    public function webaboutusupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=ABOUT_US_PHOTO_UPLOAD;
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_companyprofile";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebAboutUs::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }

            $data=[
                'title'=> $requestdata->title,
                'content'=> $requestdata->content
            ];
            
            $user = WebAboutUs::where('id',$requestdata->id)->update($data);
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function webaboutusget(Request $request)
    {
        try
        {
            $result = WebAboutUs::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=ABOUT_US_PHOTO_VIEW.$value->photo_one;
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
    
    public function webaboutusdelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebAboutUs::where('id',$request->id)->update($data);
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
    
    public function webaboutuslist(Request $request)
    {
        try
        {
            $result = WebAboutUs::where('status','0')->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=ABOUT_US_PHOTO_VIEW.$value->photo_one;
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
    
    //Cover Photo
    public function coverphotoadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebCoverPhoto();
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=WEB_COVER_PHOTO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_coverphoto";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $users=WebCoverPhoto::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
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
    
    public function coverphotoupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=WEB_COVER_PHOTO_UPLOAD;
                // $userId = WebCoverPhoto::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_coverphoto";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebCoverPhoto::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }
            
            
            
            $data=[
                'title'=> $requestdata->title,
                'content'=> $requestdata->content
            ];
            
            $user = WebCoverPhoto::where('id',$requestdata->id)->update($data);
            //dd($user);
          
           
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function coverphotoget(Request $request)
    {
        try
        {
            $result = WebCoverPhoto::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_COVER_PHOTO_VIEW.$value->photo_one;
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
    
    public function coverphotodelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebCoverPhoto::where('id',$request->id)->update($data);
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
    
    public function coverphotolist(Request $request)
    {
        try
        {
            $result = WebCoverPhoto::where('status','0')->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_COVER_PHOTO_VIEW.$value->photo_one;
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
    
    //Gallary Photo
    public function gallaryphotoadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebGallaryPhoto();
            
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->category = $requestdata->gallaryfor;
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
                $imagedataPath=WEB_GALLARY_PHOTO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_gallaryphoto";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $users=WebGallaryPhoto::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
                    "result" => true,
                    "message" => 'Photo Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Photo Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }
        
        

    }
    
    public function gallaryphotoupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=WEB_GALLARY_PHOTO_UPLOAD;
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_gallaryphoto";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebGallaryPhoto::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }
            
            
            
            $data=[
                'category'=> $requestdata->gallaryfor,
                'title'=> $requestdata->title,
                'content'=> $requestdata->content
            ];
            
            $user = WebGallaryPhoto::where('id',$requestdata->id)->update($data);
            
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function gallaryphotoget(Request $request)
    {
        try
        {
            $result = WebGallaryPhoto::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_GALLARY_PHOTO_VIEW.$value->photo_one;
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
    
    public function gallaryphotodelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebGallaryPhoto::where('id',$request->id)->update($data);
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
    
    public function gallaryphotolist(Request $request)
    {
        try
        {
            $result = WebGallaryPhoto::where('status','0')->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_GALLARY_PHOTO_VIEW.$value->photo_one;
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
    
    //Vision Mission
    public function webvisionmissionadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebVisionMission();
            $user->record_for = $requestdata->record_for;
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=WEB_VISIONMISSION_PHOTO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_gallaryphoto";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $users=WebVisionMission::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
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
    
    public function webvisionmissionupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=WEB_VISIONMISSION_PHOTO_UPLOAD;
                // $userId = WebVisionMission::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_gallaryphoto";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebVisionMission::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }
            
            
            
            $data=[
                'record_for'=> $requestdata->record_for,
                'title'=> $requestdata->title,
                'content'=> $requestdata->content
            ];
            
            $user = WebVisionMission::where('id',$requestdata->id)->update($data);
            //dd($user);
          
           
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function webvisionmissionget(Request $request)
    {
        try
        {
            $result = WebVisionMission::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_VISIONMISSION_PHOTO_VIEW.$value->photo_one;
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
    
    public function webvisionmissiondelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebVisionMission::where('id',$request->id)->update($data);
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
    
    public function webvisionmissionlist(Request $request)
    {
        try
        {
            $result = WebVisionMission::where('status','0')->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_VISIONMISSION_PHOTO_VIEW.$value->photo_one;
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
    
    
     public function webvideolist(Request $request)
    {
        try 
        {
            $targetvideo = WebVideos::where('status',0)->orderBy('id', 'DESC')->get();
            if(!$targetvideo) {
                throw new Exception(api_error(1006), 1006);
            }
           
          
            if ($targetvideo)
            {
                 return response()->json([
                    "data" => $targetvideo,
                    "result" => true,
                    "message" => 'Web Video Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Web Video Farmer Not Found'
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
    
    
    public function webvideodelete(Request $request)
    {
        $targetvideo = WebVideos::where('id',$request->id)->update(['status'=>1]);
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Web Video Deleted Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Web Video Not Found'
            ]);
            
        }
    }
    
    public function webvideoget(Request $request)
    {
        $targetvideo = WebVideos::where('id',$request->id)->orderBy('id','DESC')->where('status',0)->get();
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Web Video Get Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Web Video Not Found'
            ]);
            
        }
    }
    
    public function webvideoadd(Request $request)
    {
        $targetvideo = new WebVideos();
        $targetvideo->category = $request->videofor;
        $targetvideo->title = $request->title;
        $targetvideo->description = $request->description;
        $targetvideo->language = $request->language;
        $targetvideo->url = $request->url;
        $targetvideo->status = 0;
        $targetvideo->activeinactive = 0;
        $targetvideo->save();
       
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Web Video Added Successfully'
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
    
    public function webvideoupdate(Request $request)
    {
        $data=[
                'category'=>$request->videofor,
                'title'=>$request->title,
                'description' => $request->description,
                'language' => $request->language,
                'url' => $request->url,
            ];
        $targetvideo = WebVideos::where('id',$request->id)->update($data);
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Web Video Updated Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Web Video Not Updated'
            ]);
            
        }
    }
    ///////////////////////////////////////////////////
    //Blog Article
    public function webblogarticleadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebBlog();
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->language = $requestdata->language;
            $user->articleorschedule ='article';
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=BLOG_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_blog1";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                    $users=WebBlog::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_blog2";
                if (!empty($request->photo_two))
                {     
                    $applpic_ext = $request->file('photo_two')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_two'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                    $users=WebBlog::where('id',$user->id)->update(['photo_two'=>$photoName.".".$applpic_ext]);
                }
                
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
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
    
    public function webblogarticleupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=BLOG_CONTENT_UPLOAD;
                // $userId = WebBlog::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_companyprofile";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebBlog::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }
            
            if ($request->hasFile('photo_two'))
            {
                $imagedataPath=BLOG_CONTENT_UPLOAD;
                // $userId = WebBlog::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_two, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_companyprofile";
                if ($request->hasFile('photo_two'))
                {     
                    $applpic_ext = $request->file('photo_two')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_two'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebBlog::where('id',$requestdata->id)->update(['photo_two'=>$photoName.".".$applpic_ext]);
                }
            }

            $data=[
                'title'=> $requestdata->title,
                'content'=> $requestdata->content,
                'language'=> $requestdata->language
            ];
            
            $user = WebBlog::where('id',$requestdata->id)->update($data);
            //dd($user);
          
           
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function webblogarticleget(Request $request)
    {
        try
        {
            $result = WebBlog::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopathone=BLOG_CONTENT_VIEW.$value->photo_one;
                $value->photopathtwo=BLOG_CONTENT_VIEW.$value->photo_two;
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
    
    public function webblogarticledelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebBlog::where('id',$request->id)->update($data);
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
    
    public function webblogarticlelist(Request $request)
    {
        try
        {
            $result = WebBlog::where(['status'=>'0','articleorschedule' =>'article'])->orderBy('id', 'DESC')->get();
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
    
     ///////////////////////////////////////////////////
    //Blog Schedule
    public function webblogsscheduleadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebBlog();
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->language = $requestdata->language;
            $user->articleorschedule ='schedule';
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=BLOG_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_blog1";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                    $users=WebBlog::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_blog2";
                if (!empty($request->photo_two))
                {     
                    $applpic_ext = $request->file('photo_two')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_two'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                    $users=WebBlog::where('id',$user->id)->update(['photo_two'=>$photoName.".".$applpic_ext]);
                }
                
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
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
    
    public function webblogsscheduleupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=BLOG_CONTENT_UPLOAD;
                // $userId = WebBlog::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_blog1";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebBlog::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }
            
            if ($request->hasFile('photo_two'))
            {
                $imagedataPath=BLOG_CONTENT_UPLOAD;
                // $userId = WebBlog::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_two, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_blog2";
                if ($request->hasFile('photo_two'))
                {     
                    $applpic_ext = $request->file('photo_two')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_two'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebBlog::where('id',$requestdata->id)->update(['photo_two'=>$photoName.".".$applpic_ext]);
                }
            }

            $data=[
                'title'=> $requestdata->title,
                'content'=> $requestdata->content,
                'language'=> $requestdata->language
            ];
            
            $user = WebBlog::where('id',$requestdata->id)->update($data);
            //dd($user);
          
           
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function webblogsscheduleget(Request $request)
    {
        try
        {
            $result = WebBlog::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopathone=BLOG_CONTENT_VIEW.$value->photo_one;
                $value->photopathtwo=BLOG_CONTENT_VIEW.$value->photo_two;
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
    
    public function webblogsscheduledelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebBlog::where('id',$request->id)->update($data);
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
    
    public function webblogsschedulelist(Request $request)
    {
        try
        {
            $result = WebBlog::where(['status'=>'0','articleorschedule' =>'schedule'])->orderBy('id', 'DESC')->get();
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
    
    //Blog Testimonals
    public function webtestimonialsadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebTestiminials();
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->language = $requestdata->language;
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=TESTIMONIALS_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_Testimonials";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                    $users=WebTestiminials::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                   
                }
                
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
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
    
    public function webtestimonialsupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=TESTIMONIALS_CONTENT_UPLOAD;
                // $userId = WebTestiminials::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_Testimonials";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebTestiminials::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }

            $data=[
                'title'=> $requestdata->title,
                'content'=> $requestdata->content,
                'language'=> $requestdata->language
            ];
            
            $user = WebTestiminials::where('id',$requestdata->id)->update($data);
            //dd($user);
          
           
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function webtestimonialsget(Request $request)
    {
        try
        {
            $result = WebTestiminials::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=TESTIMONIALS_CONTENT_VIEW.$value->photo_one;
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
    
    public function webtestimonialsdelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebTestiminials::where('id',$request->id)->update($data);
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
    
    public function webtestimonialslist(Request $request)
    {
        try
        {
            $result = WebTestiminials::where('status','0')->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=TESTIMONIALS_CONTENT_VIEW.$value->photo_one;
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
    
    //Blog Testimonals
    public function webaudioadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new WebAudio();
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            $user->language = $requestdata->language;
            $user->created_by ='0';
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=AUDIO_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_audio";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $users=WebAudio::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }
            
            if ($users)
            {
                 return response()->json([
                    "data" => $users,
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
    
    public function webaudioupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=AUDIO_CONTENT_UPLOAD;
                // $userId = WebAudio::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_audio";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebAudio::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }

            $data=[
                'title'=> $requestdata->title,
                'content'=> $requestdata->content,
                'language'=> $requestdata->language
            ];
            
            $user = WebAudio::where('id',$requestdata->id)->update($data);
            //dd($user);
          
           
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function webaudioget(Request $request)
    {
        try
        {
            $result = WebAudio::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopathone=AUDIO_CONTENT_VIEW.$value->photo_one;
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
    
    public function webaudiodelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebAudio::where('id',$request->id)->update($data);
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
    
    public function webaudiolist(Request $request)
    {
        try
        {
            $result = WebAudio::where('status','0')->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=AUDIO_CONTENT_VIEW.$value->photo_one;
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
    
    
    //Product
    public function webproductadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new Product();
            $user->title = $requestdata->title;
            $user->content = $requestdata->content;
            
            $user->link = $requestdata->link;
            $user->created_by ='0';
            $user->save();
            //dd($user->id);
            
            if ($request->photo_one)
            {
        
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_product1";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $photoUpdated=Product::where('id',$user->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }

            if ($request->photo_two)
            {
        
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_product2";
                if (!empty($request->photo_two))
                {     
                    $applpic_ext = $request->file('photo_two')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_two'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $photoUpdated=Product::where('id',$user->id)->update(['photo_two'=>$photoName.".".$applpic_ext]);
            }

            if ($request->photo_three)
            {
        
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_product3";
                if (!empty($request->photo_three))
                {     
                    $applpic_ext = $request->file('photo_three')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_three'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $photoUpdated=Product::where('id',$user->id)->update(['photo_three'=>$photoName.".".$applpic_ext]);
            }

            if ($request->photo_four)
            {
        
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_product4";
                if (!empty($request->photo_four))
                {     
                    $applpic_ext = $request->file('photo_four')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_four'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $photoUpdated=Product::where('id',$user->id)->update(['photo_four'=>$photoName.".".$applpic_ext]);
            }

            if ($request->photo_five)
            {
        
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_product5";
                if (!empty($request->photo_five))
                {     
                    $applpic_ext = $request->file('photo_five')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_five'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $photoUpdated=Product::where('id',$user->id)->update(['photo_five'=>$photoName.".".$applpic_ext]);
            }

            foreach($requestdata->productDetails as $product)
            {
                $productDetails = new ProductDetails();
                $productDetails->product_id = $user->id;
                $productDetails->quantity = $product['quantity'];
                $productDetails->quantity_unit = $product['quantity_unit'];
                $productDetails->farmer_price = $product['farmer_price'];
                $productDetails->old_price = $product['old_price'];
                $productDetails->dsc_price = $product['dsc_price'];
                $productDetails->bsc_price = $product['bsc_price'];
                $productDetails->fsc_price = $product['fsc_price'];
                $productDetails->created_by ='0';
                $productDetails->save();
            }

            if ($productDetails)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function webproductupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            //dd($requestdata);
            $productDetails = ProductDetails::where('id',$requestdata->id)->first();
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                $userId = Product::where('id',$requestdata->id)->first();
                $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$productDetails->product_id."_product1";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                    $photoUpdated=Product::where('id',$productDetails->product_id)->update(['photo_one'=>$photoName.".".$applpic_ext]);          
                   
                }
                
            }

            if ($request->hasFile('photo_two'))
            {
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                $userId = Product::where('id',$requestdata->id)->first();
                $delfile=$this->deleteFile($userId->photo_two, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$productDetails->product_id."_product2";
                if (!empty($request->photo_two))
                {     
                    $applpic_ext = $request->file('photo_two')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_two'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                    $photoUpdated=Product::where('id',$productDetails->product_id)->update(['photo_two'=>$photoName.".".$applpic_ext]);          
                   
                }
                
            }

            if ($request->hasFile('photo_three'))
            {
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                $userId = Product::where('id',$requestdata->id)->first();
                $delfile=$this->deleteFile($userId->photo_three, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$productDetails->product_id."_product3";
                if (!empty($request->photo_three))
                {     
                    $applpic_ext = $request->file('photo_three')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_three'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                    $photoUpdated=Product::where('id',$productDetails->product_id)->update(['photo_three'=>$photoName.".".$applpic_ext]);          
                   
                }
                
            }

            if ($request->hasFile('photo_four'))
            {
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                $userId = Product::where('id',$requestdata->id)->first();
                $delfile=$this->deleteFile($userId->photo_four, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$productDetails->product_id."_product4";
                if (!empty($request->photo_four))
                {     
                    $applpic_ext = $request->file('photo_four')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_four'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                    $photoUpdated=Product::where('id',$productDetails->product_id)->update(['photo_four'=>$photoName.".".$applpic_ext]);          
                   
                }
                
            }

            if ($request->hasFile('photo_five'))
            {
                $imagedataPath=PRODUCT_CONTENT_UPLOAD;
                $userId = Product::where('id',$requestdata->id)->first();
                $delfile=$this->deleteFile($userId->photo_five, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$productDetails->product_id."_product5";
                if (!empty($request->photo_five))
                {     
                    $applpic_ext = $request->file('photo_five')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_five'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                    $photoUpdated=Product::where('id',$productDetails->product_id)->update(['photo_five'=>$photoName.".".$applpic_ext]);          
                   
                }
                
            }


            $data=[
                
                'title'=> $requestdata->title,
                'content'=> $requestdata->content,
                'link'=> $requestdata->link
            ];
            
            $product = Product::where('id',$productDetails->product_id)->update($data);
            
            foreach($requestdata->productDetails as $key=>$proddetail)
            {
                $proddetail=(object)$proddetail;
                $dataNew=[
                'quantity'=> $proddetail->quantity,
                'quantity_unit'=> $proddetail->quantity_unit,
                'farmer_price'=> $proddetail->farmer_price,
                'old_price'=> $proddetail->old_price,
                'dsc_price'=> $proddetail->dsc_price,
                'bsc_price'=> $proddetail->bsc_price,
                'fsc_price'=> $proddetail->fsc_price
            ];
            
            $productDetails = ProductDetails::where('id',$requestdata->id)->update($dataNew);
          
                
            }
            
           
            
            // if ($productDetails)
            // {
                 return response()->json([
                    "data" => $productDetails,
                    "result" => true,
                    "message" => 'Information Updated Successfully'
                ]);
            // }
            // else
            // {
            //      return response()->json([
            //         "data" => '',
            //         "result" => false,
            //         "message" => 'Information Not Updated'
            //     ]);
                
            // }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function webproductget(Request $request)

     {
        try
        {
            $result = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
                ->where('tbl_product.is_deleted','no')
                ->where('tbl_product_details.id',$request->id)
                 ->select(
                     
                'tbl_product.id as tbl_product_id', 
                'tbl_product.title', 
                'tbl_product.content', 
                'tbl_product.link', 
                'tbl_product.photo_one', 
                'tbl_product.photo_two', 
                'tbl_product.photo_three', 
                'tbl_product.photo_four', 
                'tbl_product.photo_five', 
                'tbl_product.created_by', 
                'tbl_product.is_deleted',

                'tbl_product_details.id',
                'tbl_product_details.product_id',
                'tbl_product_details.quantity',
                'tbl_product_details.quantity_unit',
                'tbl_product_details.farmer_price',
                'tbl_product_details.old_price',
                'tbl_product_details.dsc_price',
                'tbl_product_details.bsc_price',
                'tbl_product_details.fsc_price',
                'tbl_product_details.created_by',
                'tbl_product_details.is_deleted',
                'tbl_product_details.created_at',
                'tbl_product_details.updated_at'

                )
                ->get();

            //dd($result);
            foreach($result as $key=>$value)
            {
                $value->photopathone=PRODUCT_CONTENT_VIEW.$value->photo_one;
                $value->photopathtwo=PRODUCT_CONTENT_VIEW.$value->photo_two;
                $value->photopaththree=PRODUCT_CONTENT_VIEW.$value->photo_three;
                $value->photopathfour=PRODUCT_CONTENT_VIEW.$value->photo_four;
                $value->photopathfive=PRODUCT_CONTENT_VIEW.$value->photo_five;
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

    public function webproductdelete(Request $request)
    {
        try
        {
            $data=[
                'is_deleted'=>'yes',
            ];
            
            $user = ProductDetails::where('id',$request->id)->update($data);
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
    
    public function webproductlist(Request $request)
    {
        try
        {
            $result = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
                ->where('tbl_product_details.is_deleted','no')
                ->where('tbl_product.is_deleted','no')
                ->select('tbl_product_details.*','tbl_product.title','tbl_product.content','tbl_product.link')
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
    
    public function webproductlistbyprodname(Request $request)
    {
        try
        {
            // $result = Product::where('is_deleted','no')
            // ->select('tbl_product.title')
            // //->groupBy('title')
            // ->get();
            
            $result = DB::table('tbl_product')
                    ->where('is_deleted','no')
                 ->select('title')
                 ->groupBy('title')
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
    
    
    
     //Agency Us
    public function webagencyadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $agency = new WebAgency();
            $agency->agency_name = $requestdata->agency_name;
            $agency->lat = $requestdata->lat;
            $agency->lon = $requestdata->lon;
            $agency->agency_under_distributor_id = $requestdata->agency_under_distributor_id;
            $agency->address = $requestdata->address;
            $agency->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=AGENCY_PHOTO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$agency->id."_agencyphoto";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);           
                   
                }
                $users=WebAgency::where('id',$agency->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }
            
            if ($agency)
            {
                 return response()->json([
                    "data" => $agency,
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
    
    public function webagencyupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=AGENCY_PHOTO_UPLOAD;
                // $userId = WebAgency::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_agencyphoto";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=WebAgency::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }

            $data=[
                'agency_name'=> $requestdata->agency_name,
                'lat'=> $requestdata->lat,
                'lon'=> $requestdata->lon,
                'agency_under_distributor_id'=> $requestdata->agency_under_distributor_id,
                'address'=> $requestdata->address
            ];
            
            $user = WebAgency::where('id',$requestdata->id)->update($data);
            //dd($user);
          
           
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    public function webagencyget(Request $request)
    {
        try
        {
            $result = WebAgency::where('id',$request->id)->where('is_deleted','no')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=AGENCY_PHOTO_VIEW.$value->photo_one;
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
    
    
    public function webagencyby_lat_long_distance(Request $request)
    {
        
        try
        {
            $result = WebAgency::where('is_deleted','no')->get();
            $flag = 'false';
            foreach($result as $key=>$value)
            {
                
                $lat1=$request->lat;
                $lon1= $request->lon;
                
                $lat2=$value->lat;
                $lon2= $value->lon;
                $theta = $lon1 - $lon2;
                $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
                $dist = acos($dist);
                $dist = rad2deg($dist);
                $miles = $dist * 60 * 1.1515;
                $dist= ($miles * 1.609344);
                
                $finalKM=(int)round($dist);
                
                if($finalKM < 5)
                {
                    $flag = 'true';
                }
               
            }
         
             return response()->json([
                "data" => '',
                "result" =>$flag,
                "message" => 'Information get Successfully'
            ]);
           
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }
    }
    
    public function webagencydetails(Request $request)
    {
        try
        {
            $result = WebAgency::where('id',$request->id)->where('is_deleted','no')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=AGENCY_PHOTO_VIEW.$value->photo_one;
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
    
    public function webagencydelete(Request $request)
    {
        try
        {
            $data=[
                'is_deleted'=>'yes',
            ];
            
            $user = WebAgency::where('id',$request->id)->update($data);
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
    
    public function webagencylist(Request $request)
    {
        try
        {
            $result = WebAgency::join('usersinfo','usersinfo.user_id','=','tbl_agency_detail.agency_under_distributor_id')
            ->where('tbl_agency_detail.is_deleted','no')->orderBy('tbl_agency_detail.id', 'DESC')
            ->select( 'tbl_agency_detail.id', 'tbl_agency_detail.agency_name', 'tbl_agency_detail.lat', 'tbl_agency_detail.lon', 'tbl_agency_detail.agency_under_distributor_id',
            'tbl_agency_detail.address', 'tbl_agency_detail.photo_one')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=AGENCY_PHOTO_VIEW.$value->photo_one;
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
    
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    
    //Order
    public function weborderadd(Request $request)
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
            $ordrsummary->entry_by = 'admin';
            $ordrsummary->order_cerated_for = $requestdata->order_cerated_for;
            $ordrsummary->order_cerated_for_id = $requestdata->order_cerated_for_id;
            $ordrsummary->created_disctributor_id = $requestdata->created_disctributor_id;
            $ordrsummary->created_disctributor_amount = $requestdata->created_disctributor_amount;
            $ordrsummary->remark = $requestdata->remark;

            $ordrsummary->forwarded_bsc_id = '0';
            $ordrsummary->forwarded_dsc_id = '0';

            $ordrsummary->payment_mode = 'cod';
            $ordrsummary->is_order_confirm_from_dsc = 'yes';
            $ordrsummary->is_order_final_confirm = 'yes';
            $ordrsummary->is_order_confirm_from_dist = 'yes';

            $ordrsummary->save();
          

            $requestdata = $request;
            $allproduct=$requestdata->all_product;
            $allproductOld=json_encode($allproduct);
            $allproductNew=json_decode($allproductOld,true);
            foreach($allproductNew as $key=>$prod_details)
            {
                $orderdetails = new OrderDetail();
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
    
    public function weborderupdate(Request $request)
    {
        try
        {
            $requestdata =$request;
            $allproduct=$requestdata->all_product;
            $allproductOld=json_encode($allproduct);
            $allproductNew=json_decode($allproductOld,true);
            foreach($allproductNew as $key=>$prod_details)
            {
                 $data=[
                    'prod_id'=> $prod_details['prod_id'],
                    'qty'=>$prod_details['qty'],
                    'rate_of_prod'=>$prod_details['rate_of_prod'],
                    'final_amt' =>$prod_details['qty']*$prod_details['rate_of_prod'],
                    'is_deleted'=>'yes'
                ];
                $orderdetail = OrderDetail::where('order_no',$requestdata->order_no)->where('prod_id',$prod_details['prod_id'])->update($data);       
            }
            
            foreach($allproductNew as $key=>$prod_details)
            {
                 $data=[
                    'order_no'=> $requestdata->order_no,
                    'prod_id'=> $prod_details['prod_id'],
                    'qty'=>$prod_details['qty'],
                    'rate_of_prod'=>$prod_details['rate_of_prod'],
                    'final_amt' =>$prod_details['qty']*$prod_details['rate_of_prod'],
                    
                ];
                $orderdetail = OrderDetail::insert($data);       
            }
            
            
            $dataNew=[
                    'created_disctributor_amount'=>$requestdata->created_disctributor_amount,
                ];

            $orderdetailNew = OrderSummary::where('order_no',$requestdata->order_no)->update($dataNew);
            
           
            
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
    
    public function weborderget(Request $request)
    {
        try
        {
            $result = OrderSummary::where('order_no',$request->order_no)
            ->where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
            ->where('tbl_order_summary.is_deleted','no')->get();
        
            foreach($result as $key=>$value)
            {
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
    
    
    public function webordergetbydistributorid(Request $request)
    {
        try
        {
            // $result = OrderSummary::where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
            // ->where('tbl_order_summary.is_deleted','no')->get();
            
            // $result = OrderSummary::where('tbl_order_summary.is_deleted','no')
            // ->where('created_disctributor_id',$request->created_disctributor_id)
            //     ->sum('created_disctributor_amount')
            //     //->groupBy('created_disctributor_id', 'DESC')
            //     ->get();
                
                
                //$result = OrderSummary::select(DB::raw('SUM(created_disctributor_amount) as total_amount','created_disctributor_id'))
                // $result = OrderSummary::select(DB::raw('SUM(tbl_order_summary.created_disctributor_amount) as total_amount','tbl_order_summary.created_disctributor_id'))
                // ->join('tbl_order_detail','tbl_order_detail.order_no','=','tbl_order_summary.order_no')
                // ->where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
                // ->groupBy('tbl_order_summary.created_disctributor_id')
                // ->get();
                
                $result = OrderSummary::select(DB::raw('SUM(tbl_order_summary.created_disctributor_amount) as total_amount'))
                ->join('tbl_order_detail','tbl_order_detail.order_no','=','tbl_order_summary.order_no')
                ->join('usersinfo','usersinfo.id','=','tbl_order_summary.created_disctributor_id')
                ->where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
                ->where('tbl_order_summary.order_dispatched','yes')
                ->groupBy('tbl_order_summary.created_disctributor_id')
                ->pluck('total_amount');
                


//                 $result = OrderSummary::groupBy('created_disctributor_id')
//                 ->where('created_disctributor_id',$request->created_disctributor_id)
//   ->selectRaw('sum(created_disctributor_amount) as sum, order_no, order_date')
//   ->pluck('sum','order_no','order_date');
   
//   $result = OrderSummary::where('created_disctributor_id',$request->created_disctributor_id)
//   ->selectRaw('sum(created_disctributor_amount) as sum','*')
//   ->get();
   
        
        //$result=DB::select("SELECT SUM(created_disctributor_amount) FROM 'tbl_order_summary' WHERE 'created_disctributor_id'='4' GROUP BY 'created_disctributor_id'");
            // foreach($result as $key=>$value)
            // {
            //     $value->all_product = OrderDetail::where('order_no',$request->order_no)
            //             ->join('tbl_product','tbl_product.id','=','tbl_order_detail.prod_id')
            //             ->get();
            // }
            
            
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
    
    
    public function webordergetalldetails(Request $request)
    {
        try
        {
            // $result = OrderSummary::where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
            // ->where('tbl_order_summary.is_deleted','no')->get();
            
            // $result = OrderSummary::where('tbl_order_summary.is_deleted','no')
            // ->where('created_disctributor_id',$request->created_disctributor_id)
            //     ->sum('created_disctributor_amount')
            //     //->groupBy('created_disctributor_id', 'DESC')
            //     ->get();
                
                
                //$result = OrderSummary::select(DB::raw('SUM(created_disctributor_amount) as total_amount','created_disctributor_id'))
                // $result = OrderSummary::select(DB::raw('SUM(tbl_order_summary.created_disctributor_amount) as total_amount','tbl_order_summary.created_disctributor_id'))
                // ->join('tbl_order_detail','tbl_order_detail.order_no','=','tbl_order_summary.order_no')
                // ->where('tbl_order_summary.created_disctributor_id',$request->created_disctributor_id)
                // ->groupBy('tbl_order_summary.created_disctributor_id')
                // ->get();
                
                $result = OrderSummary::select(DB::raw('SUM(tbl_order_summary.created_disctributor_amount) as total_amount'))
                ->join('tbl_order_detail','tbl_order_detail.order_no','=','tbl_order_summary.order_no')
                ->join('usersinfo','usersinfo.id','=','tbl_order_summary.created_disctributor_id')
                ->where('tbl_order_summary.order_dispatched','yes')
                ->pluck('total_amount');
                //->get();


//                 $result = OrderSummary::groupBy('created_disctributor_id')
//                 ->where('created_disctributor_id',$request->created_disctributor_id)
//   ->selectRaw('sum(created_disctributor_amount) as sum, order_no, order_date')
//   ->pluck('sum','order_no','order_date');
   
//   $result = OrderSummary::where('created_disctributor_id',$request->created_disctributor_id)
//   ->selectRaw('sum(created_disctributor_amount) as sum','*')
//   ->get();
   
        
        //$result=DB::select("SELECT SUM(created_disctributor_amount) FROM 'tbl_order_summary' WHERE 'created_disctributor_id'='4' GROUP BY 'created_disctributor_id'");
            // foreach($result as $key=>$value)
            // {
            //     $value->all_product = OrderDetail::where('order_no',$request->order_no)
            //             ->join('tbl_product','tbl_product.id','=','tbl_order_detail.prod_id')
            //             ->get();
            // }
            
            
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
    
    
    
    public function weborderdelete(Request $request)
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
    
    public function weborderlist(Request $request)
    {
        try
        {
             $result = OrderSummary::leftJoin('usersinfo as dist_name', function($join) {
                                        $join->on('tbl_order_summary.created_disctributor_id', '=', 'dist_name.user_id');
                                    })
                                    ->where(['is_order_final_confirm' => 'yes','tbl_order_summary.is_deleted'=>'no'])
                                    ->orderBy('id', 'DESC')
                                    ->select(
                                            'tbl_order_summary.*',
                                            'dist_name.fname as fname',
                                            'dist_name.mname as mname',
                                            'dist_name.lname as lname',
                                    )
                                    ->get();
     
        
            // foreach($result as $key=>$resultnew)
            // {
            //     try
            //     {
            //         $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
            //         $resultnew->fname=$details->fname;
            //         $resultnew->mname=$details->mname;
            //         $resultnew->lname=$details->lname;
                    
            //     } catch(Exception $e) {
            //         return response()->json([
            //                 "data" => '',
            //                 "result" => false,
            //                 "error" => true,
            //                 "message" =>$e->getMessage()." ".$e->getCode()
            //             ]);
                   
            //      }
            // }

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
    
    
     public function weborderorderdetails(Request $request)
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
    
    
    public function weborderaccountsectionverified(Request $request)
    {
        try
        {
            $requestdata =$request;
        
            $data=[
                'account_approved'=> 'yes'
            ];
            
            $orderdetail = OrderSummary::where('order_no',$requestdata->order_no)
                                        ->where('created_disctributor_id',$requestdata->created_disctributor_id)
                                        ->update($data);       
           
            
            if ($orderdetail)
            {
                $message ="Your order no- ".$requestdata->order_no." is verified successfully";
                send_notification($message,$request->created_disctributor_id);
                 return response()->json([
                    "data" => $orderdetail,
                    "result" => true,
                    "message" => 'Order Verified Successfully'
                ]);
               
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Order Not Verified'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    public function weborderaccountsectionforwardtowarehouse(Request $request)
    {
        try
        {
            $requestdata =$request;
            
        
            $data=[
                'forward_to_warehouse'=> 'yes'
            ];
            
            $orderdetail = OrderSummary::where('order_no',$requestdata->order_no)
                                        ->where('created_disctributor_id',$requestdata->created_disctributor_id)
                                        ->update($data);       
           
            
            if ($orderdetail)
            {
                $message ="Your order no- ".$requestdata->order_no." is forwarded to warehouse";
                send_notification($message,$request->created_disctributor_id);
                 return response()->json([
                    "data" => $orderdetail,
                    "result" => true,
                    "message" => 'Order Verified Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Order Not Verified'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    public function weborderlistforwarehouse(Request $request)
    {
        try
        {
             $result = OrderSummary::where('tbl_order_summary.forward_to_warehouse','yes')
                        ->where('tbl_order_summary.is_deleted','no')->get();
            
            foreach($result as $key=>$resultnew)
            {
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
    
    
    // public function websalesreport(Request $request)
    // {
    //     try
    //     {
    //         $datefrom=$request->datefrom;
    //         $dateto=$request->dateto;
    //         $totalamount=0;

    //         $result=\App\Models\OrderSummary::query()
    //           ->where('tbl_order_summary.account_approved','yes')
    //           ->where('tbl_order_summary.is_deleted','no')
              
    //            ->when($request->get('datefrom'), function($query) use ($request) {
    //                //$query->whereBetween('order_date', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
    //                $query->whereBetween('order_date', [$request->datefrom,$request->dateto]);
    //             }) 
                
    //            ->get();
                                   
            
    //         foreach($result as $key=>$resultnew)
    //         {
    //             try
    //             {
    //                 $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
    //                 $resultnew->fname=$details->fname;
    //                 $resultnew->mname=$details->mname;
    //                 $resultnew->lname=$details->lname;
                    
    //                 $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
    //             } catch(Exception $e) {
    //                 return response()->json([
    //                         "data" => '',
    //                         "result" => false,
    //                         "error" => true,
    //                         "message" =>$e->getMessage()." ".$e->getCode()
    //                     ]);
                   
    //              }
    //         }
            
    //         // $result['totalamount']=$totalamount;

    //         if ($result)
    //         {
    //              return response()->json([
    //                 "data" => $result,
    //                 "totalorder" => count($result),
    //                 "totalamount" => $totalamount,
    //                 "datefrom" => $request->datefrom,
    //                 "dateto" => $request->dateto,
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

    public function websalesreport(Request $request)
    {
        try
        {
            $datefrom=$request->datefrom;
            $dateto=$request->dateto;
            $totalamount=0;

            $result=\App\Models\OrderSummary::query()

            ->leftJoin('tbl_area as districtNew', function($join) {
                $join->on('tbl_order_summary.created_disctributor_id', '=', 'districtNew.location_id');
            })
          
            ->leftJoin('usersinfo as dist_name', function($join) {
                $join->on('tbl_order_summary.created_disctributor_id', '=', 'dist_name.user_id');
            })
            ->when($request->get('dist_id'), function($query) use ($request) {
                $query->where('tbl_order_summary.created_disctributor_id', $request->dist_id);
             }) 

            ->where('tbl_order_summary.account_approved','yes')
            ->where('tbl_order_summary.is_deleted','no')
            
            ->when($request->get('datefrom'), function($query) use ($request) {
                $query->whereBetween('tbl_order_summary.order_date', [$request->datefrom,$request->dateto]);
            }) 
            
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
                'tbl_order_summary.payment_mode',
                
                'districtNew.name as district',
                
                'dist_name.fname as fname',
                'dist_name.mname as mname',
                'dist_name.lname as lname',
                )
               ->get();
                                   
            
            foreach($result as $key=>$resultnew)
            {
                try
                {
                    // $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                    // $resultnew->fname=$details->fname;
                    // $resultnew->mname=$details->mname;
                    // $resultnew->lname=$details->lname;
                    
                    $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            
            // $result['totalamount']=$totalamount;

            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "totalorder" => count($result),
                    "totalamount" => $totalamount,
                    "datefrom" => $request->datefrom,
                    "dateto" => $request->dateto,
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


    public function reportsales(Request $request)
    {
        try
        {
            $datefrom=$request->datefrom;
            $dateto=$request->dateto;
            $totalamount=0;

            $result=\App\Models\SaleSummary::query()
                ->leftJoin('tbl_area as districtNew', function($join) {
                    $join->on('tbl_sale_summary.created_disctributor_id', '=', 'districtNew.location_id');
                })
              
                ->leftJoin('usersinfo as dist_name', function($join) {
                    $join->on('tbl_sale_summary.created_disctributor_id', '=', 'dist_name.user_id');
                })
                  

              ->where('tbl_sale_summary.is_deleted','no')
              
               ->when($request->get('datefrom'), function($query) use ($request) {
                   //$query->whereBetween('order_date', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                   $query->whereBetween('tbl_sale_summary.order_date', [$request->datefrom,$request->dateto]);
                }) 

                ->when($request->get('dist_id'), function($query) use ($request) {
                    $query->where('tbl_sale_summary.created_disctributor_id', $request->dist_id);
                 }) 
                ->select( 
                'tbl_sale_summary.id',
                'tbl_sale_summary.order_no',
                'tbl_sale_summary.order_date',
                'tbl_sale_summary.order_created_by',
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
                'tbl_sale_summary.is_deleted',
                'tbl_sale_summary.created_at',
                'tbl_sale_summary.updated_at',
                
                'districtNew.name as district',
                
                'dist_name.fname as fname',
                'dist_name.mname as mname',
                'dist_name.lname as lname',
                )
               ->get();




                                   
            
            foreach($result as $key=>$resultnew)
            {
                try
                {
                    // $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                    // $resultnew->fname=$details->fname;
                    // $resultnew->mname=$details->mname;
                    // $resultnew->lname=$details->lname;
                    
                    $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            
            // $result['totalamount']=$totalamount;

            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "totalorder" => count($result),
                    "totalamount" => $totalamount,
                    "datefrom" => $request->datefrom,
                    "dateto" => $request->dateto,
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

    public function viewreportsales(Request $request)
    {
        try
        {
            $datefrom=$request->datefrom;
            $dateto=$request->dateto;
            $totalamount=0;

            $result=\App\Models\SaleSummary::query()
              ->where('tbl_sale_summary.is_deleted','no')
              ->where('tbl_sale_summary.order_no',$request->order_no)
              ->where('tbl_sale_summary.created_disctributor_id',$request->created_disctributor_id)
              ->when($request->get('datefrom'), function($query) use ($request) {
                   //$query->whereBetween('order_date', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                   $query->whereBetween('order_date', [$request->datefrom,$request->dateto]);
                }) 
                
               ->get();
                                   
            
            foreach($result as $key=>$resultnew)
            {
                try
                {

                    $resultnew->all_product = SaleDetail::where('tbl_sale_detail.order_no',$request->order_no)
                                            ->where('tbl_sale_detail.is_deleted','no')
                                            ->join('tbl_product','tbl_product.id','=','tbl_sale_detail.prod_id')
                                            ->get();

                    $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                    $resultnew->fname=$details->fname;
                    $resultnew->mname=$details->mname;
                    $resultnew->lname=$details->lname;
                    
                    $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            
            // $result['totalamount']=$totalamount;

            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "totalorder" => count($result),
                    "totalamount" => $totalamount,
                    "datefrom" => $request->datefrom,
                    "dateto" => $request->dateto,
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
    
    
    
    
    
    public function webdistdashreport(Request $request)
    {
        
        try
        {
            $totalamount=0;
            
            $result=\App\Models\UsersInfo::query()
              
              ->where('is_deleted','no')
              
               ->when($request->get('state'), function($query) use ($request) {
                   
                   $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                
               ->get();
               //dd($result);
               
           
            // foreach($result as $key=>$resultnew)
            // {
            //     try
            //     {
            //         //dd($resultnew);
            //         //$details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
            //         $resultnew->fname=$details->fname;
            //         $resultnew->mname=$details->mname;
            //         $resultnew->lname=$details->lname;
                    
            //         $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
            //     } catch(Exception $e) {
            //         return response()->json([
            //                 "data" => '',
            //                 "result" => false,
            //                 "error" => true,
            //                 "message" =>$e->getMessage()." ".$e->getCode()
            //             ]);
                   
            //      }
                 
            // }
            //dd($totalamount);
            if ($result)
            {
               
                 return response()->json([
                    "data" => $result,
                    //"totalorder" => count($result),
                    "totalamount" => $totalamount,
                    // "datefrom" => $request->datefrom,
                    // "dateto" => $request->dateto,
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
    
    
    public function webdash_farmer_count(Request $request)
    {
        try{
               
              $yearly_farmer_count= UsersInfo::where('is_deleted','no')->where('user_type','farmer')->whereYear('created_on', Carbon::now()->year)
              
                 ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                
            ->get();
            
              $count_yearly_farmer_list=sizeof($yearly_farmer_count);
              
              
              
              
              $monthly_farmer_count= UsersInfo::where('is_deleted','no')->where('user_type','farmer')->whereYear('created_on', Carbon::now()->year)->whereMonth('created_on', Carbon::now()->month)
              
              ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                ->get();
              $count_monthly_farmer_list=sizeof($monthly_farmer_count);
              
              
              
              
              $upto_today_farmer_count= UsersInfo::where('is_deleted','no')->where('user_type','farmer')
              
              ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                ->get();
              $count_upto_today_farmer_list=sizeof($upto_today_farmer_count);
              
              
              
              
              $Monthly=[
                   'name'=>'Monthly',
                  'value'=>$count_monthly_farmer_list,
                ];
                $Yearly=[
                  'name'=>'Yearly',
                  'value'=>$count_yearly_farmer_list,
                ];
                
                $Upto_Today=[
                  'name'=>'Upto Today',
                  'value'=>$count_upto_today_farmer_list,
                ];
                
               // $age = array("Peter"=>35, "Ben"=>37, "Joe"=>43);


                
                $result=json_encode([
                  $Monthly,
                  $Yearly,
                  $Upto_Today,
                ]);
                $result=stripslashes($result);
              
             // $result='[{"name":"Monthly","value":1},{"name":"Yearly","value":7},{"name":"Upto Today","value":7}]';
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
    
    
    
    
    
    
    
    
    public function webdash_distributor_count(Request $request)
    {
        try{
             
            //   $yearly_distributor_count= UsersInfo::where('is_deleted','no')->whereIn('user_type',['fsc','bsc','dsc'])->whereYear('created_on', Carbon::now()->year)
              $yearly_distributor_count= UsersInfo::where('is_deleted','no')->whereYear('created_on', Carbon::now()->year)
              
              ->when($request->get('dist_type'), function($query) use ($request) {
                info($request->dist_type);
                $query->where('user_type',$request->dist_type);
              })

                ->when($request->get('state'), function($query) use ($request) {
                  $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                
            ->get();
            
              $count_yearly_distributor_list=sizeof($yearly_distributor_count);
              
              
              
              
             // $monthly_distributor_count= UsersInfo::where('is_deleted','no')->whereIn('user_type',['fsc','bsc','dsc'])->whereYear('created_on', Carbon::now()->year)->whereMonth('created_on', Carbon::now()->month)
              $monthly_distributor_count= UsersInfo::where('is_deleted','no')->whereYear('created_on', Carbon::now()->year)->whereMonth('created_on', Carbon::now()->month)
              
              ->when($request->get('dist_type'), function($query) use ($request) {
                info($request->dist_type);
                $query->where('user_type',$request->dist_type);
              })

              ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                ->get();
              $count_monthly_distributor_list=sizeof($monthly_distributor_count);
              
              
              
              
              //$upto_today_distributor_count= UsersInfo::where('is_deleted','no')->whereIn('user_type',['fsc','bsc','dsc'])
              $upto_today_distributor_count= UsersInfo::where('is_deleted','no')
              
              ->when($request->get('dist_type'), function($query) use ($request) {
                info($request->dist_type);
                $query->where('user_type',$request->dist_type);
              })

              ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                ->get();
              $count_upto_today_distributor_list=sizeof($upto_today_distributor_count);
              
              
              
              
              $Monthly=[
                   'name'=>'Monthly',
                  'value'=>$count_monthly_distributor_list,
                ];
                $Yearly=[
                  'name'=>'Yearly',
                  'value'=>$count_yearly_distributor_list,
                ];
                
                $Upto_Today=[
                  'name'=>'Upto Today',
                  'value'=>$count_upto_today_distributor_list,
                ];
                
               // $age = array("Peter"=>35, "Ben"=>37, "Joe"=>43);


                
                $result=json_encode([
                  $Monthly,
                  $Yearly,
                  $Upto_Today,
                ]);
              
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
    
    
     public function webdistributorrderreport(Request $request)
    {
        try
        {
            $datefrom=$request->datefrom;
            $dateto=$request->dateto;
            $totalamount=0;

            $result=\App\Models\OrderSummary::query()
              ->where('tbl_order_summary.account_approved','yes')
              ->where('tbl_order_summary.is_deleted','no')
              
              ->when($request->get('datefrom'), function($query) use ($request) {
                   $query->whereBetween('order_date', [$request->datefrom,$request->dateto]);
                }) 
                
               ->when($request->get('created_disctributor_id'), function($query) use ($request) {
                   $query->where('created_disctributor_id', $request->created_disctributor_id);
                }) 
                
               ->get();
                                   
            
            foreach($result as $key=>$resultnew)
            {
                try
                {
                    $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                    $resultnew->fname=$details->fname;
                    $resultnew->mname=$details->mname;
                    $resultnew->lname=$details->lname;
                    
                    $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            
            // $result['totalamount']=$totalamount;

            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "totalorder" => count($result),
                    "totalamount" => $totalamount,
                    "datefrom" => $request->datefrom,
                    "dateto" => $request->dateto,
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
    
    
    
    public function weballorderreport(Request $request)
    {
        try
        {
            $datefrom=$request->datefrom;
            $dateto=$request->dateto;
            $totalamount=0;

            $result=\App\Models\OrderSummary::query()
              ->where('tbl_order_summary.order_no','!=','null')
             
              
               ->when($request->get('datefrom'), function($query) use ($request) {
                   //$query->whereBetween('order_date', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                   $query->whereBetween('order_date', [$request->datefrom,$request->dateto]);
                }) 
                
               ->get();
                                   
            
            foreach($result as $key=>$resultnew)
            {
                try
                {
                    $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                    $resultnew->fname=$details->fname;
                    $resultnew->mname=$details->mname;
                    $resultnew->lname=$details->lname;
                    
                    $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            
            // $result['totalamount']=$totalamount;

            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "totalorder" => count($result),
                    "totalamount" => $totalamount,
                    "datefrom" => $request->datefrom,
                    "dateto" => $request->dateto,
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
    
    
    public function weballorderconfirmnotdispatchedreport(Request $request)
    {
        try
        {
            $datefrom=$request->datefrom;
            $dateto=$request->dateto;
            $totalamount=0;

            $result=\App\Models\OrderSummary::query()
              ->where('tbl_order_summary.account_approved','yes')
              ->where('tbl_order_summary.is_deleted','no')
              ->where('tbl_order_summary.order_dispatched','no')
              
               ->when($request->get('datefrom'), function($query) use ($request) {
                   //$query->whereBetween('order_date', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                   $query->whereBetween('order_date', [$request->datefrom,$request->dateto]);
                }) 
                
               ->get();
                                   
            
            foreach($result as $key=>$resultnew)
            {
                try
                {
                    $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                    $resultnew->fname=$details->fname;
                    $resultnew->mname=$details->mname;
                    $resultnew->lname=$details->lname;
                    
                    $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            
            // $result['totalamount']=$totalamount;

            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "totalorder" => count($result),
                    "totalamount" => $totalamount,
                    "datefrom" => $request->datefrom,
                    "dateto" => $request->dateto,
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
    
    
    
    
    
    
    
    public function weballorderconfirmdispatchedreport(Request $request)
    {
        try
        {
            $datefrom=$request->datefrom;
            $dateto=$request->dateto;
            $totalamount=0;

            $result=\App\Models\OrderSummary::query()
              ->where('tbl_order_summary.account_approved','yes')
              ->where('tbl_order_summary.is_deleted','no')
              ->where('tbl_order_summary.order_dispatched','yes')
              
               ->when($request->get('datefrom'), function($query) use ($request) {
                   //$query->whereBetween('order_date', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                   $query->whereBetween('order_date', [$request->datefrom,$request->dateto]);
                }) 
                
               ->get();
                                   
            
            foreach($result as $key=>$resultnew)
            {
                try
                {
                    $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
                    $resultnew->fname=$details->fname;
                    $resultnew->mname=$details->mname;
                    $resultnew->lname=$details->lname;
                    
                    $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
                } catch(Exception $e) {
                    return response()->json([
                            "data" => '',
                            "result" => false,
                            "error" => true,
                            "message" =>$e->getMessage()." ".$e->getCode()
                        ]);
                   
                 }
            }
            
            // $result['totalamount']=$totalamount;

            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "totalorder" => count($result),
                    "totalamount" => $totalamount,
                    "datefrom" => $request->datefrom,
                    "dateto" => $request->dateto,
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
    
    
    
    
     public function weborderdispatchedfromwarehouse(Request $request)
    {
        try
        {
            $requestdata =$request;
            
            $date=date('Y-m-d');
            $data=[
                'order_dispatched'=> 'yes',
                'order_dispatched_date'=> $date
            ];
            
            $orderdetail = OrderSummary::where('order_no',$requestdata->order_no)
                                        ->where('created_disctributor_id',$requestdata->created_disctributor_id)
                                        ->update($data);       
           
            
            if ($orderdetail)
            {
                 return response()->json([
                    "data" => $orderdetail,
                    "result" => true,
                    "message" => 'Order Verified Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Order Not Verified'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    
    
    // public function weballorderreport(Request $request)
    // {
    //     try
    //     {
    //         $datefrom=$request->datefrom;
    //         $dateto=$request->dateto;
    //         $totalamount=0;

    //         $result=\App\Models\OrderSummary::query()
    //           ->where('tbl_order_summary.account_approved','yes')
    //           ->where('tbl_order_summary.is_deleted','no')
    //           //->where('tbl_order_summary.order_dispatched','yes')
              
    //           ->when($request->get('datefrom'), function($query) use ($request) {
    //               $query->whereBetween('order_date', [$request->datefrom,$request->dateto]);
    //             }) 
                
    //           ->get();
                                   
            
    //         foreach($result as $key=>$resultnew)
    //         {
    //             try
    //             {
    //                 $details=$this->commonController->getUserNameById($resultnew->created_disctributor_id);                        
    //                 $resultnew->fname=$details->fname;
    //                 $resultnew->mname=$details->mname;
    //                 $resultnew->lname=$details->lname;
                    
    //                 $totalamount=$totalamount+$resultnew->created_disctributor_amount;
                    
    //             } catch(Exception $e) {
    //                 return response()->json([
    //                         "data" => '',
    //                         "result" => false,
    //                         "error" => true,
    //                         "message" =>$e->getMessage()." ".$e->getCode()
    //                     ]);
                   
    //              }
    //         }
            
    //         // $result['totalamount']=$totalamount;

    //         if ($result)
    //         {
    //              return response()->json([
    //                 "data" => $result,
    //                 "totalorder" => count($result),
    //                 "totalamount" => $totalamount,
    //                 "datefrom" => $request->datefrom,
    //                 "dateto" => $request->dateto,
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
    
    
    
    public function webtargetvideoadd(Request $request)
    {
        $targetvideo = new TargetVideos();
        $targetvideo->title = $request->title;
        $targetvideo->description = $request->description;
        $targetvideo->language = $request->language;
        $targetvideo->to_whom_show = $request->to_whom_show;
        $targetvideo->url = $request->url;
        $targetvideo->save();
       
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Target Video Added Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Target Video Not Added'
            ]);
            
        }
    }
    
    
    
    
    public function webtargetvideoupdate(Request $request)
    {
        $data=[
                'title'=>$request->title,
                'description' => $request->description,
                'language' => $request->language,
                'to_whom_show' => $request->to_whom_show,
                'url' => $request->url,
            ];
            
        $targetvideo = TargetVideos::where('id',$request->id)->update($data);
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Target Video Updated Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Target Video Not Updated'
            ]);
            
        }
    }
    
    
    public function webtargetvideoget(Request $request)
    {
        $targetvideo = TargetVideos::where('id',$request->id)->get();
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Target Video Get Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Target Video Not Found'
            ]);
            
        }
    }
    
    
    
    public function webtargetvideolist(Request $request)
    {
        try 
        {
            $targetvideo = TargetVideos::where('is_deleted','no')->where('active','yes')->orderBy('id', 'DESC')->get();
            if(!$targetvideo) {
                throw new Exception(api_error(1006), 1006);
            }
           
          
            if ($targetvideo)
            {
                 return response()->json([
                    "data" => $targetvideo,
                    "result" => true,
                    "message" => 'Target Video Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Target Video Farmer Not Found'
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
    
    
    
    public function webtargetvideodelete(Request $request)
    {
        $targetvideo = TargetVideos::where('id',$request->id)->update(['is_deleted'=>'yes']);
      
        if ($targetvideo)
        {
             return response()->json([
                "data" => $targetvideo,
                "result" => true,
                "message" => 'Target Video Deleted Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Target Video Not Found'
            ]);
            
        }
    }
    
    public function list_notification_web(Request $request)
    {
        $result= Notification::leftJoin('usersinfo', function($join) {
                $join->on('notification.distributor_id', '=', 'usersinfo.user_id');
            })
            ->select('notification.*', 'usersinfo.fname as dfname','usersinfo.mname as dmname','usersinfo.lname as dlname','usersinfo.user_type')
            ->where('notification.is_read','no')
            ->get();
        // foreach ($result as $key=>$distInfo)
        // {
        //     $distributordetails=$this->commonController->getDistributorNameById($distInfo->distributor_id);
                    
        //             if(!$distributordetails) {
        //                 throw new Exception('unable to get ditributor details');
        //             }
        //             //dd($distributordetails['fname']);
        //             $distInfo->dfname=$distributordetails['fname'];
        //             $distInfo->dmname=$distributordetails['mname'];
        //             $distInfo->dlname=$distributordetails['lname'];
        // }
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
    
    
    
    // FSC List
    public function fsc_list(Request $request)
    {
        try
        {
             $fsclist_record= UsersInfo::where('added_by',$request->added_by)
                    ->where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','fsc')
                    ->get();
                    
            foreach($fsclist_record as $key=>$value)
            {
            $stateName=$this->commonController->getAreaNameById($value->state);
            $value->state=$stateName->name;
            
            $districtName=$this->commonController->getAreaNameById($value->district);
            $value->district=$districtName->name;
            
            $talukaName=$this->commonController->getAreaNameById($value->taluka);
            $value->taluka=$talukaName->name;
            
            $cityName=$this->commonController->getAreaNameById($value->city);
            $value->city=$cityName->name;
            }
            if($fsclist_record)
            
            {
                 return response()->json([
                    "data" => $fsclist_record,
                    "result" => true,
                    "message" => 'FSC Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'FSC Record Not Found'
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
    
    
    // BSC List
    public function bsc_list(Request $request)
    {
        try
        {
             $bsclist_record= UsersInfo::where('added_by',$request->added_by)
                    ->where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','bsc')
                    ->get();
                    
            foreach($bsclist_record as $key=>$value)
            {
            $stateName=$this->commonController->getAreaNameById($value->state);
            $value->state=$stateName->name;
            
            $districtName=$this->commonController->getAreaNameById($value->district);
            $value->district=$districtName->name;
            
            $talukaName=$this->commonController->getAreaNameById($value->taluka);
            $value->taluka=$talukaName->name;
            
            $cityName=$this->commonController->getAreaNameById($value->city);
            $value->city=$cityName->name;
            }
            if($bsclist_record)
            
            {
                 return response()->json([
                    "data" => $bsclist_record,
                    "result" => true,
                    "message" => 'BSC Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'BSC Record Not Found'
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
    
    
    
    // DSC List
    public function dsc_list(Request $request)
    {
        try
        {
             $dsclist_record= UsersInfo::leftJoin('tbl_area as stateNew', function($join) {
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
                  })
                  ->where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','dsc')
                    ->select(
                        'usersinfo.*',
                         'stateNew.name as state',
                         'districtNew.name as district',
                         'talukaNew.name as taluka',
                         'cityNew.name as city'
                        )
                    ->get();
            
            // foreach($dsclist_record as $key=>$value)
            // {
            // $stateName=$this->commonController->getAreaNameById($value->state);
            // $value->state=$stateName->name;
            
            // $districtName=$this->commonController->getAreaNameById($value->district);
            // $value->district=$districtName->name;
            
            // $talukaName=$this->commonController->getAreaNameById($value->taluka);
            // $value->taluka=$talukaName->name;
            
            // $cityName=$this->commonController->getAreaNameById($value->city);
            // $value->city=$cityName->name;
            // }
            
            if($dsclist_record)
            
            {
                 return response()->json([
                    "data" => $dsclist_record,
                    "result" => true,
                    "message" => 'DSC Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'DSC Record Not Found'
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
    
    
    
    
    
    
    
    
    
    public function webbrochureadd(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $brochure = new Downloads();
            $brochure->title = $requestdata->title;
            $brochure->content = $requestdata->content;
            $brochure->content_type = $requestdata->content_type;
            $brochure->language = $requestdata->language;
            $brochure->save();
          
            if ($request->photo_one)
            {
                $imagedataPath=DOWNLOAD_UPLOADS;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                 $idLastInserted=$brochure->id;
                 $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$idLastInserted."_download";
                 $inputfilenametoupload='photo_one';
                 if (!empty($request->hasFile($inputfilenametoupload)))
                 {     
                     $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                     $brochure=Downloads::where('id',$idLastInserted)->update(['content_name'=>$filename]);
                 }
    
            }
            
            if ($brochure)
            {
                 return response()->json([
                    "data" => $brochure,
                    "result" => true,
                    "message" => 'Download Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Download Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    
    public function webbrochurelist(Request $request)
    {
        try 
        {
            $result = Downloads::where('status',0)->orderBy('id', 'DESC')->get();
            
            if(!$result) {
                throw new Exception(api_error(1006), 1006);
            }
            
            foreach($result as $key=>$value)
            {
                $value->photopath=DOWNLOAD_VIEW.$value->content_name;
            }
            
           
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
                    "result" => true,
                    "message" => 'Download Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Download Not Found'
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
    
    
    
    
    public function webbrochureget(Request $request)
    {
        try
        {
            $result = Downloads::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=DOWNLOAD_VIEW.$value->content_name;
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
    
    
    
    public function webbrochureupdate(Request $request)
    {
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=DOWNLOAD_UPLOADS;
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_download";
                //dd($request->hasFile('photo_one'));
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=Downloads::where('id',$requestdata->id)->update(['content_name'=>$photoName.".".$applpic_ext]);
                }
            }

            $data=[
                'title'=> $requestdata->title,
                'content'=> $requestdata->content,
                'content_type'=> $requestdata->content_type,
                'language'=> $requestdata->language
            ];
            
            $user = Downloads::where('id',$requestdata->id)->update($data);
            //dd($user);
          
           
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
   
    public function webbrochuredelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = Downloads::where('id',$request->id)->update($data);
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
    
    
    
    
    
    
    
    
    public function websctresultlist(Request $request)
    {
        try 
        {
            $result = SCTResult::where('is_deleted','no');
            if(isset($request->dist_id) && $request->get('dist_id') !='') {
                $result = $result->where('created_by', $request->dist_id);
            }
            $result = $result->orderBy('id', 'DESC')
                                ->get();
            
            if(!$result) {
                throw new Exception(api_error(1006), 1006);
            }
            
            foreach($result as $key=>$value)
            {
                $value->photopathone=SCT_RESULT_PHOTO_VIEW.$value->photo_one;
                $value->photopathtwo=SCT_RESULT_PHOTO_VIEW.$value->photo_two;
                $value->photopaththree=SCT_RESULT_PHOTO_VIEW.$value->photo_three;
                $value->photopathfour=SCT_RESULT_PHOTO_VIEW.$value->photo_four;
                $value->photopathfive=SCT_RESULT_PHOTO_VIEW.$value->photo_five;
                
                if($value->created_by==0)
                    {
                        $value->dfname='Admin';
                        $value->dmname='';
                        $value->dlname='';
                    }
                    else
                    {
                            $userdetails=$this->commonController->getDistributorNameById($value->created_by);
                        
                            $value->dfname=$userdetails['fname'];
                            $value->dmname=$userdetails['mname'];
                            $value->dlname=$userdetails['lname'];
                    }
                    
            }
            
           
            if ($result)
            {
                 return response()->json([
                    "data" => $result,
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
    
    
    
    public function websctresultget(Request $request)
    {
        $sctresult = SCTResult::where('id',$request->id)->get();
        foreach($sctresult as $key=>$value)
            {
                $value->photopathone=SCT_RESULT_PHOTO_VIEW.$value->photo_one;
                $value->photopathtwo=SCT_RESULT_PHOTO_VIEW.$value->photo_two;
                $value->photopaththree=SCT_RESULT_PHOTO_VIEW.$value->photo_three;
                $value->photopathfour=SCT_RESULT_PHOTO_VIEW.$value->photo_four;
                $value->photopathfive=SCT_RESULT_PHOTO_VIEW.$value->photo_five;
                
                if($value->created_by==0)
                    {
                        $value->dfname='Admin';
                        $value->dmname='';
                        $value->dlname='';
                    }
                    else
                    {
                            $userdetails=$this->commonController->getDistributorNameById($value->created_by);
                        
                            $value->dfname=$userdetails['fname'];
                            $value->dmname=$userdetails['mname'];
                            $value->dlname=$userdetails['lname'];
                    }
                    
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
    }
    
    
    
     // Subscriber List Count
    public function subscriber_count_distributor(Request $request)
    {
        try
        {
            $subscriber_listcount= Subscriber::where('created_by',$request->created_by)
                    ->where('is_deleted','no')
                    ->get();
            //$countofsubscriber_list=sizeof($subscriber_listcount);
            if ($subscriber_listcount)
            {
                 return response()->json([
                    "data" => $subscriber_listcount,
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
    
    
    
    
    
    public function farmerlist(Request $request)
    {
        $result = UsersInfo::where('user_type', '=', 'farmer')->where('is_deleted', '=', 'no')
             ->select('user_id','fname','mname','lname','aadharcard','email','phone','state','district','taluka','city','address','pincode','crop','acre','password','photo')->orderBy('id', 'DESC')
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
    
    
    
    
    
    public function webdash_counting(Request $request)
    {
        
        try{
               
              $farmer_count= UsersInfo::where('is_deleted','no')->where('user_type','farmer')->get();
              $dsc_count= UsersInfo::where('is_deleted','no')->where('user_type','dsc')->count();
              $bsc_count= UsersInfo::where('is_deleted','no')->where('user_type','bsc')->count();
              $fsc_count= UsersInfo::where('is_deleted','no')->where('user_type','fsc')->count();
              $count_farmer_list=sizeof($farmer_count);
              
              $dist_count= UsersInfo::where('is_deleted','no')->whereIn('user_type',['fsc','bsc','dsc'])->get();
              $count_dist_list=sizeof($dist_count);

            //   $product_count = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
            //     ->where('tbl_product_details.is_deleted','no')
            //     ->where('tbl_product.is_deleted','no')
            //     ->select('tbl_product.title')
            //     ->orderBy('tbl_product.id', 'DESC')
            //     ->get();

                $product_data_distinct = DB::table('tbl_product')
                                        ->select('title')
                                        ->distinct('title')
                                        ->get();

                // Product::select('tbl_product.title','tbl_product.id')
                // ->distinct('tbl_product.title')
                // ->get();

                foreach ($product_data_distinct as $key => $value) {
                    // $data_count = ProductDetails::where("product_id",$value->id)->get()->count();
                    $data_count = ProductDetails::join('tbl_product','tbl_product_details.product_id','=','tbl_product.id')
                                                    ->where('tbl_product_details.is_deleted','no')
                                                    ->where('tbl_product.title',$value->title)
                                                    ->where('tbl_product.is_deleted','no')
                                                    ->select('tbl_product.title')
                                                    ->orderBy('tbl_product.id', 'DESC')
                                                    ->get()
                                                    ->count();
                    $value->product_count = $data_count;
                }
                // $count_product_list=sizeof($product_count);
              
              $order_count= OrderSummary::where('is_deleted','no')->get();
              $count_order_list=sizeof($order_count);
              
              
            //   $Farmer=[
            //       'name'=>'Farmer Count',
            //       'value'=>$count_farmer_list,
            //     ];
                
            //     $Distributor=[
            //       'name'=>'Distributor Count',
            //       'value'=>$count_dist_list,
            //     ];
                
            //     $Product=[
            //       'name'=>'Product Count',
            //       'value'=>$count_product_list,
            //     ];
                
            //     $Order=[
            //       'name'=>'Order Count',
            //       'value'=>$count_order_list,
            //     ];
               
                $result=json_encode([
                  'farmer_count'=>$count_farmer_list,
                  'dsc_count'=>$dsc_count,
                  'bsc_count'=>$bsc_count,
                  'fsc_count'=>$fsc_count,
                  'distributor_count'=>$count_dist_list,
                  'product_count'=>count($product_data_distinct),
                  'order_count'=>$count_order_list,
                  
                ]);
                //$result=stripslashes($result);
              
           
              if ($result)
            {
                 return response()->json([
                     //"data" => $result,
                    'farmer_count'=>$count_farmer_list,
                    'dsc_count'=>$dsc_count,
                    'bsc_count'=>$bsc_count,
                    'fsc_count'=>$fsc_count,
                    'distributor_count'=>$count_dist_list,
                    'product_count'=>count($product_data_distinct),
                    'product_count_productwise'=>$product_data_distinct,
                    'order_count'=>$count_order_list,
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
    
    
    
    
    public function webdash_farmer_list(Request $request)
    {
        try{
               
              $result= UsersInfo::where('is_deleted','no')->where('user_type','farmer')
              
                 ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                
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
    
    
    
    
    
    
    public function webdash_distributor_list(Request $request)
    {
        try{
               
              $result= UsersInfo::where('is_deleted','no')->whereIn('user_type',['fsc','bsc','dsc'])
              
                 ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('city',$request->city);
                })
                
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
    
    
    
    
    
    
    
    
    // public function webdash_farmer_sales_count(Request $request)
    // {
    //     try{
               
    //           $yearly_sales_count=SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')
    //           ->where('usersinfo.user_type','farmer')->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')->whereYear('tbl_sale_summary.created_at', Carbon::now()->year)
              
    //              ->when($request->get('state'), function($query) use ($request) {
                   
    //               $query->where('usersinfo.state',$request->state);
    //             })
                
    //             ->when($request->get('district'), function($query) use ($request) {
    //               $query->where('usersinfo.district',$request->district);
    //             })
                
    //             ->when($request->get('taluka'), function($query) use ($request) {
    //               $query->where('usersinfo.taluka',$request->taluka);
    //             })
                
    //             ->when($request->get('city'), function($query) use ($request) {
    //               $query->where('usersinfo.city',$request->city);
    //             })
                
    //         ->get();
            
    //           $count_yearly_sales_list=sizeof($yearly_sales_count);
              
              
              
              
    //           $monthly_sales_count= SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')
    //           ->where('usersinfo.user_type','farmer')->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')->whereYear('tbl_sale_summary.created_at', Carbon::now()->year)->whereMonth('tbl_sale_summary.created_at', Carbon::now()->month)
              
    //           ->when($request->get('state'), function($query) use ($request) {
                   
    //               $query->where('usersinfo.state',$request->state);
    //             })
                
    //             ->when($request->get('district'), function($query) use ($request) {
    //               $query->where('usersinfo.district',$request->district);
    //             })
                
    //             ->when($request->get('taluka'), function($query) use ($request) {
    //               $query->where('usersinfo.taluka',$request->taluka);
    //             })
                
    //             ->when($request->get('city'), function($query) use ($request) {
    //               $query->where('usersinfocity',$request->city);
    //             })
    //             ->get();
    //           $count_monthly_sales_list=sizeof($monthly_sales_count);
              
              
              
              
    //           $upto_today_sales_count= SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')->where('usersinfo.user_type','farmer')->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')
              
    //           ->when($request->get('state'), function($query) use ($request) {
                   
    //               $query->where('usersinfo.state',$request->state);
    //             })
                
    //             ->when($request->get('district'), function($query) use ($request) {
    //               $query->where('usersinfo.district',$request->district);
    //             })
                
    //             ->when($request->get('taluka'), function($query) use ($request) {
    //               $query->where('usersinfo.taluka',$request->taluka);
    //             })
                
    //             ->when($request->get('city'), function($query) use ($request) {
    //               $query->where('usersinfo.city',$request->city);
    //             })
    //             ->get();
    //           $count_upto_today_sales_list=sizeof($upto_today_sales_count);
              
              
              
              
    //           $Monthly=[
    //               'name'=>'Monthly',
    //               'value'=>$count_monthly_sales_list,
    //             ];
    //             $Yearly=[
    //               'name'=>'Yearly',
    //               'value'=>$count_yearly_sales_list,
    //             ];
                
    //             $Upto_Today=[
    //               'name'=>'Upto Today',
    //               'value'=>$count_upto_today_sales_list,
    //             ];
                
              
    //             $result=json_encode([
    //               $Monthly,
    //               $Yearly,
    //               $Upto_Today,
    //             ]);
    //             $result=stripslashes($result);
              
            
    //           if ($result)
    //         {
               
    //              return response()->json([
    //                  "data" => $result,
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
    
    
    
    
    // public function webdash_distributor_sales_count(Request $request)
    // {
    //     try{
               
    //           $yearly_sales_count=SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')
    //           ->whereIn('usersinfo.user_type',['fsc','bsc','dsc'])->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')->whereYear('tbl_sale_summary.created_at', Carbon::now()->year)
              
    //              ->when($request->get('state'), function($query) use ($request) {
                   
    //               $query->where('usersinfo.state',$request->state);
    //             })
                
    //             ->when($request->get('district'), function($query) use ($request) {
    //               $query->where('usersinfo.district',$request->district);
    //             })
                
    //             ->when($request->get('taluka'), function($query) use ($request) {
    //               $query->where('usersinfo.taluka',$request->taluka);
    //             })
                
    //             ->when($request->get('city'), function($query) use ($request) {
    //               $query->where('usersinfo.city',$request->city);
    //             })
                
    //         ->get();
            
    //           $count_yearly_sales_list=sizeof($yearly_sales_count);
              
              
              
              
    //           $monthly_sales_count= SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')
    //           ->whereIn('usersinfo.user_type',['fsc','bsc','dsc'])->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')->whereYear('tbl_sale_summary.created_at', Carbon::now()->year)->whereMonth('tbl_sale_summary.created_at', Carbon::now()->month)
              
    //           ->when($request->get('state'), function($query) use ($request) {
                   
    //               $query->where('usersinfo.state',$request->state);
    //             })
                
    //             ->when($request->get('district'), function($query) use ($request) {
    //               $query->where('usersinfo.district',$request->district);
    //             })
                
    //             ->when($request->get('taluka'), function($query) use ($request) {
    //               $query->where('usersinfo.taluka',$request->taluka);
    //             })
                
    //             ->when($request->get('city'), function($query) use ($request) {
    //               $query->where('usersinfocity',$request->city);
    //             })
    //             ->get();
    //           $count_monthly_sales_list=sizeof($monthly_sales_count);
              
              
              
              
    //           $upto_today_sales_count= SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')->whereIn('usersinfo.user_type',['fsc','bsc','dsc'])->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')
              
    //           ->when($request->get('state'), function($query) use ($request) {
                   
    //               $query->where('usersinfo.state',$request->state);
    //             })
                
    //             ->when($request->get('district'), function($query) use ($request) {
    //               $query->where('usersinfo.district',$request->district);
    //             })
                
    //             ->when($request->get('taluka'), function($query) use ($request) {
    //               $query->where('usersinfo.taluka',$request->taluka);
    //             })
                
    //             ->when($request->get('city'), function($query) use ($request) {
    //               $query->where('usersinfo.city',$request->city);
    //             })
    //             ->get();
    //           $count_upto_today_sales_list=sizeof($upto_today_sales_count);
              
              
              
              
    //           $Monthly=[
    //               'name'=>'Monthly',
    //               'value'=>$count_monthly_sales_list,
    //             ];
    //             $Yearly=[
    //               'name'=>'Yearly',
    //               'value'=>$count_yearly_sales_list,
    //             ];
                
    //             $Upto_Today=[
    //               'name'=>'Upto Today',
    //               'value'=>$count_upto_today_sales_list,
    //             ];
                
              
    //             $result=json_encode([
    //               $Monthly,
    //               $Yearly,
    //               $Upto_Today,
    //             ]);
    //             $result=stripslashes($result);
              
            
    //           if ($result)
    //         {
               
    //              return response()->json([
    //                  "data" => $result,
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
    
    
    public function web_distributor_approved_to_final_list(Request $request)
    {
        try 
        {
           
            $dist_promotion = UsersInfo::where(['user_id'=>$request->user_id])->update(['new_list_to_view'=>'n']);
                
            if ($dist_promotion)
            {
                 return response()->json([
                    "data" => $dist_promotion,
                    "result" => true,
                    "message" => 'Distributor approved Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Distributor not approved'
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
    
    
    
    public function web_distributor_promotion(Request $request)
    {
        try 
        {
            $dist_promotion = Dist_Promotion_Demotion::where(['user_id_need_to_promote_demote' => $request->user_id])->orderBy('id','desc')->first();
            \Log::info("in promotion ");
            \Log::info($dist_promotion);

            if($dist_promotion) {
                if($dist_promotion->user_type_old=='bsc') {
                    if(count(UsersInfoForStructures::where(['added_by'=>$dist_promotion->user_id_need_to_promote_demote,'user_type'=>'bsc'])->get()->toArray())>=5) {
                        User::where('id',$dist_promotion->user_id_need_to_promote_demote)->update(['user_type'=>'dsc']);
                        UsersInfo::where('user_id',$dist_promotion->user_id_need_to_promote_demote)->update(['user_type'=>'dsc']);
                        UsersInfoForStructures::where(['user_id'=>$dist_promotion->user_id_need_to_promote_demote])->update([
                            'user_type'=>'dsc'
                        ]);
                        Dist_Promotion_Demotion::where('user_id_need_to_promote_demote',$request->user_id)->update(['is_updated'=>'y']);
                        // UsersInfo::where(['user_id'=>$request->user_id])->update(['new_list_to_view'=>'n']);
                    }
                } elseif($dist_promotion->user_type_old=='fsc') {

                    \Log::info('$dist_promotion->user_type_old');
                    \Log::info($dist_promotion->user_type_old);
                    if(count(UsersInfoForStructures::where(['added_by'=>$dist_promotion->user_id_need_to_promote_demote,'user_type'=>'fsc'])->get()->toArray())>=5) {
                        User::where('id',$dist_promotion->user_id_need_to_promote_demote)->update(['user_type'=>'bsc']);
                        UsersInfo::where('user_id',$dist_promotion->user_id_need_to_promote_demote)->update(['user_type'=>'bsc']);
                        UsersInfoForStructures::where(['user_id'=>$dist_promotion->user_id_need_to_promote_demote])->update([
                            'user_type'=>'bsc'
                        ]);
                        Dist_Promotion_Demotion::where('user_id_need_to_promote_demote',$request->user_id)->update(['is_updated'=>'y']);
                        // UsersInfo::where(['user_id'=>$request->user_id])->update(['new_list_to_view'=>'n']);
                    }
                }
            } 
            if ($dist_promotion)
            {
                 return response()->json([
                    "data" => $dist_promotion,
                    "result" => true,
                    "message" => 'Distributor Promoted Successfully'
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
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    
    public function web_distributor_demotion(Request $request)
    {
        try 
        {
            $user_type=UsersInfo::where('user_id',$request->user_id)->first();
            if($user_type->user_type == 'dsc')
            {
                $usr_ty = 'bsc';
            }
            elseif($user_type->user_type == 'bsc')
            {
                $usr_ty = 'fsc';
            }
            
            $dist_demotion = User::where('id',$request->user_id)->update(['user_type'=>$usr_ty]);
            $dist_demotion = UsersInfo::where('user_id',$request->user_id)->update(['user_type'=>$usr_ty]);
          
            UsersInfoForStructures::where(['user_id'=>$request->user_id])->update([
                'user_type'=>$usr_ty
            ]);

            if ($dist_demotion)
            {
                 return response()->json([
                    "data" => $dist_demotion,
                    "result" => true,
                    "message" => 'Distributor Demoted Successfully'
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
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    

    // Satish New Code 

    public function web_distributor_promotion_demotion_own(Request $request)
    {
        try 
        {
            User::where('id',$request->user_id)->update(['user_type'=>$request->user_type]);
            UsersInfo::where('user_id',$request->user_id)->update(['user_type'=>$request->user_type]);
            $updated = UsersInfoForStructures::where(['user_id'=>$request->user_id])->update([
                'user_type'=>$request->user_type
            ]);
               
            if ($updated)
            {
                 return response()->json([
                    "result" => true,
                    "message" => 'Distributor updated Successfully'
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
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }
    }
    
    //New COde Satish 
    
    
    
    
    
    public function webdash_sales_count(Request $request)
    {
        try{
               
              $yearly_sales_count=SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')
              ->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')->whereYear('tbl_sale_summary.created_at', Carbon::now()->year)
              
                 ->when($request->get('state'), function($query) use ($request) {
                   
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
                
                ->when($request->get('user_type'), function($query) use ($request) {
                  $query->whereIn('usersinfo.user_type',['fsc','bsc','dsc']);
                })
                
                ->when($request->get('user_type'), function($query) use ($request) {
                  $query->where('usersinfo.user_type','farmer');
                })
                
            ->get();
            
              $count_yearly_sales_list=sizeof($yearly_sales_count);
              
              
              
              
              $monthly_sales_count= SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')
              ->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')->whereYear('tbl_sale_summary.created_at', Carbon::now()->year)->whereMonth('tbl_sale_summary.created_at', Carbon::now()->month)
              
              ->when($request->get('state'), function($query) use ($request) {
                   
                  $query->where('usersinfo.state',$request->state);
                })
                
                ->when($request->get('district'), function($query) use ($request) {
                  $query->where('usersinfo.district',$request->district);
                })
                
                ->when($request->get('taluka'), function($query) use ($request) {
                  $query->where('usersinfo.taluka',$request->taluka);
                })
                
                ->when($request->get('city'), function($query) use ($request) {
                  $query->where('usersinfocity',$request->city);
                })
                
                ->when($request->get('user_type'), function($query) use ($request) {
                  $query->whereIn('usersinfo.user_type',['fsc','bsc','dsc']);
                })
                
                ->when($request->get('user_type'), function($query) use ($request) {
                  $query->where('usersinfo.user_type','farmer');
                })
                
                
                ->get();
              $count_monthly_sales_list=sizeof($monthly_sales_count);
              
              
              
              
              $upto_today_sales_count= SaleSummary::join('usersinfo','usersinfo.user_id','=','tbl_sale_summary.created_disctributor_id')->where('tbl_sale_summary.is_deleted','no')->where('tbl_sale_summary.order_dispatched','yes')
              
              ->when($request->get('state'), function($query) use ($request) {
                   
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
                
                ->when($request->get('user_type'), function($query) use ($request) {
                  $query->whereIn('usersinfo.user_type',['fsc','bsc','dsc']);
                })
                
                ->when($request->get('user_type'), function($query) use ($request) {
                  $query->where('usersinfo.user_type','farmer');
                })
                
                
                ->get();
              $count_upto_today_sales_list=sizeof($upto_today_sales_count);
              
              
              
              
              $Monthly=[
                   'name'=>'Monthly',
                  'value'=>$count_monthly_sales_list,
                ];
                $Yearly=[
                  'name'=>'Yearly',
                  'value'=>$count_yearly_sales_list,
                ];
                
                $Upto_Today=[
                  'name'=>'Upto Today',
                  'value'=>$count_upto_today_sales_list,
                ];
                
              
                $result=json_encode([
                  $Monthly,
                  $Yearly,
                  $Upto_Today,
                ]);
                $result=stripslashes($result);
              
            
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
    
    
    
    public function client_logos_add_web(Request $request)
    {
        try 
        {
            $client_logo = new WebClientLogo();
            $client_logo->created_by = 0;
            $client_logo->status = 0;
            $client_logo->save();
            
            if ($request->logo)
            {
                $imagedataPath=WEB_CLIENT_LOGO_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$client_logo->id."_logo";
                if (!empty($request->logo))
                {     
                    $applpic_ext = $request->file('logo')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('logo'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);
                }
                $client_logo=WebClientLogo::where('id',$client_logo->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
            }
           
            if ($client_logo)
            {
                 return response()->json([
                    "data" => $client_logo,
                    "result" => true,
                    "message" => 'Client Logo Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Client Logo Not Added'
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

    
    
    public function client_logoslist(Request $request)
    {
        try
        {
            $result = WebClientLogo::where('status','0')->orderBy('id', 'DESC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_CLIENT_LOGO_VIEW.$value->photo_one;
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
    
    
    
    public function client_logosupdate(Request $request)
    {
        
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            if ($request->hasFile('logo'))
            {
                $imagedataPath=WEB_CLIENT_LOGO_UPLOAD;
                $userId = WebClientLogo::where('id',$requestdata->id)->first();
               
                $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_logo";
                if ($request->hasFile('logo'))
                {     
                    $applpic_ext = $request->file('logo')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('logo'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $client_logo=WebClientLogo::where('id',$requestdata->id)->update(['photo_one'=>$photoName.".".$applpic_ext]);
                }
            }
 

            if ($client_logo)
            {
                 return response()->json([
                    "data" => $client_logo,
                    "result" => true,
                    "message" => 'Logo Updated Successfully'
                ]);
            }
            elseif($request->hasFile('photo_one'))
            {
                return response()->json([
                "data" => $client_logo,
                "result" => true,
                "message" => 'Logo Updated Successfully'
                ]);
            }
            else
            {
        
                
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Logo Not Updated'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function client_logosget(Request $request)
    {
        
        try
        {
            $result = WebClientLogo::where('id',$request->id)->where('status','0')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_CLIENT_LOGO_VIEW.$value->photo_one;
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
    
    public function client_logosdelete(Request $request)
    {
        try
        {
            $data=[
                'status'=>1,
            ];
            
            $user = WebClientLogo::where('id',$request->id)->update($data);
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
                    "result" => true,
                    "message" => 'Logo Deleted Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Logo Not Deleted'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    public function front_counter_add(Request $request)
    {
        try
        {
            $counter = new Counter();
            //$counter->counter_one_name = $request->counter_one_name;
            $counter->counter_one_count = $request->counter_one_count;
            
            //$counter->counter_two_name = $request->counter_two_name;
            $counter->counter_two_count = $request->counter_two_count;
            
            //$counter->counter_three_name = $request->counter_three_name;
            $counter->counter_three_count = $request->counter_three_count;
            
            //$counter->counter_four_name = $request->counter_four_name;
            $counter->counter_four_count = $request->counter_four_count;
            
            //$counter->counter_five_name = $request->counter_five_name;
            $counter->counter_five_count = $request->counter_five_count;
            
            $counter->created_by ='0';
            $counter->save();
          
            
            if ($counter)
            {
                 return response()->json([
                    "data" => $counter,
                    "result" => true,
                    "message" => 'Counter Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Counter Not Added'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    
    public function front_counter_list(Request $request)
    {
        try
        {
            $result = Counter::where('is_deleted','no')->orderBy('id', 'DESC')->get();
            
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
    
    
    
     public function front_counter_get(Request $request)
    {
        try
        {
            $result = Counter::where('id',$request->id)->where('is_deleted','no')->get();
            
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
    
    
    
    
    public function front_counter_update(Request $request)
    {
        try
        {
            $data=[
                // 'counter_one_name'=> $request->counter_one_name,
                'farmer_count'=> $request->farmer_count,
                // 'counter_two_name'=> $request->counter_two_name,
                'youtube_sus_count'=> $request->youtube_sus_count,
                // 'counter_three_name'=> $request->counter_three_name,
                'distributor_count'=> $request->distributor_count,
                // 'counter_four_name'=> $request->counter_four_name,
                'sem_meet_count'=> $request->sem_meet_count,
                // 'counter_five_name'=> $request->counter_five_name,
                'app_down_count'=> $request->app_down_count,
                
            ];
            
            $Counter = Counter::where('id',$request->id)->update($data);
            
            if ($Counter)
            {
                 return response()->json([
                    "data" => $Counter,
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
    
    
    public function front_counter_delete(Request $request)
    {
        try 
        {
            $counter = Counter::where('id',$request->id)->update(['is_deleted'=>'yes']);
          
            if ($counter)
            {
                 return response()->json([
                    "data" => $counter,
                    "result" => true,
                    "message" => 'Counter Deleted Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Record Not Found'
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
    
    
    
    
    
    
    public function career_list(Request $request)
    {
        try
        {
            $result = Career::where('is_deleted','no')->orderBy('id', 'ASC')->get();
            foreach($result as $key=>$value)
            {
                $value->internshipmenuphoto=WEB_CAREER_VIEW.$value->internshipmenuphoto;
                $value->dsitmenuphotoview=WEB_CAREER_VIEW.$value->dsitmenuphotoview;
                $value->jobmenuphotoview=WEB_CAREER_VIEW.$value->jobmenuphotoview;
                $value->certificatephotoview=WEB_CAREER_VIEW.$value->certificatephotoview;
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
    
    
    
    public function career_update(Request $request)
    {
        
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            // if ($request->hasFile('photo_one'))
            // {
                $imagedataPath=WEB_CAREER_UPLOAD;
                // $userId = Career::where('id',$requestdata->id)->first();
                // $delfile=$this->deleteFile($userId->internshipmenuphoto, $imagedataPath);
                // $delfile=$this->deleteFile($userId->dsitmenuphotoview, $imagedataPath);
                // $delfile=$this->deleteFile($userId->jobmenuphotoview, $imagedataPath);
                // $delfile=$this->deleteFile($userId->certificatephotoview, $imagedataPath);
                
                // if ( !is_dir( $imagedataPath) ) 
                // {
                //     mkdir( $imagedataPath );       
                // }
                
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_internshipmenuphoto";
                if ($request->hasFile('internshipmenuphoto'))
                {     
                    $applpic_ext = $request->file('internshipmenuphoto')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('internshipmenuphoto'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $career=Career::where('id',$requestdata->id)->update(['internshipmenuphoto'=>$photoName.".".$applpic_ext]);
                }
                
                
                $distphotoName=$requestdata->id."_dsitmenuphotoview";
                if ($request->hasFile('dsitmenuphotoview'))
                {     
                    $diapplpic_ext = $request->file('dsitmenuphotoview')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('dsitmenuphotoview'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$distphotoName.".".$diapplpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $career=Career::where('id',$requestdata->id)->update(['dsitmenuphotoview'=>$distphotoName.".".$diapplpic_ext]);
                }
                
                
                $jobphotoName=$requestdata->id."_jobmenuphotoview";
                if ($request->hasFile('jobmenuphotoview'))
                {     
                    $applpic_ext = $request->file('jobmenuphotoview')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('jobmenuphotoview'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$jobphotoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $career=Career::where('id',$requestdata->id)->update(['jobmenuphotoview'=>$jobphotoName.".".$applpic_ext]);
                }
                
                
                $certphotoName=$requestdata->id."_certificatephotoview";
                if ($request->hasFile('certificatephotoview'))
                {     
                    $applpic_ext = $request->file('certificatephotoview')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('certificatephotoview'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$certphotoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $career=Career::where('id',$requestdata->id)->update(['certificatephotoview'=>$certphotoName.".".$applpic_ext]);
                }
                
                
            $data=[
                'enternshipmenuname'=> $requestdata->enternshipmenuname,
                'distmenuname'=> $requestdata->distmenuname,
                'jobmenuname'=> $requestdata->jobmenuname
            ];
            
            $career = Career::where('id',$requestdata->id)->update($data);
            
            

            if ($career)
            {
                 return response()->json([
                    "data" => $career,
                    "result" => true,
                    "message" => 'Information Updated Successfully'
                ]);
            }
            // elseif($request->hasFile('image'))
            // {
            //     return response()->json([
            //     "data" => $career,
            //     "result" => true,
            //     "message" => 'Information Updated Successfully'
            //     ]);
            // }
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
    
    public function career_get(Request $request)
    {
        
        try
        {
            $result = Career::where('id',$request->id)->where('is_deleted','no')->get();
            foreach($result as $key=>$value)
            {
                $value->internshipmenuphoto=WEB_CAREER_VIEW.$value->internshipmenuphoto;
                $value->dsitmenuphotoview=WEB_CAREER_VIEW.$value->dsitmenuphotoview;
                $value->jobmenuphotoview=WEB_CAREER_VIEW.$value->jobmenuphotoview;
                $value->certificatephotoview=WEB_CAREER_VIEW.$value->certificatephotoview;
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
    
    
    public function career_add(Request $request)
    {
        try 
        {
            $career = new Career();
            $career->enternshipmenuname = $request->enternshipmenuname;
            $career->distmenuname = $request->distmenuname;
            $career->jobmenuname = $request->jobmenuname;
            $career->save();
            
            $idLastInserted=$career->id;
            
            $imagedataPath=WEB_CAREER_UPLOAD;
            if ( !is_dir( $imagedataPath) ) 
            {
                mkdir( $imagedataPath );       
            }
        
            $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$idLastInserted."_internshipmenuphoto";
            $inputfilenametoupload='internshipmenuphoto';
            
            if (!empty($request->hasFile($inputfilenametoupload)))
            {   
                
                $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                $career = Career::where('id',$idLastInserted)->update(['internshipmenuphoto'=>$photoName]);
            }
            
            $disyphotoName=$idLastInserted."_dsitmenuphotoview";
            $distinputfilenametoupload='dsitmenuphotoview';
            
            if (!empty($request->hasFile($distinputfilenametoupload)))
            {   
                $filename=$this->processUpload($request, $distinputfilenametoupload,$imagedataPath,$disyphotoName);
                $career = Career::where('id',$idLastInserted)->update(['dsitmenuphotoview'=>$disyphotoName]);
            }
            
            $jobphotoName=$idLastInserted."_jobmenuphotoview";
            $jobinputfilenametoupload='jobmenuphotoview';
            
            if (!empty($request->hasFile($jobinputfilenametoupload)))
            {   
                $filename=$this->processUpload($request, $jobinputfilenametoupload,$imagedataPath,$jobphotoName);
                $career = Career::where('id',$idLastInserted)->update(['jobmenuphotoview'=>$jobphotoName]);
            }
            
            
            $certphotoName=$idLastInserted."_certificatephotoview";
            $certinputfilenametoupload='certificatephotoview';
            
            if (!empty($request->hasFile($certinputfilenametoupload)))
            {   
                $filename=$this->processUpload($request, $certinputfilenametoupload,$imagedataPath,$certphotoName);
                $career = Career::where('id',$idLastInserted)->update(['certificatephotoview'=>$certphotoName]);
            }
           
            if ($career)
            {
                 return response()->json([
                    "data" => $career,
                    "result" => true,
                    "message" => 'Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Not Added'
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
    
    
    
    
    
    
    
    public function download_internship_resume(Request $request)
    {

        $content = WebInternship::where('id', '=', $request->id)->first();
        
        $path=INTERNSHIP_CONTENT_VIEW.$content->resume;
        $filename=$content->resume;
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
            $response['message'] = 'Resume Downloaded Get Successfully';
            //$response['result'] = 'true';
            //return response()->json($response);
            return response()->json(compact('path'));
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Resume Not Found';
            $response['result'] = 'false';
            return response()->json($response);
        }
        
    }
    
    
    // Website Marquee
    public function website_marquee_add(Request $request)
    {
        try 
        {
            $marquee = new Marquee();
            $marquee->marquee = $request->marquee;
            $marquee->save();
            if ($marquee)
            {
                 return response()->json([
                    "data" => $marquee,
                    "result" => true,
                    "message" => 'Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Not Added'
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
    
    
    
    public function website_marquee_list(Request $request)
    {
        try
        {
            $result = Marquee::where('is_deleted','no')->where('is_active','yes')->orderBy('id', 'DESC')->get();
            
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
    
    
    
    public function website_marquee_get(Request $request)
    {
        
        try
        {
            $result = Marquee::where('id',$request->id)->where('is_deleted','no')->get();
            
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
    
    
    
    
    
    public function website_marquee_update(Request $request)
    {
        
        try
        {
            //$requestnew=json_decode($request->dataforinsert, true);
            // $requestdata = (object)$requestnew;
            
            $data=[
                'marquee'=> $request->marquee
            ];
            
            $marquee = Marquee::where('id',$request->id)->update($data);

            if ($marquee)
            {
                 return response()->json([
                    "data" => $marquee,
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
    
    
    
    public function website_marquee_delete(Request $request)
    {
        try
        {
            $data=[
                'is_deleted'=>'yes',
            ];
            
            $marquee = Marquee::where('id',$request->id)->update($data);
            if ($marquee)
            {
                 return response()->json([
                    "data" => $marquee,
                    "result" => true,
                    "message" => 'News Deleted Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'News Not Deleted'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    
    public function website_marquee_active(Request $request)
    {
        try
        {
            $data=[
                'is_active'=>'yes',
            ];
            
            $marquee = Marquee::where('id',$request->id)->update($data);
            if ($marquee)
            {
                 return response()->json([
                    "data" => $marquee,
                    "result" => true,
                    "message" => 'Marquee Activate Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Marquee Not Activated'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    public function website_marquee_inactive(Request $request)
    {
        try
        {
            $data=[
                'is_active'=>'no',
            ];
            
            $marquee = Marquee::where('id',$request->id)->update($data);
            if ($marquee)
            {
                 return response()->json([
                    "data" => $marquee,
                    "result" => true,
                    "message" => 'Marquee Inactivate Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Marquee Not Inactivated'
                ]);
                
            }
        }
        catch(Exception $e) {
          return  'Message: ' .$e->getMessage();
        }

    }
    
    
    
    
    public function frontenquiryget(Request $request)
    {
        try
        {
            $result = Enquiry::where('is_deleted','no')
                                ->when($request->get('datefrom'), function($query) use ($request) {
                                    $query->whereBetween('created_at', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                                }) 
                                
                                ->orderBy('id', 'DESC')
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
    
    public function internship_list(Request $request)
    {
        try
        {
            //$query->whereBetween('order_date', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
            $result = WebInternship::where('is_deleted','no')
            ->when($request->get('datefrom'), function($query) use ($request) {
                $query->whereBetween('created_at', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
             }) 
            ->orderBy('id', 'desc')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=INTERNSHIP_CONTENT_VIEW.$value->resume;
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
    
   
     public function internship_delete(Request $request)
    {
        try 
        {
            $intern = WebInternship::where('id',$request->id)->update(['is_deleted'=>'yes']);
          
            if ($intern)
            {
                 return response()->json([
                    "data" => $intern,
                    "result" => true,
                    "message" => 'Record Deleted Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Record Not Found'
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
    
    
    
    public function internship_get(Request $request)
    {
        
        try
        {
            $result = WebInternship::where('id',$request->id)->where('is_deleted','no')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=INTERNSHIP_CONTENT_VIEW.$value->resume;
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
    
    
    
    public function internship_update(Request $request)
    {
        
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            // if ($request->hasFile('photo_one'))
            // {
                $imagedataPath=INTERNSHIP_CONTENT_UPLOAD;
                $userId = WebInternship::where('id',$requestdata->id)->first();
                $delfile=$this->deleteFile($userId->resume, $imagedataPath);
                
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_resume";
                if ($request->hasFile('resume'))
                {     
                    $applpic_ext = $request->file('resume')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('resume'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $career=WebInternship::where('id',$requestdata->id)->update(['resume'=>$photoName.".".$applpic_ext]);
                }
                
                
                
                
            $data=[
                'name'=> $requestdata->name,
                'email'=> $requestdata->email,
                'mobile'=> $requestdata->mobile,
                'qualification'=> $requestdata->qualification,
                'address'=> $requestdata->address
            ];
            
            $career = WebInternship::where('id',$requestdata->id)->update($data);
            
            

            if ($career)
            {
                 return response()->json([
                    "data" => $career,
                    "result" => true,
                    "message" => 'Information Updated Successfully'
                ]);
            }
            // elseif($request->hasFile('image'))
            // {
            //     return response()->json([
            //     "data" => $career,
            //     "result" => true,
            //     "message" => 'Information Updated Successfully'
            //     ]);
            // }
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
    
    
    public function job_posting_list(Request $request)
    {
        try
        {
            $result = WebJobPosting::where('is_deleted','no')
                                    ->when($request->get('datefrom'), function($query) use ($request) {
                                        $query->whereBetween('created_at', [$request->datefrom.' 00:00:00',$request->dateto.' 23:59:59']);
                                    }) 
                                    
                                    ->select('id','name', 'email', 'mobile', 'qualification', 'experience_from', 'experience_to', 'address', 'resume')
                                    ->orderBy('id', 'DESC')
                                    ->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=JOBPOSTING_CONTENT_VIEW.$value->resume;
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
    
   
     public function job_posting_delete(Request $request)
    {
        try 
        {
            $posting = WebJobPosting::where('id',$request->id)->update(['is_deleted'=>'yes']);
          
            if ($posting)
            {
                 return response()->json([
                    "data" => $posting,
                    "result" => true,
                    "message" => 'Record Deleted Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Record Not Found'
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
    
    
    public function job_posting_get(Request $request)
    {
        
        try
        {
            $result = WebJobPosting::where('id',$request->id)->where('is_deleted','no')->get();
            foreach($result as $key=>$value)
            {
                $value->resume=JOBPOSTING_CONTENT_VIEW.$value->resume;
                
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

    public function job_posting_update(Request $request)
    {
        
        try
        {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            // if ($request->hasFile('photo_one'))
            // {
                $imagedataPath=JOBPOSTING_CONTENT_UPLOAD;
                $userId = WebJobPosting::where('id',$requestdata->id)->first();
                $delfile=$this->deleteFile($userId->resume, $imagedataPath);
                
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_resume";
                if ($request->hasFile('resume'))
                {     
                    $applpic_ext = $request->file('resume')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('resume'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $career=WebJobPosting::where('id',$requestdata->id)->update(['resume'=>$photoName.".".$applpic_ext]);
                }
                
            $data=[
                'name'=> $requestdata->name,
                'email'=> $requestdata->email,
                'mobile'=> $requestdata->mobile,
                'qualification'=> $requestdata->qualification,
                // 'experience_from'=> $requestdata->experience_from,
                // 'experience_to'=> $requestdata->experience_to,
                'address'=> $requestdata->address
            ];
            
            $career = WebJobPosting::where('id',$requestdata->id)->update($data);
            
            

            if ($career)
            {
                 return response()->json([
                    "data" => $career,
                    "result" => true,
                    "message" => 'Information Updated Successfully'
                ]);
            }
            // elseif($request->hasFile('image'))
            // {
            //     return response()->json([
            //     "data" => $career,
            //     "result" => true,
            //     "message" => 'Information Updated Successfully'
            //     ]);
            // }
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
    
    
    
    public function download_job_posting_resume(Request $request)
    {

        $jobcontent = WebJobPosting::where('id', '=', $request->id)->first();
        
        $path=JOBPOSTING_CONTENT_VIEW.$jobcontent->resume;
        $filename=$jobcontent->resume;
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
            $response['message'] = 'Resume Downloaded Get Successfully';
            //$response['result'] = 'true';
            //return response()->json($response);
            return response()->json(compact('path'));
        }
        else
        {
            $response = array();
            $response['code'] = 400;
            $response['message'] = 'Resume Not Found';
            $response['result'] = 'false';
            return response()->json($response);
        }
        
    }
    
    public function blog_reply_list(Request $request)
    {
        try
        {
            //$result = BlogReply::where('is_deleted','no')->orderBy('id', 'DESC')->get();
            
            $result = BlogReply::join('tbl_web_blog','tbl_web_blog.id','=','tbl_blog_reply.blog_id')
                ->where('tbl_blog_reply.is_deleted','no')
                ->where('tbl_web_blog.status','no')
                ->select('tbl_blog_reply.*','tbl_web_blog.title')
                ->orderBy('tbl_blog_reply.id', 'DESC')
                ->get();
                
            // foreach($result as $key=>$value)
            // {
            //     $value->photopath=JOBPOSTING_CONTENT_VIEW.$value->resume;
            // }
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
    
    
    // Product Review List
    public function product_review_list(Request $request)
    {
        try
        {
            $result = ProductReview::join('front_product','front_product.id','=','tbl_productreview.product_id')
                                    ->join('tbl_product','tbl_product.id','=','front_product.product_id')
                ->where('tbl_productreview.is_deleted','no')
                ->where('front_product.is_deleted','no')
                ->where('tbl_product.is_deleted','no')
                ->select('tbl_productreview.*','front_product.id','tbl_product.title')
                ->orderBy('tbl_productreview.id', 'DESC')
                ->get();

                
            // foreach($result as $key=>$value)
            // {
            //     $value->photopath=JOBPOSTING_CONTENT_VIEW.$value->resume;
            // }
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
    // Front Product
    public function frontproductadd(Request $request)
    {
        try
        {
            //dd($request);
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            $user = new FrontProduct();
            $user->product_id = $requestdata->product_id;
            $user->short_description = $requestdata->short_description;
            $user->rating = $requestdata->rating;
            $user->review_person_name = $requestdata->review_person_name;
            $user->review = $requestdata->review;
            $user->long_description = $requestdata->long_description;
            $user->additional_info = $requestdata->additional_info;
            
            $user->created_by ='0';
            $user->save();
            
            
            if ($request->photo_one)
            {
        
                $imagedataPath=FRONTPRODUCT_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$user->id."_photo";
                if (!empty($request->photo_one))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                }
                $users=FrontProduct::where('id',$user->id)->update(['photo'=>$photoName.".".$applpic_ext]);
                
               
            }

            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    
    
    
    public function frontproductlist(Request $request)
    {
        try
        {
            // $result = FrontProduct::join('tbl_product','tbl_product.id','=','front_product.product_id')
            //     ->where('tbl_product.is_deleted','no')
            //     ->where('front_product.is_deleted','no')
            //     ->select('front_product.*','tbl_product.title','tbl_product.photo_one')
            //     ->orderBy('tbl_product.id', 'DESC')
            //     ->get();
            
            $result = FrontProduct::join('tbl_product_details','tbl_product_details.id','=','front_product.product_id')
            ->join('tbl_product','tbl_product.id','=','tbl_product_details.product_id')
            ->where('front_product.is_deleted','no')
            ->where('tbl_product.is_deleted','no')
            ->where('tbl_product_details.is_deleted','no')
                    ->select('front_product.*','tbl_product.title','tbl_product.photo_one')
                ->get();

            foreach($result as $key=>$value)
            {
                $value->photopath=FRONTPRODUCT_CONTENT_VIEW.$value->photo;
                $value->productphotopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
                $value->long_description=strip_tags($value->long_description);
                $value->additional_info=strip_tags($value->additional_info);
                
                
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
    
    
    
    public function frontproductget(Request $request)

     {
        try
        {
            $result = FrontProduct::join('tbl_product','tbl_product.id','=','front_product.product_id')
                ->where('tbl_product.is_deleted','no')
                ->where('front_product.is_deleted','no')
                ->select('front_product.*','tbl_product.title','tbl_product.photo_one')
                ->where('front_product.id',$request->id)
                ->orderBy('tbl_product.id', 'DESC')
                ->get();
            
            foreach($result as $key=>$value)
            {
                $value->photopath=FRONTPRODUCT_CONTENT_VIEW.$value->photo;
                // $value->productphotopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
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
    
    
    public function frontproductupdate(Request $request)
    {
        try
        {
            
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            if ($request->hasFile('photo_one'))
            {
                $imagedataPath=FRONTPRODUCT_CONTENT_UPLOAD;
                $userId = FrontProduct::where('id',$requestdata->id)->first();
                
                $delfile=$this->deleteFile($userId->photo_one, $imagedataPath);
                
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$requestdata->id."_companyprofile";
                if ($request->hasFile('photo_one'))
                {     
                    $applpic_ext = $request->file('photo_one')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('photo_one'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $user=FrontProduct::where('id',$requestdata->id)->update(['photo'=>$photoName.".".$applpic_ext]);
                }
            }
            
            $data=[
                'product_id'=> $requestdata->product_id,
                'short_description'=> $requestdata->short_description,
                'rating'=> $requestdata->rating,
                'review_person_name'=> $requestdata->review_person_name,
                'review'=> $requestdata->review,
                'long_description'=> $requestdata->long_description,
                'additional_info'=> $requestdata->additional_info
            ];
            
            $user = FrontProduct::where('id',$requestdata->id)->update($data);
            
            if ($user)
            {
                 return response()->json([
                    "data" => $user,
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
    
    
    public function frontproductdelete(Request $request)
    {
        try
        {
            $data=[
                'is_deleted'=>'yes',
            ];
            
            $user = FrontProduct::where('id',$request->id)->update($data);
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
    
    
    public function frontdistributorlist(Request $request)
    {
        $result = FrontUsers:: 
        // leftJoin('usersinfo as dist_name', function($join) {
        //     $join->on('front_usersinfo.created_by', '=', 'dist_name.user_id');
        //   })
          
          
        //   ->leftJoin('usersinfo AS farm_name', function($join) {
        //     $join->on('front_usersinfo.farmer_id', '=', 'farm_name.user_id');
        //   })
          
          leftJoin('tbl_area as stateNew', function($join) {
            $join->on('front_usersinfo.state', '=', 'stateNew.location_id');
          })
          
          ->leftJoin('tbl_area as districtNew', function($join) {
            $join->on('front_usersinfo.district', '=', 'districtNew.location_id');
          })
          
          
          ->leftJoin('tbl_area as talukaNew', function($join) {
            $join->on('front_usersinfo.taluka', '=', 'talukaNew.location_id');
          })
          
          ->leftJoin('tbl_area as cityNew', function($join) {
            $join->on('front_usersinfo.city', '=', 'cityNew.location_id');
          })


        //   Business area 

        

        ->when($request->get('state'), function($query) use ($request) {
                   
            $query->where('front_usersinfo.state',$request->state);
          })
          
          ->when($request->get('district'), function($query) use ($request) {
            $query->where('front_usersinfo.district',$request->district);
          })
          
          ->when($request->get('taluka'), function($query) use ($request) {
            $query->where('front_usersinfo.business_tuluka',$request->taluka);
          })
          
          ->when($request->get('city'), function($query) use ($request) {
            $query->where('front_usersinfo.business_village',$request->city);
          })
          

        ->leftJoin('tbl_area as stateNewBusiness', function($join) {
            $join->on('front_usersinfo.business_state', '=', 'stateNewBusiness.location_id');
          })
          
          ->leftJoin('tbl_area as districtNewBusiness', function($join) {
            $join->on('front_usersinfo.business_district', '=', 'districtNewBusiness.location_id');
          })
          
          
          ->leftJoin('tbl_area as talukaNewBusiness', function($join) {
            $join->on('front_usersinfo.taluka', '=', 'talukaNewBusiness.location_id');
          })
          
          ->leftJoin('tbl_area as cityNewBusiness', function($join) {
            $join->on('front_usersinfo.city', '=', 'cityNewBusiness.location_id');
          })


          
          ->whereIn('front_usersinfo.user_type',['fsc','bsc','dsc'])
          ->where('front_usersinfo.is_deleted', '=', 'no')
          ->orderBy('front_usersinfo.list_data_status', 'ASC')
          ->select('front_usersinfo.id',
          'front_usersinfo.list_data_status',
          'front_usersinfo.list_data_read',
          'front_usersinfo.fname',
          'front_usersinfo.mname',
          'front_usersinfo.lname',
          'front_usersinfo.email',
          'front_usersinfo.phone',
          'front_usersinfo.alternate_mobile',
          'front_usersinfo.state',
          'front_usersinfo.district',
          'front_usersinfo.taluka',
          'front_usersinfo.city',
          'front_usersinfo.password',
          'front_usersinfo.visible_password',
          'front_usersinfo.user_type',
          'front_usersinfo.is_deleted',
          'front_usersinfo.active',
          'front_usersinfo.remember_token',
          'front_usersinfo.is_verified',
          'front_usersinfo.aadhar_card_image_front',
          'front_usersinfo.aadhar_card_image_back',
          'front_usersinfo.pan_card',
          'front_usersinfo.light_bill',
          'front_usersinfo.shop_act_image',
          'front_usersinfo.product_purchase_bill',
          'front_usersinfo.business_address',
          'front_usersinfo.business_state',
          'front_usersinfo.business_district',
          'front_usersinfo.business_tuluka',
          'front_usersinfo.business_village',
          'front_usersinfo.where_open_shop',
          'front_usersinfo.used_sct',
          'front_usersinfo.why_want_take_distributorship',
          'front_usersinfo.distributorship_exerience',
          'front_usersinfo.experience_farm_garder',
          'front_usersinfo.goal',
          'front_usersinfo.geolocation',
          'front_usersinfo.added_by',
          'front_usersinfo.devicetoken',
          'front_usersinfo.devicetype',
          'front_usersinfo.devicename',
          'front_usersinfo.deviceid',
          'front_usersinfo.logintime',
          'front_usersinfo.created_by',
          'front_usersinfo.created_on',
          'front_usersinfo.updated_on',

          'stateNew.name as state',
          'districtNew.name as district',
          'talukaNew.name as taluka',
          'cityNew.name as city',

          'stateNewBusiness.name as state_business',
          'districtNewBusiness.name as district_business',
          'talukaNewBusiness.name as taluka_business',
          'cityNewBusiness.name as city_business'
          )
          ->get();


        foreach($result as $key=>$value)
        {
            // $stateName=$this->commonController->getAreaNameById($value->state);
            // $value->state=$stateName->name;
            
            // $districtName=$this->commonController->getAreaNameById($value->district);
            // $value->district=$districtName->name;
            
            // $talukaName=$this->commonController->getAreaNameById($value->taluka);
            // $value->taluka=$talukaName->name;
            
            // $cityName=$this->commonController->getAreaNameById($value->city);
            // $value->city=$cityName->name;
            
            
            // $bstateName=$this->commonController->getAreaNameById($value->business_state);
            // if($bstateName) {
            //     $value->business_state=$bstateName->name;
            // } else {
            //     $value->business_state='';
            // }

            
            // $bdistrictName=$this->commonController->getAreaNameById($value->business_district);
            //  if($bdistrictName) {
            //     $value->business_district=$bdistrictName->name;
            // } else {
            //     $value->business_district='';
            // }
            
            // $btalukaName=$this->commonController->getAreaNameById($value->business_tuluka);
            //  if($btalukaName) {
            //     $value->business_tuluka=$btalukaName->name;
            // } else {
            //     $value->business_tuluka='';
            // }
            
            // $bcityName=$this->commonController->getAreaNameById($value->business_village);
            //  if($bcityName) {
            //     $value->business_village=$bcityName->name;
            // } else {
            //     $value->business_village='';
            // }
            
            
            
            $value->aadhar_card_image_front=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->aadhar_card_image_front;
            $value->aadhar_card_image_back=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->aadhar_card_image_back;
            $value->pan_card=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->pan_card;
            $value->light_bill=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->light_bill;
            $value->shop_act_image=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->shop_act_image;
            $value->product_purchase_bill=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->product_purchase_bill;
            
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
            // $response = array();
            // $response['code'] = 400;
            // $response['message'] = 'Distributor List Not Found';
            // $response['result'] = false;
            // return response()->json($response);

            $response = array();
            $response['data'] = array();
            $response['code'] = 200;
            $response['message'] = 'Distributor List Get Successfully';
            $response['result'] = true;
            return response()->json($response);
        }

    }
    
    
        public function web_frontdistributorupdate(Request $request)
    {
            $requestnew=json_decode($request->dataforinsert, true);
            $requestdata = (object)$requestnew;
            
            
            $imagedataPath=FRONT_DISTRIBUTOR_OWN_DOCUMENTS;
                
                $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$request->id."_aadhar_card_image_front";
                if ($request->hasFile('aadhar_card_image_front'))
                {     
                    $applpic_ext = $request->file('aadhar_card_image_front')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('aadhar_card_image_front'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne);    
                    $result=FrontUsers::where('id',$request->id)->update(['aadhar_card_image_front'=>$photoName.".".$applpic_ext]);
                }
                
                
            //     $distphotoName=$request->id."_aadhar_card_image_back";
            //     if ($request->hasFile('aadhar_card_image_back'))
            //     {     
            //         $diapplpic_ext = $request->file('aadhar_card_image_back')->getClientOriginalExtension();
            //         $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('aadhar_card_image_back'))); 
            //         $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
            //         $path2 = $imagedataPath.$distphotoName.".".$diapplpic_ext;
            //         file_put_contents($path2, $applicantAttachmentOne);    
            //         $result=FrontUsers::where('id',$request->id)->update(['aadhar_card_image_back'=>$distphotoName.".".$diapplpic_ext]);
            //     }
                
                
            //     $jobphotoName=$request->id."_pan_card";
            //     if ($request->hasFile('pan_card'))
            //     {     
            //         $applpic_ext = $request->file('pan_card')->getClientOriginalExtension();
            //         $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('pan_card'))); 
            //         $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
            //         $path2 = $imagedataPath.$jobphotoName.".".$applpic_ext;
            //         file_put_contents($path2, $applicantAttachmentOne);    
            //         $result=FrontUsers::where('id',$request->id)->update(['pan_card'=>$jobphotoName.".".$applpic_ext]);
            //     }
                
                
            //     $certphotoName=$request->id."_light_bill";
            //     if ($request->hasFile('light_bill'))
            //     {     
            //         $applpic_ext = $request->file('light_bill')->getClientOriginalExtension();
            //         $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('light_bill'))); 
            //         $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
            //         $path2 = $imagedataPath.$certphotoName.".".$applpic_ext;
            //         file_put_contents($path2, $applicantAttachmentOne);    
            //         $result=FrontUsers::where('id',$request->id)->update(['light_bill'=>$certphotoName.".".$applpic_ext]);
            //     }
                
            //     $shopphotoName=$request->id."_shop_act_image";
            //     if ($request->hasFile('shop_act_image'))
            //     {     
            //         $applpic_ext = $request->file('shop_act_image')->getClientOriginalExtension();
            //         $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('shop_act_image'))); 
            //         $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
            //         $path2 = $imagedataPath.$shopphotoName.".".$applpic_ext;
            //         file_put_contents($path2, $applicantAttachmentOne);    
            //         $result=FrontUsers::where('id',$request->id)->update(['shop_act_image'=>$shopphotoName.".".$applpic_ext]);
            //     }
                
            //     $productbillphotoName=$request->id."_product_purchase_bill";
            //     if ($request->hasFile('product_purchase_bill'))
            //     {     
            //         $applpic_ext = $request->file('product_purchase_bill')->getClientOriginalExtension();
            //         $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('product_purchase_bill'))); 
            //         $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
            //         $path2 = $imagedataPath.$productbillphotoName.".".$applpic_ext;
            //         file_put_contents($path2, $applicantAttachmentOne);    
            //         $result=FrontUsers::where('id',$request->id)->update(['product_purchase_bill'=>$productbillphotoName.".".$applpic_ext]);
            //     }
                
                
        $data=[
                'fname'=> ucwords($requestdata->fname),
                'mname'=> ucwords($requestdata->mname),
                'lname'=> ucwords($requestdata->lname),
                //'password'=> $requestdata->password,
                'email'=> $requestdata->email,
                'phone'=> $requestdata->phone,
                'alternate_mobile'=> $requestdata->alternate_mobile,
                'state'=> $requestdata->state,
                'district'=> $requestdata->district,
                'taluka'=> $requestdata->taluka,
                'city'=> $requestdata->city,
                'business_address'=> $requestdata->business_address,
                'business_state'=> $requestdata->business_state,
                'business_district'=> $requestdata->business_district,
                'business_tuluka'=> $requestdata->business_tuluka,
                'business_village'=> $requestdata->business_village,
                'where_open_shop'=> $requestdata->where_open_shop,
                'used_sct'=> $requestdata->used_sct,
                'why_want_take_distributorship'=> $requestdata->why_want_take_distributorship,
                'distributorship_exerience'=> $requestdata->distributorship_exerience,
                'experience_farm_garder'=> $requestdata->experience_farm_garder,
                'goal'=> $requestdata->goal
            ];
           
            $result = FrontUsers::where('id',$requestdata->id)->update($data);

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
	
	
	   public function web_frontdistributorinfo(Request $request)
    {

        // $UsersInfo = FrontUsers::where('id',$request->id)->get();
        // foreach($UsersInfo as $key=>$value)
        //     {
        //         $value->aadhar_card_image_front=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->aadhar_card_image_front;
        //         $value->aadhar_card_image_back=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->aadhar_card_image_back;
        //         $value->pan_card=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->pan_card;
        //         $value->light_bill=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->light_bill;
        //         $value->shop_act_image=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->shop_act_image;
        //         $value->product_purchase_bill=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->product_purchase_bill;
        //     }
        // if ($UsersInfo)
        // {
        //      return response()->json([
        //         "data" => $UsersInfo,
        //         "result" => true,
        //         "message" => 'Distributor info get successfully',
               
        //     ]);
        // }
        // else
        // {
        //      return response()->json([
        //         "data" => '',
        //         "result" => false,
        //         "message" => 'Distributor not found '
        //     ]);
            
        // }
        
        try
        {
            $resultNew = FrontUsers::where('id',$request->id)->update([
                'list_data_read'=>'y',
                'list_data_status'=>'1',
            ]);
            $result = FrontUsers::where('id',$request->id)->first();
            // dd($result);
            $result->aadhar_card_image_front = FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result['aadhar_card_image_front'];
            $result->aadhar_card_image_back = FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result['aadhar_card_image_back'];
            $result->light_bill = FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result['light_bill'];
            $result->pan_card = FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result['pan_card'];
            $result->product_purchase_bill = FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result['product_purchase_bill'];
            $result->shop_act_image = FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result['shop_act_image'];

          

            // $value->aadhar_card_image_front=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result[0]['aadhar_card_image_front'];
            // foreach($result as $key=>$value)
            // {
            //                 $value->aadhar_card_image_front=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value[0]['aadhar_card_image_front'];
        //         $value->aadhar_card_image_back=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->aadhar_card_image_back;
        //         $value->pan_card=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->pan_card;
        //         $value->light_bill=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->light_bill;
        //         $value->shop_act_image=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->shop_act_image;
        //         $value->product_purchase_bill=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$value->product_purchase_bill;
            //}
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
    
    
    
    public function approvedistributor(Request $request)
    {
        try
        {
            $result = FrontUsers::where('id', $request->id)->get();
            //dd($result[0]['fname']);
            $user = new User();
            $user->name = $result[0]['fname']." ".$result[0]['mname']." ".$result[0]['lname']." ";
            $user->email = $result[0]['email'];
            $user->password = app('hash')->make($result[0]['password']);
            $user->visible_password =$result[0]['password'];
            $user->user_type ='fsc';
            $user->is_approved =  'no';
            $user->save();
            $user->id;
            
            $users = new UsersInfo();
            $users->user_id = $user->id;
            $users->fname = $result[0]['fname'];
            $users->mname = $result[0]['mname'];
            $users->lname = $result[0]['lname'];
            $users->email = $result[0]['email'];
            $users->phone = $result[0]['phone'];
            $users->state = $result[0]['state'];
            $users->district = $result[0]['district'];
            $users->taluka = $result[0]['taluka'];
            $users->city = $result[0]['city'];
            $users->address = $result[0]['address'];
            $users->occupation = $result[0]['occupation'];
            $users->education = $result[0]['education'];
            $users->exp_in_agricultural = $result[0]['exp_in_agricultural'];
            $users->other_distributorship = $result[0]['other_distributorship'];
            $users->reference_from = $result[0]['reference_from'];
            $users->shop_location = $result[0]['shop_location'];
            $users->geolocation = $result[0]['geolocation'];
            $users->user_type = 'fsc';
            $users->is_deleted = 'no'; // 0 Means Active, 1 Means Delete
            $users->active = 'yes'; // 0 Means Active, 1 Means Inactive
            $users->added_by =  'superadmin'; // 0- from Superadmin 1- Distributor
            $users->save();
            
            
            
            $aadhar_card_image_front=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result[0]['aadhar_card_image_front'];
            $aadhar_card_image_back=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result[0]['aadhar_card_image_back'];
            $pan_card=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result[0]['pan_card'];
            $light_bill=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result[0]['light_bill'];
            $shop_act_image=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result[0]['shop_act_image'];
            $product_purchase_bill=FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW.$result[0]['product_purchase_bill'];
            
            
            
            
                    $imagedataPath=DISTRIBUTOR_OWN_DOCUMENTS;
                    if ( !is_dir( $imagedataPath) ) 
                    {
                        mkdir( $imagedataPath );       
                    }
                    $idLastInserted=$user->id;
                    
                    $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$idLastInserted."_aadhar_card_image_front";
                    $inputfilenametoupload=$aadhar_card_image_front;
                    if (!empty($request->hasFile($inputfilenametoupload)))
                    {     
                        $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                        $users=UsersInfo::where('user_id',$idLastInserted)->update(['aadhar_card_image_front'=>$filename]);
                    }
                    
                    $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$idLastInserted."_aadhar_card_image_back";
                    $inputfilenametoupload=$aadhar_card_image_back;
                    if (!empty($request->hasFile($inputfilenametoupload)))
                    {     
                        $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                        $users=UsersInfo::where('user_id',$idLastInserted)->update(['aadhar_card_image_back'=>$filename]);
                    }
                    
                    $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$idLastInserted."_pan_card";
                    $inputfilenametoupload=$pan_card;
                    if (!empty($request->hasFile($inputfilenametoupload)))
                    {     
                        $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                        $users=UsersInfo::where('user_id',$idLastInserted)->update(['pan_card'=>$filename]);
                    }
                    
                    
                    $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$idLastInserted."_light_bill";
                    $inputfilenametoupload=$light_bill;
                    if (!empty($request->hasFile($inputfilenametoupload)))
                    {     
                        $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                        $users=UsersInfo::where('user_id',$idLastInserted)->update(['light_bill'=>$filename]);
                    }
                    
                    
                    $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$idLastInserted."_shop_act_image";
                    $inputfilenametoupload=$shop_act_image;
                    if (!empty($request->hasFile($inputfilenametoupload)))
                    {     
                        $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                        $users = UsersInfo::where('user_id',$idLastInserted)->update(['shop_act_image'=>$filename]);
                    }
                    
                    $photoName= str_replace(array('-', ':',' '), '',date('d-m-Y h:i:s')).$idLastInserted."_product_purchase_bill";
                    $inputfilenametoupload=$product_purchase_bill;
                    if (!empty($request->hasFile($inputfilenametoupload)))
                    {     
                        $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
                        $users=UsersInfo::where('user_id',$idLastInserted)->update(['product_purchase_bill'=>$filename]);
                    }
        
                
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
        
        
        // if (count($result) > 0)
        // {
        //     $response = array();
        //     $response['data'] = $result;
        //     $response['code'] = 200;
        //     $response['message'] = 'Distributor List Get Successfully';
        //     $response['result'] = true;
        //     return response()->json($response);
        // }
        // else
        // {
        //     $response = array();
        //     $response['code'] = 400;
        //     $response['message'] = 'Distributor List Not Found';
        //     $response['result'] = false;
        //     return response()->json($response);
        // }

    }
    
    
    
    // FSC List by fsc
    public function fsc_list_by_fsc(Request $request)
    {
        try
        {
             $fsclist_record= UsersInfo::where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','fsc')
                    ->get();
                    
            
            if($fsclist_record)
            
            {
                 return response()->json([
                    "data" => $fsclist_record,
                    "result" => true,
                    "message" => 'FSC Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'FSC Record Not Found'
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
    
    
    // BSC List by bsc
    public function bsc_list_by_bsc(Request $request)
    {
        try
        {
             $bsclist_record= UsersInfo::where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','bsc')
                    ->get();
                    
            
            if($bsclist_record)
            
            {
                 return response()->json([
                    "data" => $bsclist_record,
                    "result" => true,
                    "message" => 'BSC Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'BSC Record Not Found'
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
    
    
    
    // DSC List by dsc
    public function dsc_list_by_dsc(Request $request)
    {
        try
        {
             $dsclist_record= UsersInfo::where('is_deleted','no')
                    ->where('active','yes')
                    ->where('user_type','dsc')
                    ->get();
            
            
            if($dsclist_record)
            
            {
                 return response()->json([
                    "data" => $dsclist_record,
                    "result" => true,
                    "message" => 'DSC Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'DSC Record Not Found'
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
    
    // Address Update
    public function address_update(Request $request)
    {
        $data=[
                'address'=>$request->address,
                'mobile_one'=>$request->mobile_one,
                'mobile_two' => $request->mobile_two,
                'email_office' => $request->email_office,
                'email_sales' => $request->email_sales,
                'email_careers' => $request->email_careers,
                'website_link' => $request->website_link,
                'facebook_link' => $request->facebook_link,
                'instagram_link' => $request->instagram_link,
                'twitter_link' => $request->twitter_link,
                'whatsapp_link' => $request->whatsapp_link,
                'youtube_link' => $request->youtube_link,
            ];
        $address = Address::where('id',$request->id)->update($data);
      
        if ($address)
        {
             return response()->json([
                "data" => $address,
                "result" => true,
                "message" => 'Address Updated Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Address Not Updated'
            ]);
            
        }
    }
    
    
    
    
    public function address_get(Request $request)
    {
        
        try
        {
            $result = Address::where('id',$request->id)->get();
            
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
    
    
    // Crops Add
    public function crops_add(Request $request)
    {
        try 
        {
            $crops = new Crops();
            $crops->title = $request->title;
            
            $crops->save();
            
            if ($crops)
            {
                 return response()->json([
                    "data" => $crops,
                    "result" => true,
                    "message" => 'Crops Added Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'Crops Not Added'
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
    
    
    public function crops_list(Request $request)
    {
        try
        {
            $result = Crops::where('is_deleted','no')->orderBy('id', 'DESC')->get();
            
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
    
    public function crops_get(Request $request)
    {
        
        try
        {
            $result = Crops::where('id',$request->id)->where('is_deleted','no')->get();
            
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
    
    
    public function crops_update(Request $request)
    {
        
        try
        {
            $data=['title'=> $request->title];
            
            $crops = Crops::where('id',$request->id)->update($data);

            if ($crops)
            {
                 return response()->json([
                    "data" => $crops,
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
    
    
    public function cropsdelete(Request $request)
    {
        
        try
        {
            $data=['is_deleted'=>'yes'];
            
            $crops = Crops::where('id',$request->id)->update($data);

            if ($crops)
            {
                 return response()->json([
                    "data" => $crops,
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
    
    // Principles Update
    
    public function principles_update(Request $request)
    {
        $data=[
                'first_method_heading'=>$request->first_method_heading,
                'first_method'=>$request->first_method,
                'second_rule_heading'=>$request->second_rule_heading,
                'second_rule'=>$request->second_rule,
                'third_meditation_heading' => $request->third_meditation_heading,
                'third_meditation' => $request->third_meditation,
            ];
        $princ = Principles::where('id',$request->id)->update($data);
      
        if ($princ)
        {
             return response()->json([
                "data" => $princ,
                "result" => true,
                "message" => 'Updated Successfully'
            ]);
        }
        else
        {
             return response()->json([
                "data" => '',
                "result" => false,
                "message" => 'Not Updated'
            ]);
            
        }
    }
    
    
    
    public function principles_list(Request $request)
    {
        try
        {
            $result =Principles::get();
            
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
    
    
    
    
    public function principles_get(Request $request)
    {
        
        try
        {
            $result = Principles::where('id',$request->id)->get();
            
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


    public function sct_structure_list(Request $request)
    {
        try
        {  
            $all_dist_dist = UsersInfoForStructures::where('user_type','dsc')->get();
            if ($all_dist_dist)
            {
                 return response()->json([
                    "data" => $all_dist_dist,
                    "result" => true,
                    "message" => 'Information get Successfully'
                ]);
            }

           
            $all_dist_bsc = UsersInfoForStructures::where('user_type','bsc')->get();
            if ($all_dist_bsc)
            {
                 return response()->json([
                    "data" => $all_dist_bsc,
                    "result" => true,
                    "message" => 'Information get Successfully'
                ]);
            }
            $all_dist_fsc = UsersInfoForStructures::where('user_type','fsc')->get();
            if ($all_dist_fsc)
            {
                 return response()->json([
                    "data" => $all_dist_fsc,
                    "result" => true,
                    "message" => 'Information get Successfully'
                ]);
            } else
            {
                 return response()->json([
                    "data" => $all_dist_fsc,
                    "result" => false,
                    "message" => 'Information not found'
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
    

    //SCT Structure

    public function fsc_list_structure(Request $request)
    {
        try
        {
                $dsclist_record= UsersInfoForStructures::leftJoin('usersinfo', function($join) {
                    $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
                })

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
                    })
                    ->where('users_info_for_structures.added_by',$request->added_by)
                    // ->where('usersinfo.is_deleted','no')
                    // ->where('usersinfo.active','yes')
                    ->where('users_info_for_structures.user_type','fsc')
                    ->select(
                        'usersinfo.*',
                            'stateNew.name as state',
                            'districtNew.name as district',
                            'talukaNew.name as taluka',
                            'cityNew.name as city'
                        )
                    ->get();
            
        
            if($dsclist_record)
            
            {
                    return response()->json([
                    "data" => $dsclist_record,
                    "result" => true,
                    "message" => 'DSC Record Get Successfully'
                ]);
            }
            else
            {
                    return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'DSC Record Not Found'
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
    
    
    // BSC List
    public function bsc_list_structure(Request $request)
    {
        try
        {
                $dsclist_record= UsersInfoForStructures::leftJoin('usersinfo', function($join) {
                    $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
                })

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
                    })
                    ->where('users_info_for_structures.added_by',$request->added_by)
                    // ->where('usersinfo.is_deleted','no')
                    // ->where('usersinfo.active','yes')
                    ->where('users_info_for_structures.user_type','bsc')
                    ->select(
                        'usersinfo.*',
                            'stateNew.name as state',
                            'districtNew.name as district',
                            'talukaNew.name as taluka',
                            'cityNew.name as city'
                        )
                    ->get();
            
        
            if($dsclist_record)
            
            {
                    return response()->json([
                    "data" => $dsclist_record,
                    "result" => true,
                    "message" => 'DSC Record Get Successfully'
                ]);
            }
            else
            {
                    return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'DSC Record Not Found'
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

    //New BSC List For Mannual
    public function bsc_list_structure_for_mannual_change(Request $request)
    {
        try
        {
                $dsclist_record= UsersInfoForStructures::leftJoin('usersinfo', function($join) {
                    $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
                })

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
                    })
                    // ->where('usersinfo.is_deleted','no')
                    // ->where('usersinfo.active','yes')
                    ->where('users_info_for_structures.user_type','bsc')
                    ->select(
                        'usersinfo.*',
                            'stateNew.name as state',
                            'districtNew.name as district',
                            'talukaNew.name as taluka',
                            'cityNew.name as city'
                        )
                    ->get();
            
        
            if($dsclist_record)
            
            {
                    return response()->json([
                    "data" => $dsclist_record,
                    "result" => true,
                    "message" => 'DSC Record Get Successfully'
                ]);
            }
            else
            {
                    return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'DSC Record Not Found'
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
    
     
    
    // DSC List
    public function dsc_list_structure(Request $request)
    {
        try
        {
             $dsclist_record= UsersInfoForStructures::
                leftJoin('usersinfo', function($join) {
                    $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
                })

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
                  })
                    ->where('users_info_for_structures.user_type','dsc')
                    ->select(
                        'usersinfo.*',
                         'stateNew.name as state',
                         'districtNew.name as district',
                         'talukaNew.name as taluka',
                         'cityNew.name as city'
                        )
                    ->get();
            
        
            if($dsclist_record)
            
            {
                 return response()->json([
                    "data" => $dsclist_record,
                    "result" => true,
                    "message" => 'DSC Record Get Successfully'
                ]);
            }
            else
            {
                 return response()->json([
                    "data" => '',
                    "result" => false,
                    "message" => 'DSC Record Not Found'
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


    public function getSctStructure()
    {

        try
        {
             $dsclist_record = UsersInfoForStructures::
                leftJoin('usersinfo', function($join) {
                    $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
                })

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
                  })
                    ->where('users_info_for_structures.user_type','dsc')
                    ->select(
                        'users_info_for_structures.added_by',
                        'usersinfo.*',
                         'stateNew.name as state',
                         'districtNew.name as district',
                         'talukaNew.name as taluka',
                         'cityNew.name as city'
                        )
                    ->get();
            
        
       
        } catch(Exception $e) {
            return response()->json([
                    "data" => '',
                    "result" => false,
                    "error" => true,
                    "message" =>$e->getMessage()." ".$e->getCode()
                ]);
           
        }

        $selectedFormNo='';
        $excelData =  $dsclist_record;
        $filename='All Applicant List';
        $headerApplicationReportToExcelForAll =['Name'];
        $dataExcel= [];
        // dd($dsclist_record);
        foreach ($dsclist_record as $key => $value) {

            if($value->added_by == 'superadmin') {
                $added_by_name = 'Superadmin' ;   
            } else {

                $distributordetails=$this->commonController->getUserNameById($value->added_by);
                $added_by_name = ucwords($distributordetails->fname." ".$distributordetails->mname." ".$distributordetails->lname);
            }
            
            
            array_push($dataExcel, array(
                'Level' =>'DSC',
                'Added By' => $added_by_name,
                'Name' => ucwords($value->fname." ".$value->mname." ".$value->lname),
                'Contact No.' => $value->phone,
                'State' => $value->state,
                'District' => $value->district,
                'Taluka' => $value->taluka,
            ));


            $bsclist_record = UsersInfoForStructures::leftJoin('usersinfo', function($join) {
                    $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
                })

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
                })
                ->where('users_info_for_structures.added_by',$value->user_id)
                ->where('users_info_for_structures.user_type','bsc')
                ->select(
                    'usersinfo.*',
                        'stateNew.name as state',
                        'districtNew.name as district',
                        'talukaNew.name as taluka',
                        'cityNew.name as city'
                    )
                ->get();

                if($bsclist_record) {
                    foreach ($bsclist_record as $keyNew => $valueBsc) {
                        array_push($dataExcel, array(
                            'Level' =>'BSC',
                            'Added By' => ucwords($value->fname." ".$value->mname." ".$value->lname),
                            'Name' => ucwords($valueBsc->fname." ".$valueBsc->mname." ".$valueBsc->lname),
                            'Contact No.' => $valueBsc->phone,
                            'State' => $valueBsc->state,
                            'District' => $valueBsc->district,
                            'Taluka' => $valueBsc->taluka,
                        ));



                        $fsclist_record= UsersInfoForStructures::leftJoin('usersinfo', function($join) {
                            $join->on('users_info_for_structures.user_id', '=', 'usersinfo.user_id');
                        })
        
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
                            })
                            ->where('users_info_for_structures.added_by',$valueBsc->user_id)
                            // ->where('usersinfo.is_deleted','no')
                            // ->where('usersinfo.active','yes')
                            ->where('users_info_for_structures.user_type','fsc')
                            ->select(
                                'usersinfo.*',
                                    'stateNew.name as state',
                                    'districtNew.name as district',
                                    'talukaNew.name as taluka',
                                    'cityNew.name as city'
                                )
                            ->get();


                            if($fsclist_record) {
                                foreach ($fsclist_record as $keyNew => $valueFsc) {
                                    array_push($dataExcel, array(
                                        'Level' =>'FSC',
                                        'Added By' => ucwords($valueBsc->fname." ".$valueBsc->mname." ".$valueBsc->lname),
                                        'Name' => ucwords($valueFsc->fname." ".$valueFsc->mname." ".$valueFsc->lname),
                                        'Contact No.' => $valueFsc->phone,
                                        'State' => $valueFsc->state,
                                        'District' => $valueFsc->district,
                                        'Taluka' => $valueFsc->taluka,
                                    ));

                                }
                            }
                    }

                }


                
            

        }

  
        $list = collect(
          $dataExcel

        );
        //env('APP_URL').'/uploads/sctstructure/'.
        (new FastExcel($list))->export('sctstructure.xlsx');
        // header('Access-Control-Allow-Origin: *'); // Allow requests from any origin
        // header('Access-Control-Allow-Methods: GET, OPTIONS'); // Allow GET requests and preflight OPTIONS requests


        // header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        // header('Content-Disposition: attachment; filename="sctstructure.xlsx"');
        // header('Content-Length: ' . filesize('sctstructure.xlsx'));
        // readfile('sctstructure.xlsx');


      
        $base1 = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
        $base = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

        if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
        $base1 .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
        $base .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
        }



        return response()->json([
            "data" => $base1.'sctstructure.xlsx',
            "result" => true,
            "message" => 'SCT Structure data Get Successfully'
        ]);
       
    }


   
}
