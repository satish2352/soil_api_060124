<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/
$router->get('allclear', function() {
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('view:clear');
    $exitCode = Artisan::call('config:cache');
    return "All Cache Clear";
});



$router->group(['prefix' => 'api'], function () use ($router) {


    $router->post("login", "AuthController@login");
    $router->post("login_mobileapp", "AuthController@login_mobileapp");
    $router->post("register", "AuthController@register");

    $router->post('statelist', 'CommonController@statelist');
    $router->post('districtlist', 'CommonController@districtlist');
    $router->post('talukalist', 'CommonController@talukalist');
    $router->post('villagelist', 'CommonController@villagelist');
    $router->post('checkemailexist', 'CommonController@checkemailexist');


    // Front Website API
        $router->get('firstmethodlist', 'FrontController@firstmethodlist');
        $router->get('secondrulelist', 'FrontController@secondrulelist');
        $router->get('thirdmeditationlist', 'FrontController@thirdmeditationlist');
        
        $router->get('fronttestimonialslist', 'FrontController@fronttestimonialslist');
        $router->post('fronttestimonialadd', 'FrontController@fronttestimonialadd');
        $router->get('frontaboutuslist', 'FrontController@frontaboutuslist');
        $router->get('frontvisionmissionlist', 'FrontController@frontvisionmissionlist');
        $router->get('frontphotogallerylist', 'FrontController@frontphotogallerylist');
        $router->get('frontphotogallerylistlimit', 'FrontController@frontphotogallerylistlimit');
        $router->get('frontvisionlist', 'FrontController@frontvisionlist');
        $router->get('frontmissionlist', 'FrontController@frontmissionlist');
        $router->get('frontvideogallerylist', 'FrontController@frontvideogallerylist');  
        //  $router->get('career_list_web', 'FrontController@career_list_web');
        
        $router->get('webvideo_educational', 'FrontController@webvideo_educational');
        $router->get('webvideo_educationallimit', 'FrontController@webvideo_educationallimit');
        $router->get('webvideo_farmer', 'FrontController@webvideo_farmer');
        $router->get('webvideo_farmerlimit', 'FrontController@webvideo_farmerlimit');
        $router->get('webmarquee', 'FrontController@webmarquee');
        
        $router->get('frontblogarticlelist', 'FrontController@frontblogarticlelist');
        $router->get('frontdownloadlist', 'FrontController@frontdownloadlist');
        $router->get('frontproductlist', 'FrontController@frontproductlist');
        $router->get('frontsliderlist', 'FrontController@frontsliderlist');
        $router->get('frontclientlogoslist', 'FrontController@frontclientlogoslist');
        $router->post('frontenquiryadd', 'FrontController@frontenquiryadd');
        // $router->get('frontenquiryget', 'FrontController@frontenquiryget');
        $router->post('frontproductreviewadd', 'FrontController@frontproductreviewadd');
        $router->post('frontproductget', 'FrontController@frontproductget');
        $router->post('frontblogreplyadd', 'FrontController@frontblogreplyadd');
        $router->post('frontinternshipadd', 'FrontController@frontinternshipadd');
        $router->post('frontjobpostingadd', 'FrontController@frontjobpostingadd');
        $router->post('frontdistributorregistration', 'FrontController@frontdistributorregistration');
        $router->get('counter_list', 'FrontController@counter_list');
        $router->get('front_career_list', 'FrontController@front_career_list');
        $router->get('frontagencylist', 'FrontController@frontagencylist');
        
        

        $router->group(['middleware' => 'auth'], function () use ($router) {
            $router->post("logout", "AuthController@logout");
            
            //Farmer API
            $router->post('farmerregistration', 'FarmerController@farmerregistration');
            $router->post('farmerlist', 'FarmerController@farmerlist');
            $router->post('farmerget', 'FarmerController@farmerget');
            $router->post('farmerdelete', 'FarmerController@farmerdelete');
            $router->post('farmerupdate', 'FarmerController@farmerupdate');
            $router->post('farmeractiveinactive', 'FarmerController@farmeractiveinactive');
            $router->post('farmerdetails', 'FarmerController@farmerdetails');
            
            
            //Distributor API
            //$router->post('distributorlogin', 'DistributorController@distributorlogin');
            $router->post('distributorregistration', 'DistributorController@distributorregistration');
            $router->post('distributorregistrationspecific', 'DistributorController@distributorregistrationspecific');
            $router->post('distributorregistration_by_distributor', 'DistributorController@distributorregistration_by_distributor');
            $router->post('distributorregistration_images', 'DistributorController@distributorregistration_images');
            $router->post('distributorregistration_images_update', 'DistributorController@distributorregistration_images_update');
            $router->post('distributorlist', 'DistributorController@distributorlist');
            $router->post('distributorinfo', 'DistributorController@distributorinfo');
            $router->post('distributordelete', 'DistributorController@distributordelete');
            $router->post('distributorupdatenew', 'DistributorController@distributorupdatenew');
            $router->post('distributorupdate', 'DistributorController@distributorupdate');
            $router->post('distributoractiveinactive', 'DistributorController@distributoractiveinactive');
            $router->post('messageview_by_distributor', 'DistributorController@messageview_by_distributor');
            $router->post('complaintview_by_distributor', 'DistributorController@complaintview_by_distributor');
            $router->post('block_distributor', 'DistributorController@block_distributor');
            $router->post('unblock_distributor', 'DistributorController@unblock_distributor');
            $router->post('send_notification', 'DistributorController@send_notofication');
            $router->post('read_notification', 'DistributorController@read_notification');
            $router->post('list_notification', 'DistributorController@list_notification');
            $router->post('distributordetails', 'DistributorController@distributordetails');
            $router->post('distributorchecktoken', 'DistributorController@distributorchecktoken');
            
            $router->post('farmer_registration_distributorapp', 'DistributorController@farmer_registration_distributorapp');
            
            $router->post('farmerunder_distributor', 'DistributorController@farmerunder_distributor');
            $router->post('farmermeetingadd_distributorapp', 'DistributorController@farmermeetingadd_distributorapp');
            $router->post('farmermeetingadd_images_distributorapp', 'DistributorController@farmermeetingadd_images_distributorapp');
            $router->post('farmermeetingadd_images_update_distributorapp', 'DistributorController@farmermeetingadd_images_update_distributorapp');
            $router->post('farmermeetingget_distributorapp', 'DistributorController@farmermeetingget_distributorapp');
            $router->post('farmermeetingupdate_distributorapp', 'DistributorController@farmermeetingupdate_distributorapp');
            $router->post('farmermeetingdelete_distributorapp', 'DistributorController@farmermeetingdelete_distributorapp');
            $router->post('farmermeetinglist_distributorapp', 'DistributorController@farmermeetinglist_distributorapp');
            
            $router->post('distributormeetingadd_distributorapp', 'DistributorController@distributormeetingadd_distributorapp');
            $router->post('distributormeetingadd_images_distributorapp', 'DistributorController@distributormeetingadd_images_distributorapp');
            $router->post('distributormeetingadd_images_update_distributorapp', 'DistributorController@distributormeetingadd_images_update_distributorapp');
            $router->post('distributormeetinglist_distributorapp', 'DistributorController@distributormeetinglist_distributorapp');
            $router->post('distributormeetingperticularget_distributorapp', 'DistributorController@distributormeetingperticularget_distributorapp');
            $router->post('distributormeetingupdate_distributorapp', 'DistributorController@distributormeetingupdate_distributorapp');
            
            //Farmer Visit By Distributor
            $router->post('distributorvisittofarmerlist_distributorapp', 'DistributorController@distributorvisittofarmerlist_distributorapp');
            $router->post('distributorvisittofarmeradd_distributorapp', 'DistributorController@distributorvisittofarmeradd_distributorapp');
            $router->post('distributorvisittofarmeradd_images_distributorapp', 'DistributorController@distributorvisittofarmeradd_images_distributorapp');
            $router->post('distributorvisittofarmeradd_images_update_distributorapp', 'DistributorController@distributorvisittofarmeradd_images_update_distributorapp');
            $router->post('distributorvisittofarmerget_distributorapp', 'DistributorController@distributorvisittofarmerget_distributorapp');
            $router->post('distributorvisittofarmerupdate_distributorapp', 'DistributorController@distributorvisittofarmerupdate_distributorapp');
            $router->post('distributorvisittofarmerdelete_distributorapp', 'DistributorController@distributorvisittofarmerdelete_distributorapp');
            
            
            //Target Video List
            $router->post('distributortargetvideolist_distributorapp', 'DistributorController@distributortargetvideolist_distributorapp');
            $router->post('target_video_watched_by_dist', 'DistributorController@target_video_watched_by_dist');
            
            
            
            //SCT Result
            $router->post('sct_resultadd_distributorapp', 'DistributorController@sct_resultadd_distributorapp');
            $router->post('sct_resultadd_images_distributorapp', 'DistributorController@sct_resultadd_images_distributorapp');
            $router->post('sct_resultadd_images_update_distributorapp', 'DistributorController@sct_resultadd_images_update_distributorapp');
            $router->post('sct_resultlist_distributorapp', 'DistributorController@sct_resultlist_distributorapp');
            
            //Subscriber Target
            $router->post('subscribertargetget_distributorapp', 'DistributorController@subscribertargetget_distributorapp');
            
            //Subscriber
            $router->post('suscriberadd_distributorapp', 'DistributorController@suscriberadd_distributorapp');
            $router->post('suscriberlist_distributorapp', 'DistributorController@suscriberlist_distributorapp');

            $router->post('suscribertarget_distributorapp', 'DistributorController@suscribertarget_distributorapp');
                //New Route For Video View
            $router->post('target_video_viewed_admin', 'DistributorController@target_video_viewed_admin');
            
            
            
            
            //Product List
            $router->post('allproductlist_mobileapp_first', 'DistributorMobileAppController@allproductlist_mobileapp_first');
            $router->post('allproductlist_mobileapp', 'DistributorMobileAppController@allproductlist_mobileapp');
            $router->post('allproductlist_mobileapp_new', 'DistributorMobileAppController@allproductlist_mobileapp_new');
            
            //Order From APP
            $router->post('orderadd_mobileapp', 'DistributorMobileAppController@orderadd_mobileapp');
            $router->post('orderupdate_mobileapp', 'DistributorMobileAppController@orderupdate_mobileapp');
            $router->post('orderget_mobileapp', 'DistributorMobileAppController@orderget_mobileapp');
            $router->post('orderdelete_mobileapp', 'DistributorMobileAppController@orderdelete_mobileapp');
            $router->post('orderlist_mobileapp', 'DistributorMobileAppController@orderlist_mobileapp');
            $router->post('orderview_mobileapp', 'DistributorMobileAppController@orderview_mobileapp');
            $router->post('orderdetail_mobileapp', 'DistributorMobileAppController@orderdetail_mobileapp');
        
            
            // Target Video
            $router->post('target_video_viewed_mobileapp', 'DistributorMobileAppController@target_video_viewed_mobileapp');
            $router->post('target_video_not_viewed_mobileapp', 'DistributorMobileAppController@target_video_not_viewed_mobileapp');
            
            $router->post('mytarget_farmercount_mobileapp', 'DistributorControllerNandu@mytarget_farmercount_mobileapp');
            $router->post('mytarget_youtubevideolinkcount_mobileapp', 'DistributorControllerNandu@mytarget_youtubevideolinkcount_mobileapp');
            $router->post('subscriber_count_mobileapp', 'DistributorControllerNandu@subscriber_count_mobileapp');
            $router->post('subscribers_target_count_mobileapp', 'DistributorControllerNandu@subscribers_target_count_mobileapp');
            $router->post('farmer_meeting_search_mobileapp', 'DistributorControllerNandu@farmer_meeting_search_mobileapp');
            $router->post('distributor_meeting_search_mobileapp', 'DistributorControllerNandu@distributor_meeting_search_mobileapp');
            $router->post('farmer_meeting_title_search_mobileapp', 'DistributorControllerNandu@farmer_meeting_title_search_mobileapp');
            $router->post('distributor_meeting_title_search_mobileapp', 'DistributorControllerNandu@distributor_meeting_title_search_mobileapp');
            $router->post('sct_result_search_by_date_mobileapp', 'DistributorControllerNandu@sct_result_search_by_date_mobileapp');
            $router->post('myvisit_date_filter_mobileapp', 'DistributorControllerNandu@myvisit_date_filter_mobileapp');
            $router->post('myvisit_search_by_visitno', 'DistributorControllerNandu@myvisit_search_by_visitno_mobileapp');
            $router->post('ordersearch_by_orderno', 'DistributorControllerNandu@ordersearch_by_orderno_mobileapp');
            $router->post('salesearch_by_orderno', 'DistributorControllerNandu@salesearch_by_orderno_mobileapp');
            
            
            $router->post('messageadd', 'DistributorControllerNandu@messageadd');
            $router->post('messageedit', 'DistributorControllerNandu@messageedit');
            $router->post('messagedelete', 'DistributorControllerNandu@messagedelete');
            $router->post('messageview', 'DistributorControllerNandu@messageview');
            $router->post('messagesearch', 'DistributorControllerNandu@messagesearch');
            $router->post('messagesearchbydate', 'DistributorControllerNandu@messagesearchbydate');
            
            
            
            $router->post('complaintadd', 'DistributorControllerNandu@complaintadd');
            $router->post('complaintedit', 'DistributorControllerNandu@complaintedit');
            $router->post('complaintdelete', 'DistributorControllerNandu@complaintdelete');
            $router->post('complaintview', 'DistributorControllerNandu@complaintview');
            $router->post('complaintsearch', 'DistributorControllerNandu@complaintsearch');
            $router->post('complaintsearchbydate', 'DistributorControllerNandu@complaintsearchbydate');
            

            $router->post('farmer_meeting_delete', 'DistributorControllerNandu@farmer_meeting_delete');
            $router->post('distributor_meeting_delete', 'DistributorControllerNandu@distributor_meeting_delete');
            $router->post('orderlist_by_date_mobileapp', 'DistributorControllerNandu@orderlist_by_date_mobileapp');
            $router->post('videossearch_mobileapp', 'DistributorControllerNandu@videossearch_mobileapp');
            $router->post('video_search_by_date', 'DistributorControllerNandu@video_search_by_date_mobileapp');
            $router->post('bloglist_distributorapp', 'DistributorControllerNandu@bloglist_distributorapp');
            $router->post('sct_result_search_by_title', 'DistributorControllerNandu@sct_result_search_by_title_mobileapp');
            $router->post('languageview', 'DistributorControllerNandu@languageview');
            $router->post('allvideo', 'DistributorControllerNandu@allvideo');
            $router->post('allvideoadd', 'DistributorControllerNandu@allvideoadd');
            $router->post('allvideoupdate', 'DistributorControllerNandu@allvideoupdate');
            $router->post('allvideoget', 'DistributorControllerNandu@allvideoget');
            $router->post('allvideodelete', 'DistributorControllerNandu@allvideodelete');
            $router->post('farmer_under_distributor', 'DistributorControllerNandu@farmer_under_distributor_mobileapp');
            $router->post('distributor_under_distributor', 'DistributorControllerNandu@distributor_under_distributor_mobileapp');
            $router->post('language_brochure_search', 'DistributorControllerNandu@language_brochure_search');
            
            // Sale API
            $router->post('saleadd_mobileapp', 'DistributorControllerNandu@saleadd_mobileapp');
            $router->post('salelist_by_date', 'DistributorControllerNandu@salelist_by_date_mobileapp');
            $router->post('saleget_mobileapp', 'DistributorControllerNandu@saleget_mobileapp');
            $router->post('saledelete_mobileapp', 'DistributorControllerNandu@saledelete_mobileapp');
            $router->post('saleupdate_mobileapp', 'DistributorControllerNandu@saleupdate_mobileapp');
            $router->post('salelist_mobileapp', 'DistributorControllerNandu@salelist_mobileapp');
            $router->post('saleview_mobileapp', 'DistributorControllerNandu@saleview_mobileapp');
            $router->post('allsaleproductlist_mobileapp', 'DistributorControllerNandu@allsaleproductlist_mobileapp');
            $router->post('allorderproductlist_by_distributor_mobileapp', 'DistributorControllerNandu@allorderproductlist_by_distributor_mobileapp');
            $router->post('allproductlistofdistributor', 'DistributorControllerNandu@allproductlistofdistributor_mobileapp');
            
            
            
            $router->post('distributortargetvideolistdatefilter_distributorapp', 'DistributorController@distributortargetvideolistdatefilter_distributorapp');
            $router->post('distributortargetvideosearch_distributorapp', 'DistributorController@distributortargetvideosearch_distributorapp');
            $router->post('distributorsearchbrochure_distributorapp', 'DistributorController@distributorsearchbrochure_distributorapp');
            $router->post('distributorsearchlanguage_distributorapp', 'DistributorController@distributorsearchlanguage_distributorapp');
            $router->post('distributortargetvideosearchfromall_distributorapp', 'DistributorController@distributortargetvideosearchfromall_distributorapp');
            $router->post('distributorproductsearch_distributorapp', 'DistributorController@distributorproductsearch_distributorapp');
            $router->post('distributorlistundercount_distributor', 'DistributorController@distributorlistundercount_distributor');
            
            $router->post('articleblogbydatefilter_distributorapp', 'DistributorController@articleblogbydatefilter_distributorapp');
            $router->post('articleblogsearch_distributorapp', 'DistributorController@articleblogsearch_distributorapp');
            $router->post('scheduleblogbydatefilter_distributorapp', 'DistributorController@scheduleblogbydatefilter_distributorapp');
            $router->post('scheduleblogsearch_distributorapp', 'DistributorController@scheduleblogsearch_distributorapp');
            $router->post('distributorvisittofarmercount_distributorapp', 'DistributorController@distributorvisittofarmercount_distributorapp');
            $router->post('farmermeetingcount_distributorapp', 'DistributorController@farmermeetingcount_distributorapp');
            $router->post('sct_resultcount_distributorapp', 'DistributorController@sct_resultcount_distributorapp');
            $router->post('distributortargetvideodatefilter_distributorapp', 'DistributorController@distributortargetvideodatefilter_distributorapp');
            $router->post('subscribertargetgetlist_distributorapp', 'DistributorController@subscribertargetgetlist_distributorapp');
            $router->post('subscribertargetcount_distributorapp', 'DistributorController@subscribertargetcount_distributorapp');
            
            //distributortargetvideosearchfromall_distributorapp
            
            
            
            
            
            
            
            
            
            
            ///////////////////////////////////////////////Common ///////////////////////////////////////////////////////////////////////////////////////
            $router->post('farmergetdetails', 'FarmerController@farmergetdetails');
            $router->post('distributorlistunder_distributor', 'DistributorController@distributorlistunder_distributor');
            $router->post('downloadcontentlist', 'CommonController@downloadcontentlist');
            $router->post('downloadcontent', 'CommonController@downloadcontent');
            
            
            
            
            
            
            
            
            
            ////////////////////////////////////////////// Web ////////////////////////////////////////////////////////////////////////////
            $router->post('distributorvisittofarmerlist_distributorweb', 'DistributorController@distributorvisittofarmerlist_distributorweb');
            $router->post('farmervisitdetails', 'WebAPIController@farmervisitdetails');
            $router->post('getvideodetailsdistributorall', 'DistributorController@getvideodetailsdistributorall');
            
            //Target Video List
            $router->post('distributortargetvideolist_distributorweb', 'DistributorController@distributortargetvideolist_distributorweb');
            $router->post('distributortargetvideoadd_distributorweb', 'DistributorController@distributortargetvideoadd_distributorweb');
            $router->post('distributortargetvideodelete_distributorweb', 'DistributorController@distributortargetvideodelete_distributorweb');
            $router->post('distributortargetvideoget_distributorweb', 'DistributorController@distributortargetvideoget_distributorweb');
            $router->post('distributortargetvideoupdate_distributorweb', 'DistributorController@distributortargetvideoupdate_distributorweb');
            
            //Distributor Meeting List
            $router->post('distributormeetinglist_distributorweb', 'DistributorController@distributormeetinglist_distributorweb');
            $router->post('distributormeetingdetails_distributorweb', 'DistributorController@distributormeetingdetails_distributorweb');
            $router->post('farmermeetinglist_distributorweb', 'DistributorController@farmermeetinglist_distributorweb');
            $router->post('farmermeetingdetails_distributorweb', 'DistributorController@farmermeetingdetails_distributorweb');
            
            //Plot Visit 
            $router->post('plotvisitlist_web', 'WebAPIController@plotvisitlist_web');
            $router->post('plotvisitadd_web', 'WebAPIController@plotvisitadd_web');
            $router->post('plotvisitget_web', 'WebAPIController@plotvisitget_web');
            $router->post('plotvisitupdate_web', 'WebAPIController@plotvisitupdate_web');
            $router->post('plotvisitdelete_web', 'WebAPIController@plotvisitdelete_web');
            
            // Client Logos Add
            $router->post('client_logos_add_web', 'WebAPIController@client_logos_add_web');
            $router->post('client_logoslist', 'WebAPIController@client_logoslist');
            $router->post('client_logosget', 'WebAPIController@client_logosget');
            $router->post('client_logosupdate', 'WebAPIController@client_logosupdate');
            $router->post('client_logosdelete', 'WebAPIController@client_logosdelete');
            
            // Front Career
            $router->post('career_add', 'WebAPIController@career_add');
            $router->post('career_list', 'WebAPIController@career_list');
            $router->post('career_get', 'WebAPIController@career_get');
            $router->post('career_update', 'WebAPIController@career_update');
            
            
            // Front Internship
            $router->post('internship_list', 'WebAPIController@internship_list');
            $router->post('internship_delete', 'WebAPIController@internship_delete');
            $router->post('internship_get', 'WebAPIController@internship_get');
            $router->post('internship_update', 'WebAPIController@internship_update');
            $router->post('download_internship_resume', 'WebAPIController@download_internship_resume');
            
            // Website Marquee
            $router->post('website_marquee_add', 'WebAPIController@website_marquee_add');
            $router->post('website_marquee_list', 'WebAPIController@website_marquee_list');
            $router->post('website_marquee_get', 'WebAPIController@website_marquee_get');
            $router->post('website_marquee_update', 'WebAPIController@website_marquee_update');
            $router->post('website_marquee_delete', 'WebAPIController@website_marquee_delete');
            $router->post('website_marquee_active', 'WebAPIController@website_marquee_active');
            $router->post('website_marquee_inactive', 'WebAPIController@website_marquee_inactive');
            
            
            
            // Front Enquiry
            $router->post('frontenquiryget', 'WebAPIController@frontenquiryget');
            // Front Job Posting
            $router->post('job_posting_list', 'WebAPIController@job_posting_list');
            $router->post('job_posting_delete', 'WebAPIController@job_posting_delete');
            $router->post('job_posting_get', 'WebAPIController@job_posting_get');
            $router->post('job_posting_update', 'WebAPIController@job_posting_update');
            $router->post('download_job_posting_resume', 'WebAPIController@download_job_posting_resume');
            
            // Blog Reply
            $router->post('blog_reply_list', 'WebAPIController@blog_reply_list');
            
            // Product Review
            $router->post('product_review_list', 'WebAPIController@product_review_list');
            
            ////////////////////////////////////////// Web
            //Comapny Profile
            $router->post('companyprofileadd', 'WebAPIController@companyprofileadd');
            $router->post('companyprofilelist', 'WebAPIController@companyprofilelist');
            $router->post('companyprofileget', 'WebAPIController@companyprofileget');
            $router->post('companyprofileupdate', 'WebAPIController@companyprofileupdate');
            $router->post('companyprofiledelete', 'WebAPIController@companyprofiledelete');
            
            //Web About Us
            $router->post('webaboutusadd', 'WebAPIController@webaboutusadd');
            $router->post('webaboutusupdate', 'WebAPIController@webaboutusupdate');
            $router->post('webaboutusget', 'WebAPIController@webaboutusget');
            $router->post('webaboutusdelete', 'WebAPIController@webaboutusdelete');
            $router->post('webaboutuslist', 'WebAPIController@webaboutuslist');
            
            //Web Cover Photo
            $router->post('coverphotoadd', 'WebAPIController@coverphotoadd');
            $router->post('coverphotoupdate', 'WebAPIController@coverphotoupdate');
            $router->post('coverphotoget', 'WebAPIController@coverphotoget');
            $router->post('coverphotodelete', 'WebAPIController@coverphotodelete');
            $router->post('coverphotolist', 'WebAPIController@coverphotolist');
            
            //Web Gallary Photo
            $router->post('gallaryphotoadd', 'WebAPIController@gallaryphotoadd');
            $router->post('gallaryphotoupdate', 'WebAPIController@gallaryphotoupdate');
            $router->post('gallaryphotoget', 'WebAPIController@gallaryphotoget');
            $router->post('gallaryphotodelete', 'WebAPIController@gallaryphotodelete');
            $router->post('gallaryphotolist', 'WebAPIController@gallaryphotolist');
            
            //Web VisionMission Photo
            $router->post('webvisionmissionadd', 'WebAPIController@webvisionmissionadd');
            $router->post('webvisionmissionupdate', 'WebAPIController@webvisionmissionupdate');
            $router->post('webvisionmissionget', 'WebAPIController@webvisionmissionget');
            $router->post('webvisionmissiondelete', 'WebAPIController@webvisionmissiondelete');
            $router->post('webvisionmissionlist', 'WebAPIController@webvisionmissionlist');
            
            // Counter
            $router->post('front_counter_add', 'WebAPIController@front_counter_add');
            $router->post('front_counter_list', 'WebAPIController@front_counter_list');
            $router->post('front_counter_get', 'WebAPIController@front_counter_get');
            $router->post('front_counter_delete', 'WebAPIController@front_counter_delete');
            $router->post('front_counter_update', 'WebAPIController@front_counter_update');
            
            
            
            //Web Video
            $router->post('webvideoadd', 'WebAPIController@webvideoadd');
            $router->post('webvideoupdate', 'WebAPIController@webvideoupdate');
            $router->post('webvideoget', 'WebAPIController@webvideoget');
            $router->post('webvideodelete', 'WebAPIController@webvideodelete');
            $router->post('webvideolist', 'WebAPIController@webvideolist');
            
            
            // Target Videos
            $router->post('webtargetvideoadd', 'WebAPIController@webtargetvideoadd');
            $router->post('webtargetvideoupdate', 'WebAPIController@webtargetvideoupdate');
            $router->post('webtargetvideoget', 'WebAPIController@webtargetvideoget');
            $router->post('webtargetvideodelete', 'WebAPIController@webtargetvideodelete');
            $router->post('webtargetvideolist', 'WebAPIController@webtargetvideolist');
            
            
            // Brochure
            $router->post('webbrochureadd', 'WebAPIController@webbrochureadd');
            $router->post('webbrochureupdate', 'WebAPIController@webbrochureupdate');
            $router->post('webbrochureget', 'WebAPIController@webbrochureget');
            $router->post('webbrochuredelete', 'WebAPIController@webbrochuredelete');
            $router->post('webbrochurelist', 'WebAPIController@webbrochurelist');
            
            //SCT Result
            $router->post('websctresultlist', 'WebAPIController@websctresultlist');
            $router->post('websctresultget', 'WebAPIController@websctresultget');
            
            // Sales By Distributor
            
            //Web Blog Article
            $router->post('webblogarticleadd', 'WebAPIController@webblogarticleadd');
            $router->post('webblogarticleupdate', 'WebAPIController@webblogarticleupdate');
            $router->post('webblogarticleget', 'WebAPIController@webblogarticleget');
            $router->post('webblogarticledelete', 'WebAPIController@webblogarticledelete');
            $router->post('webblogarticlelist', 'WebAPIController@webblogarticlelist');
            
            
            //webblogs Schedule Article
            $router->post('webblogsscheduleadd', 'WebAPIController@webblogsscheduleadd');
            $router->post('webblogsscheduleupdate', 'WebAPIController@webblogsscheduleupdate');
            $router->post('webblogsscheduleget', 'WebAPIController@webblogsscheduleget');
            $router->post('webblogsscheduledelete', 'WebAPIController@webblogsscheduledelete');
            $router->post('webblogsschedulelist', 'WebAPIController@webblogsschedulelist');
            
            
            //web Testimonials
            $router->post('webtestimonialsadd', 'WebAPIController@webtestimonialsadd');
            $router->post('webtestimonialsupdate', 'WebAPIController@webtestimonialsupdate');
            $router->post('webtestimonialsget', 'WebAPIController@webtestimonialsget');
            $router->post('webtestimonialsdelete', 'WebAPIController@webtestimonialsdelete');
            $router->post('webtestimonialslist', 'WebAPIController@webtestimonialslist');
            
            
            //web audio
            $router->post('webaudioadd', 'WebAPIController@webaudioadd');
            $router->post('webaudioupdate', 'WebAPIController@webaudioupdate');
            $router->post('webaudioget', 'WebAPIController@webaudioget');
            $router->post('webaudiodelete', 'WebAPIController@webaudiodelete');
            $router->post('webaudiolist', 'WebAPIController@webaudiolist');
            
            //web product
            $router->post('webproductadd', 'WebAPIController@webproductadd');
            $router->post('webproductupdate', 'WebAPIController@webproductupdate');
            $router->post('webproductget', 'WebAPIController@webproductget');
            $router->post('webproductdelete', 'WebAPIController@webproductdelete');
            $router->post('webproductlist', 'WebAPIController@webproductlist');
            $router->post('webproductlistbyprodname', 'WebAPIController@webproductlistbyprodname');
            
            
                //Web Agency
            $router->post('webagencyadd', 'WebAPIController@webagencyadd');
            $router->post('webagencyupdate', 'WebAPIController@webagencyupdate');
            $router->post('webagencyget', 'WebAPIController@webagencyget');
            $router->post('webagencydelete', 'WebAPIController@webagencydelete');
            $router->post('webagencylist', 'WebAPIController@webagencylist');
            $router->post('webagencydetails', 'WebAPIController@webagencydetails');
            $router->post('webagencyby_lat_long_distance', 'WebAPIController@webagencyby_lat_long_distance');
            
            //Order
            $router->post('weborderadd', 'WebAPIController@weborderadd');
            $router->post('weborderupdate', 'WebAPIController@weborderupdate');
            $router->post('weborderget', 'WebAPIController@weborderget');
            $router->post('weborderdelete', 'WebAPIController@weborderdelete');
            $router->post('weborderlist', 'WebAPIController@weborderlist');
            $router->post('weborderorderdetails', 'WebAPIController@weborderorderdetails');
            
            $router->post('webordergetbydistributorid', 'WebAPIController@webordergetbydistributorid');
            $router->post('webordergetalldetails', 'WebAPIController@webordergetalldetails');
            
            $router->post('weborderaccountsectionverified', 'WebAPIController@weborderaccountsectionverified');
            $router->post('weborderaccountsectionforwardtowarehouse', 'WebAPIController@weborderaccountsectionforwardtowarehouse');
            
            $router->post('weborderlistforwarehouse', 'WebAPIController@weborderlistforwarehouse');
            $router->post('weborderdispatchedfromwarehouse', 'WebAPIController@weborderdispatchedfromwarehouse');
            
            //Report
            $router->post('websalesreport', 'WebAPIController@websalesreport');
            $router->post('reportsales', 'WebAPIController@reportsales');
            $router->post('viewreportsales', 'WebAPIController@viewreportsales');

            

            
            $router->post('webdistributorrderreport', 'WebAPIController@webdistributorrderreport');
            $router->post('weballorderreport', 'WebAPIController@weballorderreport');
            $router->post('weballorderconfirmnotdispatchedreport', 'WebAPIController@weballorderconfirmnotdispatchedreport');
            $router->post('weballorderconfirmdispatchedreport', 'WebAPIController@weballorderconfirmdispatchedreport');
            //$router->post('weballorderreport', 'WebAPIController@weballorderreport');
            $router->post('webdistdashreport', 'WebAPIController@webdistdashreport');
            
            $router->post('webdash_farmer_count', 'WebAPIController@webdash_farmer_count');
            $router->post('webdash_distributor_count', 'WebAPIController@webdash_distributor_count');
            $router->post('webdash_farmer_list', 'WebAPIController@webdash_farmer_list');
            $router->post('webdash_distributor_list', 'WebAPIController@webdash_distributor_list');
            //$router->post('webdash_farmer_sales_count', 'WebAPIController@webdash_farmer_sales_count');
            //$router->post('webdash_distributor_sales_count', 'WebAPIController@webdash_distributor_sales_count');
            $router->post('webdash_sales_count', 'WebAPIController@webdash_sales_count');
            
            $router->post('web_distributor_promotion', 'WebAPIController@web_distributor_promotion');
            $router->post('web_distributor_demotion', 'WebAPIController@web_distributor_demotion');
            
            
            
            $router->post('webdash_counting', 'WebAPIController@webdash_counting');
            $router->post('list_notification_web', 'WebAPIController@list_notification_web');
            
            
            $router->post('fsc_list', 'WebAPIController@fsc_list');
            $router->post('bsc_list', 'WebAPIController@bsc_list');
            $router->post('dsc_list', 'WebAPIController@dsc_list');
            $router->post('subscriber_count_distributor', 'WebAPIController@subscriber_count_distributor');
            $router->post('webfarmerlist', 'WebAPIController@farmerlist');
            $router->post('bsc_list_by_bsc', 'WebAPIController@bsc_list_by_bsc');
            $router->post('dsc_list_by_dsc', 'WebAPIController@dsc_list_by_dsc');
            $router->post('fsc_list_by_fsc', 'WebAPIController@fsc_list_by_fsc');
            
            //front product
            $router->post('frontproductadd', 'WebAPIController@frontproductadd');
            $router->post('frontproductupdate', 'WebAPIController@frontproductupdate');
            $router->post('frontproductget', 'WebAPIController@frontproductget');
            $router->post('frontproductdelete', 'WebAPIController@frontproductdelete');
            $router->post('frontproductlist', 'WebAPIController@frontproductlist');
            
            // Front Distributor List
            $router->post('frontdistributorlist', 'WebAPIController@frontdistributorlist');
            $router->post('approvedistributor', 'WebAPIController@approvedistributor');
            
            
            $router->post('web_frontdistributorinfo', 'WebAPIController@web_frontdistributorinfo');
            $router->post('web_frontdistributorupdate', 'WebAPIController@web_frontdistributorupdate');
            
            // Address Update
            $router->post('address_update', 'WebAPIController@address_update');
            $router->post('address_get', 'WebAPIController@address_get');
            $router->post('address_list', 'DistributorController@address_list');
            
            // Crops
            $router->post('crops_add', 'WebAPIController@crops_add');
            $router->post('crops_list', 'WebAPIController@crops_list');
            $router->post('crops_get', 'WebAPIController@crops_get');
            $router->post('crops_update', 'WebAPIController@crops_update');
            $router->post('cropsdelete', 'WebAPIController@cropsdelete');
            
            
            // Principles Update
            $router->post('principles_update', 'WebAPIController@principles_update');
            $router->post('principles_get', 'WebAPIController@principles_get');
            $router->post('principles_list', 'WebAPIController@principles_list');
            
            //Mobile app Profile
            $router->post('add_profile_data_mobileapp', 'DistributorMobileAppController@add_profile_data_mobileapp');
            $router->post('edit_profile_data_mobileapp', 'DistributorMobileAppController@edit_profile_data_mobileapp');
            $router->post('update_profile_data_mobileapp', 'DistributorMobileAppController@update_profile_data_mobileapp');
            
        });
});