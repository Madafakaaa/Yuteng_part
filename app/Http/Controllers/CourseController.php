<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CourseController extends Controller
{
    /**
     * 显示所有课程记录
     * URL: GET /course
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 名称
     * @param  $request->input('filter2'): 校区
     * @param  $request->input('filter3'): 年级
     * @param  $request->input('filter4'): 科目
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        $department_access[]=0;
        // 获取数据
        $rows = DB::table('course')
                  ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                  ->leftJoin('department', 'course.course_department', '=', 'department.department_id')
                  ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                  ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                  ->whereIn('course_department', $department_access)
                  ->where('course_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 课程名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('course_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 开课校区
        if($request->filled('filter2')){
            $rows = $rows->where('course_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 课程年级
        if($request->filled('filter3')){
            $rows = $rows->where('course_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 课程科目
        if($request->filled('filter4')){
            $rows = $rows->where('course_subject', '=', $request->input('filter4'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('course_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('course/index', ['rows' => $rows,
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
     * 创建新课程页面
     * URL: GET /course/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取年级、科目信息、课程类型
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $course_types = DB::table('course_type')->where('course_type_status', 1)->get();
        return view('course/create', ['departments' => $departments,
                                      'grades' => $grades,
                                      'subjects' => $subjects,
                                      'course_types' => $course_types]);
    }

    /**
     * 创建新课程提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 课程名称
     * @param  $request->input('input2'): 开课校区
     * @param  $request->input('input3'): 课程季度
     * @param  $request->input('input4'): 课程年级
     * @param  $request->input('input5'): 课程科目
     * @param  $request->input('input6'): 课时单价
     * @param  $request->input('input7'): 课程类型
     * @param  $request->input('input8'): 课程时长
     * @param  $request->input('input9'): 课程备注
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $course_name = $request->input('input1');
        $course_department = $request->input('input2');
        $course_quarter = $request->input('input3');
        $course_grade = $request->input('input4');
        $course_subject = $request->input('input5');
        $course_unit_price = $request->input('input6');
        $course_type = $request->input('input7');
        $course_time = $request->input('input8');
        if($request->filled('input9')) {
            $course_remark = $request->input('input9');
        }else{
            $course_remark = "";
        }
        // 获取当前用户ID
        $course_createuser = Session::get('user_id');
        // 插入数据库
        try{
           DB::table('course')->insert(
                ['course_name' => $course_name,
                 'course_department' => $course_department,
                 'course_quarter' => $course_quarter,
                 'course_grade' => $course_grade,
                 'course_subject' => $course_subject,
                 'course_unit_price' => $course_unit_price,
                 'course_type' => $course_type,
                 'course_time' => $course_time,
                 'course_remark' => $course_remark,
                 'course_createuser' => $course_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('CourseController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程添加失败',
                                     'message' => '课程添加失败，请重新输入信息']);
        }
        // 返回课程列表
        return redirect()->action('CourseController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '课程添加成功',
                                 'message' => '课程名称: '.$course_name]);
    }

    /**
     * 显示单个课程详细信息
     * URL: GET /course/{id}
     * @param  int  $course_id
     */
    public function show($course_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $course = DB::table('course')
                    ->leftJoin('department', 'course.course_department', '=', 'department.department_id')
                    ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                    ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                    ->where('course_id', $course_id)
                    ->where('course_status', 1)
                    ->get();
        if($course->count()!==1){
            // 未获取到数据
            return redirect()->action('CourseController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程显示失败',
                                     'message' => '课程显示失败，请联系系统管理员']);
        }
        $course = $course[0];
        return view('course/show', ['course' => $course]);
    }

    /**
     * 修改单个课程
     * URL: GET /course/{id}/edit
     * @param  int  $course_id
     */
    public function edit($course_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $course = DB::table('course')->where('course_id', $course_id)->get();
        if($course->count()!==1){
            // 未获取到数据
            return redirect()->action('CourseController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程显示失败',
                                     'message' => '课程显示失败，请联系系统管理员']);
        }
        $course = $course[0];
        // 获取校区、年级、科目信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        // 获取课程类型
        $course_types = DB::table('course_type')
                           ->where('course_type_status', 1)
                           ->get();
        return view('course/edit', ['course' => $course,
                                    'departments' => $departments,
                                    'grades' => $grades,
                                    'subjects' => $subjects,
                                    'course_types' => $course_types]);
    }

    /**
     * 修改新课程提交数据库
     * URL: PUT /course/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 课程名称
     * @param  $request->input('input2'): 开课校区
     * @param  $request->input('input3'): 课程季度
     * @param  $request->input('input4'): 课程年级
     * @param  $request->input('input5'): 课程科目
     * @param  $request->input('input6'): 课时单价
     * @param  $request->input('input7'): 课程类型
     * @param  $request->input('input8'): 课程时长
     * @param  $request->input('input9'): 课程备注
     * @param  int  $course_id
     */
    public function update(Request $request, $course_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $course_name = $request->input('input1');
        $course_department = $request->input('input2');
        $course_quarter = $request->input('input3');
        $course_grade = $request->input('input4');
        $course_subject = $request->input('input5');
        $course_unit_price = $request->input('input6');
        $course_type = $request->input('input7');
        $course_time = $request->input('input8');
        if($request->filled('input9')) {
            $course_remark = $request->input('input9');
        }else{
            $course_remark = "";
        }
        // 更新数据库
        try{
            DB::table('course')
              ->where('course_id', $course_id)
              ->update(['course_name' => $course_name,
                        'course_department' => $course_department,
                        'course_quarter' => $course_quarter,
                        'course_grade' => $course_grade,
                        'course_subject' => $course_subject,
                        'course_unit_price' => $course_unit_price,
                        'course_type' => $course_type,
                        'course_time' => $course_time,
                        'course_remark' => $course_remark]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/course/{$course_id}/edit")->with(['notify' => true,
                                                           'type' => 'danger',
                                                           'title' => '课程修改失败',
                                                           'message' => '课程修改失败，课程名称: '.$course_name]);
        }
        return redirect("/course")->with(['notify' => true,
                                          'type' => 'success',
                                          'title' => '课程修改成功',
                                          'message' => '课程修改成功，课程名称: '.$course_name]);
    }

    /**
     * 删除课程
     * URL: DELETE /course/{id}
     * @param  int  $course_id
     */
    public function destroy($course_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $course_name = DB::table('course')->where('course_id', $course_id)->value('course_name');
        // 删除数据
        try{
            DB::table('course')->where('course_id', $class_id)->update(['course_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('CourseController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程删除失败',
                                     'message' => '课程删除失败，请联系系统管理员']);
        }
        // 返回课程列表
        return redirect()->action('CourseController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '课程删除成功',
                                 'message' => '课程名称: '.$course_name]);
    }
}
