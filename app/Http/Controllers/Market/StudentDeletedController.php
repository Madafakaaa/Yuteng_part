<?php
namespace App\Http\Controllers\Market;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class StudentDeletedController extends Controller
{
    /**
     * 离校学生管理视图
     * URL: GET /market/student/deleted
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function studentDeleted(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                  ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                  ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                  ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->whereIn('student_department', $department_access)
                  ->where('student_status', 0);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->select('student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'student.student_gender AS student_gender',
                              'student.student_guardian AS student_guardian',
                              'student.student_guardian_relationship AS student_guardian_relationship',
                              'student.student_phone AS student_phone',
                              'student.student_follow_level AS student_follow_level',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('market/studentDeleted/studentDeleted', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 离校学生恢复
     * URL: GET /market/student/deleted/restore
     * @param  int  $student_id
     */
    public function studentDeletedRestore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = decode($request->input('id'), 'student_id');
        // 更新数据
        try{
            DB::table('student')->where('student_id', $student_id)->update(['student_status' => 1]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/market/student/deleted")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '学生恢复失败',
                             'message' => '学生恢复失败，错误码:207']);
        }
        // 返回岗位列表
        return redirect("/market/student/deleted")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '学生恢复成功',
                         'message' => '学生恢复成功！']);
    }

    /**
     * 离校学生删除
     * URL: DELETE /market/student/deleted/delete
     * @param  int  $student_id
     */
    public function studentDeletedDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取student_id
        $request_ids=$request->input('id');
        $student_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $student_ids[]=decode($request_id, 'student_id');
            }
        }else{
            $student_ids[]=decode($request_ids, 'student_id');
        }
        // 删除数据
        try{
            foreach ($student_ids as $student_id){
                DB::table('student')
                  ->where('student_id', $student_id)
                  ->delete();
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/market/student/deleted")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '学生删除失败',
                         'message' => '学生删除失败，错误码:208']);
        }
        // 返回课程列表
        return redirect("/market/student/deleted")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '学生删除成功',
                       'message' => '学生删除成功!']);
    }

}
