<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SectionController extends Controller
{
    /**
     * 显示所有部门记录
     * URL: GET /section
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 部门名称
     */
    public function index(Request $request){
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
        // 岗位名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('position_name', 'like', '%'.$request->input('filter1').'%');
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
        return view('section/index', ['rows' => $rows,
                                             'sections' => $sections,
                                             'currentPage' => $currentPage,
                                             'totalPage' => $totalPage,
                                             'startIndex' => $offset,
                                             'request' => $request,
                                             'totalNum' => $totalNum]);
    }

    /**
     * 创建新部门页面
     * URL: GET /section/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('section/create');
    }

    /**
     * 创建新部门提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 部门名称
     */
    public function store(Request $request){
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
            return redirect()->action('SectionController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '部门添加失败',
                                     'message' => '部门添加失败，请重新输入信息']);
        }
        // 返回部门列表
        return redirect()->action('SectionController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '部门添加成功',
                                 'message' => '部门名称: '.$section_name]);
    }

    /**
     * 修改单个部门
     * URL: GET /section/{id}/edit
     * @param  int  $section_id
     */
    public function edit($section_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $section = DB::table('section')->where('section_id', $section_id)->get();
        if($section->count()!==1){
            // 未获取到数据
            return redirect()->action('SectionController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '部门显示失败',
                                     'message' => '部门显示失败，请联系系统管理员']);
        }
        $section = $section[0];
        return view('section/edit', ['section' => $section]);
    }

    /**
     * 修改新部门提交数据库
     * URL: PUT /section/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 部门名称
     * @param  int  $section_id
     */
    public function update(Request $request, $section_id){
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
            return redirect("/section/{$section_id}/edit")->with(['notify' => true,
                                                                  'type' => 'danger',
                                                                  'title' => '部门修改失败',
                                                                  'message' => '部门修改失败，请重新输入信息']);
        }
        return redirect("/section")->with(['notify' => true,
                                           'type' => 'success',
                                           'title' => '部门修改成功',
                                           'message' => '部门修改成功，部门名称: '.$section_name]);
    }

    /**
     * 删除部门
     * URL: DELETE /section/{id}
     * @param  int  $section_id
     */
    public function destroy($section_id){
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
            return redirect()->action('SectionController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '部门删除失败',
                                     'message' => '部门删除失败，请联系系统管理员']);
        }
        // 返回部门列表
        return redirect()->action('SectionController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '部门删除成功',
                                 'message' => '部门名称: '.$section_name]);
    }
}
