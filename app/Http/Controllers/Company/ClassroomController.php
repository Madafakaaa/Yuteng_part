<?php
namespace App\Http\Controllers\Company;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ClassroomController extends Controller
{
    /**
     * 显示所有教室记录
     * URL: GET /company/classroom
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 教室名称
     * @param  $request->input('filter2'): 教室校区
     * @param  $request->input('filter2'): 教室类型
     */
    public function classroom(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('classroom')
                  ->join('department', 'classroom.classroom_department', '=', 'department.department_id')
                  ->whereIn('classroom_department', $department_access)
                  ->where('classroom_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 教室名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('classroom_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 所属校区
        if($request->filled('filter2')){
            $rows = $rows->where('classroom_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 教室类型
        if($request->filled('filter3')){
            $rows = $rows->where('classroom_type', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 10);

        // 排序并获取数据对象
        $rows = $rows->orderBy('classroom_department', 'asc')
                     ->orderBy('classroom_id', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('company/classroom/classroom', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新教室页面
     * URL: GET /company/classroom/create
     */
    public function classroomCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        return view('company/classroom/classroomCreate', ['departments' => $departments]);
    }

    /**
     * 创建新教室提交数据库
     * URL: POST /company/classroom/create
     * @param  Request  $request
     * @param  $request->input('input1'): 教室名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 容纳人数
     * @param  $request->input('input4'): 教师类型
     */
    public function classroomStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $classroom_name = $request->input('input1');
        $classroom_department = $request->input('input2');
        $classroom_student_num = $request->input('input3');
        $classroom_type = $request->input('input4');
        // 获取当前用户ID
        $classroom_createuser = Session::get('user_id');
        // 插入数据库
        try{
           DB::table('classroom')->insert(
                ['classroom_name' => $classroom_name,
                 'classroom_department' => $classroom_department,
                 'classroom_student_num' => $classroom_student_num,
                 'classroom_type' => $classroom_type,
                 'classroom_createuser' => $classroom_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/classroom/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '教室添加失败',
                           'message' => '教室添加失败，请重新输入信息']);
        }
        // 返回教室列表
        return redirect("/company/classroom")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '教室添加成功',
                         'message' => '教室名称: '.$classroom_name]);
    }

    /**
     * 修改单个教室
     * URL: GET /company/classroom/{id}
     * @param  int  $classroom_id
     */
    public function classroomEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取classroom_id
        $classroom_id = decode($request->input('id'), 'classroom_id');
        // 获取数据信息
        $classroom = DB::table('classroom')
                    ->where('classroom_id', $classroom_id)
                    ->first();
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        return view('company/classroom/classroomEdit', ['classroom' => $classroom, 'departments' => $departments]);
    }

    /**
     * 修改新教室提交数据库
     * URL: PUT /company/classroom/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 教室名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 容纳人数
     * @param  $request->input('input4'): 教师类型
     * @param  int  $classroom_id
     */
    public function classroomUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取classroom_id
        $classroom_id = decode($request->input('id'), 'classroom_id');
         // 获取表单输入
        $classroom_name = $request->input('input1');
        $classroom_department = $request->input('input2');
        $classroom_student_num = $request->input('input3');
        $classroom_type = $request->input('input4');
        // 更新数据库
        try{
            DB::table('classroom')
              ->where('classroom_id', $classroom_id)
              ->update(['classroom_name' => $classroom_name,
                        'classroom_department' => $classroom_department,
                        'classroom_student_num' => $classroom_student_num,
                        'classroom_type' => $classroom_type]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/classroom/{$classroom_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '教室修改失败',
                           'message' => '教室修改失败，请重新输入信息']);
        }
        return redirect("/company/classroom")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '教室修改成功',
                       'message' => '教室修改成功，教室名称: '.$classroom_name]);
    }

    /**
     * 删除教室
     * URL: DELETE /company/classroom/{id}
     * @param  int  $classroom_id
     */
    public function classroomDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取classroom_id
        $request_ids=$request->input('id');
        $classroom_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $classroom_ids[]=decode($request_id, 'classroom_id');
            }
        }else{
            $classroom_ids[]=decode($request_ids, 'classroom_id');
        }
        // 删除数据
        try{
            foreach ($classroom_ids as $classroom_id){
                DB::table('classroom')
                  ->where('classroom_id', $classroom_id)
                  ->update(['classroom_status' => 0]);
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/classroom")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '教室删除失败',
                         'message' => '教室删除失败，请联系系统管理员']);
        }
        // 返回教室列表
        return redirect("/company/classroom")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '教室删除成功',
                         'message' => '教室删除成功']);
    }


}