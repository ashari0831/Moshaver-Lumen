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

// $router->get('/', function () use ($router) {
//     return $router->app->version();
// });

$router->group(['prefix'=>'api/v1'], function () use ($router){
    $router->post('/register', 'RegisterController@register');
    $router->post('/login', 'AuthController@login');
    $router->post('/resetpassword', ['as' => 'password.reset', 'uses' => 'AuthController@generateResetToken']);
    $router->put('/resetpassword', 'AuthController@resetPassword');
    $router->post('/upload-doc-file/{advisor}', 'AdvisorDocumentController@store');
    $router->post('/delete-doc-file/{file}', 'AdvisorDocumentController@destroy');
    $router->get('/download-doc-file/{file}', 'AdvisorDocumentController@download');
    $router->patch('/doc-file-status', 'AdvisorDocumentController@update');
    $router->post('/email/verify', [
        'as' => 'email.verify', 
        'uses' => 'EmailVerificationController@emailVerify'
    ]);


    $router->group(['middleware' => ['auth']], function () use ($router){   //middleware=>varified
        $router->get('/user-profile', 'AuthController@me');
        $router->patch('/user-profile', 'AuthController@update');
        $router->post('/refresh-token', 'AuthController@refresh');
        $router->post('/logout', 'AuthController@logout');
        $router->get('/advisor-profile', 'AdvisorController@show');
        $router->patch('/advisor-profile', 'AdvisorController@update');
        $router->get('/all-advisors', 'AdvisorController@index');
        $router->post('/create-advisor', [
            'middleware' => 'notRepetitiousAdvisor',
            'uses'=> 'AdvisorController@store'
        ]);
        $router->get('/user-requests', 'RequestController@user_requests');
        $router->get('/advisor-requests', 'RequestController@advisor_requests');
        $router->get('/users-comments', 'RateController@users_comments');
        $router->get('/users-comments-by-advisor-id/{advisor_id}', 'RateController@users_comments_by_advisor_id');
        $router->post('/create-comment', 'RateController@store');
        $router->get('/advisor-resume', 'AdvisorHistoryController@index');
        $router->patch('/advisor-resume/{resume}', 'AdvisorHistoryController@update');
        $router->get('/advisor-resume/{advisor}', 'AdvisorHistoryController@show');
        $router->get('/profile-image/{user}', 'AuthController@profile_image');
        $router->get('/profile-image', 'AuthController@profile_image_me');
        $router->get('/search', 'SearchController@index');
        $router->post('/email/request-verification', [
            'as' => 'email.request.verification', 
            'uses' => 'EmailVerificationController@emailRequestVerification'
        ]);
        $router->get('/advisor-docs/{advisor}', 'AdvisorDocumentController@show');
        $router->post('/reserve', 'ReservationController@store');
        $router->get('/reservation-details', 'ReservationController@index');
        $router->post('/advisor-jobtime', [
            'middleware' => 'notCreatedJobtime',
            'uses' => 'AdvisorDailyTimeController@store'
        ]);
        $router->patch('/advisor-jobtime', 'AdvisorDailyTimeController@update');
        $router->get('/advisor-jobtime', 'AdvisorDailyTimeController@show');
        $router->get('/chat', 'ChatController@chatrooms');
        $router->get('/chat/{chat_id}/messages', 'ChatController@fetchMessages');
        $router->post('/chat/{chat_id}/messages', 'ChatController@sendMessage');

        $router->group(['prefix' => '/admin', 'middleware' => ['isAdmin']], function () use ($router){
            $router->get('/comments', 'RateController@index');
            $router->patch('/comments/{comment}', 'RateController@update');
            $router->delete('/comments/{comment}', 'RateController@destroy');
            $router->get('/advisor-documents', 'AdvisorController@doc_info_for_admin');
            $router->get('/statistical-report', 'StatisticalController@get_statistical_data');
            $router->patch('/advisor-profile/{user}', 'AdvisorController@update_adv');
            $router->get('/advisor-profile/{advisor}', 'AdvisorController@advisor_resume_info');
            $router->get('/reservation-details/{advisor_user_id}', 'ReservationController@show');
            $router->delete('/reservation/{reservation}', 'ReservationController@destroy');
            $router->get('/advisor-jobtime/{advisor}', 'AdvisorDailyTimeController@admin_show');
            $router->patch('/advisor-jobtime/{advisor}', 'AdvisorDailyTimeController@admin_update');
            $router->get('/reservations', 'ReservationController@admin_reseve_details');
            $router->get('/chats/{advisor_user}', 'ChatController@particular_advisor_chats');
            $router->post('/create-user', 'UserController@store');
            $router->delete('/delete-user', 'UserController@destroy');
            $router->get('/user-profile/{user}', 'UserController@show');
            $router->get('/list-users', 'UserController@index');
            $router->get('/advisor-comments/{advisor}', 'RateController@paticular_advisor_rates');
            $router->get('/list-users-comments', 'RateController@list_users_comments');
            $router->delete('/delete-advisor/{user}', 'AdvisorController@destroy');
            $router->get('/list-advisors', 'AdvisorController@list_advisors_for_admin');
            $router->post('/create-advisor', 'AdvisorController@admin_creates_advisor');
            $router->get('/advisor-profile/{advisor}', 'AdvisorController@admin_show_advisor_profile');
            // $router->get('/list-unconfirmed-comments', 'RateController@unconfirmed_comments_for_admin');
        });
    });
});
    // $router->apiResource('photos', PhotoController::class)->only([
    //     'index', 'show'
    // ]);
    // $router->get('admin/profile', [
    //     'middleware' => 'auth',
    //     'uses' => 'AdminController@showProfile'
    // ]);
    

// $router->group(['middleware' => 'auth'], function () use ($router){

// });
// $router->get('/', ['middleware' => ['first', 'second'], function () {
//     //
// }]);
