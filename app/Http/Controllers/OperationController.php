<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class OperationController extends Controller
{

    /**
     * 修改负责人视图
     * URL: GET /market/follower/edit
     */
    public function followerEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        if($request->filled('student_id')) {
            $student_id = $request->input('student_id');
        }else{
            $student_id = '';
        }
        // 获取学生信息
        $students = DB::table('student')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->where('student_department', Session::get('user_department'))
                      ->where('student_customer_status', 1)
                      ->orderBy('student_grade', 'asc')
                      ->get();
        return view('operation/followerEdit', ['students' => $students, 'student_id' => $student_id]);
    }

    /**
     * 修改负责人视图2
     * URL: GET /market/follower/edit2
     */
    public function followerEdit2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('input1');
        // 获取学生信息
        $student = DB::table('student')
                      ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                      ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                      ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                      ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                      ->where('student_id', $student_id)
                      ->select('student.student_id AS student_id',
                                'student.student_name AS student_name',
                                'student.student_customer_status AS student_customer_status',
                                'student.student_consultant AS student_consultant',
                                'student.student_class_adviser AS student_class_adviser',
                                'consultant.user_name AS consultant_name',
                                'consultant_position.position_name AS consultant_position_name',
                                'class_adviser.user_name AS class_adviser_name',
                                'class_adviser_position.position_name AS class_adviser_position_name')
                      ->first();
        // 获取负责人信息
        $users = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_department', Session::get('user_department'))
                  ->where('user_status', 1)
                  ->get();
        return view('operation/followerEdit2', ['student' => $student, 'users' => $users]);
    }

    /**
     * 修改负责人提交
     * URL: GET /market/follower/store
     */
    public function followerStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('input1');
        if($request->filled('input2')) {
            $student_consultant = $request->input('input2');
        }else{
            $student_consultant = "";
        }
        if($request->filled('input3')) {
            $student_class_adviser = $request->input('input3');
        }else{
            $student_class_adviser = "";
        }
        // 插入数据库
        DB::beginTransaction();
        try{
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_consultant' =>  $student_consultant,
                        'student_class_adviser' =>  $student_class_adviser]);
            // 插入学生动态
            //
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            return redirect("/operation/follower/edit")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '负责人修改失败',
                           'message' => '负责人修改失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/operation/student/department")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '负责人修改成功',
                      'message' => '负责人修改成功']);
    }

    /**
     * 全部学生视图
     * URL: GET /operation/student/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function studentAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                  ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                  ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                  ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
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
                              'student.student_guardian_relationship AS student_guardian_relationship',
                              'student.student_phone AS student_phone',
                              'student.student_follow_level AS student_follow_level',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'student.student_customer_status AS student_customer_status',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('operation/studentAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 本校学生视图
     * URL: GET /operation/student/department
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function studentDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                  ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                  ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                  ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_department', Session::get('user_department'))
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
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
                              'student.student_guardian_relationship AS student_guardian_relationship',
                              'student.student_phone AS student_phone',
                              'student.student_follow_level AS student_follow_level',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'student.student_customer_status AS student_customer_status',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('operation/studentDepartment', ['rows' => $rows,
                                                   'currentPage' => $currentPage,
                                                   'totalPage' => $totalPage,
                                                   'startIndex' => $offset,
                                                   'request' => $request,
                                                   'totalNum' => $totalNum,
                                                   'filter_departments' => $filter_departments,
                                                   'filter_grades' => $filter_grades]);
    }

    /**
     * 我的学生视图
     * URL: GET /operation/student/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function studentMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                  ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                  ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                  ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_class_adviser', Session::get('user_id'))
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
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
                              'student.student_guardian_relationship AS student_guardian_relationship',
                              'student.student_phone AS student_phone',
                              'student.student_follow_level AS student_follow_level',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'student.student_customer_status AS student_customer_status',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('operation/studentMy', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 新建班级视图
     * URL: GET /operation/class/create
     */
    public function classCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取年级、科目、用户信息
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_cross_teaching', '=', 1)
                   ->where('user_department', '<>', Session::get('user_department'))
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->orderBy('user_department', 'asc');
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_department', '=', Session::get('user_department'))
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->union($users)
                   ->get();
        return view('operation/classCreate', ['grades' => $grades,
                                              'subjects' => $subjects,
                                              'users' => $users]);
    }

    /**
     * 新建班级提交
     * URL: POST /operation/class/store
     * @param  Request  $request
     * @param  $request->input('input1'): 班级名称
     * @param  $request->input('input2'): 班级校区
     * @param  $request->input('input3'): 班级年级
     * @param  $request->input('input4'): 班级科目
     * @param  $request->input('input5'): 负责教师
     * @param  $request->input('input6'): 班级人数
     * @param  $request->input('input7'): 班级备注
     */
    public function classStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $class_name = $request->input('input1');
        $class_department = $request->input('input2');
        $class_grade = $request->input('input3');
        $class_subject = $request->input('input4');
        $class_teacher = $request->input('input5');
        $class_max_num = $request->input('input6');
        if($request->filled('input7')) {
            $class_remark = $request->input('input7');
        }else{
            $class_remark = '无';
        }
        // 获取当前用户ID
        $class_createuser = Session::get('user_id');
        // 生成新班级ID
        $class_num = DB::table('class')
                       ->where('class_department', $class_department)
                       ->whereYear('class_createtime', date('Y'))
                       ->whereMonth('class_createtime', date('m'))
                       ->count()+1;
        $class_id = "C".substr(date('Ym'),2).sprintf("%02d", $class_department).sprintf("%03d", $class_num);
        // 插入数据库
        try{
            DB::table('class')->insert(
                ['class_id' => $class_id,
                 'class_name' => $class_name,
                 'class_department' => $class_department,
                 'class_grade' => $class_grade,
                 'class_subject' => $class_subject,
                 'class_teacher' => $class_teacher,
                 'class_max_num' => $class_max_num,
                 'class_remark' => $class_remark,
                 'class_createuser' => $class_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/class/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '班级添加失败',
                           'message' => '班级添加失败，请重新输入信息']);
        }
        // 返回班级列表
        return redirect("/operation/class/department")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '班级添加成功',
                       'message' => '班级名称: '.$class_name.', 班级学号: '.$class_id]);
    }

    /**
     * 全部班级视图
     * URL: GET /operation/class/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function classAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('class')
                  ->join('department', 'class.class_department', '=', 'department.department_id')
                  ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                  ->leftJoin('subject', 'class.class_subject', '=', 'subject.subject_id')
                  ->join('user', 'class.class_teacher', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->where('class_status', 1);

        // 添加筛选条件
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 班级校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('class_department', '=', $request->input('filter2'));
        }
        // 班级年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter3'));
        }
        // 班级科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter4'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('class_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();

        // 返回列表视图
        return view('operation/classAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades,
                                           'filter_subjects' => $filter_subjects]);
    }

    /**
     * 本校班级视图
     * URL: GET /operation/class/department
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function classDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('class')
                  ->join('department', 'class.class_department', '=', 'department.department_id')
                  ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                  ->leftJoin('subject', 'class.class_subject', '=', 'subject.subject_id')
                  ->join('user', 'class.class_teacher', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->where('class_department', Session::get('user_department'))
                  ->where('class_status', 1);

        // 添加筛选条件
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 班级校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('class_department', '=', $request->input('filter2'));
        }
        // 班级年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter3'));
        }
        // 班级科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter4'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('class_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();

        // 返回列表视图
        return view('operation/classDepartment', ['rows' => $rows,
                                                  'currentPage' => $currentPage,
                                                  'totalPage' => $totalPage,
                                                  'startIndex' => $offset,
                                                  'request' => $request,
                                                  'totalNum' => $totalNum,
                                                  'filter_departments' => $filter_departments,
                                                  'filter_grades' => $filter_grades,
                                                  'filter_subjects' => $filter_subjects]);
    }

    /**
     * 安排学生课程视图
     * URL: GET /operation/studentSchedule/create
     */
    public function studentScheduleCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('operation/studentScheduleCreate');
    }

    /**
     * 不规律安排学生课程视图
     * URL: GET /operation/studentSchedule/createIrregular
     */
    public function studentScheduleCreateIrregular(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('operation/studentScheduleCreateIrregular');
    }

    /**
     * 安排学生课程视图2
     * URL: GET /operation/studentSchedule/create2
     * @param  Request  $request
     * @param  $request->input('input1'): 上课日期
     * @param  $request->input('input2'): 上课时间
     * @param  $request->input('input3'): 下课时间
     */
    public function studentScheduleCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_date_start = $request->input('input1');
        $schedule_date_end = $request->input('input2');
        $schedule_days = $request->input('input3');
        $schedule_start = $request->input('input4');
        $schedule_end = $request->input('input5');
        // 判断Checkbox是否为空
        if(!isset($schedule_days)){
            return redirect("/operation/studentSchedule/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '未选择上课规律',
                           'message' => '至少选择一天上课规律，请重新输入！']);
        }
        // 表单输入数据处理
        $schedule_date_start = date('Y-m-d', strtotime( $schedule_date_start));
        $schedule_date_end = date('Y-m-d', strtotime( $schedule_date_end));
        $schedule_date_temp = $schedule_date_start;
        $schedule_dates_str = "";
        while($schedule_date_temp <= $schedule_date_end){
            foreach($schedule_days as $schedule_day){
                if(date("w", strtotime($schedule_date_temp))==$schedule_day){
                    if($schedule_dates_str==""){
                        $schedule_dates_str.=$schedule_date_temp;
                    }else{
                        $schedule_dates_str.=",".$schedule_date_temp;
                    }
                    break;
                }
            }
            $schedule_date_temp = date('Y-m-d', strtotime ("+1 day", strtotime($schedule_date_temp)));
        }

        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        if($schedule_date_num>50){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '请选择重新上课日期',
                                                       'message' => '上课日期数量过多，超过最大上限50！']);
        }
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/schedule/create")->with(['notify' => true,
                                                           'type' => 'danger',
                                                           'title' => '请选择重新上课日期',
                                                           'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $time_start = date('H:i', strtotime($schedule_start));
        $time_end = date('H:i', strtotime($schedule_end));
        if($time_start>=$time_end){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '请重新选择上课、下课时间',
                                                       'message' => '上课时间须在下课时间前！']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);
        // 获取所有本校学生名单
        $db_students = DB::table('student')
                         ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                         ->where('student_department', Session::get('user_department'))
                         ->where('student_status', 1)
                         ->orderBy('student_grade', 'asc')
                         ->orderBy('student_name', 'asc')
                         ->get();
        $student_num = count($db_students);
        $students = array();
        for($i=0;$i<$student_num;$i++){
            $students[$db_students[$i]->student_id] = array($db_students[$i]->student_id, $db_students[$i]->student_name, 0, $db_students[$i]->grade_name);
        }
        // 获取所有本校教师名单
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($db_users)
                      ->get();
        $user_num = count($db_users);
        $users = array();
        for($i=0; $i<$user_num; $i++){
            $users[$db_users[$i]->user_id] = array($db_users[$i]->user_id, $db_users[$i]->user_name, 0, $db_users[$i]->department_name, $db_users[$i]->department_id, $db_users[$i]->position_name);
        }
        // 获取所有本校教室名单
        $db_classrooms = DB::table('classroom')
                           ->where('classroom_department', Session::get('user_department'))
                           ->where('classroom_status', 1)
                           ->orderBy('classroom_createtime', 'asc')
                           ->get();
        $classroom_num = count($db_classrooms);
        $classrooms = array();
        for($i=0; $i<$classroom_num; $i++){
            $classrooms[$db_classrooms[$i]->classroom_id] = array($db_classrooms[$i]->classroom_id, $db_classrooms[$i]->classroom_name, 0);
        }
        // 获取所选时间已有上课安排的学生、教师、教室
        for($i=0; $i<$schedule_date_num; $i++){
            $rows = DB::table('schedule')
                      ->join('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->select('student.student_id', 'user.user_id', 'classroom.classroom_id')
                      ->where([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->distinct()
                      ->get();
            $row_num = count($rows);
            for($j=0; $j<$row_num; $j++){
                // 学生列表次数加一
                $students[$rows[$j]->student_id][2]=$students[$rows[$j]->student_id][2]+1;
                // 教师列表次数加一
                $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
                // 教室列表次数加一
                $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
            }
        }

        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        return view('operation/studentScheduleCreate2', ['schedule_dates_str' => $schedule_dates_str,
                                                         'schedule_dates' => $schedule_dates,
                                                         'schedule_start' => $schedule_start,
                                                         'schedule_end' => $schedule_end,
                                                         'schedule_time' => $schedule_time,
                                                         'students' => $students,
                                                         'users' => $users,
                                                         'classrooms' => $classrooms,
                                                         'subjects' => $subjects,
                                                         'courses' => $courses]);
    }

    /**
     * 不规律安排学生课程视图2
     * URL: GET /operation/studentSchedule/createIrregular2
     * @param  Request  $request
     * @param  $request->input('input1'): 上课日期
     * @param  $request->input('input2'): 上课时间
     * @param  $request->input('input3'): 下课时间
     */
    public function studentScheduleCreateIrregular2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_dates_str = $request->input('input1');
        $schedule_start = $request->input('input2');
        $schedule_end = $request->input('input3');

        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        if($schedule_date_num>50){
            return redirect("/operation/studentSchedule/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请选择重新上课日期',
                           'message' => '上课日期数量过多，超过最大上限50！']);
        }
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/operation/studentSchedule/create")
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '请选择重新上课日期',
                               'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $time_start = date('H:i', strtotime($schedule_start));
        $time_end = date('H:i', strtotime($schedule_end));
        if($time_start>=$time_end){
            return redirect("/operation/studentSchedule/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前！']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);
        // 获取所有本校学生名单
        $db_students = DB::table('student')
                         ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                         ->where('student_department', Session::get('user_department'))
                         ->where('student_status', 1)
                         ->orderBy('student_grade', 'asc')
                         ->orderBy('student_name', 'asc')
                         ->get();
        $student_num = count($db_students);
        $students = array();
        for($i=0;$i<$student_num;$i++){
            $students[$db_students[$i]->student_id] = array($db_students[$i]->student_id, $db_students[$i]->student_name, 0, $db_students[$i]->grade_name);
        }
        // 获取所有本校班级名单
        $db_classes = DB::table('class')
                        ->where('class_department', Session::get('user_department'))
                        ->where('class_status', 1)
                        ->orderBy('class_createtime', 'asc')
                        ->get();
        $class_num = count($db_classes);
        $classes = array();
        for($i=0; $i<$class_num; $i++){
            $classes[$db_classes[$i]->class_id] = array($db_classes[$i]->class_id, $db_classes[$i]->class_name, 0);
        }
        // 获取所有本校教师名单
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($db_users)
                      ->get();
        $user_num = count($db_users);
        $users = array();
        for($i=0; $i<$user_num; $i++){
            $users[$db_users[$i]->user_id] = array($db_users[$i]->user_id, $db_users[$i]->user_name, 0, $db_users[$i]->department_name, $db_users[$i]->department_id, $db_users[$i]->position_name);
        }
        // 获取所有本校教室名单
        $db_classrooms = DB::table('classroom')
                           ->where('classroom_department', Session::get('user_department'))
                           ->where('classroom_status', 1)
                           ->orderBy('classroom_createtime', 'asc')
                           ->get();
        $classroom_num = count($db_classrooms);
        $classrooms = array();
        for($i=0; $i<$classroom_num; $i++){
            $classrooms[$db_classrooms[$i]->classroom_id] = array($db_classrooms[$i]->classroom_id, $db_classrooms[$i]->classroom_name, 0);
        }
        // 获取所选时间已有上课安排的学生、教师、教室
        for($i=0; $i<$schedule_date_num; $i++){
            $rows = DB::table('schedule')
                      ->join('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->select('student.student_id', 'user.user_id', 'classroom.classroom_id')
                      ->where([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->distinct()
                      ->get();
            $row_num = count($rows);
            for($j=0; $j<$row_num; $j++){
                // 学生列表次数加一
                $students[$rows[$j]->student_id][2]=$students[$rows[$j]->student_id][2]+1;
                // 教师列表次数加一
                $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
                // 教室列表次数加一
                $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
            }
        }

        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        return view('operation/studentScheduleCreate2', ['schedule_dates_str' => $schedule_dates_str,
                                                         'schedule_dates' => $schedule_dates,
                                                         'schedule_start' => $schedule_start,
                                                         'schedule_end' => $schedule_end,
                                                         'schedule_time' => $schedule_time,
                                                         'students' => $students,
                                                         'users' => $users,
                                                         'classrooms' => $classrooms,
                                                         'subjects' => $subjects,
                                                         'courses' => $courses]);
    }

    /**
     * 安排学生课程视图3
     * URL: GET /operation/studentSchedule/create3
     * @param  Request  $request
     * @param  $request->input('input1'): 学生/班级
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程
     * @param  $request->input('input5'): 科目
     * @param  $request->input('input6'): 上课日期
     * @param  $request->input('input7'): 上课时间
     * @param  $request->input('input8'): 下课时间
     * @param  $request->input('input9'): 课程时长
     */
    public function studentScheduleCreate3(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_participant = $request->input('input1');
        $schedule_teacher = $request->input('input2');
        $schedule_classroom = $request->input('input3');
        $schedule_course = $request->input('input4');
        $schedule_subject = $request->input('input5');
        $schedule_dates_str = $request->input('input6');
        $schedule_start = $request->input('input7');
        $schedule_end = $request->input('input8');
        $schedule_time = $request->input('input9');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取学生或班级名称
        $student_name = DB::table('student')
                          ->select('student_name')
                          ->where('student_id', $schedule_participant)
                          ->first();
        $schedule_participant_name = $student_name->student_name;
        // 获取教师姓名
        $schedule_teacher_name = DB::table('user')
                                   ->select('user_name')
                                   ->where('user_id', $schedule_teacher)
                                   ->first()
                                   ->user_name;
        // 获取课程名称
        $schedule_course_name = DB::table('course')
                                   ->select('course_name')
                                   ->where('course_id', $schedule_course)
                                   ->first()
                                   ->course_name;
        // 获取科目名称
        $schedule_subject_name = DB::table('subject')
                                   ->select('subject_name')
                                   ->where('subject_id', $schedule_subject)
                                   ->first()
                                   ->subject_name;
        // 获取学生或班级年级
        $student = DB::table('student')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->select('grade.grade_id', 'grade.grade_name')
                     ->where('student_id', $schedule_participant)
                     ->first();
        $schedule_grade = $student->grade_id;
        $schedule_grade_name = $student->grade_name;
        // 获取教室名称
        $schedule_classroom_name = DB::table('classroom')
                                     ->select('classroom_name')
                                     ->where('classroom_id', $schedule_classroom)
                                     ->first()
                                     ->classroom_name;
        return view('operation/studentScheduleCreate3', ['schedule_participant' => $schedule_participant,
                                                         'schedule_participant_name' => $schedule_participant_name,
                                                         'schedule_teacher' => $schedule_teacher,
                                                         'schedule_teacher_name' => $schedule_teacher_name,
                                                         'schedule_course' => $schedule_course,
                                                         'schedule_course_name' => $schedule_course_name,
                                                         'schedule_subject' => $schedule_subject,
                                                         'schedule_subject_name' => $schedule_subject_name,
                                                         'schedule_grade' => $schedule_grade,
                                                         'schedule_grade_name' => $schedule_grade_name,
                                                         'schedule_classroom' => $schedule_classroom,
                                                         'schedule_classroom_name' => $schedule_classroom_name,
                                                         'schedule_dates' => $schedule_dates,
                                                         'schedule_dates_str' => $schedule_dates_str,
                                                         'schedule_start' => $schedule_start,
                                                         'schedule_end' => $schedule_end,
                                                         'schedule_time' => $schedule_time]);
    }

    /**
     * 安排学生课程提交
     * URL: POST /operation/studentSchedule/store
     * @param  Request  $request
     * @param  $request->input('input1'): 学生/班级
     * @param  $request->input('input2'): 教师
     * @param  $request->input('input3'): 教室
     * @param  $request->input('input4'): 科目
     * @param  $request->input('input5'): 上课日期
     * @param  $request->input('input6'): 上课时间
     * @param  $request->input('input7'): 下课时间
     * @param  $request->input('input8'): 课程时长
     * @param  $request->input('input9'): 年级
     * @param  $request->input('input10'): 课程
     */
    public function studentScheduleStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_participant = $request->input('input1');
        $schedule_teacher = $request->input('input2');
        $schedule_classroom = $request->input('input3');
        $schedule_subject = $request->input('input4');
        $schedule_dates_str = $request->input('input5');
        $schedule_start = $request->input('input6');
        $schedule_end = $request->input('input7');
        $schedule_time = $request->input('input8');
        $schedule_grade = $request->input('input9');
        $schedule_course = $request->input('input10');
        // 获取当前用户校区ID
        $schedule_department = Session::get('user_department');
        // 获取当前用户ID
        $schedule_createuser = Session::get('user_id');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        // 获取上课成员类型
        $schedule_participant_type = 0;
        // 插入数据库
        DB::beginTransaction();
        try{
            for($i=0; $i<$schedule_date_num; $i++){
                DB::table('schedule')->insert(
                    ['schedule_department' => $schedule_department,
                     'schedule_participant' => $schedule_participant,
                     'schedule_participant_type' => $schedule_participant_type,
                     'schedule_teacher' => $schedule_teacher,
                     'schedule_course' => $schedule_course,
                     'schedule_subject' => $schedule_subject,
                     'schedule_grade' => $schedule_grade,
                     'schedule_classroom' => $schedule_classroom,
                     'schedule_date' => $schedule_dates[$i],
                     'schedule_start' => $schedule_start,
                     'schedule_end' => $schedule_end,
                     'schedule_time' => $schedule_time,
                     'schedule_createuser' => $schedule_createuser]
                );
            }
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/schedule/my")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '课程安排添加失败',
                           'message' => '课程安排添加失败，请联系系统管理员。']);
        }
        DB::commit();
        // 返回本校课程安排列表
        return redirect("/operation/schedule/my")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程安排成功',
                       'message' => '课程安排成功！']);
    }

    /**
     * 安排班级课程视图
     * URL: GET /operation/classSchedule/create
     */
    public function classScheduleCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('operation/classScheduleCreate');
    }

    /**
     * 不规律安排班级课程视图
     * URL: GET /operation/classSchedule/createIrregular
     */
    public function classScheduleCreateIrregular(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('operation/classScheduleCreateIrregular');
    }

    /**
     * 安排班级课程视图2
     * URL: GET /operation/classSchedule/create2
     * @param  Request  $request
     * @param  $request->input('input1'): 上课日期
     * @param  $request->input('input2'): 上课时间
     * @param  $request->input('input3'): 下课时间
     */
    public function classScheduleCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_date_start = $request->input('input1');
        $schedule_date_end = $request->input('input2');
        $schedule_days = $request->input('input3');
        $schedule_start = $request->input('input4');
        $schedule_end = $request->input('input5');
        // 判断Checkbox是否为空
        if(!isset($schedule_days)){
            return redirect("/operation/classSchedule/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '未选择上课规律',
                           'message' => '至少选择一天上课规律，请重新输入！']);
        }
        // 表单输入数据处理
        $schedule_date_start = date('Y-m-d', strtotime( $schedule_date_start));
        $schedule_date_end = date('Y-m-d', strtotime( $schedule_date_end));
        $schedule_date_temp = $schedule_date_start;
        $schedule_dates_str = "";
        while($schedule_date_temp <= $schedule_date_end){
            foreach($schedule_days as $schedule_day){
                if(date("w", strtotime($schedule_date_temp))==$schedule_day){
                    if($schedule_dates_str==""){
                        $schedule_dates_str.=$schedule_date_temp;
                    }else{
                        $schedule_dates_str.=",".$schedule_date_temp;
                    }
                    break;
                }
            }
            $schedule_date_temp = date('Y-m-d', strtotime ("+1 day", strtotime($schedule_date_temp)));
        }

        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        if($schedule_date_num>50){
            return redirect("/operation/classSchedule/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请选择重新上课日期',
                           'message' => '上课日期数量过多，超过最大上限50！']);
        }
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/operation/classSchedule/create")
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '请选择重新上课日期',
                               'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $time_start = date('H:i', strtotime($schedule_start));
        $time_end = date('H:i', strtotime($schedule_end));
        if($time_start>=$time_end){
            return redirect("/operation/classSchedule/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前！']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);
        // 获取所有本校班级名单
        $db_classes = DB::table('class')
                        ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                        ->where('class_department', Session::get('user_department'))
                        ->where('class_status', 1)
                        ->orderBy('class_grade', 'asc')
                        ->orderBy('class_name', 'asc')
                        ->get();
        $class_num = count($db_classes);
        $classes = array();
        for($i=0; $i<$class_num; $i++){
            $classes[$db_classes[$i]->class_id] = array($db_classes[$i]->class_id, $db_classes[$i]->class_name, 0, $db_classes[$i]->grade_name);
        }
        // 获取所有本校教师名单
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($db_users)
                      ->get();
        $user_num = count($db_users);
        $users = array();
        for($i=0; $i<$user_num; $i++){
            $users[$db_users[$i]->user_id] = array($db_users[$i]->user_id, $db_users[$i]->user_name, 0, $db_users[$i]->department_name, $db_users[$i]->department_id, $db_users[$i]->position_name);
        }
        // 获取所有本校教室名单
        $db_classrooms = DB::table('classroom')
                           ->where('classroom_department', Session::get('user_department'))
                           ->where('classroom_status', 1)
                           ->orderBy('classroom_createtime', 'asc')
                           ->get();
        $classroom_num = count($db_classrooms);
        $classrooms = array();
        for($i=0; $i<$classroom_num; $i++){
            $classrooms[$db_classrooms[$i]->classroom_id] = array($db_classrooms[$i]->classroom_id, $db_classrooms[$i]->classroom_name, 0);
        }
        // 获取所选时间已有班级上课安排的班级、教师、教室
        for($i=0; $i<$schedule_date_num; $i++){
            $rows = DB::table('schedule')
                      ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('member', 'class.class_id', '=', 'member.member_class')
                      ->join('student', 'member.member_student', '=', 'student.student_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->select('class.class_id', 'student.student_id', 'user.user_id', 'classroom.classroom_id')
                      ->where([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->distinct()
                      ->get();
            $row_num = count($rows);
            for($j=0; $j<$row_num; $j++){
                // 班级列表次数加一
                $classes[$rows[$j]->class_id][2]=$classes[$rows[$j]->class_id][2]+1;
                // 教师列表次数加一
                $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
                // 教室列表次数加一
                $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
            }
        }
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        return view('operation/classScheduleCreate2', ['schedule_dates_str' => $schedule_dates_str,
                                                         'schedule_dates' => $schedule_dates,
                                                         'schedule_start' => $schedule_start,
                                                         'schedule_end' => $schedule_end,
                                                         'schedule_time' => $schedule_time,
                                                         'classes' => $classes,
                                                         'users' => $users,
                                                         'classrooms' => $classrooms,
                                                         'subjects' => $subjects,
                                                         'courses' => $courses]);
    }

    /**
     * 不规律安排班级课程视图2
     * URL: GET /operation/classSchedule/createIrregular2
     * @param  Request  $request
     * @param  $request->input('input1'): 上课日期
     * @param  $request->input('input2'): 上课时间
     * @param  $request->input('input3'): 下课时间
     */
    public function classScheduleCreateIrregular2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_dates_str = $request->input('input1');
        $schedule_start = $request->input('input2');
        $schedule_end = $request->input('input3');

        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        if($schedule_date_num>50){
            return redirect("/operation/classSchedule/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请选择重新上课日期',
                           'message' => '上课日期数量过多，超过最大上限50！']);
        }
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/operation/classSchedule/create")
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '请选择重新上课日期',
                               'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $time_start = date('H:i', strtotime($schedule_start));
        $time_end = date('H:i', strtotime($schedule_end));
        if($time_start>=$time_end){
            return redirect("/operation/classSchedule/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前！']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);
        // 获取所有本校班级名单
        $db_classes = DB::table('class')
                        ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                        ->where('class_department', Session::get('user_department'))
                        ->where('class_status', 1)
                        ->orderBy('class_grade', 'asc')
                        ->orderBy('class_name', 'asc')
                        ->get();
        $class_num = count($db_classes);
        $classes = array();
        for($i=0; $i<$class_num; $i++){
            $classes[$db_classes[$i]->class_id] = array($db_classes[$i]->class_id, $db_classes[$i]->class_name, 0, $db_classes[$i]->grade_name);
        }
        // 获取所有本校教师名单
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($db_users)
                      ->get();
        $user_num = count($db_users);
        $users = array();
        for($i=0; $i<$user_num; $i++){
            $users[$db_users[$i]->user_id] = array($db_users[$i]->user_id, $db_users[$i]->user_name, 0, $db_users[$i]->department_name, $db_users[$i]->department_id, $db_users[$i]->position_name);
        }
        // 获取所有本校教室名单
        $db_classrooms = DB::table('classroom')
                           ->where('classroom_department', Session::get('user_department'))
                           ->where('classroom_status', 1)
                           ->orderBy('classroom_createtime', 'asc')
                           ->get();
        $classroom_num = count($db_classrooms);
        $classrooms = array();
        for($i=0; $i<$classroom_num; $i++){
            $classrooms[$db_classrooms[$i]->classroom_id] = array($db_classrooms[$i]->classroom_id, $db_classrooms[$i]->classroom_name, 0);
        }
        // 获取所选时间已有班级上课安排的班级、教师、教室
        for($i=0; $i<$schedule_date_num; $i++){
            $rows = DB::table('schedule')
                      ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('member', 'class.class_id', '=', 'member.member_class')
                      ->join('student', 'member.member_student', '=', 'student.student_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->select('class.class_id', 'student.student_id', 'user.user_id', 'classroom.classroom_id')
                      ->where([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->distinct()
                      ->get();
            $row_num = count($rows);
            for($j=0; $j<$row_num; $j++){
                // 班级列表次数加一
                $classes[$rows[$j]->class_id][2]=$classes[$rows[$j]->class_id][2]+1;
                // 教师列表次数加一
                $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
                // 教室列表次数加一
                $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
            }
        }
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        return view('operation/classScheduleCreate', ['schedule_dates_str' => $schedule_dates_str,
                                                         'schedule_dates' => $schedule_dates,
                                                         'schedule_start' => $schedule_start,
                                                         'schedule_end' => $schedule_end,
                                                         'schedule_time' => $schedule_time,
                                                         'classes' => $classes,
                                                         'users' => $users,
                                                         'classrooms' => $classrooms,
                                                         'subjects' => $subjects,
                                                         'courses' => $courses]);
    }

    /**
     * 安排班级课程视图3
     * URL: GET /operation/classSchedule/create3
     * @param  Request  $request
     * @param  $request->input('input1'): 学生/班级
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程
     * @param  $request->input('input5'): 科目
     * @param  $request->input('input6'): 上课日期
     * @param  $request->input('input7'): 上课时间
     * @param  $request->input('input8'): 下课时间
     * @param  $request->input('input9'): 课程时长
     */
    public function classScheduleCreate3(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_participant = $request->input('input1');
        $schedule_teacher = $request->input('input2');
        $schedule_classroom = $request->input('input3');
        $schedule_course = $request->input('input4');
        $schedule_subject = $request->input('input5');
        $schedule_dates_str = $request->input('input6');
        $schedule_start = $request->input('input7');
        $schedule_end = $request->input('input8');
        $schedule_time = $request->input('input9');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取班级名称
        $class = DB::table('class')
                   ->select('class_name')
                   ->where('class_id', $schedule_participant)
                   ->first();
        $schedule_participant_name = $class->class_name;
        // 获取教师姓名
        $schedule_teacher_name = DB::table('user')
                                   ->select('user_name')
                                   ->where('user_id', $schedule_teacher)
                                   ->first()
                                   ->user_name;
        // 获取课程名称
        $schedule_course_name = DB::table('course')
                                   ->select('course_name')
                                   ->where('course_id', $schedule_course)
                                   ->first()
                                   ->course_name;
        // 获取科目名称
        $schedule_subject_name = DB::table('subject')
                                   ->select('subject_name')
                                   ->where('subject_id', $schedule_subject)
                                   ->first()
                                   ->subject_name;
        // 获取学生或班级年级
        $grade = DB::table('class')
                     ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                     ->select('grade.grade_id', 'grade.grade_name')
                     ->where('class_id', $schedule_participant)
                     ->first();
        $schedule_grade = $grade->grade_id;
        $schedule_grade_name = $grade->grade_name;
        // 获取教室名称
        $schedule_classroom_name = DB::table('classroom')
                                     ->select('classroom_name')
                                     ->where('classroom_id', $schedule_classroom)
                                     ->first()
                                     ->classroom_name;
        return view('operation/classScheduleCreate3', ['schedule_participant' => $schedule_participant,
                                                         'schedule_participant_name' => $schedule_participant_name,
                                                         'schedule_teacher' => $schedule_teacher,
                                                         'schedule_teacher_name' => $schedule_teacher_name,
                                                         'schedule_course' => $schedule_course,
                                                         'schedule_course_name' => $schedule_course_name,
                                                         'schedule_subject' => $schedule_subject,
                                                         'schedule_subject_name' => $schedule_subject_name,
                                                         'schedule_grade' => $schedule_grade,
                                                         'schedule_grade_name' => $schedule_grade_name,
                                                         'schedule_classroom' => $schedule_classroom,
                                                         'schedule_classroom_name' => $schedule_classroom_name,
                                                         'schedule_dates' => $schedule_dates,
                                                         'schedule_dates_str' => $schedule_dates_str,
                                                         'schedule_start' => $schedule_start,
                                                         'schedule_end' => $schedule_end,
                                                         'schedule_time' => $schedule_time]);
    }

    /**
     * 安排班级课程提交
     * URL: POST /operation/classSchedule/store
     * @param  Request  $request
     * @param  $request->input('input1'): 学生/班级
     * @param  $request->input('input2'): 教师
     * @param  $request->input('input3'): 教室
     * @param  $request->input('input4'): 科目
     * @param  $request->input('input5'): 上课日期
     * @param  $request->input('input6'): 上课时间
     * @param  $request->input('input7'): 下课时间
     * @param  $request->input('input8'): 课程时长
     * @param  $request->input('input9'): 年级
     * @param  $request->input('input10'): 课程
     */
    public function classScheduleStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_participant = $request->input('input1');
        $schedule_teacher = $request->input('input2');
        $schedule_classroom = $request->input('input3');
        $schedule_subject = $request->input('input4');
        $schedule_dates_str = $request->input('input5');
        $schedule_start = $request->input('input6');
        $schedule_end = $request->input('input7');
        $schedule_time = $request->input('input8');
        $schedule_grade = $request->input('input9');
        $schedule_course = $request->input('input10');
        // 获取当前用户校区ID
        $schedule_department = Session::get('user_department');
        // 获取当前用户ID
        $schedule_createuser = Session::get('user_id');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        // 获取上课成员类型为1
        $schedule_participant_type = 1;
        // 插入数据库
        DB::beginTransaction();
        try{
            for($i=0; $i<$schedule_date_num; $i++){
                DB::table('schedule')->insert(
                    ['schedule_department' => $schedule_department,
                     'schedule_participant' => $schedule_participant,
                     'schedule_participant_type' => $schedule_participant_type,
                     'schedule_teacher' => $schedule_teacher,
                     'schedule_course' => $schedule_course,
                     'schedule_subject' => $schedule_subject,
                     'schedule_grade' => $schedule_grade,
                     'schedule_classroom' => $schedule_classroom,
                     'schedule_date' => $schedule_dates[$i],
                     'schedule_start' => $schedule_start,
                     'schedule_end' => $schedule_end,
                     'schedule_time' => $schedule_time,
                     'schedule_createuser' => $schedule_createuser]
                );
            }
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/schedule/my")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '班级课程安排添加失败',
                           'message' => '课程安排添加失败，请联系系统管理员。']);
        }
        DB::commit();
        // 返回本校课程安排列表
        return redirect("/operation/schedule/my")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '班级课程安排成功',
                       'message' => '班级课程安排成功！']);
    }

    /**
     * 本校学生课程安排视图
     * URL: GET /operation/studentSchedule/department
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function studentScheduleDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->Join('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_participant_type', '=', 0)
                  ->where('schedule_attended', '=', 0)
                  ->where('schedule_department', '=', Session::get('user_department'));
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('operation/studentScheduleDepartment', ['rows' => $rows,
                                                               'currentPage' => $currentPage,
                                                               'totalPage' => $totalPage,
                                                               'startIndex' => $offset,
                                                               'request' => $request,
                                                               'totalNum' => $totalNum,
                                                               'filter_departments' => $filter_departments,
                                                               'filter_students' => $filter_students,
                                                               'filter_classes' => $filter_classes,
                                                               'filter_grades' => $filter_grades,
                                                               'filter_users' => $filter_users]);
    }

    /**
     * 本校班级课程安排视图
     * URL: GET /operation/classSchedule/department
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function classScheduleDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->Join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_participant_type', '=', 1)
                  ->where('schedule_attended', '=', 0)
                  ->where('schedule_department', '=', Session::get('user_department'));
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('operation/classScheduleDepartment', ['rows' => $rows,
                                                               'currentPage' => $currentPage,
                                                               'totalPage' => $totalPage,
                                                               'startIndex' => $offset,
                                                               'request' => $request,
                                                               'totalNum' => $totalNum,
                                                               'filter_departments' => $filter_departments,
                                                               'filter_classes' => $filter_classes,
                                                               'filter_grades' => $filter_grades,
                                                               'filter_users' => $filter_users]);
    }

    /**
     * 本校上课记录视图
     * URL: GET /operation/attendedSchedule/department
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function attendedScheduleDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据
        $rows = DB::table('participant')
                  ->join('student', 'participant.participant_student', '=', 'student.student_id')
                  ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->where('schedule_department', '=', Session::get('user_department'));
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('operation/attendedScheduleDepartment', ['rows' => $rows,
                                                               'currentPage' => $currentPage,
                                                               'totalPage' => $totalPage,
                                                               'startIndex' => $offset,
                                                               'request' => $request,
                                                               'totalNum' => $totalNum,
                                                               'filter_departments' => $filter_departments,
                                                               'filter_classes' => $filter_classes,
                                                               'filter_grades' => $filter_grades,
                                                               'filter_users' => $filter_users]);
    }

    /**
     * 我的学生课程安排视图
     * URL: GET /operation/schedule/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function ScheduleMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->Join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_participant_type', '=', 1)
                  ->where('schedule_attended', '=', 0)
                  ->where('schedule_department', '=', Session::get('user_department'));
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('operation/scheduleMy', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_departments' => $filter_departments,
                                               'filter_classes' => $filter_classes,
                                               'filter_grades' => $filter_grades,
                                               'filter_users' => $filter_users]);
    }

    /**
     * 我的学生上课记录视图
     * URL: GET /operation/attendedSchedule/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function attendedScheduleMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据
        $rows = DB::table('participant')
                  ->join('student', 'participant.participant_student', '=', 'student.student_id')
                  ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->where('student_class_adviser', '=', Session::get('user_id'));
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('operation/attendedScheduleMy', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_classes' => $filter_classes,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_users' => $filter_users]);
    }

}
