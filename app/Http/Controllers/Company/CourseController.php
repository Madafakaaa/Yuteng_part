<?php
namespace App\Http\Controllers\Company;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CourseController extends Controller
{

    /**
     * 课程设置
     * URL: GET /company/course
     */
    public function course(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/company/course", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        $department_access[]=0;

        // 搜索条件
        $filters = array(
                        "filter_grade" => null,
                        "filter_subject" => null,
                    );
        // 获取数据
        $rows = DB::table('course')
                  ->leftJoin('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                  ->leftJoin('department', 'course.course_department', '=', 'department.department_id')
                  ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                  ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                  ->whereIn('course_department', $department_access)
                  ->where('course_status', 1);
        // 课程年级
        if($request->filled('filter_grade')){
            $rows = $rows->where('course_grade', '=', $request->input('filter_grade'));
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 课程科目
        if($request->filled('filter_subject')){
            $rows = $rows->where('course_subject', '=', $request->input('filter_subject'));
            $filters['filter_subject']=$request->input("filter_subject");
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->orderBy('course_type', 'asc')
                     ->orderBy('course_grade', 'asc')
                     ->orderBy('course_subject', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级、科目信息(筛选)
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        // 返回列表视图
        return view('/company/course/course', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filters' => $filters,
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
        // 检测用户权限
        if(!in_array("/company/course/create", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取年级、科目信息、课程类型
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_id', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        $course_types = DB::table('course_type')->where('course_type_status', 1)->get();
        return view('/company/course/courseCreate', ['departments' => $departments,
                                                     'grades' => $grades,
                                                     'subjects' => $subjects,
                                                     'course_types' => $course_types]);
    }

    /**
     * 创建新课程提交数据库
     * URL: POST /company/course/store
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
            return redirect("/company/course/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '课程添加失败',
                           'message' => '课程添加失败，错误码:104']);
        }
        // 返回课程列表
        return redirect("/company/course")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程添加成功',
                       'message' => '课程名称: '.$course_name]);
    }

    /**
     * 修改单个课程
     * URL: GET /company/course/edit
     */
    public function courseEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/company/course/edit", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取course_id
        $course_id = decode($request->input('id'), 'course_id');
        // 获取数据信息
        $course = DB::table('course')->where('course_id', $course_id)->first();
        // 获取校区、年级、科目信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_id', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        // 获取课程类型
        $course_types = DB::table('course_type')
                           ->where('course_type_status', 1)
                           ->get();
        return view('/company/course/courseEdit', ['course' => $course,
                                                    'departments' => $departments,
                                                    'grades' => $grades,
                                                    'subjects' => $subjects,
                                                    'course_types' => $course_types]);
    }

    /**
     * 修改新课程提交数据库
     * URL: POST /course/create/update
     */
    public function courseUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取course_id
        $course_id = decode($request->input('id'), 'course_id');
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
            return redirect("company/course/edit?id=".encode($course_id, 'course_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '课程修改失败',
                           'message' => '课程修改失败，错误码:105']);
        }
        return redirect("/company/course")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '课程修改成功',
                      'message' => '课程修改成功，课程名称: '.$course_name]);
    }

    /**
     * 删除课程
     * URL: DELETE /company/course/delete
     */
    public function courseDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/company/course/delete", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取course_id
        $request_ids=$request->input('id');
        $course_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $course_ids[]=decode($request_id, 'course_id');
            }
        }else{
            $course_ids[]=decode($request_ids, 'course_id');
        }
        // 删除数据
        try{
            foreach ($course_ids as $course_id){
                DB::table('course')
                  ->where('course_id', $course_id)
                  ->update(['course_status' => 0]);
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/company/course")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '课程删除失败',
                         'message' => '课程删除失败，错误码:106']);
        }
        // 返回课程列表
        return redirect("/company/course")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程删除成功',
                       'message' => '课程删除成功!']);
    }

}
