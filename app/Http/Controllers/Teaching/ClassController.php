<?php
namespace App\Http\Controllers\Teaching;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ClassController extends Controller
{
    /**
     * 显示所有班级记录
     * URL: GET /class
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function index(Request $request){
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
        return view('teaching/class/index', ['rows' => $rows,
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
     * 创建新班级页面
     * URL: GET /class/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取校区、年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();
        return view('teaching/class/create', ['departments' => $departments,
                                              'grades' => $grades,
                                              'subjects' => $subjects,
                                              'users' => $users]);
    }

    /**
     * 创建新班级提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 班级名称
     * @param  $request->input('input2'): 班级校区
     * @param  $request->input('input3'): 班级年级
     * @param  $request->input('input3'): 班级科目
     * @param  $request->input('input6'): 负责教师
     * @param  $request->input('input5'): 班级人数
     */
    public function store(Request $request){
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
                 'class_createuser' => $class_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('Teaching\ClassController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '班级添加失败',
                                     'message' => '班级添加失败，请重新输入信息']);
        }
        // 返回班级列表
        return redirect()->action('Teaching\ClassController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '班级添加成功',
                                 'message' => '班级名称: '.$class_name.', 班级学号: '.$class_id]);
    }

    /**
     * 显示单个班级详细信息
     * URL: GET /class/{id}
     * @param  int  $class_id
     */
    public function show($class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $class = DB::table('class')
                   ->join('department', 'class.class_department', '=', 'department.department_id')
                   ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                   ->leftJoin('subject', 'class.class_subject', '=', 'subject.subject_id')
                   ->join('user', 'class.class_teacher', '=', 'user.user_id')
                   ->where('class_id', $class_id)
                   ->get();
        if($class->count()!==1){
            // 未获取到数据
            return redirect()->action('Teaching\ClassController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '班级显示失败',
                                     'message' => '班级显示失败，请联系系统管理员']);
        }
        $class = $class[0];
        // 获取班级年级
        $student_grade = $class->class_grade;
        // 获取成员数据
        $rows = DB::table('member')
                  ->join('class', 'member.member_class', '=', 'class.class_id')
                  ->join('student', 'member.member_student', '=', 'student.student_id')
                  ->where('member.member_class', $class_id)
                  ->get();
        // 获取学生信息
        $students = DB::table('student')
                         ->where('student_status', 1)
                         ->where('student_grade', $student_grade)
                         ->orderBy('student_createtime', 'asc')
                         ->get();
        return view('teaching/class/show', ['class' => $class,
                                            'students' => $students,
                                            'rows' => $rows]);
    }

    /**
     * 修改单个班级
     * URL: GET /class/{id}/edit
     * @param  int  $class_id
     */
    public function edit($class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $class = DB::table('class')
                   ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                   ->where('class_id', $class_id)
                   ->get();
        if($class->count()!==1){
            // 未获取到数据
            return redirect()->action('Teaching\ClassController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '班级显示失败',
                                     'message' => '班级显示失败，请联系系统管理员']);
        }
        $class = $class[0];
        // 获取校区、年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();
        return view('teaching/class/edit', ['class' => $class,
                                            'departments' => $departments,
                                            'grades' => $grades,
                                            'subjects' => $subjects,
                                            'users' => $users]);
    }

    /**
     * 修改新班级提交数据库
     * URL: PUT /class/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 班级名称
     * @param  $request->input('input2'): 班级校区
     * @param  $request->input('input3'): 班级年级
     * @param  $request->input('input4'): 班级科目
     * @param  $request->input('input5'): 负责教师
     * @param  $request->input('input6'): 最大人数
     * @param  int  $class_id
     */
    public function update(Request $request, $class_id){
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
        // 更新数据库
        try{
            DB::table('class')
              ->where('class_id', $class_id)
              ->update(['class_name' => $class_name,
                        'class_department' => $class_department,
                        'class_grade' => $class_grade,
                        'class_subject' => $class_subject,
                        'class_teacher' => $class_teacher,
                        'class_max_num' => $class_max_num]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/class/{$class_id}/edit")->with(['notify' => true,
                                                              'type' => 'danger',
                                                              'title' => '班级修改失败',
                                                              'message' => '班级修改失败，请重新输入信息']);
        }
        return redirect("/class")->with(['notify' => true,
                                         'type' => 'success',
                                         'title' => '班级修改成功',
                                         'message' => '班级修改成功，班级名称: '.$class_name]);
    }

    /**
     * 删除班级
     * URL: DELETE /class/{id}
     * @param  int  $class_id
     */
    public function destroy($class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $class_name = DB::table('class')->where('class_id', $class_id)->value('class_name');
        // 删除数据
        try{
            DB::table('class')->where('class_id', $class_id)->update(['class_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('Teaching\ClassController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '班级删除失败',
                                     'message' => '班级删除失败，请联系系统管理员']);
        }
        // 返回班级列表
        return redirect()->action('Teaching\ClassController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '班级删除成功',
                                 'message' => '班级名称: '.$class_name]);
    }
}
