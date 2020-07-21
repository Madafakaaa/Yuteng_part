<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ScheduleController extends Controller
{

    /**
     * 安排详情视图
     * URL: GET /schedule/{schedule_id}
     * @param  int  $schedule_id
     */
    public function schedule(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $schedule_id = decode($request->input('id'), 'schedule_id');
        // 获取数据信息
        $schedule = DB::table('schedule')
                      ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                      ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->where('schedule_id', $schedule_id)
                      ->first();
        // 获取学生信息
        $members = DB::table('member')
                      ->join('student', 'member.member_student', '=', 'student.student_id')
                      ->join('course', 'member.member_course', '=', 'course.course_id')
                      ->join('hour', [
                                       ['hour.hour_student', '=', 'member.member_student'],
                                       ['hour.hour_course', '=', 'member.member_course'],
                                     ])
                      ->where('member_class', $schedule->schedule_participant)
                      ->get();
        return view('schedule/schedule', ['schedule' => $schedule,
                                          'members' => $members]);
    }

    /**
     * 上课记录视图
     * URL: GET /attendedSchedule
     * @param  int  $schedule_id
     */
    public function attendedSchedule(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $schedule_id = decode($request->input('id'), 'schedule_id');
        // 获取数据信息
        $schedule = DB::table('schedule')
                      ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                      ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->where('schedule_id', $schedule_id)
                      ->first();
        // 获取学生信息
        $members = DB::table('participant')
                      ->join('schedule', 'participant.participant_schedule', '=', 'schedule.schedule_id')
                      ->join('student', 'participant.participant_student', '=', 'student.student_id')
                      ->leftJoin('course', 'participant.participant_course', '=', 'course.course_id')
                      ->leftJoin('hour', [
                                       ['hour.hour_student', '=', 'participant.participant_student'],
                                       ['hour.hour_course', '=', 'participant.participant_course'],
                                     ])
                      ->where('schedule_id', $schedule_id)
                      ->get();
        return view('schedule/attendedSchedule', ['schedule' => $schedule,
                                                  'members' => $members]);
    }

}
