<?php

$base1 = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
$base = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';

if(isset($_SERVER['HTTP_HOST']) && !empty($_SERVER['HTTP_HOST'])){
$base1 .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
$base .= '://'.$_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
}

define('PREFIX1', $base1.'public/');
define('BASE_PATH', $base.'/');
define('PREFIX', $base);
define('URL_HOME', PREFIX.'home');

define('DELETE_PATH', 'public/uploads/deleteimage/'); 


define('UPLOADS_WITHOUT_DOAMIN', 'public/uploads/'); 
define('UPLOADS_VIEW_WITH_DOMAIN', PREFIX1.'uploads/');

//Farmer Setting 
define('FARMER_UPLOADS', UPLOADS_WITHOUT_DOAMIN.'farmer/');
define('FARMER_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'farmer/');

//Distributor Setting 
define('DISTRIBUTOR_UPLOADS', UPLOADS_WITHOUT_DOAMIN.'distributor/');
define('DISTRIBUTOR_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'distributor/');
define('DISTRIBUTOR_OWN_DOCUMENTS', DISTRIBUTOR_UPLOADS.'distributorown/');
define('FRONT_DISTRIBUTOR_OWN_DOCUMENTS', DISTRIBUTOR_UPLOADS.'frontdistributorown/');
define('FRONT_DISTRIBUTOR_OWN_DOCUMENTS_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'distributor/frontdistributorown/');

//Mobile app profile
define('DISTRIBUTOR_PROFILE_UPLOADS', UPLOADS_WITHOUT_DOAMIN.'distributor_profile/');
define('DISTRIBUTOR_PROFILE_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'distributor_profile/');

//SCT Result Setting 
define('SCT_RESULT_UPLOADS', UPLOADS_WITHOUT_DOAMIN.'sct/');
define('SCT_RESULT_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'sct/');


//WEB Setting 
define('WEB_UPLOADS', UPLOADS_WITHOUT_DOAMIN.'web/');
define('WEB_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'web/');

//Download Setting 
define('DOWNLOAD_UPLOADS', UPLOADS_WITHOUT_DOAMIN.'downloads/');
define('DOWNLOAD_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'downloads/');

///////////////////////////////////////////////////////////////////////////////////
//Farmer Photo 
define('FARMER_PHOTO_UPLOAD', FARMER_UPLOADS.'farmerphoto/');
define('FARMER_PHOTO_VIEW', FARMER_VIEW.'farmerphoto/');
define('FARMER_PHOTO_UPLOAD_VIEW_DEFAULT', FARMER_PHOTO_VIEW.'default.png');
//Visit Photo
define('FARMER_VISIT_PHOTO_UPLOAD', FARMER_UPLOADS.'farmervisitphoto/');
define('FARMER_VISIT_PHOTO_VIEW', FARMER_VIEW.'farmervisitphoto/');


//Distributor Meeting Photo
define('DISTRIBUTOR_MEETING_PHOTO_UPLOAD', DISTRIBUTOR_UPLOADS.'distributormeeting/');
define('DISTRIBUTOR_MEETING_PHOTO_VIEW', DISTRIBUTOR_VIEW.'distributormeeting/');

//Farmer Meeting Photo
define('FARMER_MEETING_PHOTO_UPLOAD', FARMER_UPLOADS.'farmermeeting/');
define('FARMER_MEETING_PHOTO_VIEW', FARMER_VIEW.'farmermeeting/');

//SCT_RESULT_PHOTO
define('SCT_RESULT_PHOTO_UPLOAD', SCT_RESULT_UPLOADS.'sctresult/');
define('SCT_RESULT_PHOTO_VIEW', SCT_RESULT_VIEW.'sctresult/');

//WEB_COMPANY_PROFILE_PHOTO
define('WEB_COMPANY_PROFILE_PHOTO_UPLOAD', WEB_UPLOADS.'companyprofile/');
define('WEB_COMPANY_PROFILE_PHOTO_VIEW', WEB_VIEW.'companyprofile/');


//ABOUT_US_PHOTO
define('ABOUT_US_PHOTO_UPLOAD', WEB_UPLOADS.'aboutus/');
define('ABOUT_US_PHOTO_VIEW', WEB_VIEW.'aboutus/');

//WEB_COVER_PHOTO
define('WEB_COVER_PHOTO_UPLOAD', WEB_UPLOADS.'coverphoto/');
define('WEB_COVER_PHOTO_VIEW', WEB_VIEW.'coverphoto/');

//WEB_CLIENT_LOGO
define('WEB_CLIENT_LOGO_UPLOAD', WEB_UPLOADS.'clientlogos/');
define('WEB_CLIENT_LOGO_VIEW', WEB_VIEW.'clientlogos/');

//CAREER
define('WEB_CAREER_UPLOAD', WEB_UPLOADS.'career/');
define('WEB_CAREER_VIEW', WEB_VIEW.'career/');

//WEB_GALLARY_PHOTO
define('WEB_GALLARY_PHOTO_UPLOAD', WEB_UPLOADS.'gallaryphoto/');
define('WEB_GALLARY_PHOTO_VIEW', WEB_VIEW.'gallaryphoto/');


//WEB_VISIONMISSION_PHOTO
define('WEB_VISIONMISSION_PHOTO_UPLOAD', WEB_UPLOADS.'gallaryphoto/');
define('WEB_VISIONMISSION_PHOTO_VIEW', WEB_VIEW.'gallaryphoto/');

//DOWNLAOD_CONTENT
define('DOWNLAOD_CONTENT_UPLOAD', DOWNLOAD_UPLOADS);
define('DOWNLAOD_CONTENT_DOWNLAOD', DOWNLOAD_VIEW);

//BLOG_CONTENT
define('BLOG_CONTENT_UPLOAD', WEB_UPLOADS.'blog/');
define('BLOG_CONTENT_VIEW', WEB_VIEW.'blog/');

//TESTIMONIALS_CONTENT
define('TESTIMONIALS_CONTENT_UPLOAD', WEB_UPLOADS.'testimonials/');
define('TESTIMONIALS_CONTENT_VIEW', WEB_VIEW.'testimonials/');


//INTERNSHIP_CONTENT
define('INTERNSHIP_CONTENT_UPLOAD', WEB_UPLOADS.'internship/');
define('INTERNSHIP_CONTENT_VIEW', WEB_VIEW.'internship/');

//JOBPOSTING_CONTENT
define('JOBPOSTING_CONTENT_UPLOAD', WEB_UPLOADS.'jobposting/');
define('JOBPOSTING_CONTENT_VIEW', WEB_VIEW.'jobposting/');


//AUDIO_CONTENT
define('AUDIO_CONTENT_UPLOAD', WEB_UPLOADS.'audio/');
define('AUDIO_CONTENT_VIEW', WEB_VIEW.'audio/');


//PRODUCT_UPLOAD
define('PRODUCT_CONTENT_UPLOAD', WEB_UPLOADS.'product/');
define('PRODUCT_CONTENT_VIEW', WEB_VIEW.'product/');

//PRODUCT_UPLOAD
define('FRONTPRODUCT_CONTENT_UPLOAD', WEB_UPLOADS.'frontproduct/');
define('FRONTPRODUCT_CONTENT_VIEW', WEB_VIEW.'frontproduct/');


//AGENCY_PHOTO
define('AGENCY_PHOTO_UPLOAD', WEB_UPLOADS.'agency/');
define('AGENCY_PHOTO_VIEW', WEB_VIEW.'agency/');

// Message 
define('MESSAGE_UPLOADS', UPLOADS_WITHOUT_DOAMIN.'message/');
define('MESSAGE_UPLOADS_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'message/');

// COMPLAINT 
define('COMPLAINT_UPLOADS', UPLOADS_WITHOUT_DOAMIN.'complaint/');
define('COMPLAINT_VIEW', UPLOADS_VIEW_WITH_DOMAIN.'complaint/');
// // dd($_SERVER);
// define('CSSNEW', PREFIX1.'newwebsiteasset/');
// define('TESTINOMIALS_IMAGE', PREFIX1.'website/testimonials/');

// //Design Source File Paths
// define('CSS', PREFIX1.'css/');
// define('JS', PREFIX1.'js/');
// define('FONTAWSOME', PREFIX1.'font-awesome/css/');
// define('IMAGES', PREFIX1.'images/');
// define('AJAXLOADER', IMAGES.'ajax-loader.svg');
// define('AJAXLOADER_FADEIN_TIME', 100);
// define('AJAXLOADER_FADEOUT_TIME', 100);
// define('FRONT_ASSETS', PREFIX1.'front/');


// define('UPLOADS', PREFIX1.'uploads/');
// define('EXAM_UPLOADS', UPLOADS.'exams/');
// define('IMAGE_PATH_UPLOAD_SERIES', UPLOADS.'exams/series/');
// define('IMAGE_PATH_UPLOAD_SERIES_THUMB', UPLOADS.'exams/series/thumb/');

// define('IMAGE_PATH_UPLOAD_EXAMSERIES_DEFAULT', UPLOADS.'exams/series/default.png');

// define('IMAGE_PATH_UPLOAD_LMS_CATEGORIES', UPLOADS.'lms/categories/');
// define('IMAGE_PATH_UPLOAD_LMS_DEFAULT', UPLOADS.'lms/categories/default.png');
// define('IMAGE_PATH_UPLOAD_LMS_CONTENTS', UPLOADS.'lms/content/');

// define('IMAGE_PATH_UPLOAD_LMS_SERIES', UPLOADS.'lms/series/');
// define('IMAGE_PATH_UPLOAD_LMS_SERIES_THUMB', UPLOADS.'lms/series/thumb/');

// define('IMAGE_PATH_PROFILE', UPLOADS.'users/');
// define('IMAGE_PATH_PROFILE_THUMBNAIL', UPLOADS.'users/thumbnail/');
// define('IMAGE_PATH_PROFILE_THUMBNAIL_DEFAULT', UPLOADS.'users/thumbnail/default.png');

// define('IMAGE_PATH_SETTINGS', UPLOADS.'settings/');



// define('DOWNLOAD_LINK_USERS_IMPORT_EXCEL', PREFIX1.'downloads/excel-templates/users_template.xlsx');

// define('CURRENCY_CODE', '$ ');
// define('RECORDS_PER_PAGE', '8');


// define('OWNER_ROLE_ID', '1');
// define('ADMIN_ROLE_ID', '2');
// define('USER_ROLE_ID', '5');
// define('STUDENT_ROLE_ID', '5');
// define('PARENT_ROLE_ID', '6');



// define('GOOGLE_TRANSLATE_LANGUAGES_LINK', 'https://cloud.google.com/translate/docs/languages');

// define('PAYMENT_STATUS_CANCELLED', 'cancelled');
// define('PAYMENT_STATUS_SUCCESS', 'success');
// define('PAYMENT_STATUS_PENDING', 'pending');
// define('PAYMENT_STATUS_ABORTED', 'aborted');
// define('PAYMENT_RECORD_MAXTIME', '30'); //TIME IN MINUTES




// //INSTRUCTIONS MODULE
// define('URL_INSTRUCTIONS', PREFIX.'exam/instructions/list');
// define('URL_INSTRUCTIONS_ADD', PREFIX.'exams/instructions/add');
// define('URL_INSTRUCTIONS_EDIT', PREFIX.'exams/instructions/edit/');
// define('URL_INSTRUCTIONS_DELETE', PREFIX.'exams/instructions/delete/');
// define('URL_INSTRUCTIONS_GETLIST', PREFIX.'exams/instructions/getList');

// //LANGUAGES MODULE
// define('URL_LANGUAGES_LIST', PREFIX.'languages/list');
// define('URL_LANGUAGES_ADD', PREFIX.'languages/add');
// define('URL_LANGUAGES_EDIT', PREFIX.'languages/edit');
// define('URL_LANGUAGES_UPDATE_STRINGS', PREFIX.'languages/update-strings/');
// define('URL_LANGUAGES_DELETE', PREFIX.'languages/delete/');
// define('URL_LANGUAGES_GETLIST', PREFIX.'languages/getList/');
// define('URL_LANGUAGES_MAKE_DEFAULT', PREFIX.'languages/make-default/');


// //CONSTANST FOR USERS MODULE
// define('URL_USERS', PREFIX.'users');
// define('URL_USER_DETAILS', PREFIX.'users/details/');
// define('URL_USERS_EDIT', PREFIX.'users/edit/');
// define('URL_USERS_ADD', PREFIX.'users/create');
// define('URL_USERS_DELETE', PREFIX.'users/delete/');
// define('URL_USERS_SETTINGS', PREFIX.'users/settings/');
// define('URL_USERS_CHANGE_PASSWORD', PREFIX.'users/change-password/');
// define('URL_USERS_LOGOUT', PREFIX.'logout');
// define('URL_PARENT_LOGOUT', PREFIX.'parent-logout');
// define('URL_USERS_REGISTER', PREFIX.'register');
// define('URL_USERS_LOGIN', PREFIX.'login');
// define('URL_USERS_UPDATE_PARENT_DETAILS', PREFIX.'users/parent-details/');
// define('URL_SEARCH_PARENT_RECORDS', PREFIX.'users/search/parent');

// define('URL_USERS_IMPORT', PREFIX.'users/import');
// define('URL_USERS_IMPORT_REPORT', PREFIX.'users/import-report');

// // define('URL_FORGOT_PASSWORD', PREFIX.'users/forgot-password');
// define('URL_USERS_FORGOT_PASSWORD', PREFIX.'users/forgot-password');


// define('URL_RESET_PASSWORD', PREFIX.'password/reset');



// 			///////////////////
// 			//STUDENT MODULE //
// 			///////////////////

// //STUDENT NAVIGATION
// define('URL_STUDENT_EXAM_CATEGORIES', PREFIX.'exams/student/categories');
// define('URL_STUDENT_EXAM_ATTEMPTS', PREFIX.'exams/student/exam-attempts/');
// define('URL_STUDENT_ANALYSIS_SUBJECT', PREFIX.'student/analysis/subject/');
// define('URL_STUDENT_ANALYSIS_BY_EXAM', PREFIX.'student/analysis/by-exam/');
// define('URL_STUDENT_SUBSCRIPTIONS_PLANS', PREFIX.'subscription/plans');
// define('URL_STUDENT_LIST_INVOICES', PREFIX.'subscription/list-invoices/');


