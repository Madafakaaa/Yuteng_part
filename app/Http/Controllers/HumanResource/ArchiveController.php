<?php
namespace App\Http\Controllers\HumanResource;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ArchiveController extends Controller
{

    /**
     * 显示用户档案
     * URL: GET /humanResource/archive
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function archive(Request $request){
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
                  ->where('user_status', 1);


        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                    );
        // 所属校区
        if($request->filled('filter_department')){
            $rows = $rows->where('department_id', '=', $request->input('filter_department'));
            $filters['filter_department']=$request->input("filter_department");
        }

        // 排序并获取数据对象
        $rows = $rows->orderBy('user_department', 'asc')
                     ->orderBy('position_level', 'asc')
                     ->get();

        // 获取校区、岗位、等级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('humanResource/archive/archive', ['rows' => $rows,
                                                      'filters' => $filters,
                                                      'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新用户页面
     * URL: GET /humanResource/user/create
     */
    public function archiveCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_status', 1)
                   ->orderBy('user_department', 'asc')
                   ->orderBy('position_level', 'asc')
                   ->get();
        return view('humanResource/archive/archiveCreate', ['users' => $users]);
    }

    public function archiveStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取上传文件
        $file = $request->file('file');
        // 获取文件大小(MB)
        $archive_file_size = $file->getClientSize()/1024/1024+0.01;
        // 判断文件是否大于10MB
        if($archive_file_size>10){
            return redirect("/humanResource/archive/create")
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
        $archive_user = $request->input('input_archive_user');
        $archive_name = $request->input('input_archive_name');

        DB::beginTransaction();
        // 插入数据库
        try{
            DB::table('archive')
              ->insert(['archive_user' => $archive_user,
                        'archive_name' => $archive_name,
                        'archive_file_name' => $archive_file_name,
                        'archive_path' => $archive_path,
                        'archive_createuser' => Session::get('user_id')]);
            // 添加用户动态
            DB::table('user_record')->insert(
                ['user_record_user' => $archive_user,
                 'user_record_type' => "上传用户档案",
                 'user_record_content' => "上传用户档案，档案名：".$archive_name."。",
                 'user_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/humanResource/archive/create")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '用户档案添加失败',
                             'message' => '用户档案添加失败，错误码:113']);
        }
        DB::commit();
        // 上传文件
        $file->move("files/archive", $archive_path);
        // 返回用户列表
        return redirect("/humanResource/archive")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '用户档案添加成功',
                       'message' => '用户档案添加成功']);
    }

    /**
     * 删除用户
     * URL: DELETE /humanResource/user/{id}
     * @param  int  $user_id
     */
    public function archiveDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取user_id
        $request_ids=$request->input('id');
        $archive_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $archive_ids[]=decode($request_id, 'archive_id');
            }
        }else{
            $archive_ids[]=decode($request_ids, 'archive_id');
        }
        DB::beginTransaction();
        // 删除数据
        try{
            foreach ($archive_ids as $archive_id){
                // 删除档案文件
                $archive = DB::table('archive')
                               ->where('archive_id', $archive_id)
                               ->first();
                // 如果文件存在，删除文件
                if (file_exists("files/archive/".$archive->archive_path)) {
                    unlink("files/archive/".$archive->archive_path);
                }
                // 删除档案记录
                DB::table('archive')
                  ->where('archive_id', $archive_id)
                  ->delete();
            }
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/humanResource/archive")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '用户档案删除失败',
                         'message' => '用户档案删除失败，错误码:116']);
        }
        DB::commit();
        // 返回用户列表
        return back()->with(['notify' => true,
                             'type' => 'success',
                             'title' => '用户档案删除成功',
                             'message' => '用户档案删除成功']);
    }

    public function archiveDownload(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取教案id
        $archive_id = decode($request->input('id'), 'archive_id');
        // 获取教案数据信息
        $archive = DB::table('archive')->where('archive_id', $archive_id)->first();
        // 获取文件名和路径
        $file_path = "files/archive/".$archive->archive_path;
        $file_ext = explode('.', $archive->archive_file_name);
        $file_ext = end($file_ext);
        $file_name = $archive->archive_name.".".$file_ext;
        // 下载文件
        if (file_exists($file_path)) {// 文件存在
            return response()->download($file_path, $file_name ,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
        }else{ // 文件不存在
            return redirect("/education/archive")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '档案下载失败',
                         'message' => '档案文件已删除，错误码:403']);
        }
    }

    /**
     * 显示单个用户详细信息
     * URL: GET /user/{id}
     * @param  int  $user_id
     */
    public function archiveLesson(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_id = decode($request->input('id'), 'user_id');
        // 获取用户数据信息
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', $user_id)
                  ->first();

        // 获取所有上课记录
        $attended_schedules = DB::table('schedule')
                                ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                                ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                                ->join('position', 'user.user_position', '=', 'position.position_id')
                                ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                                ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                                ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                                ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                                ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                ->where('schedule_attended', '=', 1)
                                ->where('schedule_teacher', $user_id)
                                ->orderBy('schedule_date', 'desc')
                                ->orderBy('schedule_start', 'asc')
                                ->get();
        return view('humanResource/archive/archiveLesson', ['user' => $user, 'attended_schedules' => $attended_schedules]);
    }

    /**
     * 显示单个用户详细信息
     * URL: GET /user/{id}
     * @param  int  $user_id
     */
    public function archiveContract(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_id = decode($request->input('id'), 'user_id');
        // 获取用户数据信息
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', $user_id)
                  ->first();


        // 获取签约合同
        $contracts = DB::table('contract')
                       ->join('department', 'contract.contract_department', '=', 'department.department_id')
                       ->join('student', 'contract.contract_student', '=', 'student.student_id')
                       ->where('contract_createuser', '=', $user_id)
                       ->orderBy('contract_date', 'desc')
                       ->get();
        return view('humanResource/archive/archiveContract', ['user' => $user, 'contracts' => $contracts]);
    }

    public function archiveRecord(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_id = decode($request->input('id'), 'user_id');
        // 获取用户数据信息
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', $user_id)
                  ->first();


        // 获取签约合同
        $user_records = DB::table('user_record')
                          ->join('user', 'user_record.user_record_createuser', '=', 'user.user_id')
                          ->where('user_record_user', $user_id)
                          ->orderBy('user_record_id', 'desc')
                          ->get();
        return view('humanResource/archive/archiveRecord', ['user' => $user, 'user_records' => $user_records]);
    }

    public function archiveArchive(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_id = decode($request->input('id'), 'user_id');
        // 获取用户数据信息
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', $user_id)
                  ->first();


        // 获取签约合同
        $archives = DB::table('archive')
                      ->where('archive_user', $user_id)
                      ->orderBy('archive_id', 'desc')
                      ->get();
        return view('humanResource/archive/archiveArchive', ['user' => $user, 'archives' => $archives]);
    }
}
