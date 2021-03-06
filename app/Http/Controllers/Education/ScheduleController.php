<?php
namespace App\Http\Controllers\Education;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ScheduleController extends Controller
{
    /**
     * 本校班级课程安排视图
     * URL: GET /operation/schedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function schedule(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/schedule", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
                  ->where('schedule_attended', '=', 0);

        // 数据范围权限
        if (Session::get('user_access_self')==1) {
            $rows = $rows->where('schedule_teacher', '=', Session::get('user_id'));
        }
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
                     ->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
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
        return view('education/schedule/schedule', ['rows' => $rows,
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

}
