<?php
namespace App\Http\Controllers\School;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SchoolController extends Controller
{
    /**
     * 显示所有学校记录
     * URL: GET /school
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 学校名称
     * @param  $request->input('filter2'): 学校校区
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
        $totalRecord = DB::table('school')->where('school_status', 1);

        // 获取数据
        $rows = DB::table('school')
                  ->join('department', 'school.school_department', '=', 'department.department_id')
                  ->where('school_status', 1);

        // 添加筛选条件
        // 学校名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('school_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 所属校区
        if($request->filled('filter2')){
            $rows = $rows->where('school_department', '=', $request->input('filter2'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();

        // 返回列表视图
        return view('school/school/index', ['rows' => $rows,
                                            'currentPage' => $currentPage,
                                            'totalPage' => $totalPage,
                                            'startIndex' => $offset,
                                            'request' => $request,
                                            'totalNum' => $totalNum,
                                            'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新学校页面
     * URL: GET /school/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取校区信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        return view('school/school/create', ['departments' => $departments]);
    }

    /**
     * 创建新学校提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 类型
     * @param  $request->input('input4'): 地址
     */
    public function store(Request $request){
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
            return redirect()->action('School\SchoolController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '学校添加失败',
                                     'message' => '学校添加失败，请重新输入信息']);
        }
        // 返回学校列表
        return redirect()->action('School\SchoolController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '学校添加成功',
                                 'message' => '学校名称: '.$school_name]);
    }

    /**
     * 显示单个学校详细信息
     * URL: GET /school/{id}
     * @param  int  $school_id
     */
    public function show($school_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $school = DB::table('school')
                    ->join('department', 'school.school_department', '=', 'department.department_id')
                    ->where('school_id', $school_id)
                    ->get();
        // 检验数据是否存在
        if($school->count()!==1){
            // 未获取到数据
            return redirect()->action('School\SchoolController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '学校显示失败',
                                     'message' => '学校显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $school = $school[0];
        return view('school/school/show', ['school' => $school]);
    }

    /**
     * 修改单个学校
     * URL: GET /school/{id}/edit
     * @param  int  $school_id
     */
    public function edit($school_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $school = DB::table('school')
                    ->where('school_id', $school_id)
                    ->get();
        // 检验数据是否存在
        if($school->count()!==1){
            // 未获取到数据
            return redirect()->action('School\SchoolController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '学校显示失败',
                                     'message' => '学校显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $school = $school[0];
        // 获取校区信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        return view('school/school/edit', ['school' => $school, 'departments' => $departments]);
    }

    /**
     * 修改新学校提交数据库
     * URL: PUT /school/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 所属校区
     * @param  $request->input('input3'): 类型
     * @param  $request->input('input4'): 地址
     * @param  int  $school_id
     */
    public function update(Request $request, $school_id){
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
            return redirect("/school/{$school_id}/edit")->with(['notify' => true,
                                                                        'type' => 'danger',
                                                                        'title' => '学校修改失败',
                                                                        'message' => '学校修改失败，请重新输入信息']);
        }
        return redirect("/school")->with(['notify' => true,
                                          'type' => 'success',
                                          'title' => '学校修改成功',
                                          'message' => '学校修改成功，学校名称: '.$school_name]);
    }

    /**
     * 删除学校
     * URL: DELETE /school/{id}
     * @param  int  $school_id
     */
    public function destroy($school_id){
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
            return redirect()->action('School\SchoolController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '学校删除失败',
                                     'message' => '学校删除失败，请联系系统管理员']);
        }
        // 返回学校列表
        return redirect()->action('School\SchoolController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '学校删除成功',
                                 'message' => '学校名称: '.$school_name]);
    }
}
