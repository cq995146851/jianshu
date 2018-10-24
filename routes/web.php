<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/***********************************用户模块**************************************************/
//注册
Route::get('/register', 'RegisterController@toRegister')->name('toRegister');
Route::post('/register', 'RegisterController@register')->name('register');
//注册邮件
Route::get('/register/confirmEmail/{token}', 'RegisterController@confirmEmail')->name('register.confirmEmail');
//登录和登出
Route::get('/login', 'LoginController@toLogin')->name('toLogin');
Route::post('/login', 'LoginController@login')->name('login');
Route::get('/logout', 'LoginController@logout')->name('logout');
/**********************************文章模块**************************************************/
Route::group(['middleware' => 'check.login'], function () {
    //搜索页 路由先后顺序
    Route::get('/posts/search', 'PostsController@search')->name('posts.search');
    // 文章增删改查
    Route::resource('/posts', 'PostsController');
    //图片上传
    Route::post('/posts/image/upload', 'PostsController@imageUpload');
    //提交评论
    Route::post('/posts/{post}/comment', 'PostsController@comment')->name('posts.comment');
    //赞
    Route::get('/posts/{post}/zan', 'PostsController@zan')->name('posts.zan');
    //取消赞
    Route::get('/posts/{post}/unzan', 'PostsController@unzan')->name('posts.unzan');
    /***************************************个人中心模块*****************************************/
    //个人中心页面
    Route::get('/user/{user}', 'UserController@show')->name('user.show');
    //关注
    Route::post('/user/{user}/fan', 'UserController@fan')->name('user.fan');
    //取消关注
    Route::post('/user/{user}/unfan', 'UserController@unfan')->name('user.unfan');
    //个人设置
    Route::get('/user/{user}/setting', 'UserController@toSetting')->name('user.toSetting');
    Route::post('/user/{user}/setting', 'UserController@setting')->name('user.setting');
    /**********************************专题***********************************************/
    //专题页面
    Route::get('/topic/{topic}', 'TopicController@show')->name('topic.show');
    //发布文章
    Route::post('/topic/{topic}/submit', 'TopicController@submit')->name('topic.submit');
    //系统通知
    Route::get('/notice', 'NoticeController@index')->name('notice.index');
});

include_once (__DIR__.'/admin/web.php');
