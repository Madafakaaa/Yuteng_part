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
     * @param  $request->input('filter1'): 购课名称
     * @param  $request->input('filter2'): 购课校区
     * @param  $request->input('filter3'): 购课年级
     * @param  $request->input('filter4'): 购课学校
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_payment = Session::get('user_payment');
        // 获取数据库信息
        // 获取总数据数
        $totalRecord = DB::table('payment')
                         ->join('student', 'payment.payment_student', '=', 'student.student_id')
                         ->join('course', 'payment.payment_course', '=', 'course.course_id');
        // 添加筛选条件
        // 购课校区
        if ($request->has('filter1')) {
            if($request->input('filter1')!=''){
                $totalRecord = $totalRecord->where('student_department', '=', $request->input('filter1'));
            }
        }
        // 购课学生
        if ($request->has('filter2')) {
            if($request->input('filter2')!=''){
                $totalRecord = $totalRecord->where('payment_student', '=', $request->input('filter2'));
            }
        }
        // 购买课程
        if ($request->has('filter3')) {
            if($request->input('filter3')!=''){
                $totalRecord = $totalRecord->where('payment_course', '=', $request->input('filter3'));
            }
        }
        // 课程年级
        if ($request->has('filter4')) {
            if($request->input('filter4')!=''){
                $totalRecord = $totalRecord->where('student_grade', '=', $request->input('filter4'));
            }
        }
        $totalRecord = $totalRecord->count();
        // 设置每页数据(20数据/页)
        $rowPerPage = 20;
        // 获取总页数
        if($totalRecord==0){
            $totalPage = 1;
        }else{
            $totalPage = ceil($totalRecord/$rowPerPage);
        }
        // 获取当前页数
        if ($request->has('page')) {
            $currentPage = $request->input('page');
            if($currentPage<1)
                $currentPage = 1;
            if($currentPage>$totalPage)
                $currentPage = $totalPage;
        }else{
            $currentPage = 1;
        }
        // 获取数据
        $offset = ($currentPage-1)*$rowPerPage;
        $rows = DB::table('payment')
                  ->join('student', 'payment.payment_student', '=', 'student.student_id')
                  ->join('course', 'payment.payment_course', '=', 'course.course_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id');
        // 添加筛选条件
        // 购课校区
        if ($request->has('filter1')) {
            if($request->input('filter1')!=''){
                $rows = $rows->where('student_department', '=', $request->input('filter1'));
            }
        }
        // 购课学生
        if ($request->has('filter2')) {
            if($request->input('filter2')!=''){
                $rows = $rows->where('payment_student', '=', $request->input('filter2'));
            }
        }
        // 购买课程
        if ($request->has('filter3')) {
            if($request->input('filter3')!=''){
                $rows = $rows->where('payment_course', '=', $request->input('filter3'));
            }
        }
        // 课程年级
        if ($request->has('filter4')) {
            if($request->input('filter4')!=''){
                $rows = $rows->where('student_grade', '=', $request->input('filter4'));
            }
        }
        $rows = $rows->orderBy('payment_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        return view('finance/payment/index', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => ($currentPage-1)*$rowPerPage,
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
        // 获取校区、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();
        return view('finance/payment/create', ['departments' => $departments,
                                                'grades' => $grades,
                                                'schools' => $schools]);
    }

    /**
     * 创建新购课提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 购课姓名
     * @param  $request->input('input2'): 购课校区
     * @param  $request->input('input3'): 购课年级
     * @param  $request->input('input4'): 购课性别
     * @param  $request->input('input5'): 购课生日
     * @param  $request->input('input6'): 购课学校
     * @param  $request->input('input7'): 联系电话
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $payment_name = $request->input('input1');
        $payment_department = $request->input('input2');
        $payment_grade = $request->input('input3');
        $payment_gender = $request->input('input4');
        $payment_birthday = $request->input('input5');
        $payment_school = $request->input('input6');
        $payment_phone = $request->input('input7');
        // 获取当前用户ID
        $payment_createuser = Session::get('user_id');
        // 生成新购课ID
        $payment_num = DB::table('payment')
                         ->where('payment_department', $payment_department)
                         ->whereYear('payment_createtime', date('Y'))
                         ->whereMonth('payment_createtime', date('m'))
                         ->count()+1;
        $payment_id = "S".substr(date('Ym'),2).sprintf("%02d", $payment_department).sprintf("%03d", $payment_num);
        // 插入数据库
        try{
            DB::table('payment')->insert(
                ['payment_id' => $payment_id,
                 'payment_name' => $payment_name,
                 'payment_department' => $payment_department,
                 'payment_grade' => $payment_grade,
                 'payment_gender' => $payment_gender,
                 'payment_birthday' => $payment_birthday,
                 'payment_school' => $payment_school,
                 'payment_phone' => $payment_phone,
                 'payment_createuser' => $payment_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return $e;
        }
        // 返回购课列表
        return redirect()->action('Finance\PaymentController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '购课添加成功',
                                 'message' => '购课名称: '.$payment_name.', 购课学号: '.$payment_id]);
    }

    /**
     * 显示单个购课详细信息
     * URL: GET /payment/{id}
     * @param  int  $payment_id
     */
    public function show($payment_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $payment = DB::table('payment')
                     ->join('department', 'payment.payment_department', '=', 'department.department_id')
                     ->join('grade', 'payment.payment_grade', '=', 'grade.grade_id')
                     ->leftJoin('school', 'payment.payment_school', '=', 'school.school_id')
                     ->where('payment_id', $payment_id)
                     ->get();
        if($payment->count()!==1){
            // 未获取到数据
            return redirect()->action('Finance\PaymentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '购课显示失败',
                                     'message' => '购课显示失败，请联系系统管理员']);
        }
        $payment = $payment[0];
        return view('finance/payment/show', ['payment' => $payment]);
    }

    /**
     * 修改单个购课
     * URL: GET /payment/{id}/edit
     * @param  int  $payment_id
     */
    public function edit($payment_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $payment = DB::table('payment')->where('payment_id', $payment_id)->get();
        if($payment->count()!==1){
            // 未获取到数据
            return redirect()->action('Finance\PaymentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '购课显示失败',
                                     'message' => '购课显示失败，请联系系统管理员']);
        }
        $payment = $payment[0];
        // 获取校区、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();
        return view('finance/payment/edit', ['payment' => $payment,
                                             'departments' => $departments,
                                             'grades' => $grades,
                                             'schools' => $schools]);
    }

    /**
     * 修改新购课提交数据库
     * URL: PUT /payment/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 购课姓名
     * @param  $request->input('input2'): 购课校区
     * @param  $request->input('input3'): 购课年级
     * @param  $request->input('input4'): 购课性别
     * @param  $request->input('input5'): 购课生日
     * @param  $request->input('input6'): 购课学校
     * @param  $request->input('input7'): 联系电话
     * @param  int  $payment_id
     */
    public function update(Request $request, $payment_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $payment_name = $request->input('input1');
        $payment_department = $request->input('input2');
        $payment_grade = $request->input('input3');
        $payment_gender = $request->input('input4');
        $payment_birthday = $request->input('input5');
        $payment_school = $request->input('input6');
        $payment_phone = $request->input('input7');
        // 更新数据库
        try{
            DB::table('payment')
              ->where('payment_id', $payment_id)
              ->update(['payment_name' => $payment_name,
                        'payment_department' => $payment_department,
                        'payment_grade' => $payment_grade,
                        'payment_gender' => $payment_gender,
                        'payment_birthday' => $payment_birthday,
                        'payment_school' => $payment_school,
                        'payment_phone' => $payment_phone]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/payment/{$payment_id}/edit")->with(['notify' => true,
                                                                  'type' => 'danger',
                                                                  'title' => '购课修改失败',
                                                                  'message' => '购课修改失败，请重新输入信息']);
        }
        return redirect("/payment")->with(['notify' => true,
                                           'type' => 'success',
                                           'title' => '购课修改成功',
                                           'message' => '购课修改成功，购课名称: '.$payment_name]);
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
        // 获取数据信息
        $payment_name = DB::table('payment')->where('payment_id', $payment_id)->value('payment_name');
        // 删除数据
        try{
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
                                 'message' => '购课名称: '.$payment_name]);
    }
}
