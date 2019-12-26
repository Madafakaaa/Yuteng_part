<?php
namespace App\Http\Controllers\School;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class GradeController extends Controller
{
    /**
     * 显示所有年级记录
     * URL: GET /grade
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
        $rows = DB::table('grade')->where('grade_status', 1);

        // 添加筛选条件
        // 年级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('grade_name', 'like', '%'.$request->input('filter1').'%');
        }

        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($rows->count(), $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('grade_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 返回列表视图
        return view('school/grade/index', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request]);
    }

    /**
     * 创建新年级页面
     * URL: GET /grade/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('school/grade/create');
    }

    /**
     * 创建新年级提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 年级名称
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $grade_name = $request->input('input1');
        // 获取当前用户ID
        $grade_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('grade')->insert(
                ['grade_name' => $grade_name,
                 'grade_createuser' => $grade_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\GradeController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '年级添加失败',
                                     'message' => '年级添加失败，请重新输入信息']);
        }
        // 返回年级列表
        return redirect()->action('School\GradeController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '年级添加成功',
                                 'message' => '年级名称: '.$grade_name]);
    }

    /**
     * 显示单个年级详细信息
     * URL: GET /grade/{id}
     * @param  int  $grade_id
     */
    public function show($grade_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $grade = DB::table('grade')->where('grade_id', $grade_id)->get();
        if($grade->count()!==1){
            // 未获取到数据
            return redirect()->action('School\GradeController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '年级显示失败',
                                     'message' => '年级显示失败，请联系系统管理员']);
        }
        $grade = $grade[0];
        return view('school/grade/show', ['grade' => $grade]);
    }

    /**
     * 修改单个年级
     * URL: GET /grade/{id}/edit
     * @param  int  $grade_id
     */
    public function edit($grade_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $grade = DB::table('grade')->where('grade_id', $grade_id)->get();
        if($grade->count()!==1){
            // 未获取到数据
            return redirect()->action('School\GradeController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '年级显示失败',
                                     'message' => '年级显示失败，请联系系统管理员']);
        }
        $grade = $grade[0];
        return view('school/grade/edit', ['grade' => $grade]);
    }

    /**
     * 修改新年级提交数据库
     * URL: PUT /grade/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 年级名称
     * @param  int  $grade_id
     */
    public function update(Request $request, $grade_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $grade_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('grade')
                    ->where('grade_id', $grade_id)
                    ->update(['grade_name' => $grade_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/grade/{$grade_id}/edit")->with(['notify' => true,
                                                                   'type' => 'danger',
                                                                   'title' => '年级修改失败',
                                                                   'message' => '年级修改失败，请重新输入信息']);
        }
        return redirect("/grade")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '年级修改成功',
                                                           'message' => '年级修改成功，年级名称: '.$grade_name]);
    }

    /**
     * 删除年级
     * URL: DELETE /grade/{id}
     * @param  int  $grade_id
     */
    public function destroy($grade_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $grade_name = DB::table('grade')->where('grade_id', $grade_id)->value('grade_name');
        // 删除数据
        try{
            DB::table('grade')->where('grade_id', $grade_id)->update(['grade_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\GradeController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '年级删除失败',
                                     'message' => '年级删除失败，请联系系统管理员']);
        }
        // 返回年级列表
        return redirect()->action('School\GradeController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '年级删除成功',
                                 'message' => '年级名称: '.$grade_name]);
    }
}
