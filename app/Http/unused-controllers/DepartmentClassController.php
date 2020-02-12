<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DepartmentClassController extends Controller
{
    /**
     * 显示本校班级记录
     * URL: GET /departmentClass
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function department(Request $request){
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
        return view('departmentClass/index', ['rows' => $rows,
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
            return redirect("/departmentClass")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '班级删除失败',
                                                         'message' => '班级删除失败，请联系系统管理员']);
        }
        // 返回本校班级列表
        return redirect("/departmentClass")->with(['notify' => true,
                                                   'type' => 'success',
                                                   'title' => '班级删除成功',
                                                   'message' => '班级名称: '.$class_name]);
    }
}
