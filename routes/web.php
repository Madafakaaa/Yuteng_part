<?php
/*
Route::get('/', function () {
    return view('welcome');
});
//控制器路由
Route::get('/', 'UserController@function_name');
Route::get('/', 'User/OrderController@function_name');
//资源路由
Route::resource('/article', 'ArticleController');
//配置参数
Route::get('/user/{id}/{name}', function ($id,$name) {
    return view('welcome');
});
//默认参数
Route::get('/user/{id?}', function ($id=null) {
    return view('welcome');
});
//过滤参数
Route::get('/user/{id?}', function ($id=null) {
    return view('welcome');
})->where('id','[a-z]+');
//重定向
Route::redirect('/a', '/b');
//视图
Route::view('/','view_name');
//多种请求
Route::match(['get','post'], '/', function () {
    return view('welcome');
});
//任意请求
Route::any('/', function () {
    return view('welcome');
});
//路由组前缀
Route::prefix('admin')->group(function(){
    Route::get('users', function () {
        return view('welcome');
    });
    Route::get('orders', function () {
        return view('welcome');
    });
});
*/

//COMP5047
Route::POST('/comp5047', 'COMP5047Controller@getJson');
Route::GET('/comp5047/dashboard', 'COMP5047Controller@index');
Route::GET('/comp5047/getData', 'COMP5047Controller@getData');
Route::GET('/comp5047/setting', 'COMP5047Controller@setting');
Route::POST('/comp5047/update', 'COMP5047Controller@update');

// 登陆控制器
Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/exit', 'LoginController@exit');
// 主页控制器
Route::get('/home', 'HomeController@home');
// 部门管理
Route::resource('/department', 'DepartmentController');
// 岗位管理
Route::resource('/position', 'PositionController');
// 用户管理
Route::resource('/user', 'UserController');
