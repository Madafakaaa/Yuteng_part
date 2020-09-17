<?php
namespace App\Http\Controllers\HumanResource;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class UserDeletedController extends Controller
{

    /**
     * 显示所有用户记录
     * URL: GET /humanResource/user
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 用户姓名
     * @param  $request->input('filter2'): 用户校区
     * @param  $request->input('filter3'): 用户岗位
     * @param  $request->input('filter4'): 用户等级
     */
    public function userDeleted(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/user/deleted", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_status', 0);


        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                    );
        // 所属校区
        if($request->filled('filter_department')){
            $rows = $rows->where('department_id', '=', $request->input('filter_department'));
            $filters['filter_department']=$request->input("filter_department");
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('user_department', 'asc')
                     ->orderBy('position_level', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、岗位、等级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('humanResource/userDeleted/userDeleted', ['rows' => $rows,
                                                              'currentPage' => $currentPage,
                                                              'totalPage' => $totalPage,
                                                              'startIndex' => $offset,
                                                              'request' => $request,
                                                              'totalNum' => $totalNum,
                                                              'filters' => $filters,
                                                              'filter_departments' => $filter_departments]);
    }

    /**
     * 恢复用户
     * URL: DELETE /humanResource/user/{id}
     * @param  int  $user_id
     */
    public function userDeletedRestore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/user/deleted/restore", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
                  ->update(['user_status' => 1]);
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/humanResource/user/deleted")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '用户恢复失败',
                         'message' => '用户恢复失败，错误码:116']);
        }
        // 返回用户列表
        return redirect("/humanResource/user/deleted")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '用户恢复成功',
                         'message' => '用户恢复成功']);
    }


}
