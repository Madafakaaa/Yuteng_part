<?php
namespace App\Http\Controllers\School;

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
     * @param  $request->input('filter1'): 课程名称
     * @param  $request->input('filter2'): 开课校区
     * @param  $request->input('filter3'): 课程季度
     * @param  $request->input('filter4'): 课程年级
     * @param  $request->input('filter5'): 课程科目
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');
        // 获取数据库信息
        // 获取总数据数
        $totalRecord = DB::table('course')->where('course_status', 1);
        // 添加筛选条件
        // 课程名称
        if ($request->has('filter1')) {
            if($request->input('filter1')!=''){
                $totalRecord = $totalRecord->where('course_name', 'like', '%'.$request->input('filter1').'%');
            }
        }
        // 开课校区
        if ($request->has('filter2')) {
            if($request->input('filter2')!=''){
                $totalRecord = $totalRecord->where('course_department', '=', $request->input('filter2'));
            }
        }
        // 课程季度
        if ($request->has('filter3')) {
            if($request->input('filter3')!=''){
                $totalRecord = $totalRecord->where('course_quarter', '=', $request->input('filter3'));
            }
        }
        // 课程年级
        if ($request->has('filter4')) {
            if($request->input('filter4')!=''){
                $totalRecord = $totalRecord->where('course_grade', '=', $request->input('filter4'));
            }
        }
        // 课程科目
        if ($request->has('filter5')) {
            if($request->input('filter5')!=''){
                $totalRecord = $totalRecord->where('course_subject', '=', $request->input('filter5'));
            }
        }
        $totalRecord = $totalRecord->count();
        // 设置每页数据(20数据/页)
        $rowPerPage = 20;
        // 获取总页数
        if($totalRecord==0){
            $totalPage = 1;
        }else{
            $totalPage = ceil($totalRecord/$rowPerPage);
        }
        // 获取当前页数
        if ($request->has('page')) {
            $currentPage = $request->input('page');
            if($currentPage<1)
                $currentPage = 1;
            if($currentPage>$totalPage)
                $currentPage = $totalPage;
        }else{
            $currentPage = 1;
        }
        // 获取数据
        $offset = ($currentPage-1)*$rowPerPage;
        $rows = DB::table('course')
                    ->leftJoin('department', 'course.course_department', '=', 'department.department_id')
                    ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                    ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                    ->where('course_status', 1);
        // 添加筛选条件
        // 课程名称
        if ($request->has('filter1')) {
            if($request->input('filter1')!=''){
                $rows = $rows->where('course_name', 'like', '%'.$request->input('filter1').'%');
            }
        }
        // 开课校区
        if ($request->has('filter2')) {
            if($request->input('filter2')!=''){
                $rows = $rows->where('course_department', '=', $request->input('filter2'));
            }
        }
        // 课程季度
        if ($request->has('filter3')) {
            if($request->input('filter3')!=''){
                $rows = $rows->where('course_quarter', '=', $request->input('filter3'));
            }
        }
        // 课程年级
        if ($request->has('filter4')) {
            if($request->input('filter4')!=''){
                $rows = $rows->where('course_grade', '=', $request->input('filter4'));
            }
        }
        // 课程科目
        if ($request->has('filter5')) {
            if($request->input('filter5')!=''){
                $rows = $rows->where('course_subject', '=', $request->input('filter5'));
            }
        }
        $rows = $rows->orderBy('course_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        return view('school/course/index', ['rows' => $rows,
                                            'currentPage' => $currentPage,
                                            'totalPage' => $totalPage,
                                            'startIndex' => ($currentPage-1)*$rowPerPage,
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
        // 获取年级、科目信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        return view('school/course/create', ['departments' => $departments, 'grades' => $grades, 'subjects' => $subjects]);
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
     * @param  $request->input('input7'): 课时时长
     * @param  $request->input('input8'): 起始日期
     * @param  $request->input('input9'): 截止日期
     * @param  $request->input('input10'): 课程备注
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
        $course_time = $request->input('input7');
        $course_start = $request->input('input8');
        $course_end = $request->input('input9');
        $course_remark = $request->input('input10');
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
                 'course_time' => $course_time,
                 'course_start' => $course_start,
                 'course_end' => $course_end,
                 'course_remark' => $course_remark,
                 'course_createuser' => $course_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\CourseController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程添加失败',
                                     'message' => '课程添加失败，请重新输入信息']);
        }
        // 返回课程列表
        return redirect()->action('School\CourseController@index')
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
            return redirect()->action('School\CourseController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程显示失败',
                                     'message' => '课程显示失败，请联系系统管理员']);
        }
        $course = $course[0];
        return view('school/course/show', ['course' => $course]);
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
            return redirect()->action('School\CourseController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程显示失败',
                                     'message' => '课程显示失败，请联系系统管理员']);
        }
        $course = $course[0];
        // 获取校区、年级、科目信息(筛选)
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        return view('school/course/edit', ['course' => $course, 'departments' => $departments, 'grades' => $grades, 'subjects' => $subjects]);
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
     * @param  $request->input('input7'): 课时时长
     * @param  $request->input('input8'): 起始日期
     * @param  $request->input('input9'): 截止日期
     * @param  $request->input('input10'): 课程备注
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
        $course_time = $request->input('input7');
        $course_start = $request->input('input8');
        $course_end = $request->input('input9');
        $course_remark = $request->input('input10');
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
                        'course_time' => $course_time,
                        'course_start' => $course_start,
                        'course_end' => $course_end,
                        'course_remark' => $course_remark]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/course/{$course_id}/edit")->with(['notify' => true,
                                                                'type' => 'danger',
                                                                'title' => '课程修改失败',
                                                                'message' => '课程修改失败，请重新输入信息']);
        }
        return redirect("/course/{$course_id}")->with(['notify' => true,
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
            DB::table('course')->where('course_id', $course_id)->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\CourseController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程删除失败',
                                     'message' => '课程删除失败，请联系系统管理员']);
        }
        // 返回课程列表
        return redirect()->action('School\CourseController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '课程删除成功',
                                 'message' => '课程名称: '.$course_name]);
    }
}
