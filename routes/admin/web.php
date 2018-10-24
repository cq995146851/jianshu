<?php

Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
   //登录
   Route::get('login', 'LoginController@index')->name('admin.login');
   Route::post('login', 'LoginController@login')->name('admin.login');
   //登出
   Route::get('logout', 'LoginController@logout')->name('admin.logout');

   //首页
    Route::group(['middleware' => 'check.adminLogin'], function () {
        Route::get('home', 'HomeController@index')->name('admin.home.index');
    });
    //系统通知
    Route::resource('notice', 'NoticeController')->names([
        'index' => 'admin.notice.index',
        'create' => 'admin.notice.create',
        'store' => 'admin.notice.store'
    ]);
});