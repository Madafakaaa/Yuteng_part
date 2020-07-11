<?php
namespace App\Http\Controllers\Company;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DepartmentController extends Controller
{
    /**
     * 显示所有校区记录
     * URL: GET /company/department
     */
    public function department(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据
        $rows = DB::table('department')->where('department_status', 1);
        // 添加筛选条件
        // 校区名称
        if($request->filled('filter1')) {
            $rows = $rows->where('department_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 10);
        // 排序并获取数据对象
        $rows = $rows->orderBy('department_id', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 返回列表视图
        return view('/company/department/department', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum]);
    }

    /**
     * 创建新校区页面
     * URL: GET /company/department/create
     */
    public function departmentCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('/company/department/departmentCreate');
    }

    /**
     * 创建新校区提交数据库
     * URL: POST /company/department/store
     */
    public function departmentStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $department_name = $request->input('input1');
        $department_location = $request->input('input2');
        $department_phone1 = $request->input('input3');
        if($request->filled('input4')) {
            $department_phone2 = $request->input('input4');
        }else{
            $department_phone2 = "";
        }
        // 插入数据库
        try{
           DB::table('department')->insert(
                ['department_name' => $department_name,
                 'department_location' => $department_location,
                 'department_phone1' => $department_phone1,
                 'department_phone2' => $department_phone2,
                 'department_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/department/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '校区添加失败',
                           'message' => '校区名已存在，错误码:101']);
        }
        // 返回校区列表
        return redirect("/company/department")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '校区添加成功',
                       'message' => '校区添加成功!']);
    }

    /**
     * 修改单个校区
     * URL: GET /company/department/edit
     */
    public function departmentEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取id
        $department_id = decode($request->input('id'), 'department_id');
        // 获取数据信息
        $department = DB::table('department')
                        ->where('department_id', $department_id)
                        ->first();
        return view('/company/department/departmentEdit', ['department' => $department]);
    }

    /**
     * 修改校区提交数据库
     * URL: POST /company/department/update
     */
    public function departmentUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取department_id
        $department_id = decode($request->input('id'), 'department_id');
         // 获取表单输入
        $department_name = $request->input('input1');
        $department_location = $request->input('input2');
        $department_phone1 = $request->input('input3');
        if($request->filled('input4')) {
            $department_phone2 = $request->input('input4');
        }else{
            $department_phone2 = "";
        }
        // 更新数据库
        try{
            DB::table('department')
              ->where('department_id', $department_id)
              ->update(['department_name' => $department_name,
                        'department_location' => $department_location,
                        'department_phone1' => $department_phone1,
                        'department_phone2' => $department_phone2]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/department/edit?department_id={$request->input('id')}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '校区修改失败',
                           'message' => '校区修改失败，错误码:102']);
        }
        return redirect("/company/department")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '校区修改成功',
                       'message' => '校区修改成功!']);
    }

    /**
     * 删除校区
     * URL: DELETE /company/department/delete
     */
    public function departmentDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取department_id
        $request_ids=$request->input('id');
        $department_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $department_ids[]=decode($request_id, 'department_id');
            }
        }else{
            $department_ids[]=decode($request_ids, 'department_id');
        }
        // 删除数据
        try{
            foreach ($department_ids as $department_id){
                DB::table('department')
                  ->where('department_id', $department_id)
                  ->update(['department_status' => 0]);
                // 删除相关用户权限
                DB::table('user_department')
                  ->where('user_department_department', $department_id)
                  ->delete();
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/department")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '校区删除失败',
                           'message' => '校区删除失败，错误码:103']);
        }
        // 返回校区列表
        return redirect("/company/department")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '校区删除成功',
                       'message' => '校区删除成功!']);
    }

}
