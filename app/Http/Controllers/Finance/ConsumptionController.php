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
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_date_start" => date('Y-m')."-01",
                        "filter_date_end" => date('Y-m-d')
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
        $rows = $rows->groupBy('schedule_id')
                     ->orderBy('schedule_date', 'desc')
                     ->get();
        // 转为数组并获取详细课程信息
        $dashboard = array(
                             "dashboard_schedule_num" => 0,
                             "dashboard_hour_num" => 0,
                             "dashboard_attended_num" => 0,
                             "dashboard_leave_num" => 0,
                           );

        $schedules=array();
        foreach($rows as $row){
            $temp=array();
            $temp['department_name']=$row->department_name;
            $temp['class_id']=$row->class_id;
            $temp['class_name']=$row->class_name;
            $temp['schedule_attended_num']=$row->schedule_attended_num;
            $temp['schedule_leave_num']=$row->schedule_leave_num;
            $temp['schedule_absence_num']=$row->schedule_absence_num;
            $temp['grade_name']=$row->grade_name;
            $temp['subject_name']=$row->subject_name;
            $temp['schedule_date']=$row->schedule_date;
            $temp['schedule_start']=$row->schedule_start;
            $temp['schedule_end']=$row->schedule_end;
            $temp['total_hour']=$row->total_hour;
            $temp['user_id']=$row->user_id;
            $temp['user_name']=$row->user_name;
            $temp['schedule_id']=$row->schedule_id;
            $schedules[]=$temp;
            $dashboard['dashboard_schedule_num']++;
            $dashboard['dashboard_hour_num']+=$temp['total_hour'];
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
         // 获取用户校区权限
         $department_access = Session::get('department_access');
         // 搜索条件
         $filters = array(
                         "filter_user" => null,
                         "filter_date_start" => date('Y-m')."-01",
                         "filter_date_end" => date('Y-m-d')
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
         if ($request->filled('filter_user')) {
             $rows = $rows->where('schedule_teacher', '=', $request->input("filter_user"));
             $filters['filter_user']=$request->input("filter_user");
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
         $rows = $rows->groupBy('schedule_id')
                      ->orderBy('schedule_date', 'desc')
                      ->get();
         // 转为数组并获取详细课程信息
         $dashboard = array(
                              "dashboard_schedule_num" => 0,
                              "dashboard_hour_num" => 0,
                              "dashboard_attended_num" => 0,
                              "dashboard_leave_num" => 0,
                            );

         $schedules=array();
         foreach($rows as $row){
             $temp=array();
             $temp['department_name']=$row->department_name;
             $temp['class_id']=$row->class_id;
             $temp['class_name']=$row->class_name;
             $temp['schedule_attended_num']=$row->schedule_attended_num;
             $temp['schedule_leave_num']=$row->schedule_leave_num;
             $temp['schedule_absence_num']=$row->schedule_absence_num;
             $temp['grade_name']=$row->grade_name;
             $temp['subject_name']=$row->subject_name;
             $temp['schedule_date']=$row->schedule_date;
             $temp['schedule_start']=$row->schedule_start;
             $temp['schedule_end']=$row->schedule_end;
             $temp['total_hour']=$row->total_hour;
             $temp['user_id']=$row->user_id;
             $temp['user_name']=$row->user_name;
             $temp['schedule_id']=$row->schedule_id;
             $schedules[]=$temp;
             $dashboard['dashboard_schedule_num']++;
             $dashboard['dashboard_hour_num']+=$temp['total_hour'];
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
