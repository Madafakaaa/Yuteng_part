<?php
namespace App\Http\Controllers\HumanResource;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CandidateController extends Controller
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
    public function candidate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/candidate", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 搜索条件
        $filters = array("filter_date" => null);
        // 获取数据
        $db_candidates = DB::table('candidate')
                           ->join('user', 'candidate.candidate_interviewer', '=', 'user.user_id')
                           ->join('position', 'user.user_position', '=', 'position.position_id')
                           ->join('archive', 'candidate.candidate_resume', '=', 'archive.archive_id');

        // 数据范围权限
        if (Session::get('user_access_self')==1) {
            $db_candidates = $db_candidates->where('candidate_createuser', '=', Session::get('user_id'));
        }
        // 上课日期
        if ($request->filled('filter_date')) {
            $db_candidates = $db_candidates->where('candidate_createtime', 'like', $request->input('filter_date')."%");
            $filters['filter_date']=$request->input("filter_date");
        }
        $db_candidates = $db_candidates->where('candidate_status', 1)->get();
        // 转为数组
        $candidates = array();
        foreach($db_candidates as $db_candidate){
            $temp = array();
            $temp['candidate_id'] = $db_candidate->candidate_id;
            $temp['candidate_name'] = $db_candidate->candidate_name;
            $temp['candidate_gender'] = $db_candidate->candidate_gender;
            $temp['candidate_position'] = $db_candidate->candidate_position;
            $temp['candidate_phone'] = $db_candidate->candidate_phone;
            $temp['candidate_wechat'] = $db_candidate->candidate_wechat;
            $temp['user_id'] = $db_candidate->user_id;
            $temp['user_name'] = $db_candidate->user_name;
            $temp['position_name'] = $db_candidate->position_name;
            $temp['archive_path'] = $db_candidate->archive_path;
            $temp['candidate_create_time'] = date('Y-m-d', strtotime($db_candidate->candidate_createtime));
            $temp_create_user = DB::table('user')
                                  ->join('position', 'user.user_position', '=', 'position.position_id')
                                  ->where('user_id', $db_candidate->candidate_createuser)
                                  ->first();
            $temp['create_user_id'] = $temp_create_user->user_id;
            $temp['create_user_name'] = $temp_create_user->user_name;
            $temp['create_user_position_name'] = $temp_create_user->position_name;
            $candidates[] = $temp;
        }
        // 返回列表视图
        return view('humanResource/candidate/candidate', ['filters' => $filters, 'candidates' => $candidates]);
    }

    /**
     * 创建新用户页面
     * URL: GET /humanResource/user/create
     */
    public function candidateCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/candidate/create", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_status', 1)
                   ->orderBy('user_department', 'asc')
                   ->orderBy('position_level', 'asc')
                   ->get();
        return view('humanResource/candidate/candidateCreate', ['users' => $users]);
    }

    /**
     * 创建新用户提交数据库
     * URL: POST /humanResource/user/create
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
    public function candidateStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 随机生成新用户ID
        $candidate_id=chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).substr(date('Ym'),2);

        // 获取上传文件
        $file = $request->file('file');
        // 获取文件大小(MB)
        $document_file_size = $file->getClientSize()/1024/1024+0.01;
        // 判断文件是否大于10MB
        if($document_file_size>10){
            return redirect("/humanResource/candidate/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '简历文件上传失败',
                           'message' => '文件大于10MB，错误码:401']);
        }

        // 获取文件名称
        $archive_file_name = $file->getClientOriginalName();
        // 获取文件扩展名
        $archive_ext = $file->getClientOriginalExtension();
        // 生成随机文件名
        $archive_path = "A".date('ymdHis').rand(1000000000,9999999999).".".$archive_ext;

        // 获取表单输入
        $candidate_name = $request->input('input_candidate_name');
        $candidate_gender = $request->input('input_candidate_gender');
        $candidate_phone = $request->input('input_candidate_phone');
        $candidate_wechat = $request->input('input_candidate_wechat');
        $candidate_position = $request->input('input_candidate_position');
        $candidate_interviewer = $request->input('input_candidate_interviewer');
        $candidate_comment = $request->input('input_candidate_comment');

        DB::beginTransaction();
        // 插入数据库
        try{
            $archive_id = DB::table('archive')->insertGetId(['archive_user' => $candidate_id,
                                                             'archive_name' => $candidate_name."-简历",
                                                             'archive_file_name' => $archive_file_name,
                                                             'archive_path' => $archive_path,
                                                             'archive_createuser' => Session::get('user_id')]);
            DB::table('candidate')->insert(
                ['candidate_id' => $candidate_id,
                 'candidate_name' => $candidate_name,
                 'candidate_gender' => $candidate_gender,
                 'candidate_position' => $candidate_position,
                 'candidate_phone' => $candidate_phone,
                 'candidate_wechat' => $candidate_wechat,
                 'candidate_interviewer' => $candidate_interviewer,
                 'candidate_resume' => $archive_id,
                 'candidate_comment' => $candidate_comment,
                 'candidate_createuser' => Session::get('user_id')]
            );
            DB::table('user_record')->insert(
                ['user_record_user' => $candidate_id,
                 'user_record_type' => "创建面试用户",
                 'user_record_content' => "创建面试用户，姓名：".$candidate_name."，面试岗位：".$candidate_position."。",
                 'user_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/humanResource/candidate/create")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '面试用户添加失败',
                             'message' => '面试用户添加失败，错误码:113']);
        }
        DB::commit();
        // 上传文件
        $file->move("files/archive", $archive_path);
        // 返回用户列表
        return redirect("/humanResource/candidate")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '面试用户添加成功',
                       'message' => '面试用户添加成功']);
    }

    /**
     * 删除用户
     * URL: DELETE /humanResource/user/{id}
     * @param  int  $user_id
     */
    public function candidateDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/candidate/delete", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取user_id
        $request_ids=$request->input('id');
        $candidate_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $candidate_ids[]=decode($request_id, 'candidate_id');
            }
        }else{
            $candidate_ids[]=decode($request_ids, 'candidate_id');
        }
        DB::beginTransaction();
        // 删除数据
        try{
            foreach ($candidate_ids as $candidate_id){
                // 删除用户记录
                DB::table('candidate')
                  ->where('candidate_id', $candidate_id)
                  ->delete();
                // 删除用户动态
                DB::table('user_record')
                  ->where('user_record_iser', $candidate_id)
                  ->delete();
                // 删除档案文件
                $archives = DB::table('archive')
                               ->where('archive_user', $candidate_id)
                               ->get();
                foreach($archives as $archive){
                    // 如果文件存在，删除文件
                    if (file_exists("files/archive/".$archive->archive_path)) {
                        unlink("files/archive/".$archive->archive_path);
                    }
                }
                // 删除档案记录
                DB::table('archive')
                  ->where('archive_user', $candidate_id)
                  ->delete();
            }
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/humanResource/candidate")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '面试用户删除失败',
                         'message' => '面试用户删除失败，错误码:116']);
        }
        DB::commit();
        // 返回用户列表
        return redirect("/humanResource/candidate")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '面试用户删除成功',
                         'message' => '面试用户删除成功']);
    }

    /**
     * 创建新用户页面
     * URL: GET /humanResource/user/create
     */
    public function candidateUpgrade(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/candidate/upgrade", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取用户信息
        $candidate_id=decode($request->input('id'), 'candidate_id');
        $candidate = DB::table('candidate')
                        ->where('candidate_id', $candidate_id)
                        ->first();
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $positions = DB::table('position')
                       ->join('section', 'position.position_section', '=', 'section.section_id')
                       ->where('position_status', 1)
                       ->where('section_status', 1)
                       ->orderBy('position_id', 'asc')
                       ->get();
        return view('humanResource/candidate/candidateUpgrade', ['candidate' => $candidate, 'departments' => $departments, 'positions' => $positions]);
    }

    public function candidateUpgradeStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单信息
        $user_id= $request->input('input_user_id');
        $user_name = $request->input('input_user_name');
        $user_gender = $request->input('input_user_gender');
        $user_department = $request->input('input_user_department');
        $user_position = $request->input('input_user_position');
        $user_entry_date = $request->input('input_user_entry_date');
        $user_cross_teaching = $request->input('input_user_cross_teaching');
        // 判断是否为空，为空设为""
        if($request->filled('input_user_phone')) {
            $user_phone = $request->input('input_user_phone');
        }else{
            $user_phone = "无";
        }
        if($request->filled('input_user_wechat')) {
            $user_wechat = $request->input('input_user_wechat');
        }else{
            $user_wechat = "无";
        }
        if($user_gender=='男'){
            $user_photo="male.png";
        }else{
            $user_photo="female.png";
        }
        // 获取校区岗位名称
        $position_name = DB::table('position')
                            ->where('position_id', $user_position)
                            ->first()
                            ->position_name;
        $department_name = DB::table('department')
                            ->where('department_id', $user_department)
                            ->first()
                            ->department_name;
        // 获取当前用户ID
        $user_createuser = Session::get('user_id');
        // 插入数据库
        DB::beginTransaction();
        try{
            // 添加新用户
            DB::table('user')->insert(
                ['user_id' => $user_id,
                 'user_name' => $user_name,
                 'user_gender' => $user_gender,
                 'user_photo' => $user_photo,
                 'user_department' => $user_department,
                 'user_position' => $user_position,
                 'user_entry_date' => $user_entry_date,
                 'user_cross_teaching' => $user_cross_teaching,
                 'user_phone' => $user_phone,
                 'user_wechat' => $user_wechat,
                 'user_createuser' => $user_createuser]
            );
            // 更新面试用户信息
            DB::table('candidate')
              ->where('candidate_id', $user_id)
              ->update(['candidate_status' => 0]);
            // 添加用户动态
            DB::table('user_record')->insert(
                ['user_record_user' => $user_id,
                 'user_record_type' => "面试用户入职",
                 'user_record_content' => "面试用户入职，入职校区：".$department_name."，入职岗位：".$position_name."。",
                 'user_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return back()->with(['notify' => true,
                                 'type' => 'danger',
                                 'title' => '用户转正失败',
                                 'message' => '用户转正失败，错误码:113']);
        }
        DB::commit();
        // 返回用户列表
        return redirect("/humanResource/candidate")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '用户转正成功',
                       'message' => '用户转正成功']);
    }
}
