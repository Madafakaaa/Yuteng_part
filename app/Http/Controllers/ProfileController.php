<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ProfileController extends Controller
{

    /**
     * 显示个人信息
     * URL: GET /class/{id}
     * @param  int  $class_id
     */
    public function show(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', Session::get('user_id'))
                  ->first();

        return view('profile/profile', ['user' => $user]);
    }

    /**
     * 修改个人密码
     * URL: POST /user/{user_id}/password
     * @param  Request  $request
     * @param  $request->input('input1'): 原密码
     * @param  $request->input('input2'): 新密码
     * @param  $request->input('input3'): 新密码确认
     * @param  int  $user_id
     */
    public function passwordUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_id = decode($request->input('id'),'user_id');
        $user = DB::table('user')->where('user_id', Session::get('user_id'))->first();
         // 获取表单输入
        $password_old = $request->input('input1');
        $password_new = $request->input('input2');
        $password_confirmed = $request->input('input3');
        if($user->user_password!=$password_old){
            return redirect("/profile")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '密码修改失败',
                           'message' => '密码修改失败，原密码错误，请重新输入信息！']);
        }
        if($password_new!=$password_confirmed){
            return redirect("/profile")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '密码修改失败',
                           'message' => '密码修改失败，新密码两次输入不同，请重新输入信息！']);
        }
        // 更新数据库
        try{
            DB::table('user')
              ->where('user_id', Session::get('user_id'))
              ->update(['user_password' => $password_new]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/profile")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '密码修改失败',
                           'message' => '密码修改失败，请联系系统管理员！']);
        }
        return redirect("/profile")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '密码修改成功',
                       'message' => '密码修改成功！']);
    }

}
