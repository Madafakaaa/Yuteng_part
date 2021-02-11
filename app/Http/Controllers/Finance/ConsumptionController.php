<?php
namespace App\Http\Controllers\Finance;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ConsumptionController extends Controller
{


    public function consumptionDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/finance/consumption/department", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_date_start" => date('Y-m')."-01",
                        "filter_date_end" => date('Y-m-d')
                    );

        // 数据面板
        $dashboard = array(
                             "dashboard_schedule_num" => 0,
                             "dashboard_hour_num" => 0,
                             "dashboard_attended_num" => 0,
                             "dashboard_leave_num" => 0,
                             "dashboard_department_name" => "全部校区",
                           );

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->where('schedule_attended', 1)
                  ->whereIn('schedule_department', $department_access);
        // 期限
        if ($request->filled('filter_date_start')) {
            $filters['filter_date_start']=$request->input("filter_date_start");
        }
        if ($request->filled('filter_date_end')) {
            $filters['filter_date_end']=$request->input("filter_date_end");
        }
        $rows = $rows->where('schedule_date', '>=', $filters['filter_date_start']);
        $rows = $rows->where('schedule_date', '<=', $filters['filter_date_end']);
        // 校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('schedule_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
            $dashboard['dashboard_department_name'] = DB::table('department')->where('department_id', $request->input("filter_department"))->first()->department_name;
        }
        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'desc')
                     ->get();
        $schedules=array();
        foreach($rows as $row){
             $temp=array();
             $temp['department_name']=$row->department_name;
             $temp['class_id']=$row->class_id;
             $temp['class_name']=$row->class_name;
             $temp['class_max_num']=$row->class_max_num;
             $temp['schedule_attended_num']=$row->schedule_attended_num;
             $temp['schedule_leave_num']=$row->schedule_leave_num;
             $temp['schedule_absence_num']=$row->schedule_absence_num;
             $temp['grade_name']=$row->grade_name;
             $temp['subject_name']=$row->subject_name;
             $temp['schedule_date']=$row->schedule_date;
             $temp['schedule_start']=$row->schedule_start;
             $temp['schedule_end']=$row->schedule_end;
             $start_list=explode(":", $temp['schedule_start']);
             $end_list=explode(":", $temp['schedule_end']);
             $duration=round((60*($end_list[0]-$start_list[0])+($end_list[1]-$start_list[1]))/60, 2);
             $temp['duration']=$duration;
             $temp['user_id']=$row->user_id;
             $temp['user_name']=$row->user_name;
             $temp['schedule_id']=$row->schedule_id;
             $temp['consumption_price']=0;
             // 获取上课学生
             $temp['student_num'] = 0;
             $temp['participants'] = array();
             $db_participants = DB::table('participant')
                                  ->join('student', 'participant.participant_student', '=', 'student.student_id')
                                  ->leftJoin('user', 'user.user_id', '=', 'student.student_class_adviser')
                                  ->leftJoin('course', 'participant.participant_course', '=', 'course.course_id')
                                  ->leftJoin('hour', [
                                                       ['hour_student', 'student_id'],
                                                       ['hour_course', 'course_id'],
                                                     ])
                                  ->where('participant_schedule', $row->schedule_id)
                                  ->get();
             foreach($db_participants as $db_participant){
                 $temp_participant = array();
                 $temp_participant['student_name'] = $db_participant->student_name;
                 $temp_participant['student_id'] = $db_participant->student_id;
                 $temp_participant['user_name'] = $db_participant->user_name;
                 $temp_participant['course_name'] = $db_participant->course_name;
                 $temp_participant['course_type'] = $db_participant->course_type;
                 $temp_participant['participant_attend_status'] = $db_participant->participant_attend_status;
                 $temp_participant['participant_amount'] = $db_participant->participant_amount;
                 $temp_participant['participant_hour'] = round($db_participant->participant_amount*$db_participant->course_time/60, 1);
                 $temp_participant['participant_consumption_price'] = $db_participant->participant_amount*$db_participant->hour_average_price;
                 $temp['participants'][] = $temp_participant;
                 $temp['student_num']++;
                 $temp['consumption_price']+=$temp_participant['participant_consumption_price'];
                 $dashboard['dashboard_hour_num']+=$db_participant->participant_amount;
             }
             $schedules[]=$temp;
             $dashboard['dashboard_schedule_num']++;
             $dashboard['dashboard_attended_num']+=$temp['schedule_attended_num'];
             $dashboard['dashboard_leave_num']+=$temp['schedule_leave_num'];
             $dashboard['dashboard_leave_num']+=$temp['schedule_absence_num'];
        }

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('finance/consumptionDepartment', ['schedules' => $schedules,
                                                   'dashboard' => $dashboard,
                                                   'filters' => $filters,
                                                   'filter_departments' => $filter_departments]);
    }

     public function consumptionUser(Request $request){
         // 检查登录状态
         if(!Session::has('login')){
             return loginExpired(); // 未登录，返回登陆视图
         }
        // 检测用户权限
        if(!in_array("/finance/consumption/user", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
         // 获取用户校区权限
         $department_access = Session::get('department_access');
         // 搜索条件
         $filters = array(
                         "filter_user" => null,
                         "filter_date_start" => date('Y-m')."-01",
                         "filter_date_end" => date('Y-m-d')
                     );

         // 数据面板
         $dashboard = array(
                              "dashboard_schedule_num" => 0,
                              "dashboard_hour_num" => 0,
                              "dashboard_attended_num" => 0,
                              "dashboard_leave_num" => 0,
                              "dashboard_user_name" => "全部教师",
                            );

         // 获取数据
         $rows = DB::table('schedule')
                   ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                   ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                   ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                   ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                   ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                   ->where('schedule_attended', 1)
                   ->whereIn('schedule_department', $department_access);
         // 教师
         if ($request->filled('filter_user')) {
             $rows = $rows->where('schedule_teacher', '=', $request->input("filter_user"));
             $filters['filter_user']=$request->input("filter_user");
             $dashboard['dashboard_user_name'] = DB::table('user')->where('user_id', $request->input("filter_user"))->first()->user_name;
         }
         // 期限
         if ($request->filled('filter_date_start')) {
             $filters['filter_date_start']=$request->input("filter_date_start");
         }
         if ($request->filled('filter_date_end')) {
             $filters['filter_date_end']=$request->input("filter_date_end");
         }
         $rows = $rows->where('schedule_date', '>=', $filters['filter_date_start']);
         $rows = $rows->where('schedule_date', '<=', $filters['filter_date_end']);
         // 排序并获取数据对象
         $rows = $rows->orderBy('schedule_date', 'desc')
                      ->get();

         $schedules=array();
        foreach($rows as $row){
             $temp=array();
             $temp['department_name']=$row->department_name;
             $temp['class_id']=$row->class_id;
             $temp['class_name']=$row->class_name;
             $temp['class_max_num']=$row->class_max_num;
             $temp['schedule_attended_num']=$row->schedule_attended_num;
             $temp['schedule_leave_num']=$row->schedule_leave_num;
             $temp['schedule_absence_num']=$row->schedule_absence_num;
             $temp['grade_name']=$row->grade_name;
             $temp['subject_name']=$row->subject_name;
             $temp['schedule_date']=$row->schedule_date;
             $temp['schedule_start']=$row->schedule_start;
             $temp['schedule_end']=$row->schedule_end;
             $start_list=explode(":", $temp['schedule_start']);
             $end_list=explode(":", $temp['schedule_end']);
             $duration=round((60*($end_list[0]-$start_list[0])+($end_list[1]-$start_list[1]))/60, 2);
             $temp['duration']=$duration;
             $temp['user_id']=$row->user_id;
             $temp['user_name']=$row->user_name;
             $temp['schedule_id']=$row->schedule_id;
             $temp['consumption_price']=0;
             // 获取上课学生
             $temp['student_num'] = 0;
             $temp['participants'] = array();
             $db_participants = DB::table('participant')
                                  ->join('student', 'participant.participant_student', '=', 'student.student_id')
                                  ->leftJoin('user', 'user.user_id', '=', 'student.student_class_adviser')
                                  ->leftJoin('course', 'participant.participant_course', '=', 'course.course_id')
                                  ->leftJoin('hour', [
                                                       ['hour_student', 'student_id'],
                                                       ['hour_course', 'course_id'],
                                                     ])
                                  ->where('participant_schedule', $row->schedule_id)
                                  ->orderBy('participant_attend_status', 'asc')
                                  ->get();
             foreach($db_participants as $db_participant){
                 $temp_participant = array();
                 $temp_participant['student_name'] = $db_participant->student_name;
                 $temp_participant['student_id'] = $db_participant->student_id;
                 $temp_participant['user_name'] = $db_participant->user_name;
                 $temp_participant['course_name'] = $db_participant->course_name;
                 $temp_participant['course_type'] = $db_participant->course_type;
                 $temp_participant['participant_attend_status'] = $db_participant->participant_attend_status;
                 $temp_participant['participant_amount'] = $db_participant->participant_amount;
                 $temp_participant['participant_hour'] = round($db_participant->participant_amount*$db_participant->course_time/60, 1);
                 $temp_participant['participant_consumption_price'] = $db_participant->participant_amount*$db_participant->hour_average_price;
                 $temp['participants'][] = $temp_participant;
                 $temp['student_num']++;
                 $temp['consumption_price']+=$temp_participant['participant_consumption_price'];
                 $dashboard['dashboard_hour_num']+=$db_participant->participant_amount;
             }
             $schedules[]=$temp;
             $dashboard['dashboard_schedule_num']++;
             $dashboard['dashboard_attended_num']+=$temp['schedule_attended_num'];
             $dashboard['dashboard_leave_num']+=$temp['schedule_leave_num'];
             $dashboard['dashboard_leave_num']+=$temp['schedule_absence_num'];
        }

         // 获取校区、学生、课程、年级信息(筛选)
         $filter_users = DB::table('user')->join('department', 'user.user_department', '=', 'department.department_id')->where('user_status', 1)->whereIn('user_department', $department_access)->orderBy('user_department', 'asc')->get();

         // 返回列表视图
         return view('finance/consumptionUser', ['schedules' => $schedules,
                                                  'dashboard' => $dashboard,
                                                  'filters' => $filters,
                                                  'filter_users' => $filter_users]);
     }
}
