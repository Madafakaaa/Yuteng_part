<?php
namespace App\Http\Controllers\School;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DepartmentController extends Controller
{
    /**
     * 显示所有校区记录
     * URL: GET /department
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区名称
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
        $totalRecord = DB::table('department')->where('department_status', 1);
        // 添加筛选条件
        // 校区名称
        if ($request->has('filter1')) {
            if($request->input('filter1')!=''){
                $totalRecord = $totalRecord->where('department_name', 'like', '%'.$request->input('filter1').'%');
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
        $offset = ($currentPage-1)*$rowPerPage;
        // 获取数据
        $rows = DB::table('department')->where('department_status', 1);
        // 添加筛选条件
        // 校区名称
        if ($request->has('filter1')) {
            if($request->input('filter1')!=''){
                $rows = $rows->where('department_name', 'like', '%'.$request->input('filter1').'%');
            }
        }
        $rows = $rows->orderBy('department_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        return view('school/department/index', ['rows' => $rows,
                                                'currentPage' => $currentPage,
                                                'totalPage' => $totalPage,
                                                'startIndex' => ($currentPage-1)*$rowPerPage]);
    }

    /**
     * 创建新校区页面
     * URL: GET /department/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('school/department/create');
    }

    /**
     * 创建新校区提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 校区名称
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $department_name = $request->input('input1');
        // 获取当前用户ID
        $department_createuser = Session::get('user_id');
        // 插入数据库
        try{
           DB::table('department')->insert(
                ['department_name' => $department_name,
                 'department_createuser' => $department_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\DepartmentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '校区添加失败',
                                     'message' => '校区添加失败，请重新输入信息']);
        }
        // 返回校区列表
        return redirect()->action('School\DepartmentController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '校区添加成功',
                                 'message' => '校区名称: '.$department_name]);
    }

    /**
     * 显示单个校区详细信息
     * URL: GET /department/{id}
     * @param  int  $department_id
     */
    public function show($department_id){
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
            return redirect()->action('School\DepartmentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '校区显示失败',
                                     'message' => '校区显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $department = $department[0];
        return view('school/department/show', ['department' => $department]);
    }

    /**
     * 修改单个校区
     * URL: GET /department/{id}/edit
     * @param  int  $department_id
     */
    public function edit($department_id){
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
            return redirect()->action('School\DepartmentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '校区显示失败',
                                     'message' => '校区显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $department = $department[0];
        return view('school/department/edit', ['department' => $department]);
    }

    /**
     * 修改新校区提交数据库
     * URL: PUT /department/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 校区名称
     * @param  int  $department_id
     */
    public function update(Request $request, $department_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $department_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('department')
              ->where('department_id', $department_id)
              ->update(['department_name' => $department_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/department/{$department_id}/edit")->with(['notify' => true,
                                                                        'type' => 'danger',
                                                                        'title' => '校区修改失败',
                                                                        'message' => '校区修改失败，请重新输入信息']);
        }
        return redirect("/department/{$department_id}")->with(['notify' => true,
                                                               'type' => 'success',
                                                               'title' => '校区修改成功',
                                                               'message' => '校区修改成功，校区名称: '.$department_name]);
    }

    /**
     * 删除校区
     * URL: DELETE /department/{id}
     * @param  int  $department_id
     */
    public function destroy($department_id){
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
            return redirect()->action('School\DepartmentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '校区删除失败',
                                     'message' => '校区删除失败，请联系系统管理员']);
        }
        // 返回校区列表
        return redirect()->action('School\DepartmentController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '校区删除成功',
                                 'message' => '校区名称: '.$department_name]);
    }
}
