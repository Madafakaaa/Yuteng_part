<?php
namespace App\Http\Controllers\Education;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class StudentController extends Controller
{
    /**
     * 全部学生视图
     * URL: GET /operation/student
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function student(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/student", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                  ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                  ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                  ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->whereIn('student_department', $department_access)
                  ->where('student_contract_num', '>', 0)
                  ->where('student_status', 1);

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_student" => null,
                        "filter_consultant" => null,
                        "filter_class_adviser" => null,
                    );

        // 客户校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('student_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 客户年级
        if ($request->filled('filter_grade')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter_grade'));
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 客户名称
        if ($request->filled('filter_student')) {
            $rows = $rows->where('student_id', '=', $request->input('filter_student'));
            $filters['filter_student']=$request->input("filter_student");
        }
        // 课程顾问
        if ($request->filled('filter_consultant')) {
            $rows = $rows->where('student_consultant', '=', $request->input('filter_consultant'));
            $filters['filter_consultant']=$request->input("filter_consultant");
        }
        // 班主任
        if ($request->filled('filter_class_adviser')) {
            $rows = $rows->where('student_class_adviser', '=', $request->input('filter_class_adviser'));
            $filters['filter_class_adviser']=$request->input("filter_class_adviser");
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->select('student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'student.student_gender AS student_gender',
                              'student.student_guardian AS student_guardian',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_id AS consultant_id',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_id AS class_adviser_id',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 转为数组并获取学生课时信息
        $students = array();
        foreach($rows as $row){
            $temp = array();
            $temp['student_id']=$row->student_id;
            $temp['student_name']=$row->student_name;
            $temp['student_gender']=$row->student_gender;
            $temp['student_guardian']=$row->student_guardian;
            $temp['department_name']=$row->department_name;
            $temp['grade_name']=$row->grade_name;
            $temp['consultant_id']=$row->consultant_id;
            $temp['consultant_name']=$row->consultant_name;
            $temp['consultant_position_name']=$row->consultant_position_name;
            $temp['class_adviser_id']=$row->class_adviser_id;
            $temp['class_adviser_name']=$row->class_adviser_name;
            $temp['class_adviser_position_name']=$row->class_adviser_position_name;
            $temp['student_hour_num'] = 0;
            $student_hours = array();
            $hours = DB::table('hour')
                       ->join('course', 'hour.hour_course', '=', 'course.course_id')
                       ->where('hour_student', '=', $row->student_id)
                       ->get();
            foreach($hours as $hour){
                $hour_temp = array();
                $hour_temp['course_id']=$hour->course_id;
                $hour_temp['course_name']=$hour->course_name;
                $hour_temp['hour_remain']=$hour->hour_remain;
                $hour_temp['hour_used']=$hour->hour_used;
                $temp['student_hour_num']++;
                $student_hours[] = $hour_temp;
            }
            $temp['student_hours'] = $student_hours;
            $students[] = $temp;
        }

        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_users = DB::table('user')
                          ->join('department', 'user.user_department', '=', 'department.department_id')
                          ->join('position', 'user.user_position', '=', 'position.position_id')
                          ->where('user_status', 1)
                          ->whereIn('user_department', $department_access)
                          ->orderBy('user_department', 'asc')
                          ->orderBy('user_position', 'desc')
                          ->get();
        $filter_students = DB::table('student')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->where('student_status', 1)
                             ->where('student_contract_num', '>', 0)
                             ->whereIn('student_department', $department_access)
                             ->orderBy('student_department', 'asc')
                             ->orderBy('student_grade', 'asc')
                             ->get();
        // 返回列表视图
        return view('education/student/student', ['students' => $students,
                                                  'currentPage' => $currentPage,
                                                  'totalPage' => $totalPage,
                                                  'startIndex' => $offset,
                                                  'request' => $request,
                                                  'filters' => $filters,
                                                  'totalNum' => $totalNum,
                                                  'filter_departments' => $filter_departments,
                                                  'filter_grades' => $filter_grades,
                                                  'filter_students' => $filter_students,
                                                  'filter_users' => $filter_users]);
    }

}
