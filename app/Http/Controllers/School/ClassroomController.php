<?php
namespace App\Http\Controllers\School;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ClassroomController extends Controller
{
    /**
     * 显示所有教室记录
     * URL: GET /classroom
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 教室名称
     * @param  $request->input('filter2'): 教室校区
     * @param  $request->input('filter2'): 教室类型
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('classroom')
                  ->join('department', 'classroom.classroom_department', '=', 'department.department_id')
                  ->where('classroom_status', 1);

        // 添加筛选条件
        // 教室名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('classroom_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 所属校区
        if($request->filled('filter2')){
            $rows = $rows->where('classroom_department', '=', $request->input('filter2'));
        }
        // 教室类型
        if($request->filled('filter3')){
            $rows = $rows->where('classroom_type', '=', $request->input('filter3'));
        }

        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($rows->count(), $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('classroom_department', 'asc')
                     ->orderBy('classroom_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();

        // 返回列表视图
        return view('school/classroom/index', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新教室页面
     * URL: GET /classroom/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取校区信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        return view('school/classroom/create', ['departments' => $departments]);
    }

    /**
     * 创建新教室提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 教室名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 容纳人数
     * @param  $request->input('input4'): 教师类型
     */
    public function store(Request $request){
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
            return redirect()->action('School\ClassroomController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '教室添加失败',
                                     'message' => '教室添加失败，请重新输入信息']);
        }
        // 返回教室列表
        return redirect()->action('School\ClassroomController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '教室添加成功',
                                 'message' => '教室名称: '.$classroom_name]);
    }

    /**
     * 显示单个教室详细信息
     * URL: GET /classroom/{id}
     * @param  int  $classroom_id
     */
    public function show($classroom_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $classroom = DB::table('classroom')
                    ->join('department', 'classroom.classroom_department', '=', 'department.department_id')
                    ->where('classroom_id', $classroom_id)
                    ->get();
        // 检验数据是否存在
        if($classroom->count()!==1){
            // 未获取到数据
            return redirect()->action('School\ClassroomController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '教室显示失败',
                                     'message' => '教室显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $classroom = $classroom[0];
        return view('school/classroom/show', ['classroom' => $classroom]);
    }

    /**
     * 修改单个教室
     * URL: GET /classroom/{id}/edit
     * @param  int  $classroom_id
     */
    public function edit($classroom_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $classroom = DB::table('classroom')
                    ->where('classroom_id', $classroom_id)
                    ->get();
        // 检验数据是否存在
        if($classroom->count()!==1){
            // 未获取到数据
            return redirect()->action('School\ClassroomController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '教室显示失败',
                                     'message' => '教室显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $classroom = $classroom[0];
        return view('school/classroom/edit', ['classroom' => $classroom]);
    }

    /**
     * 修改新教室提交数据库
     * URL: PUT /classroom/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 教室名称
     * @param  int  $classroom_id
     */
    public function update(Request $request, $classroom_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $classroom_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('classroom')
              ->where('classroom_id', $classroom_id)
              ->update(['classroom_name' => $classroom_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/classroom/{$classroom_id}/edit")->with(['notify' => true,
                                                                        'type' => 'danger',
                                                                        'title' => '教室修改失败',
                                                                        'message' => '教室修改失败，请重新输入信息']);
        }
        return redirect("/classroom/{$classroom_id}")->with(['notify' => true,
                                                               'type' => 'success',
                                                               'title' => '教室修改成功',
                                                               'message' => '教室修改成功，教室名称: '.$classroom_name]);
    }

    /**
     * 删除教室
     * URL: DELETE /classroom/{id}
     * @param  int  $classroom_id
     */
    public function destroy($classroom_id){
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
            return redirect()->action('School\ClassroomController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '教室删除失败',
                                     'message' => '教室删除失败，请联系系统管理员']);
        }
        // 返回教室列表
        return redirect()->action('School\ClassroomController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '教室删除成功',
                                 'message' => '教室名称: '.$classroom_name]);
    }
}
