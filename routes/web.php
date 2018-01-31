<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

/***********************************后台相关***********************************/
Route::group(['prefix' => 'admin'], function () {

    Route::group(['middleware'=>'check.login'], function(){
        //后台注册
        //Route::get('reg','Admin\LoginController@reg');
        //执行注册
        //Route::post('reg','Admin\LoginController@doReg');

        //后台登录
        Route::get('login','Admin\LoginController@login');
        //执行登录
        Route::post('login','Admin\LoginController@doLogin');

        //验证码
        Route::get('captcha/{tmp}','Admin\LoginController@captcha');
    });

    Route::group(['middleware'=>'check.admin.login'], function(){
        //演出统计
        Route::get('show/tj','Admin\ShowController@tj');
        //冻结演出
        Route::post('show/frozen','Admin\ShowController@doFrozen');
        //解冻演出
        Route::post('show/thaw','Admin\ShowController@doThaw');

        //删除演出票面价
        Route::post('show/deleteShowPrice/{id}','Admin\ShowController@doDeleteShowPrice');
        //修改演出票面价
        Route::post('show/updateShowPrice/{id}','Admin\ShowController@doUpdateShowPrice');
        //添加演出票面价
        Route::post('show/storeShowPrice/{id}','Admin\ShowController@doStoreShowPrice');
        //删除演出场次
        Route::post('show/deleteShowTime/{id}','Admin\ShowController@doDeleteShowTime');
        
        //演出管理
        Route::resource('show','Admin\ShowController');

        //添加城市
        Route::get('city','Admin\VenueController@city');
        //执行添加城市
        Route::post('city','Admin\VenueController@doCity');
        //获取指定城市的场馆
        Route::get('getVenue/{cityName}','Admin\VenueController@getVenueByCityName');
        //场馆管理
        Route::resource('venue','Admin\VenueController');

        //冻结卖家
        Route::post('user/frozen','Admin\UserController@doFrozen');
        //解冻卖家
        Route::post('user/thaw','Admin\UserController@doThaw');
        //代挂单 获取指定演出的场次
        Route::get('user/getShowTime/{id}','Admin\UserController@getShowTimeByShowId');
        //代挂单 获取指定演出场次的票价
        Route::get('user/getShowPrice/{id}','Admin\UserController@getShowPriceByShowTimeId');
        //执行代挂单
        Route::post('user/proxy','Admin\UserController@doProxy');
        //卖家统计
        Route::get('user/tj','Admin\UserController@tj');
        //卖家管理
        Route::resource('user','Admin\UserController');

        //上架挂单
        Route::post('order/onSell','Admin\OrderController@doOnSell');
        //下架挂单
        Route::post('order/offSell','Admin\OrderController@doOffSell');
        //挂单统计
        Route::get('order/tj','Admin\OrderController@tj');
        //挂单管理
        Route::resource('order','Admin\OrderController');

        //第三方平台
        Route::get('admin/platform','Admin\AdminController@platform');
        //前台URL说明
        Route::get('admin/urlExplain','Admin\AdminController@urlExplain');
        //管理员管理
        Route::resource('admin','Admin\AdminController');

        //退出后台
        Route::get('logout','Admin\LoginController@logout');
    });
});