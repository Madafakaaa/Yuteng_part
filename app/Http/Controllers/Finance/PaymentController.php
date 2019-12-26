<?php
namespace App\Http\Controllers\Finance;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class PaymentController extends Controller
{
    /**
     * 显示所有购课记录
     * URL: GET /payment
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 课程
     * @param  $request->input('filter4'): 年级
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('payment')
                  ->join('student', 'payment.payment_student', '=', 'student.student_id')
                  ->join('course', 'payment.payment_course', '=', 'course.course_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'payment.payment_createuser', '=', 'user.user_id');
        // 添加筛选条件
        // 购课校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_department', '=', $request->input('filter1'));
        }
        // 购课学生
        if ($request->filled('filter2')) {
            $rows = $rows->where('payment_student', '=', $request->input('filter2'));
        }
        // 购买课程
        if ($request->filled('filter3')) {
            $rows = $rows->where('payment_course', '=', $request->input('filter3'));
        }
        // 课程年级
        if ($request->filled('filter5')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter4'));
        }

        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($rows->count(), $request, 20);

        // 排序并获取数据对象
        $rows = $rows->where('payment_status', 1)
                     ->orderBy('payment_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();

        // 返回列表视图
        return view('finance/payment/index', ['rows' => $rows,
                                              'currentPage' => $currentPage,
                                              'totalPage' => $totalPage,
                                              'startIndex' => $offset,
                                              'request' => $request,
                                              'filter_departments' => $filter_departments,
                                              'filter_students' => $filter_students,
                                              'filter_courses' => $filter_courses,
                                              'filter_grades' => $filter_grades]);
    }

    /**
     * 创建新购课页面
     * URL: GET /payment/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生信息
        $students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        return view('finance/payment/create', ['students' => $students]);
    }

    /**
     * 创建新购课页面2
     * URL: GET /payment/create
     */
    public function create_second(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_id = $request->input('input1');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取课程信息
        $courses = DB::table('course')
                     ->whereIn('course_grade', [0, $student->student_grade])
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_end', '>=', date('Y-m-d'))
                     ->where('course_status', 1)
                     ->orderBy('course_createtime', 'asc')
                     ->get();
        return view('finance/payment/create_second', ['student' => $student,
                                                      'courses' => $courses]);
    }

    /**
     * 创建新购课提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 购课学生
     * @param  $request->input('input2'): 购买课程
     * @param  $request->input('input3'): 课时单价
     * @param  $request->input('input4'): 购买课时
     * @param  $request->input('input5'): 购课金额
     * @param  $request->input('input6'): 购课时间
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $payment_student = $request->input('input1');
        $course = explode(",", $request->input('input2'));
	    $payment_course = $course[0];
        $payment_unit_price = $request->input('input3');
        $payment_amount = $request->input('input4');
        $payment_price = $request->input('input5');
        $payment_date = $request->input('input6');
        // 获取当前用户ID
        $payment_createuser = Session::get('user_id');
        // 插入数据库
        try{
            // 插入Payment表
            DB::table('payment')->insert(
                ['payment_student' => $payment_student,
                 'payment_course' => $payment_course,
                 'payment_unit_price' => $payment_unit_price,
                 'payment_amount' => $payment_amount,
                 'payment_price' => $payment_price,
                 'payment_date' => $payment_date,
                 'payment_createuser' => $payment_createuser]
            );
            // 检查是否已经购买过此课程
            $hour_num = DB::table('hour')
                          ->where('hour_student', $payment_student)
                          ->where('hour_course', $payment_course)
                          ->count();
            if($hour_num==0){//没有记录，创建新纪录
                // 插入Hour表
                DB::table('hour')->insert(
                    ['hour_student' => $payment_student,
                     'hour_course' => $payment_course,
                     'hour_remain' => $payment_amount,
                     'hour_used' => 0]
                );
            }else{
                // 更新Hour表
                DB::table('hour')
                  ->where('hour_student', $payment_student)
                  ->where('hour_course', $payment_course)
                  ->increment('hour_remain', $payment_amount);
            }
        }
        // 捕获异常
        catch(Exception $e){
            return $e;
        }
        // 获取学生、课程名称
        $student_name = DB::table('student')
                          ->where('student_id', $payment_student)
                          ->value('student_name');
        $course_name = DB::table('course')
                         ->where('course_id', $payment_course)
                         ->value('course_name');
        // 返回购课列表
        return redirect()->action('Finance\PaymentController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '购课添加成功',
                                 'message' => '购课学生: '.$student_name.', 购买课程: '.$course_name.",购买课时：".$payment_amount."课时。"]);
    }

    /**
     * 删除购课
     * URL: DELETE /payment/{id}
     * @param  int  $payment_id
     */
    public function destroy($payment_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取Payment信息
        $payment = DB::table('payment')
                     ->where('payment_id', $payment_id)
                     ->first();
        // 获取Hour信息
        $hour = DB::table('hour')
                  ->where('hour_student', $payment->payment_student)
                  ->where('hour_course', $payment->payment_course)
                  ->first();
        if($hour->hour_remain>=$payment->payment_amount){//剩余课时足够
            try{
                // 更新Hour表
                DB::table('hour')
                  ->where('hour_student', $payment->payment_student)
                  ->where('hour_course', $payment->payment_course)
                  ->decrement('hour_remain', $payment->payment_amount);
                // 删除无效Hour数据
                DB::table('hour')
                  ->where('hour_remain', 0)
                  ->where('hour_used', 0)
                  ->delete();
                // 删除Payment数据
                DB::table('payment')->where('payment_id', $payment_id)->update(['payment_status' => 0]);
            }
            // 捕获异常
            catch(Exception $e){
                return redirect()->action('Finance\PaymentController@index')
                                 ->with(['notify' => true,
                                         'type' => 'danger',
                                         'title' => '购课删除失败',
                                         'message' => '购课删除失败，请联系系统管理员']);
            }
            // 返回购课列表
            return redirect()->action('Finance\PaymentController@index')
                             ->with(['notify' => true,
                                     'type' => 'success',
                                     'title' => '购课删除成功',
                                     'message' => '购课删除成功']);
        }else{
            return redirect()->action('Finance\PaymentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '购课删除失败',
                                     'message' => '学生剩余课时不足，购课删除失败。']);
        }
    }
}
