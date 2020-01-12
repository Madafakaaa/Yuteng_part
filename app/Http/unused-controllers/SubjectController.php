<?php
namespace App\Http\Controllers\School;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SubjectController extends Controller
{
    /**
     * 显示所有科目记录
     * URL: GET /subject
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
        $rows = DB::table('subject')->where('subject_status', 1);
        // 添加筛选条件
        // 科目名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('subject_name', 'like', '%'.$request->input('filter1').'%');
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('subject_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)->get();

        // 返回列表视图
        return view('school/subject/index', ['rows' => $rows,
                                             'currentPage' => $currentPage,
                                             'totalPage' => $totalPage,
                                             'startIndex' => $offset,
                                             'request' => $request,
                                             'totalNum' => $totalNum]);
    }

    /**
     * 创建新科目页面
     * URL: GET /subject/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('school/subject/create');
    }

    /**
     * 创建新科目提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 科目名称
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $subject_name = $request->input('input1');
        // 获取当前用户ID
        $subject_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('subject')->insert(
                ['subject_name' => $subject_name,
                 'subject_createuser' => $subject_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\SubjectController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '科目添加失败',
                                     'message' => '科目添加失败，请重新输入信息']);
        }
        // 返回科目列表
        return redirect()->action('School\SubjectController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '科目添加成功',
                                 'message' => '科目名称: '.$subject_name]);
    }

    /**
     * 显示单个科目详细信息
     * URL: GET /subject/{id}
     * @param  int  $subject_id
     */
    public function show($subject_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $subject = DB::table('subject')->where('subject_id', $subject_id)->get();
        if($subject->count()!==1){
            // 未获取到数据
            return redirect()->action('School\SubjectController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '科目显示失败',
                                     'message' => '科目显示失败，请联系系统管理员']);
        }
        $subject = $subject[0];
        return view('school/subject/show', ['subject' => $subject]);
    }

    /**
     * 修改单个科目
     * URL: GET /subject/{id}/edit
     * @param  int  $subject_id
     */
    public function edit($subject_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $subject = DB::table('subject')->where('subject_id', $subject_id)->get();
        if($subject->count()!==1){
            // 未获取到数据
            return redirect()->action('School\SubjectController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '科目显示失败',
                                     'message' => '科目显示失败，请联系系统管理员']);
        }
        $subject = $subject[0];
        return view('school/subject/edit', ['subject' => $subject]);
    }

    /**
     * 修改新科目提交数据库
     * URL: PUT /subject/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 科目名称
     * @param  int  $subject_id
     */
    public function update(Request $request, $subject_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $subject_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('subject')
                    ->where('subject_id', $subject_id)
                    ->update(['subject_name' => $subject_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/subject/{$subject_id}/edit")->with(['notify' => true,
                                                                   'type' => 'danger',
                                                                   'title' => '科目修改失败',
                                                                   'message' => '科目修改失败，请重新输入信息']);
        }
        return redirect("/subject")->with(['notify' => true,
                                           'type' => 'success',
                                           'title' => '科目修改成功',
                                           'message' => '科目修改成功，科目名称: '.$subject_name]);
    }

    /**
     * 删除科目
     * URL: DELETE /subject/{id}
     * @param  int  $subject_id
     */
    public function destroy($subject_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $subject_name = DB::table('subject')->where('subject_id', $subject_id)->value('subject_name');
        // 删除数据
        try{
            DB::table('subject')->where('subject_id', $subject_id)->update(['subject_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\SubjectController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '科目删除失败',
                                     'message' => '科目删除失败，请联系系统管理员']);
        }
        // 返回科目列表
        return redirect()->action('School\SubjectController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '科目删除成功',
                                 'message' => '科目名称: '.$subject_name]);
    }
}
