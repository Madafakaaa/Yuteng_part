<?php
namespace App\Http\Controllers\Operation;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class AttendedScheduleController extends Controller
{
    /**
     * 我的班级课程安排视图
     * URL: GET /operation/attendedSchedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function attendedSchedule(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->Join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('user AS creator', 'schedule.schedule_createuser', '=', 'creator.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->whereIn('schedule_department', $department_access)
                  ->where('schedule_attended', '=', 1);

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_class" => null,
                        "filter_subject" => null,
                        "filter_teacher" => null,
                        "filter_date" => null,
                    );

        // 班级校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('class_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 班级年级
        if ($request->filled('filter_grade')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter_grade'));
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 班级科目
        if ($request->filled('filter_subject')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter_subject'));
            $filters['filter_subject']=$request->input("filter_subject");
        }
        // 班级
        if ($request->filled('filter_class')) {
            $rows = $rows->where('class_id', '=', $request->input('filter_class'));
            $filters['filter_class']=$request->input("filter_class");
        }
        // 负责教师
        if ($request->filled('filter_teacher')) {
            $rows = $rows->where('class_teacher', '=', $request->input('filter_teacher'));
            $filters['filter_teacher']=$request->input("filter_teacher");
        }
        // 上课日期
        if ($request->filled('filter_date')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter_date'));
            $filters['filter_date']=$request->input("filter_date");
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->select('schedule_id',
                              'schedule_date',
                              'schedule_start',
                              'schedule_end',
                              'class_id',
                              'class_name',
                              'class_max_num',
                              'class_current_num',
                              'teacher.user_id AS teacher_id',
                              'teacher.user_name AS teacher_name',
                              'creator.user_id AS creator_id',
                              'creator.user_name AS creator_name',
                              'department_name',
                              'subject_name',
                              'grade_name',
                              'classroom_name',
                              'course_name')
                     ->orderBy('schedule_date', 'desc')
                     ->orderBy('schedule_start', 'desc')
                     ->orderBy('schedule_time', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        $filter_users = DB::table('user')
                          ->join('department', 'user.user_department', '=', 'department.department_id')
                          ->join('position', 'user.user_position', '=', 'position.position_id')
                          ->where('user_status', 1)
                          ->whereIn('user_department', $department_access)
                          ->orderBy('user_department', 'asc')
                          ->orderBy('user_position', 'desc')
                          ->get();
        $filter_classes = DB::table('class')
                          ->join('department', 'class.class_department', '=', 'department.department_id')
                          ->where('class_status', 1)
                          ->whereIn('class_department', $department_access)
                          ->orderBy('class_department', 'asc')
                          ->orderBy('class_grade', 'asc')
                          ->get();

        // 返回列表视图
        return view('operation/attendedSchedule/attendedSchedule', ['rows' => $rows,
                                                                   'currentPage' => $currentPage,
                                                                   'totalPage' => $totalPage,
                                                                   'startIndex' => $offset,
                                                                   'request' => $request,
                                                                   'filters' => $filters,
                                                                   'totalNum' => $totalNum,
                                                                   'filter_departments' => $filter_departments,
                                                                   'filter_grades' => $filter_grades,
                                                                   'filter_subjects' => $filter_subjects,
                                                                   'filter_classes' => $filter_classes,
                                                                   'filter_users' => $filter_users]);
    }

    public function attendedScheduleDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取schedule_id
        $request_ids=$request->input('id');
        $schedule_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $schedule_ids[]=decode($request_id, 'schedule_id');
            }
        }else{
            $schedule_ids[]=decode($request_ids, 'schedule_id');
        }
        // 删除数据
        try{
            foreach ($schedule_ids as $schedule_id){
                // 获取上课记录和成员记录信息
                $schedule = DB::table('schedule')
                              ->where('schedule_id', $schedule_id)
                              ->first();
                $participants = DB::table('participant')
                                  ->where('participant_schedule', $schedule_id)
                                  ->get();
                // 恢复课时
                foreach ($participants as $participant){
                    // 增加学生剩余课时
                    DB::table('hour')
                      ->where('hour_course', $participant->participant_course)
                      ->where('hour_student', $participant->participant_student)
                      ->increment('hour_remain', $participant->participant_amount);
                    // 减少学生已用课时
                    DB::table('hour')
                      ->where('hour_course', $participant->participant_course)
                      ->where('hour_student', $participant->participant_student)
                      ->decrement('hour_used', $participant->participant_amount);
                }
                // 删除上课记录和成员记录
                DB::table('schedule')
                  ->where('schedule_id', $schedule_id)
                  ->delete();
                DB::table('participant')
                  ->where('participant_schedule', $schedule_id)
                  ->delete();
                // 更新班级信息,减少上课记录数量
                DB::table('class')
                  ->where('class_id', $schedule->schedule_participant)
                  ->decrement('class_attended_num');
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/attendedSchedule")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '上课记录删除失败',
                         'message' => '上课记录删除失败，错误码:329']);
        }
        // 返回课程列表
        return redirect("/operation/attendedSchedule")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '上课记录删除成功',
                       'message' => '上课记录删除成功!']);
    }

}
