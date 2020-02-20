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
    public function schedule($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $schedule = DB::table('schedule')
                      ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                      ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->where('schedule_id', $schedule_id)
                      ->first();
        return view('schedule/schedule', ['schedule' => $schedule]);
    }

    /**
     * 上课记录详情视图
     * URL: GET /attendedSchedule/{participant_id}
     * @param  int  $participant_id
     */
    public function attendedSchedule($participant_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $schedule = DB::table('participant')
                      ->join('schedule', 'participant.participant_schedule', '=', 'schedule.schedule_id')
                      ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                      ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->where('participant_id', $participant_id)
                      ->first();
        // 获取上课成员数据信息
        $participants = DB::table('participant')
                          ->join('student', 'participant.participant_student', '=', 'student.student_id')
                          ->join('hour', 'participant.participant_hour', '=', 'hour.hour_id')
                          ->join('course', 'hour.hour_course', '=', 'course.course_id')
                          ->where('participant.participant_schedule', $schedule->schedule_id)
                          ->get();
        return view('schedule/attendedSchedule', ['schedule' => $schedule,
                                                  'participants' => $participants]);
    }

    /**
     * 上课记录复核
     * URL: GET /attendedSchedule/{participant_id}/check
     * @param  int  $participant_id
     */
    public function attendedScheduleCheck($participant_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取上课成员数据信息
        $participants = DB::table('participant')
                          ->join('student', 'participant.participant_student', '=', 'student.student_id')
                          ->where('participant.participant_id', $participant_id)
                          ->first();
        if($participants->student_class_adviser!=Session::get('user_id')){
            return redirect("/operation/attendedSchedule/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '上课记录复核失败',
                          'message' => '非学生班主任操作，上课记录复核失败！']);
        }
        DB::beginTransaction();
        // 插入数据库
        try{
            DB::table('participant')
              ->where('participant.participant_id', $participant_id)
              ->update(['participant_checked' => 1,
                        'participant_checked_user' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            // 返回我的学生上课记录
            return redirect("/operation/attendedSchedule/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '上课记录复核失败',
                          'message' => '上课记录复核失败，请联系系统管理员！']);
        }
        DB::commit();
        return redirect("/operation/attendedSchedule/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '上课记录复核成功',
                      'message' => '上课记录复核成功！']);
    }

}
