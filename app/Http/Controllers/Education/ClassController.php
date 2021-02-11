<?php
namespace App\Http\Controllers\Education;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ClassController extends Controller
{
    /**
     * 全部班级视图
     * URL: GET /operation/class
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function class(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/class", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('class')
                  ->join('department', 'class.class_department', '=', 'department.department_id')
                  ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                  ->leftJoin('subject', 'class.class_subject', '=', 'subject.subject_id')
                  ->join('user', 'class.class_teacher', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->whereIn('class_department', $department_access)
                  ->where('class_status', 1);

        // 数据范围权限
        if (Session::get('user_access_self')==1) {
            $rows = $rows->where('class_teacher', '=', Session::get('user_id'));
        }
        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_class" => null,
                        "filter_subject" => null,
                        "filter_teacher" => null,
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
        // 班级名称
        if ($request->filled('filter_class')) {
            $rows = $rows->where('class_id', '=', $request->input('filter_class'));
            $filters['filter_class']=$request->input("filter_class");
        }
        // 负责教师
        if ($request->filled('filter_teacher')) {
            $rows = $rows->where('class_teacher', '=', $request->input('filter_teacher'));
            $filters['filter_teacher']=$request->input("filter_teacher");
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('class_department', 'asc')
                     ->orderBy('class_grade', 'asc')
                     ->orderBy('class_subject', 'asc')
                     ->orderBy('class_max_num', 'asc')
                     ->orderBy('class_current_num', 'asc')
                     ->orderBy('class_schedule_num', 'desc')
                     ->orderBy('class_attended_num', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、科目信息(筛选)
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

        $members = array();
        $schedules = array();
        foreach($rows as $row){
            //获取班级成员
            $temp_member = array();
            $db_members = DB::table('member')
                            ->join('student', 'member.member_student', '=', 'student.student_id')
                            ->where('member_class', $row->class_id)
                            ->get();
            foreach($db_members as $db_member){
                $temp = array();
                $temp['student_name'] = $db_member->student_name;
                $temp['student_id'] = $db_member->student_id;
                $temp_member[] = $temp;
            }
            $members[] = $temp_member;
            //获取课程安排
            $temp_schedule = array();
            $db_schedules = DB::table('schedule')
                              ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                              ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                              ->where('schedule_participant', $row->class_id)
                              ->where('schedule_attended', 0)
                              ->get();
            foreach($db_schedules as $db_schedule){
                $temp = array();
                $temp['schedule_id'] = $db_schedule->schedule_id;
                $temp['schedule_date'] = $db_schedule->schedule_date;
                $temp['schedule_start'] = $db_schedule->schedule_start;
                $temp['schedule_end'] = $db_schedule->schedule_end;
                $temp['user_name'] = $db_schedule->user_name;
                $temp_schedule[] = $temp;
            }
            $schedules[] = $temp_schedule;
        }

        // 返回列表视图
        return view('education/class/class', ['rows' => $rows,
                                              'members' => $members,
                                              'schedules' => $schedules,
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
