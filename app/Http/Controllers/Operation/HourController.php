<?php
namespace App\Http\Controllers\Operation;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class HourController extends Controller
{
    /**
     * 全部学生视图
     * URL: GET /operation/hour
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function hour(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('hour')
                  ->join('student', 'student.student_id', '=', 'hour.hour_student')
                  ->join('course', 'course.course_id', '=', 'hour.hour_course')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                  ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                  ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                  ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->whereIn('student_department', $department_access)
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->select('course.course_name AS course_name',
                              'course.course_id AS course_id',
                              'hour.hour_remain AS hour_remain',
                              'hour.hour_used AS hour_used',
                              'hour.hour_cleaned AS hour_cleaned',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'student.student_gender AS student_gender',
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

        $datas = array();
        foreach($rows as $row){
            $temp = array();
            $temp['course_name'] = $row->course_name;
            $temp['course_id'] = $row->course_id;
            $temp['hour_remain'] = $row->hour_remain;
            $temp['hour_used'] = $row->hour_used;
            $temp['hour_cleaned'] = $row->hour_cleaned;
            $temp['student_id'] = $row->student_id;
            $temp['student_name'] = $row->student_name;
            $temp['student_gender'] = $row->student_gender;
            $temp['department_name'] = $row->department_name;
            $temp['grade_name'] = $row->grade_name;
            $temp['consultant_name'] = $row->consultant_name;
            $temp['consultant_position_name'] = $row->consultant_position_name;
            $temp['class_adviser_name'] = $row->class_adviser_name;
            $temp['class_adviser_position_name'] = $row->class_adviser_position_name;
            // 获取课程安排信息
            $schedule_count = 0;
            $classes = DB::table('member')
                         ->join('class', 'member.member_class', '=', 'class.class_id')
                         ->where('member_student', $row->student_id)
                         ->where('member_course', $row->course_id)
                         ->get();
            $schedule_classes = array();
            foreach($classes as $class){
                $temp2 = array();
                $temp2['class_name'] = $class->class_name;
                $temp2['class_id'] = $class->class_id;
                $temp2['class_schedule_num'] = $class->class_schedule_num;
                $schedule_classes[] = $temp2;
                $schedule_count+=$class->class_schedule_num;
            }
            $temp['schedule_count']=$schedule_count;
            $temp['schedule_classes']=$schedule_classes;
            $datas[] = $temp;
        }
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('operation/hour/hour', ['datas' => $datas,
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
     * 学生退费视图
     * URL: POST /operation/refund/create
     * @param  $request->input('input1'): 退课学生
     */
    public function refundCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = decode($request->input('student_id'), 'student_id');
        $course_id = decode($request->input('course_id'), 'course_id');
        if(!DB::table('hour')->where('hour_student', $student_id)->where('hour_course', $course_id)->exists()){
            return redirect("/operation/hour")->with(['notify' => true,
                                                     'type' => 'danger',
                                                     'title' => '退费失败',
                                                     'message' => '学生没有可退课时,请重新选择.']);
        }
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->where('hour_student', $student_id)
                  ->where('hour_course', $course_id)
                  ->first();
        if($hour->hour_remain<=0){
            return redirect("/operation/hour")->with(['notify' => true,
                                                     'type' => 'danger',
                                                     'title' => '退费失败',
                                                     'message' => '学生没有可退课时,请重新选择.']);
        }
        // 获取学生信息
        $student = DB::table('student')
                  ->where('student_id', $student_id)
                  ->first();
        // 获取课程信息
        $course = DB::table('course')
                  ->where('course_id', $course_id)
                  ->first();
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        // 获取退款原因
        $refund_reasons = DB::table('refund_reason')
                           ->where('refund_reason_status', 1)
                           ->get();
        return view('operation/hour/refundCreate', ['hour' => $hour,
                                                    'course' => $course,
                                                    'student' => $student,
                                                    'payment_methods' => $payment_methods,
                                                    'refund_reasons' => $refund_reasons]);
    }

    /**
     * 学生退费提交
     * URL: POST /operation/refund/store
     * @param  Request  $request
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function refundStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $refund_student = $request->input('input1');
        $refund_course = $request->input('input2');
        $refund_amount = round((float)$request->input('input3'), 2);
        $refund_reason = $request->input('input4');
        $refund_payment_method = $request->input('input5');
        $refund_date = $request->input('input6');
        if($request->filled('input7')) {
            $refund_remark = $request->input('input7');
        }else{
        	$refund_remark = "";
        }
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->where('hour_student', $refund_student)
                  ->where('hour_course', $refund_course)
                  ->first();
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入Refund表
            DB::table('refund')->insert(
                ['refund_course' => $refund_course,
                 'refund_student' => $refund_student,
                 'refund_remain' => $hour->hour_remain,
                 'refund_used' => $hour->hour_used,
                 'refund_cleaned' => $hour->hour_cleaned,
                 'refund_unit_price' => $hour->hour_average_price,
                 'refund_amount' => $refund_amount,
                 'refund_reason' => $refund_reason,
                 'refund_payment_method' => $refund_payment_method,
                 'refund_date' => $refund_date,
                 'refund_remark' => $refund_remark,
                 'refund_createuser' => Session::get('user_id')]
            );
            // 删除Hour表
            DB::table('hour')
              ->where('hour_student', $refund_student)
              ->where('hour_course', $refund_course)
              ->delete();
            // 添加学生动态
            //DB::table('student_record')->insert(
            //    ['student_record_student' => $student_id,
            //     'student_record_type' => '学生退费',
            //     'student_record_content' => '学生退费，课程：'.$refund_contract.'，课程名称：'.$hour->course_name.'，共退课时：'.$refund_total_hour.' 课时，共退金额：'.$refund_actual_amount.' 元。退费人：'.Session::get('user_name')."。",
            //     'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            // 返回购课列表
            return redirect("/operation/hour")
                   ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '退费失败',
                             'message' => '退费失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/operation/hour")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费成功',
                      'message' => '退费成功']);
    }

    /**
     * 课时清理视图
     * URL: POST /operation/hour/clean
     * @param  $request->input('input1'): 退课学生
     */
    public function hourClean(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = decode($request->input('student_id'), 'student_id');
        $course_id = decode($request->input('course_id'), 'course_id');
        if(!DB::table('hour')->where('hour_student', $student_id)->where('hour_course', $course_id)->exists()){
            return redirect("/operation/hour")->with(['notify' => true,
                                                     'type' => 'danger',
                                                     'title' => '课时清理失败',
                                                     'message' => '学生没有可清理课时,请重新选择.']);
        }
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->where('hour_student', $student_id)
                  ->where('hour_course', $course_id)
                  ->first();
        if($hour->hour_remain<=0){
            return redirect("/operation/hour")->with(['notify' => true,
                                                     'type' => 'danger',
                                                     'title' => '课时清理失败',
                                                     'message' => '学生没有可清理课时,请重新选择.']);
        }
        // 获取学生信息
        $student = DB::table('student')
                  ->where('student_id', $student_id)
                  ->first();
        // 获取课程信息
        $course = DB::table('course')
                  ->where('course_id', $course_id)
                  ->first();
        return view('operation/hour/hourClean', ['hour' => $hour,
                                                 'course' => $course,
                                                 'student' => $student]);
    }

    /**
     * 学生退费提交
     * URL: POST /operation/refund/store
     * @param  Request  $request
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function hourCleanStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $hour_cleaned_record_student = $request->input('input1');
        $hour_cleaned_record_course = $request->input('input2');
        $hour_cleaned_record_amount = round((float)$request->input('input3'), 2);
        if($request->filled('input4')) {
            $hour_cleaned_record_remark = $request->input('input4');
        }else{
        	$hour_cleaned_record_remark = "";
        }
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->where('hour_student', $hour_cleaned_record_student)
                  ->where('hour_course', $hour_cleaned_record_course)
                  ->first();
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入hour_cleaned_record表
            DB::table('hour_cleaned_record')->insert(
                ['hour_cleaned_record_student' => $hour_cleaned_record_student,
                 'hour_cleaned_record_course' => $hour_cleaned_record_course,
                 'hour_cleaned_record_amount' => $hour_cleaned_record_amount,
                 'hour_cleaned_record_remark' => $hour_cleaned_record_remark,
                 'hour_cleaned_record_createuser' => Session::get('user_id')]
            );
            // 更新Hour表
            DB::table('hour')
              ->where('hour_student', $hour_cleaned_record_student)
              ->where('hour_course', $hour_cleaned_record_course)
              ->decrement('hour_remain', $hour_cleaned_record_amount);
            DB::table('hour')
              ->where('hour_student', $hour_cleaned_record_student)
              ->where('hour_course', $hour_cleaned_record_course)
              ->increment('hour_cleaned', $hour_cleaned_record_amount);
            // 添加学生动态
            //DB::table('student_record')->insert(
            //    ['student_record_student' => $student_id,
            //     'student_record_type' => '学生退费',
            //     'student_record_content' => '学生退费，课程：'.$refund_contract.'，课程名称：'.$hour->course_name.'，共退课时：'.$refund_total_hour.' 课时，共退金额：'.$refund_actual_amount.' 元。退费人：'.Session::get('user_name')."。",
            //     'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            // 返回购课列表
            return redirect("/operation/hour")
                   ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '课时清理失败',
                             'message' => '课时清理失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/operation/hour")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '课时清理成功',
                      'message' => '课时清理成功']);
    }

}
