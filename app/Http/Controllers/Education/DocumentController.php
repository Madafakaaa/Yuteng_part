<?php
namespace App\Http\Controllers\Education;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DocumentController extends Controller
{

    /**
     * 教案中心视图
     * URL: GET /education/document
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 用户姓名
     * @param  $request->input('filter2'): 用户校区
     * @param  $request->input('filter3'): 档案名称
     */
    public function document(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据document
        $rows = DB::table('document')
                  ->join('department', 'document.document_department', '=', 'department.department_id')
                  ->join('subject', 'document.document_subject', '=', 'subject.subject_id')
                  ->join('grade', 'document.document_grade', '=', 'grade.grade_id')
                  ->join('user', 'document.document_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id');

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_name" => null,
                        "filter_subject" => null,
                        "filter_semester" => null,
                    );

        // 教案校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('document_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 教案年级
        if ($request->filled('filter_grade')) {
            $rows = $rows->where('document_grade', '=', $request->input('filter_grade'));
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 教案科目
        if ($request->filled('filter_subject')) {
            $rows = $rows->where('document_subject', '=', $request->input('filter_subject'));
            $filters['filter_subject']=$request->input("filter_subject");
        }
        // 教案学期
        if ($request->filled('filter_semester')) {
            $rows = $rows->where('document_semester', '=', $request->input('filter_semester'));
            $filters['filter_semester']=$request->input("filter_semester");
        }
        // 判断是否有搜索条件
        $filter_status = 0;
        // 教案名称
        if ($request->filled('filter_name')) {
            $rows = $rows->where('document_name', 'like', '%'.$request->input('filter_name').'%');
            $filters['filter_name']=$request->input("filter_name");
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('document_grade', 'asc')
                     ->orderBy('document_subject', 'asc')
                     ->orderBy('document_department', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('education/document/document', ['rows' => $rows,
                                                    'currentPage' => $currentPage,
                                                    'totalPage' => $totalPage,
                                                    'startIndex' => $offset,
                                                    'request' => $request,
                                                    'filters' => $filters,
                                                    'totalNum' => $totalNum,
                                                    'filter_status' => $filter_status,
                                                    'filter_departments' => $filter_departments,
                                                    'filter_subjects' => $filter_subjects,
                                                    'filter_grades' => $filter_grades]);
    }

    /**
     * 教案上传视图
     * URL: GET /education/document/create
     */
    public function documentCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document/create", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取年级、科目信息
        $grades = DB::table('grade')->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->orderBy('subject_id', 'asc')->get();
        return view('education/document/documentCreate', ['grades' => $grades, 'subjects' => $subjects]);
    }

    /**
     * 教案上传提交
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 档案名称
     * @param  $request->input('input2'): 科目
     * @param  $request->input('input3'): 年级
     * @param  $request->input('input4'): 学期
     * @param  $request->file('file');: 档案文件
     */
    public function documentStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取上传文件
        $file = $request->file('file');
        // 获取文件大小(MB)
        $document_file_size = $file->getClientSize()/1024/1024+0.01;
        // 判断文件是否大于10MB
        if($document_file_size>10){
            return redirect("/education/document/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '教案上传失败',
                           'message' => '文件大于10MB，错误码:401']);
        }
        // 获取文件名称
        $document_file_name = $file->getClientOriginalName();
        // 获取文件扩展名
        $document_ext = $file->getClientOriginalExtension();
        // 生成随机文件名
        $document_path = "D".date('ymdHis').rand(1000000000,9999999999).".".$document_ext;
        // 获取表单输入
        $document_name = $request->input('input1');
        $document_subject = $request->input('input2');
        $document_grade = $request->input('input3');
        $document_semester = $request->input('input4');
        // 插入数据库
        try{
            DB::table('document')->insert(
                ['document_department' => Session::get('user_department'),
                 'document_name' => $document_name,
                 'document_subject' => $document_subject,
                 'document_grade' => $document_grade,
                 'document_semester' => $document_semester,
                 'document_file_name' => $document_file_name,
                 'document_file_size' => $document_file_size,
                 'document_path' => $document_path,
                 'document_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/education/document/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '教案上传失败',
                           'message' => '教案上传失败，错误码:402']);
        }
        // 上传文件
        $file->move("files/document", $document_path);
        // 返回用户列表
        return redirect("/education/document")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '教案上传成功',
                       'message' => '教案上传成功']);
    }

    /**
     * 下载档案文件
     * URL: GET /education/document/{document_id}
     * @param  int  $document_id
     */
    public function documentDownload(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document/download", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取教案id
        $document_id = decode($request->input('id'), 'document_id');
        // 获取教案数据信息
        $document = DB::table('document')->where('document_id', $document_id)->first();
        // 获取文件名和路径
        $file_path = "files/document/".$document->document_path;
        $file_ext = explode('.', $document->document_file_name);
        $file_ext = end($file_ext);
        $file_name = $document->document_name.".".$file_ext;
        // 下载文件
        if (file_exists($file_path)) {// 文件存在
            return response()->download($file_path, $file_name ,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
        }else{ // 文件不存在
            return redirect("/education/document")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '档案下载失败',
                         'message' => '档案文件已删除，错误码:403']);
        }
    }

    /**
     * 删除教案
     * URL: DELETE /document/{id}
     * @param  int  $document_id
     */
    public function documentDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document/delete", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取教案id
        $document_id = decode($request->input('id'), 'document_id');
        // 获取数据路径
        $document = DB::table('document')->where('document_id', $document_id)->first();
        $document_path = "files/document/".$document->document_path;
        // 删除数据
        DB::table('document')->where('document_id', $document_id)->delete();
        // 如果文件存在，删除文件
        if (file_exists($document_path)) {
            unlink($document_path);
        }
        // 返回教案视图
        return redirect("/education/document")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '教案删除成功',
                       'message' => '教案删除成功!']);
    }

}
