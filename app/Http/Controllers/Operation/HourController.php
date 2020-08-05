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
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_name" => null,
                        "filter_consultant" => null,
                        "filter_class_adviser" => null,
                    );

        // 客户校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('student_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 客户年级
        if ($request->filled('filter_grade')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter_grade'));
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter_name')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter_name').'%');
            $filters['filter_name']=$request->input("filter_name");
            $filter_status = 1;
        }
        // 课程顾问
        if ($request->filled('filter_consultant')) {
            $rows = $rows->where('student_consultant', '=', $request->input('filter_consultant'));
            $filters['filter_consultant']=$request->input("filter_consultant");
            $filter_status = 1;
        }
        // 课程顾问
        if ($request->filled('filter_class_adviser')) {
            $rows = $rows->where('student_consultant', '=', $request->input('filter_class_adviser'));
            $filters['filter_class_adviser']=$request->input("filter_class_adviser");
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
                              'hour.hour_average_price AS hour_average_price',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'student.student_gender AS student_gender',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_id AS consultant_id',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_id AS class_adviser_id',
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
            $temp['hour_average_price'] = $row->hour_average_price;
            $temp['student_id'] = $row->student_id;
            $temp['student_name'] = $row->student_name;
            $temp['student_gender'] = $row->student_gender;
            $temp['department_name'] = $row->department_name;
            $temp['grade_name'] = $row->grade_name;
            $temp['consultant_id']=$row->consultant_id;
            $temp['consultant_name']=$row->consultant_name;
            $temp['consultant_position_name']=$row->consultant_position_name;
            $temp['class_adviser_id']=$row->class_adviser_id;
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
        $filter_users = DB::table('user')
                          ->join('department', 'user.user_department', '=', 'department.department_id')
                          ->join('position', 'user.user_position', '=', 'position.position_id')
                          ->where('user_status', 1)
                          ->whereIn('user_department', $department_access)
                          ->orderBy('user_department', 'asc')
                          ->orderBy('user_position', 'desc')
                          ->get();
        // 返回列表视图
        return view('operation/hour/hour', ['datas' => $datas,
                                            'currentPage' => $currentPage,
                                            'totalPage' => $totalPage,
                                            'startIndex' => $offset,
                                            'request' => $request,
                                            'filters' => $filters,
                                            'totalNum' => $totalNum,
                                            'filter_status' => $filter_status,
                                            'filter_departments' => $filter_departments,
                                            'filter_grades' => $filter_grades,
                                            'filter_users' => $filter_users]);
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
                                                     'message' => '学生没有可退课时，错误码:310']);
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
                                                     'message' => '学生没有可退课时，错误码:311']);
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
        // 计算可退金额
        $refund_amount = ($hour->hour_remain+$hour->hour_used)*$hour->hour_average_price-$hour->hour_used*$course->course_unit_price;
        return view('operation/hour/refundCreate', ['hour' => $hour,
                                                    'course' => $course,
                                                    'student' => $student,
                                                    'payment_methods' => $payment_methods,
                                                    'refund_reasons' => $refund_reasons,
                                                    'refund_amount' => $refund_amount]);
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
            // 返回购课列表
            return redirect("/operation/hour")
                   ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '退费失败',
                             'message' => '退费失败，错误码:312']);
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
    public function hourEdit(Request $request){
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
                                                     'message' => '学生没有可清理课时，错误码:313']);
        }
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->where('hour_student', $student_id)
                  ->where('hour_course', $course_id)
                  ->first();

        // 获取学生信息
        $student = DB::table('student')
                  ->where('student_id', $student_id)
                  ->first();
        // 获取课程信息
        $course = DB::table('course')
                  ->where('course_id', $course_id)
                  ->first();
        return view('operation/hour/hourEdit', ['hour' => $hour,
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
    public function hourUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $hour_update_record_student = $request->input('input1');
        $hour_update_record_course = $request->input('input2');
        $hour_update_record_amount = round((float)$request->input('input3'), 1);
        $hour_update_record_average_price = round((float)$request->input('input4'), 2);
        if($request->filled('input5')) {
            $hour_update_record_remark = $request->input('input5');
        }else{
        	$hour_update_record_remark = "";
        }
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->where('hour_student', $hour_update_record_student)
                  ->where('hour_course', $hour_update_record_course)
                  ->first();
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入hour_update_record表
            DB::table('hour_update_record')->insert(
                ['hour_update_record_student' => $hour_update_record_student,
                 'hour_update_record_course' => $hour_update_record_course,
                 'hour_update_record_remain_before' => $hour->hour_remain,
                 'hour_update_record_average_price_before' => $hour->hour_average_price,
                 'hour_update_record_remain_after' => $hour_update_record_amount,
                 'hour_update_record_average_price_after' => $hour_update_record_average_price,
                 'hour_update_record_remark' => $hour_update_record_remark,
                 'hour_update_record_createuser' => Session::get('user_id')]
            );
            // 更新Hour表
            DB::table('hour')
              ->where('hour_student', $hour_update_record_student)
              ->where('hour_course', $hour_update_record_course)
              ->update(['hour_remain' => $hour_update_record_amount,
                        'hour_average_price' => $hour_update_record_average_price]);
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
            // 返回购课列表
            return redirect("/operation/hour")
                   ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '课时修改失败',
                             'message' => '课时修改失败，错误码:315']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/operation/hour")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '课时修改成功',
                      'message' => '课时修改成功']);
    }

}
