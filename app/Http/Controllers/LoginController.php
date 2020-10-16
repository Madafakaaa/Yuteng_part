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
            return redirect('/home');
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
                     ->join('section', 'position.position_section', '=', 'section.section_id')
                     ->where('user_id', $request_user_id)
                     ->where('user_status', 1)
                     ->get();
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
            return redirect('/')->with(['notify' => true,
                                         'type' => 'danger',
                                         'title' => '登录系统失败',
                                         'message' => '您的用户名或密码有误，请重新输入']);
        }

        // 获取用户校区权限
        $department_access = array();
        $user_departments = DB::table('user_department')
                              ->where('user_department_user', $request_user_id)
                              ->get();
        foreach($user_departments AS $user_department){
            $department_access[] = $user_department->user_department_department;
        }
        // 获取用户页面权限
        $user_accesses = array();
        $db_user_accesses = DB::table('user_access')
                              ->where('user_access_user', $request_user_id)
                              ->get();
        foreach($db_user_accesses AS $db_user_access){
            $user_accesses[] = $db_user_access->user_access_access;
        }
        // 获取用户页面权限
        $access_categories = array();
        $db_access_categories = DB::table('user_access')
                                  ->join('access', 'user_access.user_access_access', '=', 'access.access_url')
                                  ->where('user_access_user', $request_user_id)
                                  ->select('access_category')
                                  ->distinct()
                                  ->get();
        foreach($db_access_categories AS $db_access_category){
            $access_categories[] = $db_access_category->access_category;
        }


        // 获取用户校区主页权限
        $user_dashboards = array();
        $db_user_dashboards = DB::table('user_dashboard')
                                    ->where('user_dashboard_user', $request_user_id)
                                    ->get();
        foreach($db_user_dashboards AS $db_user_dashboard){
            $user_dashboards[] = $db_user_dashboard->user_dashboard_dashboard;
        }
        // 注册信息到Session中
        Session::put('login', true);
        Session::put('user_id', $db_user->user_id);
        Session::put('user_name', $db_user->user_name);
        Session::put('user_section', $db_user->section_name);
        Session::put('user_position', $db_user->position_name);
        Session::put('user_level', $db_user->position_level);
        Session::put('user_gender', $db_user->user_gender);
        Session::put('user_photo', $db_user->user_photo);
        Session::put('user_department', $db_user->user_department);
        Session::put('user_department_name', $db_user->department_name);
        Session::put('user_access_self', $db_user->user_access_self);
        Session::put('department_access', $department_access);
        Session::put('user_accesses', $user_accesses);
        Session::put('access_categories', $access_categories);
        Session::put('user_dashboards', $user_dashboards);
        // 返回主界面视图
        return redirect('/home')->with(['notify' => true,
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
        return redirect('/')->with(['notify' => true,
                                    'type' => 'success',
                                    'title' => '退出系统成功',
                                    'message' => '',]);
    }

}
