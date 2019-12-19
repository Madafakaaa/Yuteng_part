<?php
namespace App\Http\Controllers\Teaching;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class MemberController extends Controller
{

    /**
     * 创建新成员提交数据库
     * URL: POST
     * @param  int  $class_id
     * @param  Request  $request
     * @param  $request->input('input1'): 学生id
     */
    public function add($class_id, Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $member_class = $class_id;
        // 获取表单输入
        $member_student = $request->input('input1');
        // 获取当前用户ID
        $member_createuser = Session::get('user_id');
        // 修改数据库
        try{
            // 插入成员数据
            DB::table('member')->insert(
                ['member_class' => $member_class,
                 'member_student' => $member_student,
                 'member_createuser' => $member_createuser]
            );
            // 增加班级当前人数
            DB::table('class')
              ->where('class_id', $class_id)
              ->increment('class_current_num');
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/class/".$member_class)->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '成员添加失败',
                                                            'message' => '成员已存在，请重新选择学生！']);
        }
        return redirect("/class/".$member_class)->with(['notify' => true,
                                                        'type' => 'success',
                                                        'title' => '成员添加成功',
                                                        'message' => '成员添加成功！']);
    }

    /**
     * 删除成员
     * URL: DELETE /member/{id}
     * @param  int  $class_id
     * @param  Request  $request
     * @param  $request->input('input1'): 学生id
     */
    public function delete($class_id, Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_id = $request->input('input1');
        // 获取数据信息
        $class_name = DB::table('class')->where('class_id', $class_id)->value('class_name');
        $student_name = DB::table('student')->where('student_id', $student_id)->value('student_name');
        // 删除数据
        try{
            // 删除成员数据
            DB::table('member')
              ->where('member_class', $class_id)
              ->where('member_student', $student_id)
              ->delete();
            // 减少班级当前人数
            DB::table('class')
              ->where('class_id', $class_id)
              ->decrement('class_current_num');
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/class/".$class_id)->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '班级成员删除失败',
                                                            'message' => '班级删除失败，请练习系统管理员！']);
        }
        // 返回成员列表
        return redirect("/class/".$class_id)->with(['notify' => true,
                                                        'type' => 'success',
                                                        'title' => '班级成员删除成功',
                                                        'message' => '班级名称: '.$class_name.',学生名称: '.$student_name]);
    }
}
