<?php

namespace App\Http\Controllers;
use Exception;
//use JWTAuth;
use App\Task;
use Illuminate\Http\Request;
use App\Model\UsersInfo;
use App\User;
use App\Model\FarmerMeeting;
use App\Model\DistributorMeeting;
use App\Model\TargetVideos;
use App\Model\TargetVideosToDistributor;
use App\Model\FarmerVistByDistributor;
use App\Model\WebCompanyProfile;
use App\Model\WebAboutUs;
use App\Model\WebCoverPhoto;
use App\Model\WebClientLogo;
use App\Model\WebGallaryPhoto;
use App\Model\WebVisionMission;
use App\Model\WebVideos;
use App\Model\WebBlog;
use App\Model\FrontUsers;
use App\Model\WebTestiminials;
use App\Model\WebInternship;
use App\Model\FrontProduct;
use App\Model\WebJobPosting;
use App\Model\WebAudio;
use App\Model\Product;
use App\Model\WebAgency;
use App\Model\ProductDetails;
use App\Model\Notification;
use App\Model\SCTResult;
use App\Model\Subscriber;
use App\Model\Dist_Promotion_Demotion;
use App\Model\BlogReply;
use App\Model\Counter;
use App\Model\Career;
use App\Model\Marquee;
use File;
use Carbon\Carbon;
use App\Model\SaleSummary;
use App\Model\Enquiry;
use App\Model\ProductReview;
use App\Model\OrderSummary;
use App\Model\OrderDetail;
use App\Model\Downloads;
use App\Model\Principles;
use DB;

use App\Http\Controllers\CommonController As CommonController;

class FrontController extends Controller
{
    protected $user;

    public function __construct()
    {
        $this->commonController=new CommonController();
        
        //$this->user = JWTAuth::parseToken()->authenticate();
       // $this->commonController->validateToken($this->user);
    }
    
    
    
    public function fronttestimonialslist()
    {
        try
        {
            $result = WebTestiminials::where('status','0')->orderBy('id', 'DESC')->take(10)->get();
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
    
    
    public function frontaboutuslist(Request $request)
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
    
    
    public function frontvisionmissionlist(Request $request)
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
    
    
    
    
    
    
    
    public function frontvisionlist(Request $request)
    {
        try
        {
            $result = WebVisionMission::where('status','0')->where('record_for','Vision')->orderBy('id', 'DESC')->get();
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
    
    public function frontmissionlist(Request $request)
    {
        try
        {
            $result = WebVisionMission::where('status','0')->where('record_for','Mission')->orderBy('id', 'DESC')->get();
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
    
    
    public function frontphotogallerylist(Request $request)
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
    
    
    
    public function frontphotogallerylistlimit(Request $request)
    {
        try
        {
            $result = WebGallaryPhoto::where('status','0')->orderBy('id', 'DESC')->LIMIT(20)->get();
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
    
    
    public function frontvideogallerylist(Request $request)
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
    
    
    
    
    
    
    public function webvideo_educational(Request $request)
    {
        try 
        {
            $targetvideo = WebVideos::where('status',0)->where('category','Educational')->orderBy('id', 'DESC')->get();
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
    
    public function webvideo_educationallimit(Request $request)
    {
        try 
        {
            $targetvideo = WebVideos::where('status',0)->where('category','Educational')->orderBy('id', 'DESC')->LIMIT(8)->get();
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
    
    
    public function webvideo_farmer(Request $request)
    {
        try 
        {
            $targetvideofar = WebVideos::where('status',0)->where('category','Farmer')->orderBy('id', 'DESC')->get();
            if(!$targetvideofar) {
                throw new Exception(api_error(1006), 1006);
            }
           
          
            if ($targetvideofar)
            {
                 return response()->json([
                    "data" => $targetvideofar,
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
    
    
    
    public function webvideo_farmerlimit(Request $request)
    {
        try 
        {
            $targetvideofar = WebVideos::where('status',0)->where('category','Farmer')->orderBy('id', 'DESC')->LIMIT(8)->get();
            if(!$targetvideofar) {
                throw new Exception(api_error(1006), 1006);
            }
           
          
            if ($targetvideofar)
            {
                 return response()->json([
                    "data" => $targetvideofar,
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
    
    
    
    
    public function webmarquee(Request $request)
    {
        try 
        {
            $marquee = Marquee::where('is_deleted','no')->where('is_active','yes')->orderBy('id', 'DESC')->pluck("marquee");
            if(!$marquee) {
                throw new Exception(api_error(1006), 1006);
            }
           
          
            if ($marquee)
            {
                 return response()->json([
                    "data" => $marquee,
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
    
    public function frontblogarticlelist(Request $request)
    {
        try
        {
            if(!empty($request->id)){
                $result = WebBlog::where(['id'=>$request->id,'status'=>'0','articleorschedule' =>'article'])->orderBy('id', 'DESC')->get();

            }else{
                $result = WebBlog::where(['status'=>'0','articleorschedule' =>'article'])->orderBy('id', 'DESC')->get();

            }
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
    
    
    public function frontdownloadlist(Request $request)
    {
        try 
        {
            $result = Downloads::where('status',0)->orderBy('id', 'DESC')->take(4)->get();
            
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
    
    
    
    
    public function frontproductlist(Request $request)
    {
        try
        {
                if(!empty($request->prod)){
                    $result = FrontProduct::join('tbl_product_details','tbl_product_details.id','=','front_product.product_id')
                    ->join('tbl_product','tbl_product.id','=','tbl_product_details.product_id')
                    ->where('front_product.is_deleted','no')
                    ->where('tbl_product.is_deleted','no')
                    ->where('tbl_product.id',$request->prod)
                    ->where('tbl_product_details.is_deleted','no')
                    ->select('front_product.*','tbl_product.id','tbl_product.title','tbl_product.photo_one')
                    ->get();
                }else{
                    $result = FrontProduct::join('tbl_product_details','tbl_product_details.id','=','front_product.product_id')
                    ->join('tbl_product','tbl_product.id','=','tbl_product_details.product_id')
                    ->where('front_product.is_deleted','no')
                    ->where('tbl_product.is_deleted','no')
                    ->where('tbl_product_details.is_deleted','no')
                    ->select('front_product.*','tbl_product.id','tbl_product.title','tbl_product.photo_one')
                    ->get();
                }

            foreach($result as $key=>$value)
            {
                $value->photopath=FRONTPRODUCT_CONTENT_VIEW.$value->photo;
                $value->productphotopath=PRODUCT_CONTENT_VIEW.$value->photo_one;
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
    
    public function frontenquiryadd(Request $request)
    {
        try
        {
            $detail = array($request->details);
            $det = implode(", " , $detail);
            $enquiry = new Enquiry();
            $enquiry->name = $request->name;
            $enquiry->email = $request->email;
            $enquiry->mobile = $request->mobile;
            $enquiry->details = $det;
            $enquiry->comment = $request->comment;
            $enquiry->save();
          
            if ($enquiry)
            {
                 return response()->json([
                    "data" => $enquiry,
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
    
    
    public function frontenquiryget(Request $request)
    {
        try
        {
            $result = Enquiry::where('is_deleted','no')->orderBy('id', 'DESC')->get();
            
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
    
    
    
    
    
    public function frontproductreviewadd(Request $request)
    {
        try
        {
            $prod_review = new ProductReview();
            $prod_review->product_id = $request->product_id;
            $prod_review->name = $request->name;
            $prod_review->email = $request->email;
            $prod_review->comment = $request->comment;
            $prod_review->save();
          
            if ($prod_review)
            {
                 return response()->json([
                    "data" => $prod_review,
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
    
    
    
    public function frontproductreviewlist(request $request)
    {
        try
        {
            $result = ProductReview::where('product_id',$request->pruduct_id)->where('is_deleted','no')->orderBy('id', 'DESC')->take(1)->get();
            
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
    
    
    public function fronttestimonialadd(Request $request)
    {
        try
        {
            $user = new WebTestiminials();
            $user->title = $request->name;
            $user->mobile = $request->mobile;
            $user->content = $request->feedback;
            $user->language = $request->language;
            $user->video = $request->video;
            $user->created_by ='0'; // 0 means Admin, but there is no id for Farmer, so 0 kept as it is
            $user->save();
          
            if ($request->photo_one)
            {
        
                $imagedataPath=TESTIMONIALS_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName=$user->id."_Testimonials";
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
    
    
    public function frontblogreplyadd(Request $request)
    {
        try
        {
            $blog_reply = new BlogReply();
            $blog_reply->blog_id = $request->blog_id;
            $blog_reply->name = $request->name;
            $blog_reply->email = $request->email;
            $blog_reply->comment = $request->comment;
            $blog_reply->save();
            
            if ($blog_reply)
            {
                 return response()->json([
                    "data" => $blog_reply,
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
    
    
    public function frontsliderlist(Request $request)
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
    
    
    public function frontclientlogoslist(Request $request)
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
    
    
    
    
    
    
    
    public function frontinternshipadd(Request $request)
    {
        try
        {
            $internship = new WebInternship();
            $internship->name = $request->name;
            $internship->email = $request->email;
            $internship->mobile = $request->mobile;
            $internship->qualification = $request->qualification;
            $internship->address = $request->address;
            $internship->save();
          
            if ($request->resume)
            {
        
                $imagedataPath=INTERNSHIP_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName=$internship->id."_Resume";
                if (!empty($request->resume))
                {     
                    $applpic_ext = $request->file('resume')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('resume'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                    $internship=WebInternship::where('id',$internship->id)->update(['resume'=>$photoName.".".$applpic_ext]);
                   
                }
                
            }
            
            if ($internship)
            {
                 return response()->json([
                    "data" => $internship,
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
    
    
    
    
    
    public function frontjobpostingadd(Request $request)
    {
        try
        {
            $posting = new WebJobPosting();
            $posting->name = $request->name;
            $posting->email = $request->email;
            $posting->mobile = $request->mobile;
            $posting->qualification = $request->qualification;
            $posting->experience_from = $request->experience_from;
            $posting->experience_to = $request->experience_to;
            $posting->address = $request->address;
            $posting->save();
          
            if ($request->resume)
            {
                $imagedataPath=JOBPOSTING_CONTENT_UPLOAD;
                if ( !is_dir( $imagedataPath) ) 
                {
                    mkdir( $imagedataPath );       
                }
                
                $photoName=$posting->id."_Resume";
                if (!empty($request->resume))
                {     
                    $applpic_ext = $request->file('resume')->getClientOriginalExtension();
                    $fileUploadAttachmentOne = base64_encode(file_get_contents($request->file('resume'))); 
                    $applicantAttachmentOne = base64_decode($fileUploadAttachmentOne);
                    $path2 = $imagedataPath.$photoName.".".$applpic_ext;
                    file_put_contents($path2, $applicantAttachmentOne); 
                    $internship=WebJobPosting::where('id',$posting->id)->update(['resume'=>$photoName.".".$applpic_ext]);
                   
                }
                
            }
            
            if ($posting)
            {
                 return response()->json([
                    "data" => $posting,
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
    
    
    
    
    
    
    public function frontdistributorregistration(Request $request)
    {
        try
        {
            $users = new FrontUsers();
            $users->fname = $request->fname;
            $users->mname = $request->mname;
            $users->lname = $request->lname;
            $users->password = $request->password;
            $users->email = $request->email;
            $users->phone = $request->phone;
            $users->alternate_mobile = $request->alternate_mobile;
            $users->state = $request->state;
            $users->district = $request->district;
            $users->taluka = $request->taluka;
            $users->city = $request->city;
            $users->business_address = $request->business_address;
            $users->business_state = $request->business_state;
            $users->business_district = $request->business_district;
            $users->business_tuluka = $request->business_tuluka;
            $users->business_village = $request->business_village;
            $users->where_open_shop = $request->where_open_shop;
            $users->used_sct = $request->used_sct;
            $users->why_want_take_distributorship = $request->why_want_take_distributorship;
            $users->distributorship_exerience = $request->distributorship_exerience;
            $users->experience_farm_garder = $request->experience_farm_garder;
            $users->goal = $request->goal;
            $users->user_type = 'fsc';
            $users->is_deleted = 'no'; // 0 Means Active, 1 Means Delete
            $users->active = 'no'; // 0 Means Active, 1 Means Inactive
            $users->added_by =  ($request->created_by) ? $request->created_by: 'superadmin'; // 0- from Superadmin 1- Distributor
            $users->save();
            
            
            $idLastInserted=$users->id;
        $imagedataPath=FRONT_DISTRIBUTOR_OWN_DOCUMENTS;
        $photoName=$idLastInserted."_aadhar_card_image_front";
        $inputfilenametoupload='aadhar_card_image_front';
        
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=FrontUsers::where('id',$idLastInserted)->update(['aadhar_card_image_front'=>$filename]);
        }
        
        $photoName=$idLastInserted."_aadhar_card_image_back";
        $inputfilenametoupload='aadhar_card_image_back';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=FrontUsers::where('id',$idLastInserted)->update(['aadhar_card_image_back'=>$filename]);
        }
        
        $photoName=$idLastInserted."_pan_card";
        $inputfilenametoupload='pan_card';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=FrontUsers::where('id',$idLastInserted)->update(['pan_card'=>$filename]);
        }
        
        
        $photoName=$idLastInserted."_light_bill";
        $inputfilenametoupload='light_bill';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=FrontUsers::where('id',$idLastInserted)->update(['light_bill'=>$filename]);
        }
        
        
        $photoName=$idLastInserted."_shop_act_image";
        $inputfilenametoupload='shop_act_image';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
           
            $users = FrontUsers::where('id',$idLastInserted)->update(['shop_act_image'=>$filename]);
           
        }
        
        $photoName=$idLastInserted."_product_purchase_bill";
        $inputfilenametoupload='product_purchase_bill';
        if (!empty($request->hasFile($inputfilenametoupload)))
        {     
            $filename=$this->processUpload($request, $inputfilenametoupload,$imagedataPath,$photoName);
            $users=FrontUsers::where('id',$idLastInserted)->update(['product_purchase_bill'=>$filename]);
           
        }
        
        
        
                
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
    
    
    public function counter_list()
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
    
    
    
    public function front_career_list(Request $request)
    {
        try
        {
            $result = Career::where('is_deleted','no')->orderBy('id', 'ASC')->get();
            foreach($result as $key=>$value)
            {
                $value->photopath=WEB_CAREER_VIEW.$value->image;
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
    
    
    
    public function frontagencylist(Request $request)
    {
        try
        {
            $result = WebAgency::join('usersinfo','usersinfo.user_id','=','tbl_agency_detail.agency_under_distributor_id')
            ->where('tbl_agency_detail.is_deleted','no')->orderBy('tbl_agency_detail.id', 'DESC')->get();
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
    
    
    public function firstmethodlist(request $request)
    {
        try
        {
            $result = Principles::select('first_method','first_method_heading')->get();
            
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
    
    public function secondrulelist(request $request)
    {
        try
        {
            $result = Principles::select('second_rule','second_rule_heading')->get();
            
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
    
    public function thirdmeditationlist(request $request)
    {
        try
        {
            $result = Principles::select('third_meditation','third_meditation_heading')->get();
            
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
    
}
