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

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('class_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 班级年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 班级科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter4'));
            $filter_status = 1;
        }
        // 负责教师
        if ($request->filled('filter5')) {
            $rows = $rows->where('class_teacher', '=', $request->input('filter5'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('class_id', 'asc')
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
                $temp_member[] = $temp;
            }
            $members[] = $temp_member;
            //获取课程安排
            $temp_schedule = array();
            $db_schedules = DB::table('schedule')
                              ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                              ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                              ->where('schedule_participant', $row->class_id)
                              ->get();
            foreach($db_schedules as $db_schedule){
                $temp = array();
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
                                              'totalNum' => $totalNum,
                                              'filter_status' => $filter_status,
                                              'filter_departments' => $filter_departments,
                                              'filter_grades' => $filter_grades,
                                              'filter_subjects' => $filter_subjects,
                                              'filter_users' => $filter_users]);
    }

}
