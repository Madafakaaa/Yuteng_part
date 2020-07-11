<?php
namespace App\Http\Controllers\Company;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SectionController extends Controller
{

    /**
     * 显示所有部门记录
     * URL: GET /company/section
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('section'): 部门
     */
    public function section(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取岗位数据
        $rows = DB::table('position')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('position_status', 1);
        // 添加筛选条件
        // 部门ID
        if ($request->filled('section')) {
            $rows = $rows->where('position.position_section', '=', $request->input('section'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 10);

        // 排序并获取数据对象
        $rows = $rows->orderBy('position_section', 'asc')
                     ->orderBy('position_level', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取部门数据
        $sections = DB::table('section')
                   ->where('section_status', 1)
                   ->orderBy('section_id', 'asc')
                   ->get();

        // 返回列表视图
        return view('company/section/section', ['rows' => $rows,
                                      'sections' => $sections,
                                      'currentPage' => $currentPage,
                                      'totalPage' => $totalPage,
                                      'startIndex' => $offset,
                                      'request' => $request,
                                      'totalNum' => $totalNum]);
    }

    /**
     * 创建新部门页面
     * URL: GET /company/section/create
     */
    public function sectionCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('company/section/sectionCreate');
    }

    /**
     * 创建新部门提交数据库
     * URL: POST /company/section/create
     * @param  Request  $request
     * @param  $request->input('input1'): 部门名称
     */
    public function sectionStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $section_name = $request->input('input1');
        // 获取当前用户ID
        $section_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('section')->insert(
                ['section_name' => $section_name,
                 'section_createuser' => $section_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/section/create")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '部门添加失败',
                             'message' => '部门添加失败，错误码:117']);
        }
        // 返回部门列表
        return redirect("/company/section")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '部门添加成功',
                         'message' => '部门名称: '.$section_name]);
    }

    /**
     * 修改单个部门
     * URL: GET /company/section/{id}/
     * @param  int  $section_id
     */
    public function sectionEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取section_id
        $section_id = decode($request->input('id'), 'section_id');
        // 获取数据信息
        $section = DB::table('section')->where('section_id', $section_id)->first();
        return view('company/section/sectionEdit', ['section' => $section]);
    }

    /**
     * 修改新部门提交数据库
     * URL: PUT /company/section/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 部门名称
     * @param  int  $section_id
     */
    public function sectionUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取section_id
        $section_id = decode($request->input('id'), 'section_id');
         // 获取表单输入
        $section_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('section')
              ->where('section_id', $section_id)
              ->update(['section_name' => $section_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/section/?id={$request->input('id')}")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '部门修改失败',
                          'message' => '部门修改失败，错误码:118']);
        }
        return redirect("/company/section")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '部门修改成功',
                       'message' => '部门修改成功，部门名称: '.$section_name]);
    }

    /**
     * 删除部门
     * URL: DELETE /company/section/{id}
     * @param  int  $section_id
     */
    public function sectionDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取section_id
        $section_id = decode($request->input('id'), 'section_id');
        // 获取数据信息
        $section_name = DB::table('section')->where('section_id', $section_id)->value('section_name');
        // 删除数据
        try{
            DB::table('section')->where('section_id', $section_id)->update(['section_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/section")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '部门删除失败',
                             'message' => '部门删除失败，错误码:119']);
        }
        // 返回部门列表
        return redirect("/company/section")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '部门删除成功',
                         'message' => '部门名称: '.$section_name]);
    }

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
        return view('company/section/positionCreate', ['sections' => $sections]);
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
                             'message' => '岗位添加失败，错误码:120']);
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
    public function positionEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取position_id
        $position_id = decode($request->input('id'), 'position_id');
        // 获取数据信息
        $position = DB::table('position')->where('position_id', $position_id)->first();
        // 获取部门信息
        $sections = DB::table('section')->where('section_status', 1)->get();
        return view('company/section/positionEdit', ['sections' => $sections, 'position' => $position]);
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
    public function positionUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取position_id
        $position_id = decode($request->input('id'), 'position_id');
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
            return redirect("/company/position?id={$request->input('id')}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '岗位修改失败',
                           'message' => '岗位修改失败，错误码:121']);
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
    public function positionDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取position_id
        $position_id = decode($request->input('id'), 'position_id');
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
                             'message' => '岗位删除失败，错误码:122']);
        }
        // 返回岗位列表
        return redirect("/company/section")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '岗位删除成功',
                         'message' => '岗位名称: '.$position_name]);
    }

}
