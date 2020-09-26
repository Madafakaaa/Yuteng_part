<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CandidateController extends Controller
{
    /**
     * 显示单个用户详细信息
     * URL: GET /candidate/{id}
     * @param  int  $candidate_id
     */
    public function candidate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $candidate_id = decode($request->input('id'), 'candidate_id');
        // 获取用户数据信息
        $candidate = DB::table('candidate')
                        ->join('user', 'candidate.candidate_interviewer', '=', 'user.user_id')
                        ->join('archive', 'candidate.candidate_resume', '=', 'archive.archive_id')
                        ->where('candidate_id', $candidate_id)
                        ->first();
        // 获取所有面试用户动态
        $user_records = DB::table('user_record')
                          ->join('user', 'user_record.user_record_createuser', '=', 'user.user_id')
                          ->where('user_record_user', $candidate_id)
                          ->orderBy('user_record_id', 'desc')
                          ->get();

        return view('candidate/candidate', ['candidate' => $candidate,
                                            'user_records' => $user_records]);
    }

    public function record(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_record_user = $request->input('candidate_id');
        $user_record_type = "面试用户动态";
        $user_record_content = $request->input('user_record_content');
        // 更新数据库
        try{
            // 添加用户动态
            DB::table('user_record')->insert(
                ['user_record_user' => $user_record_user,
                 'user_record_type' => $user_record_type,
                 'user_record_content' => $user_record_content,
                 'user_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return back()->with(['notify' => true,
                                'type' => 'danger',
                                'title' => '动态添加失败',
                                'message' => '动态添加失败，请重新输入信息']);
        }
        return redirect("/candidate?id=".encode($request->input('candidate_id'), 'candidate_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '动态添加成功',
                       'message' => '动态添加成功']);
    }

    /**
     * 修改新用户提交数据库
     * URL: PUT /candidate/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 用户姓名
     * @param  $request->input('input2'): 用户性别
     * @param  int  $candidate_id: 用户id
     */
    public function update(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $candidate_id = decode($request->input('id'), 'candidate_id');
        // 获取表单输入
        $candidate_name = $request->input('input1');
        $candidate_gender = $request->input('input2');
        $candidate_department = $request->input('input3');
        $candidate_position = $request->input('input4');
        $candidate_entry_date = $request->input('input5');
        $candidate_cross_teaching = $request->input('input6');
        if($request->filled('input7')) {
            $candidate_phone = $request->input('input7');
        }else{
            $candidate_phone = "无";
        }
        if($request->filled('input8')) {
            $candidate_wechat = $request->input('input8');
        }else{
            $candidate_wechat = "无";
        }
        // 更新数据库
        try{
            DB::table('candidate')
              ->where('candidate_id', $candidate_id)
              ->update(['candidate_name' => $candidate_name,
                        'candidate_gender' => $candidate_gender,
                        'candidate_department' => $candidate_department,
                        'candidate_position' => $candidate_position,
                        'candidate_entry_date' => $candidate_entry_date,
                        'candidate_cross_teaching' => $candidate_cross_teaching,
                        'candidate_phone' => $candidate_phone,
                        'candidate_wechat' => $candidate_wechat]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/candidate?id=".$request->input('id'))->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '用户修改失败',
                                                            'message' => '用户修改失败，请重新输入信息']);
        }
        return redirect("/candidate?id=".$request->input('id'))->with(['notify' => true,
                                                   'type' => 'success',
                                                   'title' => '用户修改成功',
                                                   'message' => '用户修改成功']);
    }

}
