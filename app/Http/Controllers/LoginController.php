<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class LoginController extends Controller
{

    /**
     * 登录界面
     * URL: GET /
     */
    public function index()
    {
        // 检查登录状态,
        if(Session::has('login')){
            // 已登录，返回主页视图
            return redirect()->action('HomeController@home');
        }else{
            // 未登录，返回登陆视图
            return view('login');
        }
    }

    /**
     * 登录信息验证
     * URL: POST /login
     * @param  Request  $request
     * @param  $request->input('input1'): 用户名
     * @param  $request->input('input2'): 密码
     */
    public function login(Request $request)
    {
        // 获取表单输入
        $request_user_id = $request->input('input1');
        $request_user_password = $request->input('input2');
        // 获取数据库对应用户信息
        $db_user = DB::table('user')
                     ->join('department', 'user.user_department', '=', 'department.department_id')
                     ->join('position', 'user.user_position', '=', 'position.position_id')
                     ->where('user_id', $request_user_id)->get();
        // 未在数据库中获得对应用户数据，返回到主页
        if($db_user->count()!==1){
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '登录系统失败',
                                     'message' => '您的用户名或密码有误，请重新输入']);
        }
        // 获取用户密码
        $db_user = $db_user[0];
        $db_user_password = $db_user->user_password;
        // 表单输入密码与数据库不一致，返回到主页
        if($db_user_password!==$request_user_password){
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '登录系统失败',
                                     'message' => '您的用户名或密码有误，请重新输入']);
        }
        // 注册信息到Session中
        Session::put('login', true);
        Session::put('user_id', $db_user->user_id);
        Session::put('user_name', $db_user->user_name);
        Session::put('user_level', $db_user->position_level);
        Session::put('user_gender', $db_user->user_gender);
        Session::put('user_department', $db_user->user_department);
        Session::put('user_department_name', $db_user->department_name);
        // 返回主界面视图
        return redirect()->action('HomeController@home')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '登陆系统成功',
                                 'message' => '欢迎，'.$db_user->user_name]);
    }

    /**
     * 用户退出系统
     * URL: GET /exit
     */
    public function exit()
    {
        // 清空会话信息
        Session::flush();
        // 返回登陆视图
        return redirect()
               ->action('LoginController@index')
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '退出系统成功',
                       'message' => '',]);
    }
}
