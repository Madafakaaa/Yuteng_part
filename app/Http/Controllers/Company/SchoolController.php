<?php
namespace App\Http\Controllers\Company;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SchoolController extends Controller
{

    /**
     * 显示所有学校记录
     * URL: GET /company/school
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 学校名称
     * @param  $request->input('filter2'): 学校校区
     */
    public function school(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('school')
                  ->join('department', 'school.school_department', '=', 'department.department_id')
                  ->whereIn('school_department', $department_access)
                  ->where('school_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学校名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('school_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 所属校区
        if($request->filled('filter2')){
            $rows = $rows->where('school_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('school_department', 'asc')
                     ->orderBy('school_id', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('/company/school/school', ['rows' => $rows,
                                        'currentPage' => $currentPage,
                                        'totalPage' => $totalPage,
                                        'startIndex' => $offset,
                                        'request' => $request,
                                        'totalNum' => $totalNum,
                                        'filter_status' => $filter_status,
                                        'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新学校页面
     * URL: GET /company/school/create
     */
    public function schoolCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        return view('company/school/schoolCreate', ['departments' => $departments]);
    }

    /**
     * 创建新学校提交数据库
     * URL: POST /company/school/create
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 类型
     * @param  $request->input('input4'): 地址
     */
    public function schoolStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $school_name = $request->input('input1');
        $school_department = $request->input('input2');
        $school_type = $request->input('input3');
        $school_location = $request->input('input4');
        // 获取当前用户ID
        $school_createuser = Session::get('user_id');
        // 插入数据库
        try{
           DB::table('school')->insert(
                ['school_name' => $school_name,
                 'school_department' => $school_department,
                 'school_location' => $school_location,
                 'school_type' => $school_type,
                 'school_createuser' => $school_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/school/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '大区添加失败',
                           'message' => '大区添加失败，请重新输入信息']);
        }
        // 返回学校列表
        return redirect("/company/school")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '大区添加成功',
                       'message' => '大区名称: '.$school_name]);
    }

    /**
     * 修改单个学校
     * URL: GET /company/school/{id}
     * @param  int  $school_id
     */
    public function schoolEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取school_id
        $school_id = decode($request->input('id'), 'school_id');
        // 获取数据信息
        $school = DB::table('school')
                    ->where('school_id', $school_id)
                    ->first();
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        return view('company/school/schoolEdit', ['school' => $school, 'departments' => $departments]);
    }

    /**
     * 修改新学校提交数据库
     * URL: PUT /company/school/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 类型
     * @param  $request->input('input4'): 地址
     * @param  int  $school_id
     */
    public function schoolUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取school_id
        $school_id = decode($request->input('id'), 'school_id');
        // 获取表单输入
        $school_name = $request->input('input1');
        $school_department = $request->input('input2');
        $school_type = $request->input('input3');
        $school_location = $request->input('input4');
        // 更新数据库
        try{
            DB::table('school')
              ->where('school_id', $school_id)
              ->update(['school_name' => $school_name,
                        'school_department' => $school_department,
                        'school_location' => $school_location,
                        'school_type' => $school_type]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("company/school/edit?id={encode($school_id, 'school_id')}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '大区修改失败',
                           'message' => '大区修改失败，请重新输入信息']);
        }
        return redirect("/company/school")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '大区修改成功',
                       'message' => '大区修改成功，学校名称: '.$school_name]);
    }

    /**
     * 删除学校
     * URL: DELETE /company/school/{id}
     * @param  int  $school_id
     */
    public function schoolDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取school_id
        $request_ids=$request->input('id');
        $school_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $school_ids[]=decode($request_id, 'school_id');
            }
        }else{
            $school_ids[]=decode($request_ids, 'school_id');
        }
        // 删除数据
        try{
            foreach ($school_ids as $school_id){
                DB::table('school')
                  ->where('school_id', $school_id)
                  ->update(['school_status' => 0]);
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/school")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '大区删除失败',
                         'message' => '大区删除失败，请联系系统管理员']);
        }
        // 返回学校列表
        return redirect("/company/school")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '大区删除成功',
                       'message' => '大区删除成功']);
    }


}
