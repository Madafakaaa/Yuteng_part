<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DepartmentStudentController extends Controller
{
    /**
     * 显示所有学生记录
     * URL: GET /student
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 学生名称
     * @param  $request->input('filter2'): 学生校区
     * @param  $request->input('filter3'): 学生年级
     * @param  $request->input('filter4'): 学生学校
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'student.student_follower', '=', 'user.user_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 1)
                  ->where('student_department', Session::get('user_department'))
                  ->where('student_status', 1);
        // 添加筛选条件
        // 学生名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 学生学校
        if ($request->filled('filter4')) {
            $rows = $rows->where('student_school', '=', $request->input('filter4'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('student_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、学校信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();

        // 返回列表视图
        return view('departmentStudent/index', ['rows' => $rows,
                                              'currentPage' => $currentPage,
                                              'totalPage' => $totalPage,
                                              'startIndex' => $offset,
                                              'request' => $request,
                                              'totalNum' => $totalNum,
                                              'filter_departments' => $filter_departments,
                                              'filter_grades' => $filter_grades,
                                              'filter_schools' => $filter_schools]);
    }

    /**
     * 删除学生
     * URL: DELETE /student/{id}
     * @param  int  $student_id
     */
    public function destroy($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student_name = DB::table('student')->where('student_id', $student_id)->value('student_name');
        // 删除数据
        try{
            DB::table('student')->where('student_id', $student_id)->update(['student_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/departmentStudent")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '学生删除失败',
                                                         'message' => '学生删除失败，请联系系统管理员']);
        }
        // 返回学生列表
        return redirect("/departmentStudent")->with(['notify' => true,
                                                     'type' => 'success',
                                                     'title' => '学生删除成功',
                                                     'message' => '学生名称: '.$student_name]);
    }
}
