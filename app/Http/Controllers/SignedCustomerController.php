<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SignedCustomerController extends Controller
{

    /**
     * 显示全部客户记录
     * URL: GET /index
     * @param  Request  $request
     * @param  $request->input('page'): 页数
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
		          ->join('contract', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1)
                  ->where('user_id', Session::get('user_id'));
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_grade', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('signedCustomer/index', ['rows' => $rows,
                                       'currentPage' => $currentPage,
                                       'totalPage' => $totalPage,
                                       'startIndex' => $offset,
                                       'request' => $request,
                                       'totalNum' => $totalNum,
                                       'filter_departments' => $filter_departments,
                                       'filter_grades' => $filter_grades]);
    }

}
