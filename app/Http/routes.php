<?php

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

$app->get('/', function () use ($app) {
    return $app->version();
});


$app->group(['prefix' => 'api/v1','namespace' => 'App\Http\Controllers\Api'], function($app)
{
    $app->get('getschooldetails','ApiController@getSchoolDetails');

    $app->post('getroutes','ApiController@getRoutes');

    $app->post('gettripdetails','ApiController@getTripDetails');

    $app->post('submitattendance','ApiController@submitAttendance');

    $app->post('getstudentslist','ApiController@getStudentsList');

    $app->post('checkentry','ApiController@checkEntry');
  
});