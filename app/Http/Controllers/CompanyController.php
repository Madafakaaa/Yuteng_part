<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CompanyController extends Controller
{
    /**
     * 显示所有校区记录
     * URL: GET /company/department
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区名称
     */
    public function department(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('department')
                  ->where('department_status', 1);

        // 添加筛选条件
        // 校区名称
        if($request->filled('filter1')) {
            $rows = $rows->where('department_name', 'like', '%'.$request->input('filter1').'%');
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 10);

        // 排序并获取数据对象
        $rows = $rows->orderBy('department_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 返回列表视图
        return view('company/department', ['rows' => $rows,
                                            'currentPage' => $currentPage,
                                            'totalPage' => $totalPage,
                                            'startIndex' => $offset,
                                            'request' => $request,
                                            'totalNum' => $totalNum]);
    }

    /**
     * 创建新校区页面
     * URL: GET /company/department/create
     */
    public function departmentCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('company/departmentCreate');
    }

    /**
     * 创建新校区提交数据库
     * URL: GET /company/department/store
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 地址
     * @param  $request->input('input3'): 电话1
     * @param  $request->input('input4'): 电话2
     */
    public function departmentStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $department_name = $request->input('input1');
        $department_location = $request->input('input2');
        $department_phone1 = $request->input('input3');
        if($request->filled('input4')) {
            $department_phone2 = $request->input('input4');
        }else{
            $department_phone2 = "";
        }
        // 获取当前用户ID
        $department_createuser = Session::get('user_id');
        // 插入数据库
        try{
           DB::table('department')->insert(
                ['department_name' => $department_name,
                 'department_location' => $department_location,
                 'department_phone1' => $department_phone1,
                 'department_phone2' => $department_phone2,
                 'department_createuser' => $department_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/department")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '校区添加失败',
                                                         'message' => '校区添加失败，请重新输入信息']);
        }
        // 返回校区列表
        return redirect("/company/department")->with(['notify' => true,
                                                     'type' => 'success',
                                                     'title' => '校区添加成功',
                                                     'message' => '校区名称: '.$department_name]);
    }

    /**
     * 修改单个校区
     * URL: GET /company/department/{department_id}
     * @param  int  $department_id
     */
    public function departmentEdit($department_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $department = DB::table('department')
                        ->where('department_id', $department_id)
                        ->get();
        // 检验数据是否存在
        if($department->count()!==1){
            // 未获取到数据
            return redirect()->action('DepartmentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '校区显示失败',
                                     'message' => '校区显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $department = $department[0];
        return view('/company/departmentEdit', ['department' => $department]);
    }

    /**
     * 修改新校区提交数据库
     * URL: PUT /company/department/{department_id}
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 地址
     * @param  $request->input('input3'): 电话1
     * @param  $request->input('input4'): 电话2
     * @param  int  $department_id
     */
    public function departmentUpdate(Request $request, $department_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $department_name = $request->input('input1');
        $department_location = $request->input('input2');
        $department_phone1 = $request->input('input3');
        if($request->filled('input4')) {
            $department_phone2 = $request->input('input4');
        }else{
            $department_phone2 = "";
        }
        // 更新数据库
        try{
            DB::table('department')
              ->where('department_id', $department_id)
              ->update(['department_name' => $department_name,
                        'department_location' => $department_location,
                        'department_phone1' => $department_phone1,
                        'department_phone2' => $department_phone2]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/department/edit/{$department_id}")->with(['notify' => true,
                                                                                'type' => 'danger',
                                                                                'title' => '校区修改失败',
                                                                                'message' => '校区修改失败，请重新输入信息']);
        }
        return redirect("/company/department")->with(['notify' => true,
                                                      'type' => 'success',
                                                      'title' => '校区修改成功',
                                                      'message' => '校区修改成功，校区名称: '.$department_name]);
    }

    /**
     * 删除校区
     * URL: DELETE /company/department/{id}
     * @param  int  $department_id
     */
    public function departmentDelete($department_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $department_name = DB::table('department')
                             ->where('department_id', $department_id)
                             ->value('department_name');
        // 删除数据
        try{
            DB::table('department')
              ->where('department_id', $department_id)
              ->update(['department_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/department")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '校区删除失败',
                                                         'message' => '校区删除失败，请联系系统管理员']);
        }
        // 返回校区列表
        return redirect("/company/department")->with(['notify' => true,
                                                         'type' => 'success',
                                                         'title' => '校区删除成功',
                                                         'message' => '校区名称: '.$department_name]);
    }

    /**
     * 显示所有课程记录
     * URL: GET /company/course
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 名称
     * @param  $request->input('filter2'): 校区
     * @param  $request->input('filter3'): 年级
     * @param  $request->input('filter4'): 科目
     */
    public function course(Request $request){
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
        return view('/company/course', ['rows' => $rows,
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
     * URL: GET /company/course/create
     */
    public function courseCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取年级、科目信息、课程类型
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $course_types = DB::table('course_type')->where('course_type_status', 1)->get();
        return view('company/courseCreate', ['departments' => $departments,
                                              'grades' => $grades,
                                              'subjects' => $subjects,
                                              'course_types' => $course_types]);
    }

    /**
     * 创建新课程提交数据库
     * URL: POST /company/course/create
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
    public function courseStore(Request $request){
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
            return redirect("/company/course/create")->with(['notify' => true,
                                                             'type' => 'danger',
                                                             'title' => '课程添加失败',
                                                             'message' => '课程添加失败，请重新输入信息']);
        }
        // 返回课程列表
        return redirect("/company/course")->with(['notify' => true,
                                                 'type' => 'success',
                                                 'title' => '课程添加成功',
                                                 'message' => '课程名称: '.$course_name]);
    }

    /**
     * 修改单个课程
     * URL: GET /company/course/{course_id}
     * @param  int  $course_id
     */
    public function courseEdit($course_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $course = DB::table('course')->where('course_id', $course_id)->first();
        // 获取校区、年级、科目信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        // 获取课程类型
        $course_types = DB::table('course_type')
                           ->where('course_type_status', 1)
                           ->get();
        return view('company/courseEdit', ['course' => $course,
                                            'departments' => $departments,
                                            'grades' => $grades,
                                            'subjects' => $subjects,
                                            'course_types' => $course_types]);
    }

    /**
     * 修改新课程提交数据库
     * URL: PUT /course/create/{id}
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
    public function courseUpdate(Request $request, $course_id){
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
            return redirect("company/course/{$course_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '课程修改失败',
                           'message' => '课程修改失败，课程名称: '.$course_name]);
        }
        return redirect("/company/course")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '课程修改成功',
                      'message' => '课程修改成功，课程名称: '.$course_name]);
    }

    /**
     * 删除课程
     * URL: DELETE /company/course/{id}
     * @param  int  $course_id
     */
    public function courseDelete($course_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $course_name = DB::table('course')->where('course_id', $course_id)->value('course_name');
        // 删除数据
        try{
            DB::table('course')->where('course_id', $course_id)->update(['course_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/course")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '课程删除失败',
                         'message' => '课程删除失败，请联系系统管理员']);
        }
        // 返回课程列表
        return redirect("/company/course")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程删除成功',
                       'message' => '课程名称: '.$course_name]);
    }

    /**
     * 显示所有学校记录
     * URL: GET /company/school
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 学校名称
     * @param  $request->input('filter2'): 学校校区
     */
    public function school(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('school')
                  ->join('department', 'school.school_department', '=', 'department.department_id')
                  ->whereIn('school_department', $department_access)
                  ->where('school_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学校名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('school_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 所属校区
        if($request->filled('filter2')){
            $rows = $rows->where('school_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('school_department', 'asc')
                     ->orderBy('school_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('/company/school', ['rows' => $rows,
                                        'currentPage' => $currentPage,
                                        'totalPage' => $totalPage,
                                        'startIndex' => $offset,
                                        'request' => $request,
                                        'totalNum' => $totalNum,
                                        'filter_status' => $filter_status,
                                        'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新学校页面
     * URL: GET /company/school/create
     */
    public function schoolCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        return view('company/schoolCreate', ['departments' => $departments]);
    }

    /**
     * 创建新学校提交数据库
     * URL: POST /company/school/create
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 类型
     * @param  $request->input('input4'): 地址
     */
    public function schoolStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $school_name = $request->input('input1');
        $school_department = $request->input('input2');
        $school_type = $request->input('input3');
        $school_location = $request->input('input4');
        // 获取当前用户ID
        $school_createuser = Session::get('user_id');
        // 插入数据库
        try{
           DB::table('school')->insert(
                ['school_name' => $school_name,
                 'school_department' => $school_department,
                 'school_location' => $school_location,
                 'school_type' => $school_type,
                 'school_createuser' => $school_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/school/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '大区添加失败',
                           'message' => '大区添加失败，请重新输入信息']);
        }
        // 返回学校列表
        return redirect("/company/school")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '大区添加成功',
                       'message' => '大区名称: '.$school_name]);
    }

    /**
     * 修改单个学校
     * URL: GET /company/school/{id}
     * @param  int  $school_id
     */
    public function schoolEdit($school_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $school = DB::table('school')
                    ->where('school_id', $school_id)
                    ->first();
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        return view('company/schoolEdit', ['school' => $school, 'departments' => $departments]);
    }

    /**
     * 修改新学校提交数据库
     * URL: PUT /company/school/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 类型
     * @param  $request->input('input4'): 地址
     * @param  int  $school_id
     */
    public function schoolUpdate(Request $request, $school_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $school_name = $request->input('input1');
        $school_department = $request->input('input2');
        $school_type = $request->input('input3');
        $school_location = $request->input('input4');
        // 更新数据库
        try{
            DB::table('school')
              ->where('school_id', $school_id)
              ->update(['school_name' => $school_name,
                        'school_department' => $school_department,
                        'school_location' => $school_location,
                        'school_type' => $school_type]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/school/{$school_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '大区修改失败',
                           'message' => '大区修改失败，请重新输入信息']);
        }
        return redirect("/company/school")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '大区修改成功',
                       'message' => '大区修改成功，学校名称: '.$school_name]);
    }

    /**
     * 删除学校
     * URL: DELETE /company/school/{id}
     * @param  int  $school_id
     */
    public function schoolDelete($school_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $school_name = DB::table('school')
                         ->where('school_id', $school_id)
                         ->value('school_name');
        // 删除数据
        try{
            DB::table('school')
              ->where('school_id', $school_id)
              ->update(['school_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/school")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '大区删除失败',
                           'message' => '大区删除失败，请联系系统管理员']);
        }
        // 返回学校列表
        return redirect("/company/school")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '大区删除成功',
                       'message' => '大区名称: '.$school_name]);
    }

    /**
     * 显示所有教室记录
     * URL: GET /company/classroom
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 教室名称
     * @param  $request->input('filter2'): 教室校区
     * @param  $request->input('filter2'): 教室类型
     */
    public function classroom(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('classroom')
                  ->join('department', 'classroom.classroom_department', '=', 'department.department_id')
                  ->whereIn('classroom_department', $department_access)
                  ->where('classroom_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 教室名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('classroom_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 所属校区
        if($request->filled('filter2')){
            $rows = $rows->where('classroom_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 教室类型
        if($request->filled('filter3')){
            $rows = $rows->where('classroom_type', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 10);

        // 排序并获取数据对象
        $rows = $rows->orderBy('classroom_department', 'asc')
                     ->orderBy('classroom_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('company/classroom', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新教室页面
     * URL: GET /company/classroom/create
     */
    public function classroomCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        return view('company/classroomCreate', ['departments' => $departments]);
    }

    /**
     * 创建新教室提交数据库
     * URL: POST /company/classroom/create
     * @param  Request  $request
     * @param  $request->input('input1'): 教室名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 容纳人数
     * @param  $request->input('input4'): 教师类型
     */
    public function classroomStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $classroom_name = $request->input('input1');
        $classroom_department = $request->input('input2');
        $classroom_student_num = $request->input('input3');
        $classroom_type = $request->input('input4');
        // 获取当前用户ID
        $classroom_createuser = Session::get('user_id');
        // 插入数据库
        try{
           DB::table('classroom')->insert(
                ['classroom_name' => $classroom_name,
                 'classroom_department' => $classroom_department,
                 'classroom_student_num' => $classroom_student_num,
                 'classroom_type' => $classroom_type,
                 'classroom_createuser' => $classroom_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/classroom/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '教室添加失败',
                           'message' => '教室添加失败，请重新输入信息']);
        }
        // 返回教室列表
        return redirect("/company/classroom")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '教室添加成功',
                         'message' => '教室名称: '.$classroom_name]);
    }

    /**
     * 修改单个教室
     * URL: GET /company/classroom/{id}
     * @param  int  $classroom_id
     */
    public function classroomEdit($classroom_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $classroom = DB::table('classroom')
                    ->where('classroom_id', $classroom_id)
                    ->first();
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        return view('company/classroomEdit', ['classroom' => $classroom, 'departments' => $departments]);
    }

    /**
     * 修改新教室提交数据库
     * URL: PUT /company/classroom/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 教室名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 容纳人数
     * @param  $request->input('input4'): 教师类型
     * @param  int  $classroom_id
     */
    public function classroomUpdate(Request $request, $classroom_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $classroom_name = $request->input('input1');
        $classroom_department = $request->input('input2');
        $classroom_student_num = $request->input('input3');
        $classroom_type = $request->input('input4');
        // 更新数据库
        try{
            DB::table('classroom')
              ->where('classroom_id', $classroom_id)
              ->update(['classroom_name' => $classroom_name,
                        'classroom_department' => $classroom_department,
                        'classroom_student_num' => $classroom_student_num,
                        'classroom_type' => $classroom_type]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/classroom/{$classroom_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '教室修改失败',
                           'message' => '教室修改失败，请重新输入信息']);
        }
        return redirect("/company/classroom")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '教室修改成功',
                       'message' => '教室修改成功，教室名称: '.$classroom_name]);
    }

    /**
     * 删除教室
     * URL: DELETE /company/classroom/{id}
     * @param  int  $classroom_id
     */
    public function classroomDelete($classroom_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $classroom_name = DB::table('classroom')
                             ->where('classroom_id', $classroom_id)
                             ->value('classroom_name');
        // 删除数据
        try{
            DB::table('classroom')
              ->where('classroom_id', $classroom_id)
              ->update(['classroom_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/classroom")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '教室删除失败',
                             'message' => '教室删除失败，请联系系统管理员']);
        }
        // 返回教室列表
        return redirect("/company/classroom")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '教室删除成功',
                         'message' => '教室名称: '.$classroom_name]);
    }

    /**
     * 显示所有用户记录
     * URL: GET /company/user
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 用户姓名
     * @param  $request->input('filter2'): 用户校区
     * @param  $request->input('filter3'): 用户岗位
     * @param  $request->input('filter4'): 用户等级
     */
    public function user(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->whereIn('user_department', $department_access)
                  ->where('user_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 用户姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('user_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 用户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('user_department', $request->input('filter2'));
            $filter_status = 1;
        }
        // 用户部门
        if ($request->filled('filter3')) {
            $rows = $rows->where('position_section', $request->input('filter3'));
            $filter_status = 1;
        }
        // 用户岗位
        if ($request->filled('filter4')) {
            $rows = $rows->where('user_position', $request->input('filter4'));
            $filter_status = 1;
        }
        // 用户等级
        if ($request->filled('filter5')) {
            $rows = $rows->where('position_level', $request->input('filter5'));
            $filter_status = 1;
        }
        // 跨校区教学
        if ($request->filled('filter6')) {
            $rows = $rows->where('user_cross_teaching', $request->input('filter6'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('user_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、岗位、等级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_sections = DB::table('section')->where('section_status', 1)->orderBy('section_createtime', 'asc')->get();
        $filter_positions = DB::table('position')->where('position_status', 1)->orderBy('position_createtime', 'asc')->get();

        // 返回列表视图
        return view('company/user', ['rows' => $rows,
                                      'currentPage' => $currentPage,
                                      'totalPage' => $totalPage,
                                      'startIndex' => $offset,
                                      'request' => $request,
                                      'totalNum' => $totalNum,
                                      'filter_status' => $filter_status,
                                      'filter_departments' => $filter_departments,
                                      'filter_sections' => $filter_sections,
                                      'filter_positions' => $filter_positions]);
    }

    /**
     * 创建新用户页面
     * URL: GET /company/user/create
     */
    public function userCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $positions = DB::table('position')
                       ->join('section', 'position.position_section', '=', 'section.section_id')
                       ->where('position_status', 1)
                       ->where('section_status', 1)
                       ->orderBy('position_createtime', 'asc')
                       ->get();
        return view('company/userCreate', ['departments' => $departments, 'positions' => $positions]);
    }

    /**
     * 创建新用户提交数据库
     * URL: POST /company/user/create
     * @param  Request  $request
     * @param  $request->input('input1'): 用户姓名
     * @param  $request->input('input2'): 用户性别
     * @param  $request->input('input3'): 用户校区
     * @param  $request->input('input4'): 用户岗位
     * @param  $request->input('input5'): 入职日期
     * @param  $request->input('input6'): 是否可以跨校区上课
     * @param  $request->input('input7'): 用户手机
     * @param  $request->input('input8'): 用户微信
     */
    public function userStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 随机生成新用户ID
        $user_id=chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).substr(date('Ym'),2);
        // 获取表单输入
        $user_name = $request->input('input1');
        $user_gender = $request->input('input2');
        $user_department = $request->input('input3');
        $user_position = $request->input('input4');
        $user_entry_date = $request->input('input5');
        $user_cross_teaching = $request->input('input6');
        // 判断是否为空，为空设为""
        if($request->filled('input7')) {
            $user_phone = $request->input('input7');
        }else{
            $user_phone = "无";
        }
        if($request->filled('input8')) {
            $user_wechat = $request->input('input8');
        }else{
            $user_wechat = "无";
        }
        // 获取当前用户ID
        $user_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('user')->insert(
                ['user_id' => $user_id,
                 'user_name' => $user_name,
                 'user_gender' => $user_gender,
                 'user_department' => $user_department,
                 'user_position' => $user_position,
                 'user_entry_date' => $user_entry_date,
                 'user_cross_teaching' => $user_cross_teaching,
                 'user_phone' => $user_phone,
                 'user_wechat' => $user_wechat,
                 'user_createuser' => $user_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/user/create")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '用户添加失败',
                             'message' => '用户添加失败，请重新输入信息']);
        }
        // 返回用户列表
        return redirect("/company/user")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '用户添加成功',
                       'message' => '用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }

    /**
     * 用户权限视图
     * URL: GET /company/user/access/{user_id}
     * @param  int  $user_id
     */
    public function userAccess($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取全部校区
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', $user_id)
                  ->first();
        // 获取全部校区
        $departments = DB::table('department')
                          ->where('department_status', 1)
                          ->orderBy('department_id', 'asc')
                          ->get();
        $department_array = array();
        foreach($departments AS $department){
            $department_array[$department->department_id] = array($department->department_id, $department->department_name, 0);
        }
        // 获取用户校区权限
        $user_departments = DB::table('user_department')
                              ->where('user_department_user', $user_id)
                              ->get();
        foreach($user_departments AS $user_department){
            $department_array[$user_department->user_department_department][2]=1;
        }
        // 获取全部页面种类及其页面
        $page_categories = $users = DB::table('page')->select('page_category')->distinct()->get();
        $categories = array();
        $pages = array();
        foreach($page_categories AS $page_category){
            $temp = array($page_category->page_category);
            $page_array = array();
            $temp_pages = DB::table('page')->where('page_category', $page_category->page_category)->get();
            foreach($temp_pages AS $temp_page){
                $page_array[$temp_page->page_id] = array($temp_page->page_id, $temp_page->page_name);
                $pages[$temp_page->page_id] = array($temp_page->page_id, $temp_page->page_name, 0);
            }
            $temp[] = $page_array;
            $categories[] = $temp;
        }
        // 获取用户页面权限
        $user_pages = DB::table('user_page')
                        ->where('user_page_user', $user_id)
                        ->get();
        foreach($user_pages AS $user_page){
            $pages[$user_page->user_page_page][2] = 1;
        }
        return view('company/userAccess', ['user' => $user,
                                            'department_array' => $department_array,
                                            'categories' => $categories,
                                            'pages' => $pages]);
    }

    /**
     * 修改用户权限提交
     * URL: POST /user/access/{user_id}
     * @param  Request  $request
     * @param  $request->input('departments'): 校区权限
     * @param  $request->input('pages'): 页面权限
     * @param  int  $user_id: 用户id
     */
    public function userAccessUpdate(Request $request, $user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $departments = $request->input('departments');
        $pages = $request->input('pages');
        // 更新数据库
        DB::beginTransaction();
        try{
            // 删除原有权限
            DB::table('user_department')
              ->where('user_department_user', $user_id)
              ->delete();
            DB::table('user_page')
              ->where('user_page_user', $user_id)
              ->delete();
            if($departments!=NULL){
                // 添加校区权限
                foreach($departments as $department){
                    DB::table('user_department')->insert(
                        ['user_department_user' => $user_id,
                         'user_department_department' => $department]
                    );
                }
            }
            if($pages!=NULL){
                // 添加页面权限
                foreach($pages as $page){
                    DB::table('user_page')->insert(
                        ['user_page_user' => $user_id,
                         'user_page_page' => $page]
                    );
                }
            }
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/company/user/access/{$user_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '用户权限修改失败',
                           'message' => '用户权限修改失败！']);
        }
        DB::commit();
        return redirect("/company/user/access/{$user_id}")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '用户权限修改成功',
                       'message' => '用户权限修改成功,新权限将在重新登录后生效！']);
    }

    /**
     * 恢复用户默认密码
     * URL: GET /company/user/password/restore/{user_id}
     * @param  Request  $request
     * @param  $request->input('departments'): 校区权限
     * @param  $request->input('pages'): 页面权限
     * @param  int  $user_id: 用户id
     */
    public function userPasswordRestore($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 更新数据库
        DB::beginTransaction();
        try{
            // 更改密码为000000
            DB::table('user')->where('user_id', $user_id)->update(['user_password' => '000000']);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/company/user")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '恢复用户默认密码失败',
                           'message' => '恢复用户默认密码失败！']);
        }
        DB::commit();
        return redirect("/company/user")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '恢复用户默认密码成功',
                       'message' => '恢复用户默认密码成功！']);
    }

    /**
     * 删除用户
     * URL: DELETE /company/user/{id}
     * @param  int  $user_id
     */
    public function userDelete($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $user_name = DB::table('user')->where('user_id', $user_id)->value('user_name');
        // 删除数据
        try{
            DB::table('user')->where('user_id', $user_id)->update(['user_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/user")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '用户删除失败',
                             'message' => '用户删除失败，请联系系统管理员']);
        }
        // 返回用户列表
        return redirect("/company/user")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '用户删除成功',
                         'message' => '用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }

    /**
     * 显示所有部门记录
     * URL: GET /company/section
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('section'): 部门
     */
    public function section(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取岗位数据
        $rows = DB::table('position')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('position_status', 1);
        // 添加筛选条件
        // 部门ID
        if ($request->filled('section')) {
            $rows = $rows->where('position.position_section', '=', $request->input('section'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 10);

        // 排序并获取数据对象
        $rows = $rows->orderBy('position_section', 'asc')
                     ->orderBy('position_level', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取部门数据
        $sections = DB::table('section')
                   ->where('section_status', 1)
                   ->orderBy('section_id', 'asc')
                   ->get();

        // 返回列表视图
        return view('company/section', ['rows' => $rows,
                                      'sections' => $sections,
                                      'currentPage' => $currentPage,
                                      'totalPage' => $totalPage,
                                      'startIndex' => $offset,
                                      'request' => $request,
                                      'totalNum' => $totalNum]);
    }

    /**
     * 创建新部门页面
     * URL: GET /company/section/create
     */
    public function sectionCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('company/sectionCreate');
    }

    /**
     * 创建新部门提交数据库
     * URL: POST /company/section/create
     * @param  Request  $request
     * @param  $request->input('input1'): 部门名称
     */
    public function sectionStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $section_name = $request->input('input1');
        // 获取当前用户ID
        $section_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('section')->insert(
                ['section_name' => $section_name,
                 'section_createuser' => $section_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/section/create")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '部门添加失败',
                             'message' => '部门添加失败，请重新输入信息']);
        }
        // 返回部门列表
        return redirect("/company/section")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '部门添加成功',
                         'message' => '部门名称: '.$section_name]);
    }

    /**
     * 修改单个部门
     * URL: GET /company/section/{id}/
     * @param  int  $section_id
     */
    public function sectionEdit($section_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $section = DB::table('section')->where('section_id', $section_id)->first();
        return view('company/sectionEdit', ['section' => $section]);
    }

    /**
     * 修改新部门提交数据库
     * URL: PUT /company/section/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 部门名称
     * @param  int  $section_id
     */
    public function sectionUpdate(Request $request, $section_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $section_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('section')
              ->where('section_id', $section_id)
              ->update(['section_name' => $section_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/section/{$section_id}")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '部门修改失败',
                          'message' => '部门修改失败，请重新输入信息']);
        }
        return redirect("/company/section")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '部门修改成功',
                       'message' => '部门修改成功，部门名称: '.$section_name]);
    }

    /**
     * 删除部门
     * URL: DELETE /company/section/{id}
     * @param  int  $section_id
     */
    public function sectionDelete($section_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $section_name = DB::table('section')->where('section_id', $section_id)->value('section_name');
        // 删除数据
        try{
            DB::table('section')->where('section_id', $section_id)->update(['section_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/section")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '部门删除失败',
                             'message' => '部门删除失败，请联系系统管理员']);
        }
        // 返回部门列表
        return redirect("/company/section")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '部门删除成功',
                         'message' => '部门名称: '.$section_name]);
    }

    /**
     * 创建新岗位页面
     * URL: GET /company/position/create
     */
    public function positionCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取部门信息
        $sections = DB::table('section')->where('section_status', 1)->get();
        return view('company/positionCreate', ['sections' => $sections]);
    }

    /**
     * 创建新岗位提交数据库
     * URL: POST /company/position/create
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 部门
     * @param  $request->input('input3'): 等级
     */
    public function positionStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $position_name = $request->input('input1');
        $position_section = $request->input('input2');
        $position_level = $request->input('input3');
        // 获取当前用户ID
        $position_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('position')->insert(
                ['position_name' => $position_name,
                 'position_section' => $position_section,
                 'position_level' => $position_level,
                 'position_createuser' => $position_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/position/create")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '岗位添加失败',
                             'message' => '岗位添加失败，请重新输入信息']);
        }
        // 返回岗位列表
        return redirect("/company/section")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '岗位添加成功',
                       'message' => '岗位名称: '.$position_name]);
    }

    /**
     * 修改单个岗位
     * URL: GET /company/position/{id}/edit
     * @param  int  $position_id
     */
    public function positionEdit($position_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $position = DB::table('position')->where('position_id', $position_id)->first();
        // 获取部门信息
        $sections = DB::table('section')->where('section_status', 1)->get();
        return view('company/positionEdit', ['sections' => $sections, 'position' => $position]);
    }

    /**
     * 修改新岗位提交数据库
     * URL: PUT /company/position/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 部门
     * @param  $request->input('input3'): 等级
     * @param  int  $position_id
     */
    public function positionUpdate(Request $request, $position_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $position_name = $request->input('input1');
        $position_section = $request->input('input2');
        $position_level = $request->input('input3');
        // 更新数据库
        try{
            DB::table('position')
                    ->where('position_id', $position_id)
                    ->update(['position_name' => $position_name,
                              'position_section' => $position_section,
                              'position_level' => $position_level]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/position/{$position_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '岗位修改失败',
                           'message' => '岗位修改失败，请重新输入信息']);
        }
        return redirect("/company/section")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '岗位修改成功',
                       'message' => '岗位修改成功，岗位名称: '.$position_name]);
    }

    /**
     * 删除岗位
     * URL: DELETE /company/position/{id}
     * @param  int  $position_id
     */
    public function positionDelete($position_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $position_name = DB::table('position')->where('position_id', $position_id)->value('position_name');
        // 删除数据
        try{
            DB::table('position')->where('position_id', $position_id)->update(['position_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/section")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '岗位删除失败',
                             'message' => '岗位删除失败，请联系系统管理员']);
        }
        // 返回岗位列表
        return redirect("/company/section")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '岗位删除成功',
                         'message' => '岗位名称: '.$position_name]);
    }

}
