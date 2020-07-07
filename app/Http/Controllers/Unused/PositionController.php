<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class PositionController extends Controller
{

    /**
     * 创建新岗位页面
     * URL: GET /company/position/create
     */
    public function positionCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取部门信息
        $sections = DB::table('section')->where('section_status', 1)->get();
        return view('company/positionCreate', ['sections' => $sections]);
    }

    /**
     * 创建新岗位提交数据库
     * URL: POST /company/position/create
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 部门
     * @param  $request->input('input3'): 等级
     */
    public function positionStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $position_name = $request->input('input1');
        $position_section = $request->input('input2');
        $position_level = $request->input('input3');
        // 获取当前用户ID
        $position_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('position')->insert(
                ['position_name' => $position_name,
                 'position_section' => $position_section,
                 'position_level' => $position_level,
                 'position_createuser' => $position_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/position/create")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '岗位添加失败',
                             'message' => '岗位添加失败，请重新输入信息']);
        }
        // 返回岗位列表
        return redirect("/company/section")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '岗位添加成功',
                       'message' => '岗位名称: '.$position_name]);
    }

    /**
     * 修改单个岗位
     * URL: GET /company/position/{id}/edit
     * @param  int  $position_id
     */
    public function positionEdit($position_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $position = DB::table('position')->where('position_id', $position_id)->first();
        // 获取部门信息
        $sections = DB::table('section')->where('section_status', 1)->get();
        return view('company/positionEdit', ['sections' => $sections, 'position' => $position]);
    }

    /**
     * 修改新岗位提交数据库
     * URL: PUT /company/position/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 部门
     * @param  $request->input('input3'): 等级
     * @param  int  $position_id
     */
    public function positionUpdate(Request $request, $position_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $position_name = $request->input('input1');
        $position_section = $request->input('input2');
        $position_level = $request->input('input3');
        // 更新数据库
        try{
            DB::table('position')
                    ->where('position_id', $position_id)
                    ->update(['position_name' => $position_name,
                              'position_section' => $position_section,
                              'position_level' => $position_level]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/position/{$position_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '岗位修改失败',
                           'message' => '岗位修改失败，请重新输入信息']);
        }
        return redirect("/company/section")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '岗位修改成功',
                       'message' => '岗位修改成功，岗位名称: '.$position_name]);
    }

    /**
     * 删除岗位
     * URL: DELETE /company/position/{id}
     * @param  int  $position_id
     */
    public function positionDelete($position_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $position_name = DB::table('position')->where('position_id', $position_id)->value('position_name');
        // 删除数据
        try{
            DB::table('position')->where('position_id', $position_id)->update(['position_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/section")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '岗位删除失败',
                             'message' => '岗位删除失败，请联系系统管理员']);
        }
        // 返回岗位列表
        return redirect("/company/section")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '岗位删除成功',
                         'message' => '岗位名称: '.$position_name]);
    }
}