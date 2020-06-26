<?php
namespace App\Http\Controllers\Market;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class MyRefundController extends Controller
{
    /**
     * 我的退费视图
     * URL: GET /market/myRefund/
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function myRefund(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('refund')
                  ->join('student', 'refund.refund_student', '=', 'student.student_id')
                  ->join('course', 'refund.refund_course', '=', 'course.course_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('user AS createuser', 'refund.refund_createuser', '=', 'createuser.user_id')
                  ->join('position AS createuser_position', 'createuser.user_position', '=', 'createuser_position.position_id')
                  ->leftJoin('user AS checked_user', 'refund.refund_checked_user', '=', 'checked_user.user_id')
                  ->leftJoin('position AS checked_user_position', 'checked_user.user_position', '=', 'checked_user_position.position_id')
                  ->where('refund_type', '=', 0)
                  ->where('refund_createuser', Session::get('user_id'));

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
        $rows = $rows->select('refund.refund_id AS refund_id',
                              'refund.refund_contract AS refund_contract',
                              'refund.refund_total_hour AS refund_total_hour',
                              'refund.refund_fine AS refund_fine',
                              'refund.refund_actual_amount AS refund_actual_amount',
                              'refund.refund_date AS refund_date',
                              'refund.refund_checked AS refund_checked',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'department.department_name AS department_name',
                              'course.course_name AS course_name',
                              'createuser.user_name AS createuser_name',
                              'createuser_position.position_name AS createuser_position_name',
                              'checked_user.user_name AS checked_user_name',
                              'checked_user_position.position_name AS checked_user_position_name')
                     ->orderBy('refund_date', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('market/myRefund/myRefund', ['rows' => $rows,
                                                 'currentPage' => $currentPage,
                                                 'totalPage' => $totalPage,
                                                 'startIndex' => $offset,
                                                 'request' => $request,
                                                 'totalNum' => $totalNum,
                                                 'filter_status' => $filter_status,
                                                 'filter_departments' => $filter_departments,
                                                 'filter_students' => $filter_students,
                                                 'filter_grades' => $filter_grades]);
    }

}
