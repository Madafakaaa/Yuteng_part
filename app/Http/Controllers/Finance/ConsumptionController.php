<?php
namespace App\Http\Controllers\Finance;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ConsumptionController extends Controller
{
    /**
     * 签约管理视图
     * URL: GET /market/contract
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function consumption(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取月份
        $month = date('Y-m');
        if ($request->filled('month')) {
            $month=$request->input("month");
        }
        //获取上一月月份
        $last_month = date('Y-m', strtotime ('-1 month', strtotime($month)));
        // 获取dashboard数据
        $dashboard = array();

        // 获取当月总排课数量
        $dashboard['total_lesson_num'] = DB::table('schedule')
                                           ->whereIn('schedule_department', $department_access)
                                           ->where('schedule_date', 'like', $month."%")
                                           ->count();
        // 获取上月总排课数量
        $total_lesson_num_last = DB::table('schedule')
                                   ->whereIn('schedule_department', $department_access)
                                   ->where('schedule_date', 'like', $last_month."%")
                                   ->count();
        if($total_lesson_num_last==0){
            $dashboard['total_lesson_num_change'] = 0;
        }else{
            $dashboard['total_lesson_num_change'] = round(100*($dashboard['total_lesson_num']-$total_lesson_num_last)/$total_lesson_num_last, 2);
        }

        // 获取当月已上课数量
        $dashboard['total_attended_lesson_num'] = DB::table('schedule')
                                                    ->where('schedule_attended', '=', 1)
                                                    ->whereIn('schedule_department', $department_access)
                                                    ->where('schedule_date', 'like', $month."%")
                                                    ->count();
        // 获取上月已上课数量
        $total_attended_lesson_num_last = DB::table('schedule')
                                            ->where('schedule_attended', '=', 1)
                                            ->whereIn('schedule_department', $department_access)
                                            ->where('schedule_date', 'like', $last_month."%")
                                            ->count();
        if($total_attended_lesson_num_last==0){
            $dashboard['total_attended_lesson_num_change'] = 0;
        }else{
            $dashboard['total_attended_lesson_num_change'] = round(100*($dashboard['total_attended_lesson_num']-$total_attended_lesson_num_last)/$total_attended_lesson_num_last, 2);
        }

        // 获取当月已上课人次
        $dashboard['total_attended_student_num'] = DB::table('schedule')
                                                    ->where('schedule_attended', '=', 1)
                                                    ->whereIn('schedule_department', $department_access)
                                                    ->where('schedule_date', 'like', $month."%")
                                                    ->sum('schedule_attended_num');
        // 获取上月已上课人次
        $total_attended_student_num_last = DB::table('schedule')
                                            ->where('schedule_attended', '=', 1)
                                            ->whereIn('schedule_department', $department_access)
                                            ->where('schedule_date', 'like', $last_month."%")
                                            ->sum('schedule_attended_num');
        if($total_attended_student_num_last==0){
            $dashboard['total_attended_student_num_change'] = 0;
        }else{
            $dashboard['total_attended_student_num_change'] = round(100*($dashboard['total_attended_student_num']-$total_attended_student_num_last)/$total_attended_student_num_last, 2);
        }

        // 获取当月总消耗课时
        $dashboard['total_attended_hour_num'] = DB::table('participant')
                                                  ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                                                  ->where('schedule_attended', '=', 1)
                                                  ->whereIn('schedule_department', $department_access)
                                                  ->where('schedule_date', 'like', $month."%")
                                                  ->sum('participant_amount');
        // 获取上月总消耗课时
        $total_attended_hour_num_last = DB::table('participant')
                                          ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                                          ->where('schedule_attended', '=', 1)
                                          ->whereIn('schedule_department', $department_access)
                                          ->where('schedule_date', 'like', $last_month."%")
                                          ->sum('participant_amount');
        if($total_attended_hour_num_last==0){
            $dashboard['total_attended_hour_num_change'] = 0;
        }else{
            $dashboard['total_attended_hour_num_change'] = round(100*($dashboard['total_attended_hour_num']-$total_attended_hour_num_last)/$total_attended_hour_num_last, 2);
        }

        // 获取每个校区课程信息
        $department_schedules = DB::table('department')
                                  ->leftJoin('schedule', 'schedule.schedule_department', '=', 'department.department_id')
                                  ->leftJoin('participant', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                                  ->select(DB::raw('COUNT(DISTINCT schedule_id) AS attended_schedule_num, SUM(participant_amount) AS total_hour_num, department_id, department_name'))
                                  ->whereIn('schedule_department', $department_access)
                                  ->where('schedule_attended', '=', 1)
                                  ->where('schedule_date', 'like', $month."%")
                                  ->groupBy('schedule_department')
                                  ->orderBy('attended_schedule_num', 'desc')
                                  ->orderBy('total_hour_num', 'desc')
                                  ->get();


        // 获取每个用户签约信息
        $user_schedules = DB::table('user')
                            ->join('department', 'user.user_department', '=', 'department.department_id')
                            ->leftJoin('schedule', 'schedule.schedule_teacher', '=', 'user.user_id')
                            ->leftJoin('participant', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                            ->select(DB::raw('COUNT(DISTINCT schedule_id) AS attended_schedule_num, SUM(participant_amount) AS total_hour_num, user_id, user_name, department_name'))
                            ->whereIn('user_department', $department_access)
                            ->where('schedule_attended', '=', 1)
                            ->where('schedule_date', 'like', $month."%")
                            ->groupBy('schedule_teacher')
                            ->orderBy('attended_schedule_num', 'desc')
                            ->orderBy('total_hour_num', 'desc')
                            ->get();
        // 返回列表视图
        return view('finance/consumption', ['dashboard' => $dashboard,
                                            'department_schedules' => $department_schedules,
                                            'user_schedules' => $user_schedules,
                                            'month' => $month]);
    }

    public function consumptionDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_month" => date('Y-m')
                    );

        // 获取数据
        $rows = DB::table('schedule')
                  ->leftJoin('participant', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->select(DB::raw('sum(participant_amount) as total_hour, schedule_id, schedule_date, schedule_start, schedule_end, schedule_attended_num, schedule_leave_num, schedule_absence_num, user_id, user_name, class_id, class_name, department_name, grade_name, subject_name'))
                  ->where('schedule_attended', 1)
                  ->whereIn('schedule_department', $department_access);
        // 校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('schedule_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 月份
        if ($request->filled('filter_month')) {
            $filters['filter_month']=$request->input("filter_month");
        }
        $rows = $rows->where('schedule_date', 'like', $filters['filter_month']."%");
        // 排序并获取数据对象
        $rows = $rows->groupBy('schedule_id')
                     ->orderBy('schedule_date', 'desc')
                     ->get();
        // 转为数组并获取详细课程信息
        $dashboard = array(
                             "dashboard_contract_num" => 0,
                             "dashboard_contract_num_today" => 0,
                             "dashboard_price_total" => 0,
                             "dashboard_price_total_today" => 0
                           );

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('finance/consumptionDepartment', ['rows' => $rows,
                                                   'dashboard' => $dashboard,
                                                   'filters' => $filters,
                                                   'filter_departments' => $filter_departments]);
    }

    public function consumptionUser(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 搜索条件
        $filters = array(
                        "filter_user" => null,
                        "filter_grade" => null,
                        "filter_type" => null,
                        "filter_month" => date('Y-m')
                    );
        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->whereIn('contract_department', $department_access);
        // 签约人
        if ($request->filled('filter_user')) {
            $rows = $rows->where('contract_createuser', '=', $request->input("filter_user"));
            $filters['filter_user']=$request->input("filter_user");
        }
        // 类型
        if ($request->filled('filter_type')) {
            $rows = $rows->where('contract_type', '=', $request->input("filter_type")-1);
            $filters['filter_type']=$request->input("filter_type");
        }
        // 年级
        //if ($request->filled('filter_grade')) {
            //$rows = $rows->where('student_grade', '=', $request->input('filter_grade'));
            //$filters['filter_grade']=$request->input("filter_grade");
        //}

        // 月份
        if ($request->filled('filter_month')) {
            $filters['filter_month']=$request->input("filter_month");
        }
        $rows = $rows->where('contract_date', 'like', $filters['filter_month']."%");
        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_date', 'desc')
                     ->get();

        // 转为数组并获取详细课程信息
        $dashboard = array(
                             "dashboard_contract_num" => 0,
                             "dashboard_contract_num_today" => 0,
                             "dashboard_price_total" => 0,
                             "dashboard_price_total_today" => 0
                           );

        $contracts = array();
        foreach($rows as $row){
            $temp=array();
            $temp['department_name']=$row->department_name;
            $temp['contract_date']=$row->contract_date;
            $temp['user_name']=$row->user_name;
            $temp['student_name']=$row->student_name;
            $temp['student_gender']=$row->student_gender;
            $temp['grade_name']=$row->grade_name;
            $temp['contract_type']=$row->contract_type;
            $temp['contract_total_price']=$row->contract_total_price;
            $temp['contract_paid_price']=$row->contract_paid_price;
            $temp['contract_courses']=array();
            // 获取合同课程
            $contract_courses = DB::table('contract_course')
                                  ->join('course', 'contract_course.contract_course_course', '=', 'course.course_id')
                                  ->where('contract_course_contract', $row->contract_id)
                                  ->get();
            foreach($contract_courses as $contract_course){
                $temp_course = array();
                $temp_course['course_name']=$contract_course->course_name;
                $temp_course['course_type']=$contract_course->course_type;
                $temp_course['contract_course_original_hour']=$contract_course->contract_course_original_hour;
                $temp_course['contract_course_original_unit_price']=$contract_course->contract_course_original_unit_price;
                $temp_course['contract_course_discount_rate']=$contract_course->contract_course_discount_rate;
                $temp_course['contract_course_discount_amount']=$contract_course->contract_course_discount_amount;
                $temp_course['contract_course_free_hour']=$contract_course->contract_course_free_hour;
                $temp_course['contract_course_total_hour']=$contract_course->contract_course_total_hour;
                $temp_course['contract_course_total_price']=$contract_course->contract_course_total_price;
                $temp['contract_courses'][]=$temp_course;
            }
            $temp['contract_course_num']=count($temp['contract_courses']);
            $contracts[]=$temp;
            // 更新dashboard
            $dashboard['dashboard_contract_num']++;
            $dashboard['dashboard_price_total']+=$temp['contract_total_price'];
            if($temp['contract_date']==date('Y-m-d')){
                $dashboard['dashboard_contract_num_today']++;
                $dashboard['dashboard_price_total_today']+=$temp['contract_total_price'];
            }
        }

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_users = DB::table('user')
                          ->join('department', 'user.user_department', '=', 'department.department_id')
                          ->where('user_status', 1)
                          ->orderBy('user_department', 'asc')
                          ->get();

        // 返回列表视图
        return view('finance/contractUser', ['contracts' => $contracts,
                                               'dashboard' => $dashboard,
                                               'filters' => $filters,
                                               'filter_users' => $filter_users,
                                               'filter_grades' => $filter_grades]);
    }

}
