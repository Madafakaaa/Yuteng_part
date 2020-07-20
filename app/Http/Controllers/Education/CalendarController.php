<?php
namespace App\Http\Controllers\Education;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CalendarController extends Controller
{

    public function calendarWeek(Request $request)
    {
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_ids = Session::get('department_access');

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_subject" => null,
                        "filter_date" => null,
                    );

        // 获取日期
        if ($request->filled('filter_date')) {
            $date = $request->input("filter_date");
            $filters['filter_date'] = $request->input("filter_date");
        }else{
            $date = date('Y-m-d');
        }
        // 班级校区
        if ($request->filled('filter_department')) {
            $filters['filter_department']=$request->input("filter_department");
        }
        // 班级年级
        if ($request->filled('filter_grade')) {
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 班级科目
        if ($request->filled('filter_subject')) {
            $filters['filter_subject']=$request->input("filter_subject");
        }

        // 获取周一、周日日期
        $diff = array(6, 0, 1, 2, 3, 4, 5);
        $first_day = date('Y-m-d', strtotime ("-".$diff[date("w",strtotime($date))]." day", strtotime($date)));
        $last_day = date('Y-m-d', strtotime ("+6 day", strtotime($first_day)));
        // 获取上周周一日期
        $first_day_prev = date('Y-m-d', strtotime ("-7 day", strtotime($first_day)));
        // 获取下周周一日期
        $first_day_next = date('Y-m-d', strtotime ("+7 day", strtotime($first_day)));

        // 生成（校区分类）颜色
        $attended_border_color = '#00FF7F';
        $unattended_border_color = '#FF4040';

        // 生成校区分类
        $calendars = array();
        $index = 0;
        foreach($department_ids as $department_id){
            $department = DB::table('department')
                            ->where('department_id', $department_id)
                            ->first();

            $unattended_calendar=array();
            $unattended_calendar['id']=$department_id.'_unattended';
            $unattended_calendar['name']=$department->department_name.' - 未点名';
            $unattended_calendar['color']='#FFFFFF';
            $unattended_calendar['bgColor']=getColor($index);
            $unattended_calendar['dragBgColor']=getColor($index);
            $unattended_calendar['borderColor']=$unattended_border_color;

            $attended_calendar=array();
            $attended_calendar['id']=$department_id.'_attended';
            $attended_calendar['name']=$department->department_name;
            $attended_calendar['color']='#FFFFFF';
            $attended_calendar['bgColor']=getColor($index);
            $attended_calendar['dragBgColor']=getColor($index);
            $attended_calendar['borderColor']=$attended_border_color;

            $calendars[] = $unattended_calendar;
            $calendars[] = $attended_calendar;

            $index++;
        }

        // 课程表内容数据数组
        $rows = array();
        // 获取课程安排
        $schedules = DB::table('schedule')
                       ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                       ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                       ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                       ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                       ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                       ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                       ->whereIn('schedule_department', $department_ids)
                       ->where('schedule_attended', '=', 0)
                       ->where('schedule_date', '>=', $first_day)
                       ->where('schedule_date', '<=', $last_day);
        // 班级校区
        if ($request->filled('filter_department')) {
            $schedules = $schedules->where('schedule_department', '=', $request->input("filter_department"));
        }
        // 班级年级
        if ($request->filled('filter_grade')) {
            $schedules = $schedules->where('schedule_grade', '=', $request->input('filter_grade'));
        }
        // 班级科目
        if ($request->filled('filter_subject')) {
            $schedules = $schedules->where('schedule_subject', '=', $request->input('filter_subject'));
        }
        $schedules = $schedules->get();

        foreach($schedules as $schedule){
            $temp = array();
            $temp['calendarId'] = $schedule->schedule_department.'_unattended';
            $temp['title'] = $schedule->class_name." ".$schedule->grade_name." ".$schedule->subject_name;
            $temp['body'] = "<a href='javascript:history.go(-1)'><button type='button' class='btn btn-primary btn-sm'>点名</button></a>";
            $temp['location'] = $schedule->department_name." ".$schedule->classroom_name;
            $temp['start']= $schedule->schedule_date." ".$schedule->schedule_start;
            $temp['end']= $schedule->schedule_date." ".$schedule->schedule_end;
            $temp['teacher'] = $schedule->user_name;
            $temp['schedule_id'] = encode($schedule->schedule_id, 'schedule_id');
            $temp['attended'] = 0;
            // 获取班级成员
            $temp['attendees'] = array();
            $attendees = DB::table('member')
                           ->join('student', 'member.member_student', '=', 'student.student_id')
                           ->where('member_class', '=', $schedule->schedule_participant)
                           ->get();
            foreach($attendees as $attendee){
                $temp['attendees'][] = $attendee->student_name;
            }
            $rows[] = $temp;
        }

        // 获取上课记录
        $attended_schedules = DB::table('schedule')
                                ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                                ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                                ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                                ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                                ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                                ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                                ->whereIn('schedule_department', $department_ids)
                                ->where('schedule_attended', '=', 1)
                                ->where('schedule_date', '>=', $first_day)
                                ->where('schedule_date', '<=', $last_day);

        // 班级校区
        if ($request->filled('filter_department')) {
            $attended_schedules = $attended_schedules->where('schedule_department', '=', $request->input("filter_department"));
        }
        // 班级年级
        if ($request->filled('filter_grade')) {
            $attended_schedules = $attended_schedules->where('schedule_grade', '=', $request->input('filter_grade'));
        }
        // 班级科目
        if ($request->filled('filter_subject')) {
            $attended_schedules = $attended_schedules->where('schedule_subject', '=', $request->input('filter_subject'));
        }
        $attended_schedules = $attended_schedules->get();

        foreach($attended_schedules as $schedule){
            $temp = array();
            $temp['calendarId'] = $schedule->schedule_department.'_attended';
            $temp['title'] = $schedule->class_name." ".$schedule->grade_name." ".$schedule->subject_name;
            $temp['body'] = "<a href='javascript:history.go(-1)' ><button type='button' class='btn btn-primary btn-sm disabled'>已点名</button></a>";
            $temp['location'] = $schedule->department_name." ".$schedule->classroom_name;
            $temp['start']= $schedule->schedule_date." ".$schedule->schedule_start;
            $temp['end']= $schedule->schedule_date." ".$schedule->schedule_end;
            $temp['teacher'] = $schedule->user_name;
            $temp['schedule_id'] = encode($schedule->schedule_id, 'schedule_id');
            $temp['attended'] = 1;
            // 获取上课成员
            $temp['attendees'] = array();
            $attendees = DB::table('participant')
                           ->join('student', 'participant.participant_student', '=', 'student.student_id')
                           ->where('participant_schedule', '=', $schedule->schedule_id)
                           ->get();
            foreach($attendees as $attendee){
                $temp['attendees'][] = $attendee->student_name;
            }
            $rows[] = $temp;
        }

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_ids)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        return view('education/calendar/week', ['calendars' => $calendars,
                                                'rows' => $rows,
                                                'first_day' => $first_day,
                                                'last_day' => $last_day,
                                                'first_day_prev' => $first_day_prev,
                                                'first_day_next' => $first_day_next,
                                                'filters' => $filters,
                                                'filter_departments' => $filter_departments,
                                                'filter_grades' => $filter_grades,
                                                'filter_subjects' => $filter_subjects]);
    }

}
