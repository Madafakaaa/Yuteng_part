<?php
namespace App\Http\Controllers\Company;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class UserController extends Controller
{

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
        $rows = $rows->orderBy('user_id', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、岗位、等级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_sections = DB::table('section')->where('section_status', 1)->orderBy('section_id', 'asc')->get();
        $filter_positions = DB::table('position')->where('position_status', 1)->orderBy('position_id', 'asc')->get();

        // 返回列表视图
        return view('company/user/user', ['rows' => $rows,
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
                       ->orderBy('position_id', 'asc')
                       ->get();
        return view('company/user/userCreate', ['departments' => $departments, 'positions' => $positions]);
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
    public function userAccess(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取user_id
        $user_id = decode($request->input('id'), 'user_id');
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
        return view('company/user/userAccess', ['user' => $user,
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
    public function userAccessUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取user_id
        $user_id = decode($request->input('id'), 'user_id');
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
            return redirect("/company/user/access?id={$request->input('id')}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '用户权限修改失败',
                           'message' => '用户权限修改失败！']);
        }
        DB::commit();
        return redirect("/company/user/access?id={$request->input('id')}")
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
    public function userPasswordRestore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取user_id
        $user_id = decode($request->input('id'), 'user_id');
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
    public function userDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取user_id
        $request_ids=$request->input('id');
        $user_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $user_ids[]=decode($request_id, 'user_id');
            }
        }else{
            $user_ids[]=decode($request_ids, 'user_id');
        }
        // 删除数据
        try{
            foreach ($user_ids as $user_id){
                DB::table('user')
                  ->where('user_id', $user_id)
                  ->update(['user_status' => 0]);
            }
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
                         'message' => '用户删除成功']);
    }


}
