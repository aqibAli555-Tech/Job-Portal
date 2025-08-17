<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'App\Http\Controllers\Api\V1',], function () {

    Route::get('homepage', 'HomeController@Homepage');
    Route::post('employeeregister', 'RegisterController@employee_register');
    Route::post('employerregister', 'RegisterController@employer_register');
    Route::post('login', 'LoginController@login');
});


