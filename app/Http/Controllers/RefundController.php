<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class RefundController extends Controller
{
    /**
     * 显示所有退费记录
     * URL: GET /refund
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('refund')
                  ->join('student', 'refund.refund_student', '=', 'student.student_id')
                  ->join('course', 'refund.refund_course', '=', 'course.course_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'refund.refund_createuser', '=', 'user.user_id');
        // 添加筛选条件
        // 购课校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_department', '=', $request->input('filter1'));
        }
        // 购课学生
        if ($request->filled('filter2')) {
            $rows = $rows->where('refund_student', '=', $request->input('filter2'));
        }
        // 课程年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('refund_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();

        // 返回列表视图
        return view('refund/index', ['rows' => $rows,
                                     'currentPage' => $currentPage,
                                     'totalPage' => $totalPage,
                                     'startIndex' => $offset,
                                     'request' => $request,
                                     'totalNum' => $totalNum,
                                     'filter_departments' => $filter_departments,
                                     'filter_students' => $filter_students,
                                     'filter_grades' => $filter_grades]);
    }

    /**
     * 选择退费学生
     * URL: GET /refund/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生信息
        $students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        return view('refund/create', ['students' => $students]);
    }

    /**
     * 选择退费课时
     * URL: POST /refund/create/step2
     * @param  $request->input('input1'): 退课学生
     */
    public function createStep2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('input1');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hours = DB::table('hour')
                     ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                     ->join('course', 'hour.hour_course', '=', 'course.course_id')
                     ->where('hour_type', 0)
                     ->where('hour_remain', '>', 0)
                     ->where('hour_student', $student_id)
                     ->get();
        if($hours->count()==0){
            return redirect("/refund/create")->with(['notify' => true,
                                                     'type' => 'danger',
                                                     'title' => '请重新选择学生',
                                                     'message' => '学生没有可退课时,请重新选择学生.']);
        }
        return view('refund/create2', ['student' => $student,
                                       'hours' => $hours]);
    }

    /**
     * 选择退费方式
     * URL: POST /refund/create/step3
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     */
    public function createStep3(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        $contract_id = $hour->contract_id;
        $course_id = $hour->course_id;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - ($hour->hour_used * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        // 获取退款原因
        $refund_reasons = DB::table('refund_reason')
                           ->where('refund_reason_status', 1)
                           ->get();
        return view('refund/create3', ['student' => $student,
                                       'hour' => $hour,
                                       'refund_amount' => $refund_amount,
                                       'refund_reasons' => $refund_reasons,
                                       'payment_methods' => $payment_methods]);
    }

    /**
     * 确认退费信息
     * URL: POST /refund/create/step4
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function createStep4(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        $refund_fine = round((float)$request->input('input3'), 2);
        $refund_reason = $request->input('input4');
        $refund_payment_method = $request->input('input5');
        $refund_date = $request->input('input6');
        $refund_remark = $request->input('input7');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        $contract_id = $hour->contract_id;
        $course_id = $hour->course_id;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - ($hour->hour_used * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        $refund_actual_amount = $refund_amount - $refund_fine;
        if($refund_actual_amount<0){
            $refund_actual_amount = 0;
        }
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        // 获取退款原因
        $refund_reasons = DB::table('refund_reason')
                           ->where('refund_reason_status', 1)
                           ->get();
        return view('refund/create4', ['student' => $student,
                                       'hour' => $hour,
                                       'refund_actual_amount' => $refund_actual_amount,
                                       'refund_amount' => $refund_amount,
                                       'refund_fine' => $refund_fine,
                                       'refund_reason' => $refund_reason,
                                       'refund_payment_method' => $refund_payment_method,
                                       'refund_date' => $refund_date,
                                       'refund_remark' => $refund_remark]);
    }

    /**
     * 创建新退费记录提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        $refund_fine = round((float)$request->input('input3'), 2);
        $refund_reason = $request->input('input4');
        $refund_payment_method = $request->input('input5');
        $refund_date = $request->input('input6');
        if($request->filled('input7')) {
            $refund_remark = $request->input('input7');
        }else{
        	$refund_remark = "";
        }
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        // 获取提交数据
        $refund_contract = $hour->contract_id;
        $refund_course = $hour->course_id;
        $refund_department = $student->student_department;
        $refund_student = $student_id;
        $refund_remain_hour = $hour->hour_remain;
        $refund_free_hour = $hour->contract_course_free_hour;
        $refund_total_hour = $hour->hour_remain + $hour->contract_course_free_hour;
        $refund_used_hour = $hour->hour_used;
        $refund_actual_total_price = $hour->contract_course_total_price;
        $refund_original_unit_price = $hour->contract_course_original_unit_price;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - ($hour->hour_used * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        $refund_actual_amount = $refund_amount - $refund_fine;
        if($refund_actual_amount<0){
            $refund_actual_amount = 0;
        }
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入Refund表
            DB::table('refund')->insert(
                ['refund_contract' => $refund_contract,
                 'refund_course' => $refund_course,
                 'refund_department' => $refund_department,
                 'refund_student' => $refund_student,
                 'refund_remain_hour' => $refund_remain_hour,
                 'refund_free_hour' => $refund_free_hour,
                 'refund_total_hour' => $refund_total_hour,
                 'refund_used_hour' => $refund_used_hour,
                 'refund_actual_total_price' => $refund_actual_total_price,
                 'refund_original_unit_price' => $refund_original_unit_price,
                 'refund_fine' => $refund_fine,
                 'refund_amount' => $refund_amount,
                 'refund_actual_amount' => $refund_actual_amount,
                 'refund_reason' => $refund_reason,
                 'refund_payment_method' => $refund_payment_method,
                 'refund_date' => $refund_date,
                 'refund_remark' => $refund_remark,
                 'refund_createuser' => Session::get('user_id')]
            );
            // 更新Contract_course表
            DB::table('contract_course')
              ->where('contract_course_contract', $refund_contract)
              ->where('contract_course_course', $refund_course)
              ->update(['contract_course_status' => 0]);
            // 更新Hour表
            // 正常课时
            DB::table('hour')
              ->where('hour_contract', $refund_contract)
              ->where('hour_course', $refund_course)
              ->where('hour_type', 0)
              ->update(['hour_remain' => 0,
                        'hour_refunded' => $refund_remain_hour]);
            // 赠送课时
            DB::table('hour')
              ->where('hour_contract', $refund_contract)
              ->where('hour_course', $refund_course)
              ->where('hour_type', 1)
              ->update(['hour_remain' => 0,
                        'hour_refunded' => $refund_free_hour]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            // 返回购课列表
            return redirect()->action('refundController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '退费失败',
                                     'message' => '退费失败，请联系系统管理员']);
        }
        DB::commit();
        // 获取学生名称
        $student_name = DB::table('student')
                          ->where('student_id', $refund_student)
                          ->value('student_name');
        // 返回购课列表
        return redirect("/refund")->with(['notify' => true,
                                          'type' => 'success',
                                          'title' => '退费成功',
                                          'message' => '退费学生: '.$student_name]);
    }

    /**
     * 显示合同详细信息
     * URL: GET /refund/{id}
     * @param  int  $refund_id
     */
    public function show($refund_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $refund = DB::table('refund')
                    ->join('student', 'refund.refund_student', '=', 'student.student_id')
                    ->join('course', 'refund.refund_course', '=', 'course.course_id')
                    ->join('department', 'student.student_department', '=', 'department.department_id')
                    ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                    ->join('user', 'refund.refund_createuser', '=', 'user.user_id')
                    ->where('refund_id', $refund_id)
                    ->get();
        // 检验数据是否存在
        if($refund->count()!==1){
            // 未获取到数据
            return redirect()->action('refundController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '退费信息显示失败',
                                     'message' => '退费信息显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $refund = $refund[0];
        // 获取审核用户信息
        $refund_checked_user_name = "";
        if($refund->refund_checked==1){
            $refund_checked_user_name = DB::table('user')
                                          ->where('user_id', '=', $refund->refund_checked_user)
                                          ->select('user_name')
                                          ->first()
                                          ->user_name;
        }
        return view('refund/show', ['refund' => $refund,
                                    'refund_checked_user_name' => $refund_checked_user_name]);
    }

    /**
     * 显示合同详细信息
     * URL: GET /refund/{id}/edit
     * @param  int  $refund_id
     */
    public function edit($refund_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取退费信息
        $refund = DB::table('refund')
                    ->where('refund_id', $refund_id)
                    ->first();
        if($refund->refund_checked==1){
            return redirect("/refund")->with(['notify' => true,
                                              'type' => 'danger',
                                              'title' => '退费记录审核失败！',
                                              'message' => '退费记录审核失败，记录己经被审核']);
        }
        if($refund->refund_createuser==Session::get('user_id')){
            return redirect("/refund")->with(['notify' => true,
                                              'type' => 'danger',
                                              'title' => '退费记录审核失败！',
                                              'message' => '退费记录审核失败，不可审核本人提交记录']);
        }
        // 更新数据库
        try{
            DB::table('refund')
              ->where('refund_id', $refund_id)
              ->update(['refund_checked' => 1,
                        'refund_checked_user' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/refund")->with(['notify' => true,
                                              'type' => 'danger',
                                              'title' => '退费记录审核失败！',
                                              'message' => '退费记录审核失败，请联系系统管理员']);
        }
        return redirect("/refund")->with(['notify' => true,
                                          'type' => 'success',
                                          'title' => '退费记录审核成功！',
                                          'message' => '退费记录审核成功！']);
    }

    /**
     * 删除购课
     * URL: DELETE /refund/{id}
     * @param  int  $refund_id
     */
    public function destroy($refund_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取refund信息
        $refund = DB::table('refund')
                    ->where('refund_id', $refund_id)
                    ->first();
        DB::beginTransaction();
        try{
            // 更新Hour表
            // 正常课时
            DB::table('hour')
              ->where('hour_contract', $refund->refund_contract)
              ->where('hour_course', $refund->refund_course)
              ->where('hour_type', 0)
              ->update(['hour_refunded' => 0,
                        'hour_remain' => $refund->refund_remain_hour]);
            // 赠送课时
            DB::table('hour')
              ->where('hour_contract', $refund->refund_contract)
              ->where('hour_course', $refund->refund_course)
              ->where('hour_type', 1)
              ->update(['hour_refunded' => 0,
                        'hour_remain' => $refund->refund_free_hour]);
            // 更新Contract_course表
            DB::table('contract_course')
              ->where('contract_course_contract', $refund->refund_contract)
              ->where('contract_course_course', $refund->refund_course)
              ->update(['contract_course_status' => 1]);
            // 删除Refund表
            DB::table('refund')
              ->where('refund_id', $refund_id)
              ->delete();
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/refund")->with(['notify' => true,
                                              'type' => 'danger',
                                              'title' => '退费记录删除失败！',
                                              'message' => '退费记录删除失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/refund")->with(['notify' => true,
                                          'type' => 'success',
                                          'title' => '退费记录删除成功！',
                                          'message' => '退费记录删除成功！']);
    }
}
