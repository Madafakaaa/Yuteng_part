<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CalendarController extends Controller
{

    /**
     * 课程表
     * URL: GET /calendar
     */
    public function calendar(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_schedule = Session::get('user_schedule');
        // 获取表单信息
        $date = date('Y-m-d');

        // 获取一周日期数组
        $diff=array(6, 0, 1, 2, 3, 4, 5);
        $first_day = date('Y-m-d', strtotime ("-".$diff[date("w",strtotime($date))]." day", strtotime($date)));
        $days = array();
        for($i=0; $i<7; $i++){
            $days[] = date('Y-m-d', strtotime ("+".$i." day", strtotime($first_day)));;
        }
        // 获取反转日期数组
        $days_fliped = array_flip($days);

        // 创建上课时间数组
        $times = array("08:00:00", "08:30:00", "09:00:00", "09:30:00",
                       "10:00:00", "10:30:00", "11:00:00", "11:30:00",
                       "12:00:00", "12:30:00", "13:00:00", "13:30:00",
                       "14:00:00", "14:30:00", "15:00:00", "15:30:00",
                       "16:00:00", "16:30:00", "17:00:00", "17:30:00",
                       "18:00:00", "18:30:00", "19:00:00", "19:30:00",
                       "20:00:00", "20:30:00", "21:00:00", "21:30:00",
                       "22:00:00");
        // 获取反转时间数组
        $times_fliped = array_flip($times);

        // 获取课程表信息
        $schedules = DB::table('schedule')
                       ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                       ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                       ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                       ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                       ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                       ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                       ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                       ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                       ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                       ->where('schedule_date', '>=', $days[0])
                       ->where('schedule_date', '<=', $days[6]);
        // 添加筛选条件
        // 学生、班级筛选
        if ($request->filled('filter1')) {
            $schedules = $schedules->where('schedule_participant', '=', $request->input('filter1'));
        }
        $schedules = $schedules->get();
        // 创建课程表数据
        $calendar = array(); // -1：没有课程，-2：已有课程占位，其它：$schedules中index
        for($i=0; $i<29; $i++){
            for($j=0; $j<7; $j++){
                $calendar[$i][$j] = -1;
            }
        }
        // 课程安排插入课程表
        foreach($schedules as $index => $schedule){
            $date_index = $days_fliped[$schedule->schedule_date];
            $start_time_index = $times_fliped[$schedule->schedule_start];
            $end_time_index = $times_fliped[$schedule->schedule_end];
            $calendar[$start_time_index][$date_index] = $index;
            for($i=$start_time_index+1; $i<$end_time_index; $i++){
                $calendar[$i][$date_index] = -2;
            }
        }
        // 获取筛选数据
        // 获取学生、班级信息
        $students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        // 获取教师信息
        $users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();
        $ch_str = array('零', '一', '二', '三', '四', '五', '六', '日');
        // 返回课程表视图
        return view('calendar/calendar', ['students' => $students,
                                          'classes' => $classes,
                                          'schedules' => $schedules,
                                          'calendar' => $calendar,
                                          'days' => $days,
                                          'times' => $times,
                                          'ch_str' => $ch_str]);
    }


}
