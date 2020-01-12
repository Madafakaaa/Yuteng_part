<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class StudentController extends Controller
{
    /**
     * 显示所有学生记录
     * URL: GET /student
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 学生名称
     * @param  $request->input('filter2'): 学生校区
     * @param  $request->input('filter3'): 学生年级
     * @param  $request->input('filter4'): 学生学校
     */
    public function index(Request $request){
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
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_status', 1);
        // 添加筛选条件
        // 学生名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 学生学校
        if ($request->filled('filter4')) {
            $rows = $rows->where('student_school', '=', $request->input('filter4'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('student_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、学校信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();

        // 返回列表视图
        return view('student/index', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_departments' => $filter_departments,
                                               'filter_grades' => $filter_grades,
                                               'filter_schools' => $filter_schools]);
    }

    /**
     * 创建新学生页面
     * URL: GET /student/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取校区、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();
        return view('student/create', ['departments' => $departments,
                                                'grades' => $grades,
                                                'schools' => $schools]);
    }

    /**
     * 创建新学生提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 学生姓名
     * @param  $request->input('input2'): 学生校区
     * @param  $request->input('input3'): 学生年级
     * @param  $request->input('input4'): 学生性别
     * @param  $request->input('input5'): 学生生日
     * @param  $request->input('input6'): 学生学校
     * @param  $request->input('input7'): 监护人姓名
     * @param  $request->input('input8'): 监护人关系
     * @param  $request->input('input9'): 联系电话
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_name = $request->input('input1');
        $student_department = $request->input('input2');
        $student_grade = $request->input('input3');
        $student_gender = $request->input('input4');
        $student_birthday = $request->input('input5');
        $student_school = $request->input('input6');
        $student_guardian = $request->input('input7');
        $student_guardian_relationship = $request->input('input8');
        $student_phone = $request->input('input9');
        // 获取当前用户ID
        $student_createuser = Session::get('user_id');
        // 生成新学生ID
        $student_num = DB::table('student')
                         ->where('student_department', $student_department)
                         ->whereYear('student_createtime', date('Y'))
                         ->whereMonth('student_createtime', date('m'))
                         ->count()+1;
        $student_id = "S".substr(date('Ym'),2).sprintf("%02d", $student_department).sprintf("%03d", $student_num);
        // 插入数据库
        try{
            DB::table('student')->insert(
                ['student_id' => $student_id,
                 'student_name' => $student_name,
                 'student_department' => $student_department,
                 'student_grade' => $student_grade,
                 'student_gender' => $student_gender,
                 'student_birthday' => $student_birthday,
                 'student_school' => $student_school,
                 'student_guardian' => $student_guardian,
                 'student_guardian_relationship' => $student_guardian_relationship,
                 'student_phone' => $student_phone,
                 'student_createuser' => $student_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return $e;
        }
        // 返回学生列表
        return redirect()->action('StudentController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '学生添加成功',
                                 'message' => '学生名称: '.$student_name.', 学生学号: '.$student_id]);
    }

    /**
     * 显示单个学生详细信息
     * URL: GET /student/{id}
     * @param  int  $student_id
     */
    public function show($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                     ->where('student_id', $student_id)
                     ->get();
        if($student->count()!==1){
            // 未获取到数据
            return redirect()->action('StudentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '学生显示失败',
                                     'message' => '学生显示失败，请联系系统管理员']);
        }
        $student = $student[0];
        return view('student/show', ['student' => $student]);
    }

    /**
     * 修改单个学生
     * URL: GET /student/{id}/edit
     * @param  int  $student_id
     */
    public function edit($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student = DB::table('student')->where('student_id', $student_id)->get();
        if($student->count()!==1){
            // 未获取到数据
            return redirect()->action('StudentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '学生显示失败',
                                     'message' => '学生显示失败，请联系系统管理员']);
        }
        $student = $student[0];
        // 获取校区、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();
        return view('student/edit', ['student' => $student,
                                              'departments' => $departments,
                                              'grades' => $grades,
                                              'schools' => $schools]);
    }

    /**
     * 修改新学生提交数据库
     * URL: PUT /student/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 学生姓名
     * @param  $request->input('input2'): 学生校区
     * @param  $request->input('input3'): 学生年级
     * @param  $request->input('input4'): 学生性别
     * @param  $request->input('input5'): 学生生日
     * @param  $request->input('input6'): 学生学校
     * @param  $request->input('input7'): 监护人姓名
     * @param  $request->input('input8'): 监护人关系
     * @param  $request->input('input9'): 联系电话
     * @param  int  $student_id
     */
    public function update(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $student_name = $request->input('input1');
        $student_department = $request->input('input2');
        $student_grade = $request->input('input3');
        $student_gender = $request->input('input4');
        $student_birthday = $request->input('input5');
        $student_school = $request->input('input6');
        $student_guardian = $request->input('input7');
        $student_guardian_relationship = $request->input('input8');
        $student_phone = $request->input('input9');
        // 更新数据库
        try{
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_name' => $student_name,
                        'student_department' => $student_department,
                        'student_grade' => $student_grade,
                        'student_gender' => $student_gender,
                        'student_birthday' => $student_birthday,
                        'student_school' => $student_school,
                        'student_guardian' => $student_guardian,
                        'student_guardian_relationship' => $student_guardian_relationship,
                        'student_phone' => $student_phone]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/student/{$student_id}/edit")->with(['notify' => true,
                                                                  'type' => 'danger',
                                                                  'title' => '学生修改失败',
                                                                  'message' => '学生修改失败，请重新输入信息']);
        }
        return redirect("/student")->with(['notify' => true,
                                            'type' => 'success',
                                            'title' => '学生修改成功',
                                            'message' => '学生修改成功，学生名称: '.$student_name]);
    }

    /**
     * 删除学生
     * URL: DELETE /student/{id}
     * @param  int  $student_id
     */
    public function destroy($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student_name = DB::table('student')->where('student_id', $student_id)->value('student_name');
        // 删除数据
        try{
            DB::table('student')->where('student_id', $student_id)->update(['student_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('StudentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '学生删除失败',
                                     'message' => '学生删除失败，请联系系统管理员']);
        }
        // 返回学生列表
        return redirect()->action('StudentController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '学生删除成功',
                                 'message' => '学生名称: '.$student_name]);
    }
}
