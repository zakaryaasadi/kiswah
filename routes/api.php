<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/', function () {
    return ['status' => 'success', 'message' => 'KISWAH API'];
});


Route::get('/loc', function () {
    $locations = (new \App\Services\Tookan())->viewRegions();
    return ['status' => 'success', 'data' => $locations];
});

Route::group(['prefix' => 'tookan/update'], function () {
    Route::any('/task', 'TookanController@taskUpdate');
    Route::any('/process', 'TookanController@processOrders');
});
Route::group(['prefix' => 'customer'], function () {
    Route::get('/', 'API\CustomerController@index');
    Route::post('/login', 'API\CustomerController@authenticate');
    Route::post('/register', 'API\CustomerController@store');
    Route::post('/generate-otp', 'API\CustomerController@generateOtp');
    Route::post('/confirm-otp', 'API\CustomerController@confirmOtp');

    Route::post('/forgot-password', 'API\CustomerController@forgetPassword');
    Route::post('/update-password', 'API\CustomerController@updatePassword');
    Route::post('/change-password', 'API\CustomerController@changePassword')->middleware('auth:api');
    Route::post('/verify-password-otp', 'API\CustomerController@verifyOtp');
    Route::group(['middleware' => 'auth:api'], function () {
        Route::apiResource('locations', 'API\LocationsController');
        Route::get('/profile', 'API\CustomerController@getAuthenticatedUser');
        Route::post('/profile', 'API\CustomerController@update');
    });
});
Route::get('/news', 'API\NewsController@index');
Route::get('/latest-news', 'API\NewsController@news');
Route::get('/publication', 'API\NewsController@post');
Route::get('charities', 'API\CharityController@index');
Route::get('settings', 'API\SettingController@index');
Route::get('donation-types', 'API\DonationTypeController@index');
Route::get('donation-types/unaccept', 'API\DonationTypeController@unaccept');
Route::post('help', 'API\HelpController@store');
Route::get('web-dates', 'API\TaskController@webDates');
Route::post('tasks/web', 'API\TaskController@webTask');

Route::group(['middleware' => 'auth:api'], function () {
    Route::post('tasks/create', 'API\TaskController@create');
    Route::get('available-dates', 'API\TaskController@availableDates');
    Route::apiResource('tasks', 'API\TaskController');
});
Route::post('add_region', 'API\RegionController@addRegion');

//ADMIN ROUTES
Route::group(['prefix' => 'admin'], function () {
    Route::post('/login', 'API\AdminController@authenticate');
    Route::post('/register', 'API\AdminController@store');
    Route::post('/forgot-password', 'API\AdminController@forgetPassword');
    Route::post('/update-password', 'API\AdminController@updatePassword');
    Route::post('/change-password', 'API\AdminController@changePassword');
    Route::post('/verify-password-otp', 'API\AdminController@verifyOtp');
});

Route::group(['middleware' => ['auth:users', 'scopes:view_as_admin'], 'prefix' => 'admin'], function () {
    Route::get('/profile', 'API\AdminController@getAuthenticatedUser');
    Route::post('/profile', 'API\AdminController@uploadAvatar');
    Route::post('donations/{id}', 'API\DonationTypeController@update');
    Route::post('users/{id}', 'API\AdminCustomersController@update');
    Route::apiResource('donations', 'API\DonationTypeController');

    Route::group(['prefix' => 'customers'], function () {
        Route::get('/{id}/locations', 'API\AdminCustomersController@showLocations');
        Route::get('/{id}/tasks', 'API\AdminCustomersController@showTasks');
        Route::apiResource('/', 'API\AdminCustomersController');
    });
    Route::group(['prefix' => 'tasks'], function () {
        Route::get('/completed', 'API\AdminTasksController@completed');
        Route::get('/unassigned', 'API\AdminTasksController@unassigned');
        Route::get('/all', 'API\AdminTasksController@all');
        Route::get('/assigned', 'API\AdminTasksController@assigned');
        Route::patch('/{id}/status', 'API\AdminTasksController@updateStatus');
    });
    Route::apiResource('/tasks', 'API\AdminTasksController')->name('index', 'admin.task');
    Route::apiResource('/settings', 'API\SettingController');

    Route::post('news/{id}', 'API\NewsController@update')->whereNumber('id');
    Route::post('charities/{id}', 'API\CharityController@update')->whereNumber('id');
    Route::post('region/{id}', 'API\RegionController@update')->whereNumber('id');
    Route::apiResource('/charities', 'API\CharityController');
    Route::apiResource('news', 'API\NewsController');
    Route::apiResource('helps', 'API\HelpController');
    Route::apiResource('agents', 'API\AgentController');
    Route::apiResource('missions', 'API\MissionController');
    Route::apiResource('teams', 'API\TeamController');
    Route::apiResource('merchants', 'API\MerchantController');
    Route::apiResource('/admins', 'API\UserController');
    Route::apiResource('region', 'API\RegionController');
});
