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
     * URL: GET /operation/follower/edit
     */
    public function followerEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('student_id');
        // 获取学生信息
        $student = DB::table('student')
                      ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                      ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                      ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                      ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                      ->where('student_id', $student_id)
                      ->select('student.student_id AS student_id',
                                'student.student_department AS student_department',
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
                  ->where('user_department', $student->student_department)
                  ->where('user_status', 1)
                  ->get();
        return view('operation/followerEdit', ['student' => $student, 'users' => $users]);
    }

    /**
     * 修改负责人提交
     * URL: GET /operation/follower/store
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
            return redirect("/operation/student/all")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '负责人修改失败',
                           'message' => '负责人修改失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/operation/student/all")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '负责人修改成功',
                      'message' => '负责人修改成功']);
    }

    /**
     * 插入班级视图
     * URL: GET /operation/member/add
     */
    public function memberAdd(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('student_id');
        // 获取学生信息
        $student = DB::table('student')
                      ->join('grade', 'grade.grade_id', '=', 'student.student_grade')
                      ->where('student_id', $student_id)
                      ->first();

        // 获取班级信息
        $classes = DB::table('class')
                      ->join('subject', 'subject.subject_id', '=', 'class.class_subject')
                      ->join('user', 'user.user_id', '=', 'class.class_teacher')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->where('class_department', '=', $student->student_department)
                      ->where('class_grade', '=', $student->student_grade)
                      ->whereColumn('class_current_num', '<', 'class_max_num')
                      ->where('class_status', 1)
                      ->get();
        return view('operation/memberAdd', ['student' => $student, 'classes' => $classes]);
    }

    /**
     * 插入班级提交
     * URL: GET /operation/member/store
     */
    public function memberStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('input1');
        $class_id = $request->input('input2');
        // 插入数据库
        DB::beginTransaction();
        try{
            // 添加班级成员
            DB::table('member')->insert(
                ['member_class' => $class_id,
                 'member_student' => $student_id,
                 'member_createuser' => Session::get('user_id')]
            );
            // 更新班级人数
            DB::table('class')
              ->where('class_id', $class_id)
              ->increment('class_current_num');
            // 插入学生动态
            //
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/member/add")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '插入班级失败',
                           'message' => '插入班级失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/operation/student/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '插入班级成功',
                      'message' => '插入班级成功']);
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
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
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
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('operation/studentAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 学生删除(转为离校)
     * URL: DELETE /operation/student/all/{student_id}
     * @param  int  $student_id
     */
    public function studentDelete($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 更新数据
        try{
            DB::table('student')->where('student_id', $student_id)->update(['student_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/student/all")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '学生删除失败',
                             'message' => '学生删除失败，请联系系统管理员']);
        }
        // 返回岗位列表
        return redirect("/operation/student/all")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '学生删除成功,学生转为离校学生',
                         'message' => '学生删除成功,学生转为离校学生！']);
    }

    /**
     * 离校学生管理视图
     * URL: GET /operation/student/deleted
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function studentDeleted(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
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
                  ->where('student_customer_status', 1)
                  ->where('student_status', 0);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
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
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('operation/studentDeleted', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 离校学生恢复
     * URL: GET /operation/student/deleted/restore/{student_id}
     * @param  int  $student_id
     */
    public function studentRestore($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 更新数据
        try{
            DB::table('student')->where('student_id', $student_id)->update(['student_status' => 1]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/student/deleted")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '学生恢复失败',
                             'message' => '学生恢复失败，请联系系统管理员']);
        }
        // 返回岗位列表
        return redirect("/operation/student/deleted")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '学生恢复成功',
                         'message' => '学生恢复成功！']);
    }

    /**
     * 离校学生删除
     * URL: DELETE /operation/student/deleted/{student_id}
     * @param  int  $student_id
     */
    public function studentDeletedDelete($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 更新数据
        try{
            DB::table('student')->where('student_id', $student_id)->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/student/deleted")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '离校学生删除失败',
                             'message' => '离校学生删除失败，请联系系统管理员']);
        }
        // 返回岗位列表
        return redirect("/operation/student/deleted")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '离校学生删除成功',
                         'message' => '离校学生删除成功！']);
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
                  ->where('student_class_adviser', Session::get('user_id'))
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
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
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('operation/studentMy', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
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

        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_cross_teaching', '=', 1)
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->orderBy('user_department', 'asc');
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->whereIn('user_department', $department_access)
                   ->where('user_cross_teaching', '=', 0)
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->union($users)
                   ->get();
        return view('operation/classCreate', ['departments' => $departments,
                                              'grades' => $grades,
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
        return redirect("/operation/class/all")
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
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('operation/classAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades,
                                           'filter_subjects' => $filter_subjects]);
    }

    /**
     * 删除班级
     * URL: DELETE /operation/class/all/{class_id}
     * @param  int  $class_id
     */
    public function classDelete($class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $class_name = DB::table('class')->where('class_id', $class_id)->value('class_name');
        // 删除数据
        DB::beginTransaction();
        try{
            DB::table('class')->where('class_id', $class_id)->update(['class_status' => 0]);
            //删除上课安排
            DB::table('schedule')
              ->where('schedule_participant', $class_id)
              ->where('schedule_attended', 0)
              ->delete();
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/class/all")->with(['notify' => true,
                                                             'type' => 'danger',
                                                             'title' => '班级删除失败',
                                                             'message' => '班级删除失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回班级列表
        return redirect("/operation/class/all")->with(['notify' => true,
                                                         'type' => 'success',
                                                         'title' => '班级删除成功',
                                                         'message' => '班级名称: '.$class_name]);
    }

    /**
     * 安排学生课程视图
     * URL: GET /operation/studentSchedule/create
     */
    public function studentScheduleCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('student_id');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取教师名单
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', $student->student_department)
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', $student->student_department)
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($teachers)
                      ->get();
        // 获取教室名单
        $classrooms = DB::table('classroom')
                        ->where('classroom_department', $student->student_department)
                        ->where('classroom_status', 1)
                        ->orderBy('classroom_id', 'asc')
                        ->get();
        // 获取科目
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        // 获取课程
        $courses = DB::table('course')->where('course_grade', $student->student_grade)->where('course_status', 1)->orderBy('course_id', 'asc')->get();
        // 获取年级、科目、用户信息
        return view('operation/studentScheduleCreate', ['student' => $student,
                                                        'teachers' => $teachers,
                                                        'classrooms' => $classrooms,
                                                        'subjects' => $subjects,
                                                        'courses' => $courses]);
    }

    /**
     * 安排学生课程视图2
     * URL: GET /operation/studentSchedule/create2
     * @param  Request  $request
     * @param  $request->input('input_student'): 学生/班级
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程
     * @param  $request->input('input5'): 科目
     * @param  $request->input('input6'): 上课日期
     * @param  $request->input('input7'): 上课时间
     * @param  $request->input('input8'): 下课时间
     * @param  $request->input('input9'): 课程时长
     */
    public function studentScheduleCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取表单输入
        $schedule_student = $request->input('input_student');
        $schedule_date_start = $request->input('input_date_start');
        $schedule_date_end = $request->input('input_date_end');
        $schedule_days = $request->input('input_days');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_course = $request->input('input_course');
        $schedule_subject = $request->input('input_subject');
        // 判断Checkbox是否为空
        if(!isset($schedule_days)){
            return redirect("/operation/studentSchedule/create?student_id={$schedule_student}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '未选择上课规律',
                           'message' => '至少选择一天上课，请重新输入！']);
        }

        // 日期数据处理
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
        // 判断日期数量是否大于50
        if($schedule_date_num>50){
            return redirect("/operation/studentSchedule/create?student_id={$schedule_student}")->with(['notify' => true,
                                                                                                   'type' => 'danger',
                                                                                                   'title' => '请选择重新上课日期',
                                                                                                   'message' => '上课日期数量过多，超过最大上限50！']);
        }
        // 验证日期格式
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/operation/studentSchedule/create?student_id={$schedule_student}")->with(['notify' => true,
                                                                                                       'type' => 'danger',
                                                                                                       'title' => '请选择重新上课日期',
                                                                                                       'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $schedule_start = date('H:i', strtotime($schedule_start));
        $schedule_end = date('H:i', strtotime($schedule_end));
        if($schedule_start>=$schedule_end){
            return redirect("/operation/studentSchedule/create?student_id={$schedule_student}")->with(['notify' => true,
                                                                                                   'type' => 'danger',
                                                                                                   'title' => '请重新选择上课、下课时间',
                                                                                                   'message' => '上课时间须在下课时间前！']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);

        // 获取学生信息
        $schedule_student = DB::table('student')
                              ->where('student_id', $schedule_student)
                              ->join('department', 'student.student_department', '=', 'department.department_id')
                              ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                              ->first();
        // 获取教师信息
        $schedule_teacher = DB::table('user')
                              ->where('user_id', $schedule_teacher)
                              ->first();
        // 获取课程信息
        $schedule_course = DB::table('course')
                             ->where('course_id', $schedule_course)
                             ->first();
        // 获取科目名称
        $schedule_subject = DB::table('subject')
                              ->where('subject_id', $schedule_subject)
                              ->first();
        // 获取教室名称
        $schedule_classroom = DB::table('classroom')
                                ->where('classroom_id', $schedule_classroom)
                                ->first();
        return view('operation/studentScheduleCreate2', ['schedule_student' => $schedule_student,
                                                         'schedule_teacher' => $schedule_teacher,
                                                         'schedule_course' => $schedule_course,
                                                         'schedule_subject' => $schedule_subject,
                                                         'schedule_classroom' => $schedule_classroom,
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
        $schedule_department = $request->input('input_department');
        $schedule_participant = $request->input('input_student');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_subject = $request->input('input_subject');
        $schedule_dates_str = $request->input('input_dates_str');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_time = $request->input('input_time');
        $schedule_grade = $request->input('input_grade');
        $schedule_course = $request->input('input_course');
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
            return redirect("/operation/studentSchedule/create?student_id={$schedule_student}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '学生课程安排失败',
                           'message' => '学生课程安排失败，请联系系统管理员。']);
        }
        DB::commit();
        // 返回本校课程安排列表
        return view('operation/studentScheduleCreateResult', ['student_id' => $schedule_participant]);
    }

    /**
     * 安排班级课程视图
     * URL: GET /operation/classSchedule/create
     */
    public function classScheduleCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取班级id
        $class_id = $request->input('class_id');
        // 获取班级信息
        $class = DB::table('class')
                     ->join('department', 'class.class_department', '=', 'department.department_id')
                     ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                     ->where('class_id', $class_id)
                     ->first();
        // 获取教师名单
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', $class->class_department)
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', $class->class_department)
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($teachers)
                      ->get();
        // 获取教室名单
        $classrooms = DB::table('classroom')
                        ->where('classroom_department', $class->class_department)
                        ->where('classroom_status', 1)
                        ->orderBy('classroom_id', 'asc')
                        ->get();
        // 获取科目
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        // 获取课程
        $courses = DB::table('course')->where('course_grade', $class->class_grade)->where('course_status', 1)->orderBy('course_id', 'asc')->get();
        // 获取年级、科目、用户信息
        return view('operation/classScheduleCreate', ['class' => $class,
                                                        'teachers' => $teachers,
                                                        'classrooms' => $classrooms,
                                                        'subjects' => $subjects,
                                                        'courses' => $courses]);
    }

    /**
     * 安排班级课程视图2
     * URL: GET /operation/classSchedule/create2
     * @param  Request  $request
     * @param  $request->input('input_class'): 班级
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程
     * @param  $request->input('input5'): 科目
     * @param  $request->input('input6'): 上课日期
     * @param  $request->input('input7'): 上课时间
     * @param  $request->input('input8'): 下课时间
     * @param  $request->input('input9'): 课程时长
     */
    public function classScheduleCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取表单输入
        $schedule_class = $request->input('input_class');
        $schedule_date_start = $request->input('input_date_start');
        $schedule_date_end = $request->input('input_date_end');
        $schedule_days = $request->input('input_days');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_course = $request->input('input_course');
        $schedule_subject = $request->input('input_subject');
        // 判断Checkbox是否为空
        if(!isset($schedule_days)){
            return redirect("/operation/classSchedule/create?class_id={$schedule_class}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '未选择上课规律',
                           'message' => '至少选择一天上课，请重新输入！']);
        }

        // 日期数据处理
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
        // 判断日期数量是否大于50
        if($schedule_date_num>50){
            return redirect("/operation/classSchedule/create?class_id={$schedule_class}")->with(['notify' => true,
                                                                                                   'type' => 'danger',
                                                                                                   'title' => '请选择重新上课日期',
                                                                                                   'message' => '上课日期数量过多，超过最大上限50！']);
        }
        // 验证日期格式
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/operation/classSchedule/create?class_id={$schedule_class}")->with(['notify' => true,
                                                                                                       'type' => 'danger',
                                                                                                       'title' => '请选择重新上课日期',
                                                                                                       'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $schedule_start = date('H:i', strtotime($schedule_start));
        $schedule_end = date('H:i', strtotime($schedule_end));
        if($schedule_start>=$schedule_end){
            return redirect("/operation/classSchedule/create?class_id={$schedule_class}")->with(['notify' => true,
                                                                                                   'type' => 'danger',
                                                                                                   'title' => '请重新选择上课、下课时间',
                                                                                                   'message' => '上课时间须在下课时间前！']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);

        // 获取班级信息
        $schedule_class = DB::table('class')
                              ->where('class_id', $schedule_class)
                              ->join('department', 'class.class_department', '=', 'department.department_id')
                              ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                              ->first();
        // 获取教师信息
        $schedule_teacher = DB::table('user')
                              ->where('user_id', $schedule_teacher)
                              ->first();
        // 获取课程信息
        $schedule_course = DB::table('course')
                             ->where('course_id', $schedule_course)
                             ->first();
        // 获取科目名称
        $schedule_subject = DB::table('subject')
                              ->where('subject_id', $schedule_subject)
                              ->first();
        // 获取教室名称
        $schedule_classroom = DB::table('classroom')
                                ->where('classroom_id', $schedule_classroom)
                                ->first();
        return view('operation/classScheduleCreate2', ['schedule_class' => $schedule_class,
                                                         'schedule_teacher' => $schedule_teacher,
                                                         'schedule_course' => $schedule_course,
                                                         'schedule_subject' => $schedule_subject,
                                                         'schedule_classroom' => $schedule_classroom,
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
     * @param  $request->input('input1'): 班级
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
        $schedule_department = $request->input('input_department');
        $schedule_participant = $request->input('input_class');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_subject = $request->input('input_subject');
        $schedule_dates_str = $request->input('input_dates_str');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_time = $request->input('input_time');
        $schedule_grade = $request->input('input_grade');
        $schedule_course = $request->input('input_course');
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
            return redirect("/operation/classSchedule/create?class_id={$schedule_class}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '班级课程安排失败',
                           'message' => '班级课程安排失败，请联系系统管理员。']);
        }
        DB::commit();
        // 返回本校课程安排列表
        return view('operation/classScheduleCreateResult', ['class_id' => $schedule_participant]);
    }

    /**
     * 学生课程视图
     * URL: GET /operation/studentSchedule/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function studentScheduleAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->Join('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('user AS creator', 'schedule.schedule_createuser', '=', 'creator.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->whereIn('schedule_department', $department_access)
                  ->where('schedule_participant_type', '=', 0)
                  ->where('schedule_attended', '=', 0);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 学生科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_subject', '=', $request->input('filter4'));
            $filter_status = 1;
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
                               'student_name',
                               'student_gender',
                               'teacher.user_name AS teacher_name',
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

        // 返回列表视图
        return view('operation/studentScheduleAll', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_status' => $filter_status,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_subjects' => $filter_subjects]);
    }


    /**
     * 学生课程删除
     * URL: DELETE /operation/studentSchedule/{schedule_id}
     * @param  int  $class_id
     */
    public function studentScheduleDelete($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 更新数据库
        try{
            DB::table('schedule')
              ->where('schedule_id', $schedule_id)
              ->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/studentSchedule/all")->with(['notify' => true,
                                                                    'type' => 'danger',
                                                                    'title' => '课程安排删除失败',
                                                                    'message' => '课程安排删除失败！']);
        }
        // 返回
        return redirect("/operation/studentSchedule/all")->with(['notify' => true,
                                                                'type' => 'success',
                                                                'title' => '课程安排删除成功',
                                                                'message' => '课程安排删除成功！']);
    }

    /**
     * 本校班级课程安排视图
     * URL: GET /operation/classSchedule/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function classScheduleAll(Request $request){
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
                  ->where('schedule_participant_type', '=', 1)
                  ->where('schedule_attended', '=', 0);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 学生科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_subject', '=', $request->input('filter4'));
            $filter_status = 1;
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
                              'class_name',
                              'teacher.user_name AS teacher_name',
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

        // 返回列表视图
        return view('operation/classScheduleAll', ['rows' => $rows,
                                                   'currentPage' => $currentPage,
                                                   'totalPage' => $totalPage,
                                                   'startIndex' => $offset,
                                                   'request' => $request,
                                                   'totalNum' => $totalNum,
                                                   'filter_status' => $filter_status,
                                                   'filter_departments' => $filter_departments,
                                                   'filter_grades' => $filter_grades,
                                                   'filter_subjects' => $filter_subjects]);
    }

    /**
     * 班级课程删除
     * URL: DELETE /operation/classSchedule/{schedule_id}
     * @param  int  $schedule_id
     */
    public function classScheduleDelete($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 更新数据库
        try{
            DB::table('schedule')
              ->where('schedule_id', $schedule_id)
              ->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/classSchedule/all")->with(['notify' => true,
                                                                    'type' => 'danger',
                                                                    'title' => '课程安排删除失败',
                                                                    'message' => '课程安排删除失败！']);
        }
        // 返回
        return redirect("/operation/classSchedule/all")->with(['notify' => true,
                                                                'type' => 'success',
                                                                'title' => '课程安排删除成功',
                                                                'message' => '课程安排删除成功！']);
    }

    /**
     * 上课记录视图
     * URL: GET /operation/attendedSchedule/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function attendedScheduleAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('participant')
                  ->join('student', 'participant.participant_student', '=', 'student.student_id')
                  ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->leftJoin('hour', 'participant.participant_hour', '=', 'hour.hour_id')
                  ->leftJoin('course', 'hour.hour_course', '=', 'course.course_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->leftJoin('user AS checked_user', 'participant.participant_checked_user', '=', 'checked_user.user_id')
                  ->select('participant.participant_id AS participant_id',
                           'student.student_name AS student_name',
                           'subject.subject_name AS subject_name',
                           'grade.grade_name AS grade_name',
                           'classroom.classroom_name AS classroom_name',
                           'class.class_name AS class_name',
                           'teacher.user_name AS teacher_name',
                           'participant.participant_attend_status AS participant_attend_status',
                           'participant.participant_amount AS participant_amount',
                           'participant.participant_checked AS participant_checked',
                           'checked_user.user_name AS checked_user_name',
                           'schedule.schedule_id AS schedule_id',
                           'schedule.schedule_date AS schedule_date',
                           'schedule.schedule_start AS schedule_start',
                           'schedule.schedule_end AS schedule_end',
                           'course.course_name AS course_name')
                  ->whereIn('schedule_department', $department_access);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student.student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class.class_name', 'like', '%'.$request->input('filter2').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter4'));
            $filter_status = 1;
        }
        // 学生科目
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_subject', '=', $request->input('filter5'));
            $filter_status = 1;
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
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('operation/attendedScheduleAll', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_status' => $filter_status,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_subjects' => $filter_subjects]);
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

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->leftJoin('member', 'member.member_class', '=', 'class.class_id')
                  ->leftJoin('student AS class_member', 'member.member_student', '=', 'class_member.student_id')
                  ->where([
                      ['schedule.schedule_attended', '=', 0],
                      ['student.student_class_adviser', '=', Session::get('user_id')],
                  ])
                  ->orWhere([
                      ['schedule.schedule_attended', '=', 0],
                      ['class_member.student_class_adviser', '=', Session::get('user_id')],
                  ]);
        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student.student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级名称
        if ($request->filled('filter2')) {
            $rows = $rows->where('class.class_name', 'like', '%'.$request->input('filter2').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter4'));
            $filter_status = 1;
        }
        // 学生科目
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_subject', '=', $request->input('filter5'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->select('schedule.schedule_id AS schedule_id',
                              'schedule.schedule_date AS schedule_date',
                              'schedule.schedule_start AS schedule_start',
                              'schedule.schedule_end AS schedule_end',
                              'schedule.schedule_time AS schedule_time',
                              'schedule.schedule_participant_type AS schedule_participant_type',
                              'department.department_name AS department_name',
                              'teacher.user_name AS teacher_name',
                              'course.course_name AS course_name',
                              'subject.subject_name AS subject_name',
                              'grade.grade_name AS grade_name',
                              'classroom.classroom_name AS classroom_name',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'class.class_id AS class_id',
                              'class.class_name AS class_name',
                              'class_member.student_id AS class_member_id',
                              'class_member.student_name AS class_member_name')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('operation/scheduleMy', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_status' => $filter_status,
                                               'filter_departments' => $filter_departments,
                                               'filter_grades' => $filter_grades,
                                               'filter_subjects' => $filter_subjects]);
    }

    /**
     * 我的学生课程安排删除
     * URL: DELETE /operation/schedule/my/{schedule_id}
     * @param  int  $schedule_id
     */
    public function myScheduleDelete($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 更新数据库
        try{
            DB::table('schedule')
              ->where('schedule_id', $schedule_id)
              ->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/schedule/my")->with(['notify' => true,
                                                                    'type' => 'danger',
                                                                    'title' => '课程安排删除失败',
                                                                    'message' => '课程安排删除失败！']);
        }
        // 返回
        return redirect("/operation/schedule/my")->with(['notify' => true,
                                                                'type' => 'success',
                                                                'title' => '课程安排删除成功',
                                                                'message' => '课程安排删除成功！']);
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
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('participant')
                  ->join('student', 'participant.participant_student', '=', 'student.student_id')
                  ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->leftJoin('hour', 'participant.participant_hour', '=', 'hour.hour_id')
                  ->leftJoin('course', 'hour.hour_course', '=', 'course.course_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->leftJoin('user AS checked_user', 'participant.participant_checked_user', '=', 'checked_user.user_id')
                  ->select('participant.participant_id AS participant_id',
                           'student.student_name AS student_name',
                           'subject.subject_name AS subject_name',
                           'grade.grade_name AS grade_name',
                           'classroom.classroom_name AS classroom_name',
                           'class.class_name AS class_name',
                           'teacher.user_name AS teacher_name',
                           'participant.participant_attend_status AS participant_attend_status',
                           'participant.participant_amount AS participant_amount',
                           'participant.participant_checked AS participant_checked',
                           'checked_user.user_name AS checked_user_name',
                           'schedule.schedule_id AS schedule_id',
                           'schedule.schedule_date AS schedule_date',
                           'schedule.schedule_start AS schedule_start',
                           'schedule.schedule_end AS schedule_end',
                           'course.course_name AS course_name')
                  ->where('student_class_adviser', '=', Session::get('user_id'));
        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student.student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class.class_name', 'like', '%'.$request->input('filter2').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter4'));
            $filter_status = 1;
        }
        // 学生科目
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_subject', '=', $request->input('filter5'));
            $filter_status = 1;
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
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('operation/attendedScheduleMy', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_status' => $filter_status,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_subjects' => $filter_subjects]);
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

    /**
     * 签约合同视图
     * URL: POST /operation/contract/create
     */
    public function contractCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('student_id');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取已购课程
        $hours = DB::table('hour')
                   ->join('course', 'course.course_id', '=', 'hour.hour_course')
                   ->join('contract', 'contract.contract_id', '=', 'hour.hour_contract')
                   ->where('hour_student', $student_id)
                   ->get();

        // 获取课程信息
        $courses = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->join('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', $student->student_grade)
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_status', 1)
                     ->orderBy('course_type', 'asc')
                     ->orderBy('course_time', 'asc')
                     ->get();

        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        return view('operation/contractCreate', ['student' => $student,
                                              'hours' => $hours,
                                              'courses' => $courses,
                                              'payment_methods' => $payment_methods]);
    }

    /**
     * 签约合同提交
     * URL: POST /operation/contract/store
     * @param  Request  $request
     * @param  $request->input('student_id'): 购课学生
     * @param  $request->input('selected_course_num'): 购买课程数量
     */
    public function contractStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取当前用户ID
        $contract_createuser = Session::get('user_id');
        // 获取表单输入
        $request_student_id = $request->input('student_id');
        $request_selected_course_num = $request->input('selected_course_num');
        $request_contract_payment_method = $request->input('payment_method');
        $request_contract_date = $request->input('contract_date');
        $request_contract_paid_price = $request->input('contract_paid_price');
        if($request->filled('remark')) {
            $request_contract_remark = $request->input('remark');
        }else{
            $request_contract_remark = "";
        }
        $request_contract_type = 1;
        $request_contract_extra_fee = round((float)$request->input("extra_fee"), 2);
        $request_courses = array();
        // 生成新合同号(上一个合同号加一或新合同号001)
        $sub_student_id = substr($request_student_id , 1 , 10);
        if(DB::table('contract')->where('contract_student', $request_student_id)->exists()){
            $pre_contract_num = DB::table('contract')
                                  ->where('contract_student', $request_student_id)
                                  ->orderBy('contract_createtime', 'desc')
                                  ->limit(1)
                                  ->first();
            $new_contract_num = intval(substr($pre_contract_num->contract_id , 10 , 12))+1;
        }else{
            $new_contract_num = 1;
        }
        $contract_id = "H".$sub_student_id.sprintf("%02d", $new_contract_num);
        for($i=1; $i<=$request_selected_course_num; $i++){
            $temp = array();
            $temp[] = (int)$request->input("course_{$i}_0");
            $temp[] = round((float)$request->input("course_{$i}_1"), 2);
            $temp[] = round((float)$request->input("course_{$i}_2"), 1);
            $temp[] = round((float)$request->input("course_{$i}_3"), 2);
            $temp[] = round((float)$request->input("course_{$i}_4")/100, 2);
            $temp[] = round((float)$request->input("course_{$i}_5"), 2);
            $temp[] = round((float)$request->input("course_{$i}_6"), 1);
            $temp[] = round((float)$request->input("course_{$i}_7"), 1);
            $temp[] = round((float)$request->input("course_{$i}_8"), 2);
            $request_courses[] = $temp;
        }
        // 计算合同总信息
        $contract_student = $request_student_id;
        $contract_course_num = $request_selected_course_num;
        $contract_original_hour = 0;
        $contract_free_hour = 0;
        $contract_total_hour = 0;
        $contract_original_price = 0;
        $contract_discount_price = 0;
        $contract_total_price = 0;
        $contract_paid_price = $request_contract_paid_price;
        $contract_date = $request_contract_date;
        $contract_remark = $request_contract_remark;
        $contract_payment_method = $request_contract_payment_method;
        foreach($request_courses as $request_course){
            $contract_original_hour += $request_course[2];
            $contract_free_hour += $request_course[6];
            $contract_total_hour += $request_course[7];
            $contract_original_price += $request_course[3];
            $contract_total_price += $request_course[8];
        }
        $contract_discount_price = $contract_original_price - $contract_total_price;
        $contract_original_price = round($contract_original_price, 2);
        $contract_discount_price = round($contract_discount_price, 2);
        $contract_total_price = round($contract_total_price+$request_contract_extra_fee, 2);
        // 获取学生校区
        $student_department = DB::table('student')
                                ->where('student_id', $request_student_id)
                                ->first()
                                ->student_department;
        // 获取学生签约状态
        $student_customer_status = DB::table('student')
                                     ->where('student_id', $request_student_id)
                                     ->first()
                                     ->student_customer_status;
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入Contract表
            DB::table('contract')->insert(
                ['contract_id' => $contract_id,
                 'contract_department' => $student_department,
                 'contract_student' => $contract_student,
                 'contract_course_num' => $contract_course_num,
                 'contract_original_hour' => $contract_original_hour,
                 'contract_free_hour' => $contract_free_hour,
                 'contract_total_hour' => $contract_total_hour,
                 'contract_original_price' => $contract_original_price,
                 'contract_discount_price' => $contract_discount_price,
                 'contract_total_price' => $contract_total_price,
                 'contract_paid_price' => $contract_paid_price,
                 'contract_date' => $contract_date,
                 'contract_payment_method' => $contract_payment_method,
                 'contract_remark' => $contract_remark,
                 'contract_type' => $request_contract_type,
                 'contract_section' => 1,
                 'contract_extra_fee' => $request_contract_extra_fee,
                 'contract_createuser' => $contract_createuser]
            );
            foreach($request_courses as $request_course){
                $contract_course_discount_total = round(($request_course[3] - $request_course[8]), 2);
                $contract_course_actual_unit_price = round(($request_course[8]/$request_course[7]), 2);
                // 插入Contract_course表
                DB::table('contract_course')->insert(
                    ['contract_course_contract' => $contract_id,
                     'contract_course_course' => $request_course[0],
                     'contract_course_original_hour' => $request_course[2],
                     'contract_course_free_hour' => $request_course[6],
                     'contract_course_total_hour' => $request_course[7],
                     'contract_course_discount_rate' => $request_course[4],
                     'contract_course_discount_amount' => $request_course[5],
                     'contract_course_discount_total' => $contract_course_discount_total,
                     'contract_course_original_unit_price' => $request_course[1],
                     'contract_course_actual_unit_price' => $contract_course_actual_unit_price,
                     'contract_course_original_price' => $request_course[3],
                     'contract_course_total_price' => $request_course[8],
                     'contract_course_createuser' => $contract_createuser]
                );
                // 插入Hour表
                DB::table('hour')->insert(
                    ['hour_contract' => $contract_id,
                     'hour_student' => $contract_student,
                     'hour_course' => $request_course[0],
                     'hour_original' => $request_course[2]+$request_course[6],
                     'hour_remain' => $request_course[2],
                     'hour_used' => 0,
                     'hour_remain_free' => $request_course[6],
                     'hour_used_free' => 0,
                     'hour_createuser' => $contract_createuser]
                );
            }
            // 增加学生签约次数
            DB::table('student')
              ->where('student_id', $request_student_id)
              ->increment('student_contract_num');
            // 更新客户状态、最后签约时间
            DB::table('student')
              ->where('student_id', $request_student_id)
              ->update(['student_customer_status' =>  1,
                        'student_last_contract_date' =>  date('Y-m-d')]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $request_student_id,
                 'student_record_type' => '签约合同',
                 'student_record_content' => '客户首次签约合同，合同号：'.$contract_id.'，课程种类：'.$contract_course_num.' 种，合计金额：'.$contract_total_price.' 元。签约人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            // 返回购课列表
            return redirect("/operation/contract/create")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '购课添加失败',
                         'message' => '购课添加失败，请重新添加']);
        }
        DB::commit();
        // 获取学生、课程名称
        $student_name = DB::table('student')
                          ->where('student_id', $contract_student)
                          ->value('student_name');
        // 返回购课列表
        return redirect("/operation/contract/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '购课添加成功',
                      'message' => '购课学生: '.$student_name]);
    }

    /**
     * 删除购课
     * URL: DELETE /operation/contract/{contract_id}
     * @param  int  $contract_id
     */
    public function contractDelete($contract_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取Contract信息
        $contract = DB::table('contract')
                      ->where('contract_id', $contract_id)
                      ->first();
        // 获取课程包中已使用Hour信息数量
        $invalid_hour_num = DB::table('hour')
                              ->where([
                                        ['hour_contract', '=', $contract_id],
                                        ['hour_used', ">", 0],
                                       ])
                              ->orWhere([
                                        ['hour_contract', '=', $contract_id],
                                        ['hour_used_free', ">", 0],
                                       ])
                              ->orWhere([
                                        ['hour_contract', '=', $contract_id],
                                        ['hour_refunded', ">", 0],
                                       ])
                              ->count();
        // 没有使用过课时
        if($invalid_hour_num==0){
            DB::beginTransaction();
            try{
                // 删除Hour表
                DB::table('hour')
                  ->where('hour_contract', $contract_id)
                  ->delete();
                // 删除Contract_course表
                DB::table('contract_course')
                  ->where('contract_course_contract', $contract_id)
                  ->delete();
                // 删除Contract表
                DB::table('contract')
                  ->where('contract_id', $contract_id)
                  ->delete();
            }
            // 捕获异常
            catch(Exception $e){
                DB::rollBack();
                return redirect("/operation/contract/my")
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '购课记录删除失败',
                               'message' => '购课记录删除失败，请联系系统管理员']);
            }
            DB::commit();
            // 返回购课列表
            return redirect("/operation/contract/my")
                   ->with(['notify' => true,
                           'type' => 'success',
                           'title' => '购课记录删除成功',
                           'message' => '购课记录删除成功']);
        }else{
            return redirect("/operation/contract/my")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '购课记录删除失败',
                           'message' => '学生剩余课时不足，购课记录删除失败。']);
        }
    }

    /**
     * 补缴费用
     * URL: GET /operation/contract/{contract_id}
     * @param  int  $contract_id
     */
    public function contractEdit($contract_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据
        $contract = DB::table('contract')
                      ->join('student', 'contract.contract_student', '=', 'student.student_id')
                      ->join('department', 'student.student_department', '=', 'department.department_id')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->where('contract_id', '=', $contract_id)
                      ->first();
        // 返回列表视图
        return view('operation/contractEdit', ['contract' => $contract]);
    }

    /**
     * 更新费用
     * URL: POST /operation/contract/{contract_id}
     * @param  int  $contract_id
     */
    public function contractUpdate(Request $request, $contract_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        if ($request->filled('input2')) {
            $contract_remark = $request->input('input2');
        }else{
            $contract_remark = "";
        }
        DB::beginTransaction();
        try{
            DB::table('contract')
              ->where('contract_id', $contract_id)
              ->update(['contract_paid_price' =>  $request->input('input1'),
                        'contract_remark' => $contract_remark]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/contract/my")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '缴费提交失败',
                           'message' => '缴费提交失败，请联系系统管理员']);
        }
        DB::commit();
        return redirect("/operation/contract/my")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '缴费提交成功',
                       'message' => '缴费提交成功，请联系系统管理员']);
    }

    /**
     * 部门签约视图
     * URL: GET /operation/contract/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function contractAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'contract.contract_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->whereIn('contract_department', $department_access)
                  ->where('contract_section', '=', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('operation/contractAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_students' => $filter_students,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 我的签约视图
     * URL: GET /operation/contract/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function contractMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->where('contract_section', '=', 1)
                  ->where('contract_createuser', '=', Session::get('user_id'));

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('operation/contractMy', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_students' => $filter_students,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 学生退费视图
     * URL: POST /operation/refund/create
     * @param  $request->input('input1'): 退课学生
     */
    public function refundCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('student_id');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hours = DB::table('hour')
                   ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                   ->join('course', 'hour.hour_course', '=', 'course.course_id')
                   ->where('hour_remain', '>', 0)
                   ->where('hour_student', $student_id)
                   ->get();
        if($hours->count()==0){
            return redirect("/operation/refund/create")->with(['notify' => true,
                                                             'type' => 'danger',
                                                             'title' => '请重新选择学生',
                                                             'message' => '学生没有可退课时,请重新选择学生.']);
        }
        return view('operation/refundCreate', ['student' => $student,
                                             'hours' => $hours]);
    }

    /**
     * 学生退费视图2
     * URL: POST /operation/refund/create2
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     */
    public function refundCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        $contract_id = $hour->contract_id;
        $course_id = $hour->course_id;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - (($hour->hour_used + $hour->hour_used_free) * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        // 获取退款原因
        $refund_reasons = DB::table('refund_reason')
                           ->where('refund_reason_status', 1)
                           ->get();
        return view('operation/refundCreate2', ['student' => $student,
                                               'hour' => $hour,
                                               'refund_amount' => $refund_amount,
                                               'refund_reasons' => $refund_reasons,
                                               'payment_methods' => $payment_methods]);
    }

    /**
     * 学生退费视图3
     * URL: POST /operation/refund/create3
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function refundCreate3(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        $refund_fine = round((float)$request->input('input3'), 2);
        $refund_reason = $request->input('input4');
        $refund_payment_method = $request->input('input5');
        $refund_date = $request->input('input6');
        $refund_remark = $request->input('input7');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        $contract_id = $hour->contract_id;
        $course_id = $hour->course_id;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - (($hour->hour_used + $hour->hour_used_free) * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        $refund_actual_amount = $refund_amount - $refund_fine;
        if($refund_actual_amount<0){
            $refund_actual_amount = 0;
        }
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        // 获取退款原因
        $refund_reasons = DB::table('refund_reason')
                           ->where('refund_reason_status', 1)
                           ->get();
        return view('operation/refundCreate3', ['student' => $student,
                                               'hour' => $hour,
                                               'refund_actual_amount' => $refund_actual_amount,
                                               'refund_amount' => $refund_amount,
                                               'refund_fine' => $refund_fine,
                                               'refund_reason' => $refund_reason,
                                               'refund_payment_method' => $refund_payment_method,
                                               'refund_date' => $refund_date,
                                               'refund_remark' => $refund_remark]);
    }

    /**
     * 学生退费提交
     * URL: POST /operation/refund/store
     * @param  Request  $request
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function refundStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        $refund_fine = round((float)$request->input('input3'), 2);
        $refund_reason = $request->input('input4');
        $refund_payment_method = $request->input('input5');
        $refund_date = $request->input('input6');
        if($request->filled('input7')) {
            $refund_remark = $request->input('input7');
        }else{
        	$refund_remark = "";
        }
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        // 获取提交数据
        $refund_contract = $hour->contract_id;
        $refund_course = $hour->course_id;
        $refund_department = $student->student_department;
        $refund_student = $student_id;
        $refund_remain_hour = $hour->hour_remain;
        $refund_free_hour = $hour->hour_remain_free;
        $refund_total_hour = $refund_remain_hour + $refund_free_hour;
        $refund_used_hour = $hour->hour_used+$hour->hour_used_free;
        $refund_actual_total_price = $hour->contract_course_total_price;
        $refund_original_unit_price = $hour->contract_course_original_unit_price;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - ($refund_used_hour * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        $refund_actual_amount = $refund_amount - $refund_fine;
        if($refund_actual_amount<0){
            $refund_actual_amount = 0;
        }
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入Refund表
            DB::table('refund')->insert(
                ['refund_type' => 1,
                 'refund_hour' => $hour_id,
                 'refund_contract' => $refund_contract,
                 'refund_course' => $refund_course,
                 'refund_department' => $refund_department,
                 'refund_student' => $refund_student,
                 'refund_remain_hour' => $refund_remain_hour,
                 'refund_free_hour' => $refund_free_hour,
                 'refund_total_hour' => $refund_total_hour,
                 'refund_used_hour' => $refund_used_hour,
                 'refund_actual_total_price' => $refund_actual_total_price,
                 'refund_original_unit_price' => $refund_original_unit_price,
                 'refund_fine' => $refund_fine,
                 'refund_amount' => $refund_amount,
                 'refund_actual_amount' => $refund_actual_amount,
                 'refund_reason' => $refund_reason,
                 'refund_payment_method' => $refund_payment_method,
                 'refund_date' => $refund_date,
                 'refund_remark' => $refund_remark,
                 'refund_createuser' => Session::get('user_id')]
            );
            // 更新Contract_course表
            DB::table('contract_course')
              ->where('contract_course_contract', $refund_contract)
              ->where('contract_course_course', $refund_course)
              ->update(['contract_course_status' => 0]);
            // 更新Hour表
            DB::table('hour')
              ->where('hour_contract', $refund_contract)
              ->where('hour_course', $refund_course)
              ->update(['hour_remain' => 0,
                        'hour_remain_free' =>0,
                        'hour_refunded' => $refund_total_hour]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '学生退费',
                 'student_record_content' => '学生退费，合同号：'.$refund_contract.'，课程名称：'.$hour->course_name.'，共退课时：'.$refund_total_hour.' 课时，共退金额：'.$refund_actual_amount.' 元。退费人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            // 返回购课列表
            return redirect("/operation/refund/create")
                   ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '退费失败',
                             'message' => '退费失败，请联系系统管理员']);
        }
        DB::commit();
        // 获取学生名称
        $student_name = DB::table('student')
                          ->where('student_id', $refund_student)
                          ->value('student_name');
        // 返回购课列表
        return redirect("/operation/refund/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费成功',
                      'message' => '退费学生: '.$student_name]);
    }

    /**
     * 部门退费视图
     * URL: GET /operation/refund/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function refundAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('refund')
                  ->join('student', 'refund.refund_student', '=', 'student.student_id')
                  ->join('course', 'refund.refund_course', '=', 'course.course_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('user AS createuser', 'refund.refund_createuser', '=', 'createuser.user_id')
                  ->join('position AS createuser_position', 'createuser.user_position', '=', 'createuser_position.position_id')
                  ->leftJoin('user AS checked_user', 'refund.refund_checked_user', '=', 'checked_user.user_id')
                  ->leftJoin('position AS checked_user_position', 'checked_user.user_position', '=', 'checked_user_position.position_id')
                  ->whereIn('refund_department', $department_access)
                  ->where('refund_type', '=', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->select('refund.refund_id AS refund_id',
                              'refund.refund_contract AS refund_contract',
                              'refund.refund_total_hour AS refund_total_hour',
                              'refund.refund_fine AS refund_fine',
                              'refund.refund_actual_amount AS refund_actual_amount',
                              'refund.refund_date AS refund_date',
                              'refund.refund_checked AS refund_checked',
                              'refund.refund_createuser AS refund_createuser',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'department.department_name AS department_name',
                              'course.course_name AS course_name',
                              'createuser.user_name AS createuser_name',
                              'createuser_position.position_name AS createuser_position_name',
                              'checked_user.user_name AS checked_user_name',
                              'checked_user_position.position_name AS checked_user_position_name')
                     ->orderBy('refund_date', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('operation/refundAll', ['rows' => $rows,
                                         'currentPage' => $currentPage,
                                         'totalPage' => $totalPage,
                                         'startIndex' => $offset,
                                         'request' => $request,
                                         'totalNum' => $totalNum,
                                         'filter_status' => $filter_status,
                                         'filter_departments' => $filter_departments,
                                         'filter_students' => $filter_students,
                                         'filter_grades' => $filter_grades]);
    }

    /**
     * 我的退费视图
     * URL: GET /operation/refund/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function refundMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('refund')
                  ->join('student', 'refund.refund_student', '=', 'student.student_id')
                  ->join('course', 'refund.refund_course', '=', 'course.course_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('user AS createuser', 'refund.refund_createuser', '=', 'createuser.user_id')
                  ->join('position AS createuser_position', 'createuser.user_position', '=', 'createuser_position.position_id')
                  ->leftJoin('user AS checked_user', 'refund.refund_checked_user', '=', 'checked_user.user_id')
                  ->leftJoin('position AS checked_user_position', 'checked_user.user_position', '=', 'checked_user_position.position_id')
                  ->where('refund_type', '=', 1)
                  ->where('refund_createuser', Session::get('user_id'));

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->select('refund.refund_id AS refund_id',
                              'refund.refund_contract AS refund_contract',
                              'refund.refund_total_hour AS refund_total_hour',
                              'refund.refund_fine AS refund_fine',
                              'refund.refund_actual_amount AS refund_actual_amount',
                              'refund.refund_date AS refund_date',
                              'refund.refund_checked AS refund_checked',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'department.department_name AS department_name',
                              'course.course_name AS course_name',
                              'createuser.user_name AS createuser_name',
                              'createuser_position.position_name AS createuser_position_name',
                              'checked_user.user_name AS checked_user_name',
                              'checked_user_position.position_name AS checked_user_position_name')
                     ->orderBy('refund_date', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('operation/refundMy', ['rows' => $rows,
                                         'currentPage' => $currentPage,
                                         'totalPage' => $totalPage,
                                         'startIndex' => $offset,
                                         'request' => $request,
                                         'totalNum' => $totalNum,
                                         'filter_status' => $filter_status,
                                         'filter_departments' => $filter_departments,
                                         'filter_students' => $filter_students,
                                         'filter_grades' => $filter_grades]);
    }

    /**
     * 审核退课
     * URL: GET /operation/refund/{refund_id}
     * @param  int  $refund_id
     */
    public function refundCheck($refund_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        DB::beginTransaction();
        try{
            // 删除Refund表
            DB::table('refund')
              ->where('refund_id', $refund_id)
              ->update(['refund_checked' => 1,
                        'refund_checked_user' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/refund/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '退费记录审核失败！',
                          'message' => '退费记录审核失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/operation/refund/all")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费记录审核成功！',
                      'message' => '退费记录审核成功！']);
    }

    /**
     * 删除退课
     * URL: DELETE /operation/refund/{refund_id}
     * @param  int  $refund_id
     */
    public function refundDelete($refund_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取refund信息
        $refund = DB::table('refund')
                    ->join('course', 'refund.refund_course', '=', 'course.course_id')
                    ->where('refund_id', $refund_id)
                    ->first();
        DB::beginTransaction();
        try{
            // 更新Hour表
            DB::table('hour')
              ->where('hour_id', $refund->refund_hour)
              ->update(['hour_refunded' => 0,
                        'hour_remain' => $refund->refund_remain_hour,
                        'hour_remain_free' => $refund->refund_free_hour]);
            // 更新Contract_course表
            DB::table('contract_course')
              ->where('contract_course_contract', $refund->refund_contract)
              ->where('contract_course_course', $refund->refund_course)
              ->update(['contract_course_status' => 1]);
            // 删除Refund表
            DB::table('refund')
              ->where('refund_id', $refund_id)
              ->delete();
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $refund->refund_student,
                 'student_record_type' => '删除学生退费',
                 'student_record_content' => '删除学生退费记录，合同号：'.$refund->refund_contract.
                                             '，课程名称：'.$refund->course_name.
                                             '，恢复正常课时：'.$refund->refund_remain_hour.' 课时，恢复赠送课时：'.$refund->refund_free_hour.' 课时。
                                             删除人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            return redirect("/operation/refund/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '退费记录删除失败！',
                          'message' => '退费记录删除失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/operation/refund/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费记录删除成功！',
                      'message' => '退费记录删除成功！']);
    }

    /**
     * 课程考勤视图
     * URL: GET /education/schedule/attend/{schedule_id}
     * @param  int  $schedule_id
     */
    public function scheduleAttend($schedule_id){
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
        // 获取上课日期、时间
        $schedule_date = $schedule->schedule_date;
        $schedule_start = $schedule->schedule_start;
        $schedule_end = $schedule->schedule_end;
        $schedule_department = $schedule->schedule_department;
        // 获取所有可用教师
        $teachers = DB::table('user')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', $schedule_department)
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc');
        $teachers = DB::table('user')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', $schedule_department)
                      ->where('user_status', 1)
                      ->union($teachers)
                      ->get();
        // 获取所有可用教室名单
        $classrooms = DB::table('classroom')
                        ->where('classroom_department', $schedule_department)
                        ->where('classroom_status', 1)
                        ->orderBy('classroom_id', 'asc')
                        ->get();
        // 获取所有科目
        $subjects = DB::table('subject')
                        ->where('subject_status', 1)
                        ->orderBy('subject_id', 'asc')
                        ->get();

        // 获取上课成员(学生/班级成员)
        $student_courses = array();
        // 获取成员ID
        $schedule_participant = $schedule->schedule_participant;
        // 获取成员ID首字母
        $schedule_type = substr($schedule_participant , 0 , 1);
        if($schedule_type=="S"){ // 上课成员为学生
            // 获取学生信息
            $student = DB::table('student')
                         ->join('department', 'student.student_department', '=', 'department.department_id')
                         ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                         ->where('student_id', $schedule_participant)
                         ->first();
            // 获取学生已购课程
            $courses = DB::table('student')
                         ->join('hour', 'student.student_id', '=', 'hour.hour_student')
                         ->join('course', 'hour.hour_course', '=', 'course.course_id')
                         ->where([
                             ['student.student_id', '=', $schedule_participant],
                             ['hour.hour_remain', '>', '0'],
                         ])
                         ->orWhere([
                             ['student.student_id', '=', $schedule_participant],
                             ['hour.hour_remain_free', '>', '0'],
                         ])
                         ->get();
            $student_courses[] = array($student, $courses);
        }else{ // 上课成员为班级
            // 获取班级学生
            $members = DB::table('class')
                         ->join('member', 'class.class_id', '=', 'member.member_class')
                         ->join('student', 'member.member_student', '=', 'student.student_id')
                         ->where('class_id', $schedule_participant)
                         ->get();
            foreach ($members as $member){
                // 获取学生信息
                $student = DB::table('student')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                             ->where('student_id', $member->student_id)
                             ->first();
                // 获取学生已购课程
                $courses = DB::table('student')
                             ->join('hour', 'student.student_id', '=', 'hour.hour_student')
                             ->join('course', 'hour.hour_course', '=', 'course.course_id')
                             ->where([
                                 ['student.student_id', '=', $member->student_id],
                                 ['hour.hour_remain', '>', '0'],
                             ])
                             ->orWhere([
                                 ['student.student_id', '=', $member->student_id],
                                 ['hour.hour_remain_free', '>', '0'],
                             ])
                             ->get();
                $student_courses[] = array($student, $courses);
            }
        }
        return view('operation/scheduleAttend', ['schedule' => $schedule,
                                                 'teachers' => $teachers,
                                                 'classrooms' => $classrooms,
                                                 'subjects' => $subjects,
                                                 'student_courses' => $student_courses]);
    }

    /**
     * 课程考勤视图2
     * URL: POST /education/schedule/attend/{schedule_id}/step2
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     * @param  $request->input('input3'): 学生人数
     */
    public function scheduleAttend2(Request $request, $schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_date = $request->input('input_date');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_subject = $request->input('input_subject');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_student_num = $request->input('input_student_num');
        // 判断时间合法性
        if($schedule_start>=$schedule_end){
            return redirect("/operation/schedule/attend/{$schedule_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前！']);
        }
        // 获取教师姓名
        $schedule_teacher_name = DB::table('user')
                                   ->select('user_name')
                                   ->where('user_id', $schedule_teacher)
                                   ->first()
                                   ->user_name;
        // 获取科目名称
        $schedule_subject_name = DB::table('subject')
                                   ->select('subject_name')
                                   ->where('subject_id', $schedule_subject)
                                   ->first()
                                   ->subject_name;
        // 获取教室名称
        $schedule_classroom_name = DB::table('classroom')
                                   ->select('classroom_name')
                                   ->where('classroom_id', $schedule_classroom)
                                   ->first()
                                   ->classroom_name;
        // 声明数据数组
        $student_courses = array();
        for($i=1;$i<=$schedule_student_num;$i++){
            $participant_student = $request->input('input'.$i.'_0');
            $student_name = DB::table('student')
                              ->where('student_id', $participant_student)
                              ->first()
                              ->student_name;
            $participant_attend_status = $request->input('input'.$i.'_1');
            if($participant_attend_status==2){
                $student_courses[] = array($participant_student, $participant_attend_status, 0, 0, $student_name, "无");
                continue;
            }
            $participant_hour = $request->input('input'.$i.'_2');
            $participant_amount = $request->input('input'.$i.'_3');
            if($participant_attend_status!=2){
                // 查询剩余课时
                $hour = DB::table('hour')
                          ->join('course', 'hour.hour_course', '=', 'course.course_id')
                          ->where('hour_id', $participant_hour)
                          ->first();
                $hour_remain = $hour->hour_remain+$hour->hour_remain_free;
                $course_name = $hour->course_name;
                // 剩余课时不足
                if($participant_amount>$hour_remain){
                    // 查询学生名称
                    $student_name = DB::table('student')
                                      ->where('student_id', $participant_student)
                                      ->first()
                                      ->student_name;
                    // 返回第一步
                    return redirect("/operation/schedule/attend/{$schedule_id}")
                           ->with(['notify' => true,
                                   'type' => 'danger',
                                   'title' => '学生剩余课时不足，请重新选择',
                                   'message' => $student_name.'剩余课时不足，请重新选择']);

                }
            }else{
                $course_name = "无";
            }
            $student_courses[] = array($participant_student, $participant_attend_status, $participant_hour, $participant_amount, $student_name, $course_name);
        }
        // 获取数据信息
        $schedule = DB::table('schedule')
                      ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                      ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->where('schedule_id', $schedule_id)
                      ->first();
        return view('operation/scheduleAttend2', ['schedule' => $schedule,
                                                 'schedule_date' => $schedule_date,
                                                 'schedule_start' => $schedule_start,
                                                 'schedule_end' => $schedule_end,
                                                 'schedule_teacher' => $schedule_teacher,
                                                 'schedule_teacher_name' => $schedule_teacher_name,
                                                 'schedule_subject' => $schedule_subject,
                                                 'schedule_subject_name' => $schedule_subject_name,
                                                 'schedule_classroom' => $schedule_classroom,
                                                 'schedule_classroom_name' => $schedule_classroom_name,
                                                 'student_courses' => $student_courses]);
    }

    /**
     * 课程考勤提交
     * URL: POST /operation/schedule/attend/{schedule_id}/store
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     * @param  $request->input('input3'): 学生人数
     */
    public function scheduleAttendStore(Request $request, $schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取表单输入
        $schedule_date = $request->input('input_date');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_subject = $request->input('input_subject');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_student_num = $request->input('input_student_num');

        // 获取安排信息
        $schedule = DB::table('schedule')
                      ->where('schedule_id', $schedule_id)
                      ->first();

        // 统计上课人数
        $schedule_attended_num = 0; // 正常
        $schedule_leave_num = 0; // 请假
        $schedule_absence_num = 0; // 旷课

        DB::beginTransaction();
        try{
            for($i=1;$i<=$schedule_student_num;$i++){
                $participant_student = $request->input('input'.$i.'_0');
                $participant_attend_status = $request->input('input'.$i.'_1');
                if($participant_attend_status==1){ // 正常（计课时）
                    $participant_hour = $request->input('input'.$i.'_2');
                    $participant_amount = $request->input('input'.$i.'_3');
                    $schedule_attended_num = $schedule_attended_num + 1; // 增加正常上课人数
                }else if($participant_attend_status==2){ // 请假（不计课时）
                    $participant_hour = 0;
                    $participant_amount = 0;
                    $schedule_leave_num = $schedule_leave_num + 1; // 增加请假人数
                }else { // 旷课（计课时）
                    $participant_hour = $request->input('input'.$i.'_2');
                    $participant_amount = $request->input('input'.$i.'_3');
                    $schedule_absence_num = $schedule_absence_num + 1; // 增加旷课人数
                }
                // 扣除剩余课时
                if($participant_attend_status!=2){
                    // 获取剩余课时信息
                    $hour = DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->first();
                    // 有正常课时
                    if($hour->hour_remain>0){
                        //正常课时足够
                        if($hour->hour_remain>=$participant_amount){
                            // 扣除学生正常课时
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->decrement('hour_remain', $participant_amount);
                            // 增加已用正常课时数
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->increment('hour_used', $participant_amount);
                        }else{ //正常课时不足
                            // 扣除学生正常课时
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->decrement('hour_remain', $hour->hour_remain);
                            // 增加已用正常课时数
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->increment('hour_used', $hour->hour_remain);
                            // 剩余需扣除赠送课时
                            $participant_free_amount = $participant_amount-$hour->hour_remain;
                            // 扣除学生赠送课时
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->decrement('hour_remain_free', $participant_free_amount);
                            // 增加已用赠送课时数
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->increment('hour_used_free', $participant_free_amount);
                        }
                    }else{ // 没有正常课时
                        // 扣除学生正常课时
                        DB::table('hour')
                          ->where('hour_id', $participant_hour)
                          ->decrement('hour_remain_free', $participant_amount);
                        // 增加已用正常课时数
                        DB::table('hour')
                          ->where('hour_id', $participant_hour)
                          ->increment('hour_used_free', $participant_amount);
                    }
                }
                // 添加上课成员表
                DB::table('participant')->insert(
                    ['participant_schedule' => $schedule_id,
                     'participant_student' => $participant_student,
                     'participant_attend_status' => $participant_attend_status,
                     'participant_hour' => $participant_hour,
                     'participant_amount' => $participant_amount,
                     'participant_createuser' => Session::get('user_id')]
                );
            }
            DB::table('schedule')
              ->where('schedule_id', $schedule_id)
              ->update(['schedule_date' => $schedule_date,
                        'schedule_start' => $schedule_start,
                        'schedule_end' => $schedule_end,
                        'schedule_teacher' => $schedule_teacher,
                        'schedule_subject' => $schedule_subject,
                        'schedule_classroom' => $schedule_classroom,
                        'schedule_student_num' => $schedule_student_num,
                        'schedule_attended_num' => $schedule_attended_num,
                        'schedule_leave_num' => $schedule_leave_num,
                        'schedule_absence_num' => $schedule_absence_num,
                        'schedule_attended' => 1,
                        'schedule_attended_user' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            // 返回第一步
            return redirect("/operation/schedule/attend/{$schedule_id}")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '学生剩余课时不足，请重新选择',
                         'message' => '学生剩余课时不足，请重新选择']);
        }
        DB::commit();
        // 返回我的上课记录视图
        return redirect("/operation/schedule/attend/{$schedule_id}/result");
    }

    /**
     * 课程考勤提交成功
     * URL: GET /operation/schedule/attend/success
     */
    public function scheduleAttendResult(){
        // 返回课程考勤视图
        return view('operation/scheduleAttendResult');
    }
}
