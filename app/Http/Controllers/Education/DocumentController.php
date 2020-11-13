<?php
namespace App\Http\Controllers\Education;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DocumentController extends Controller
{

    public function document(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取科目信息
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        // 返回列表视图
        return view('education/document/document', ['subjects' => $subjects]);
    }

    public function documentSubject(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 教案科目
        if ($request->filled('subject_id')) {
            $subject_id = $request->input("subject_id");
        }else{
            return redirect("/education/document");
        }
        $subject = DB::table('subject')->where('subject_id', $subject_id)->first();
        // 获取年级信息
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('education/document/documentSubject', ['subject' => $subject, 'grades' => $grades]);
    }

    public function documentSubjectGrade(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 教案科目
        if ($request->filled('subject_id')) {
            $subject_id = $request->input("subject_id");
        }else{
            return redirect("/education/document");
        }
        // 教案年级
        if ($request->filled('grade_id')) {
            $grade_id = $request->input("grade_id");
        }else{
            return redirect("/education/document");
        }
        $subject = DB::table('subject')->where('subject_id', $subject_id)->first();
        $grade = DB::table('grade')->where('grade_id', $grade_id)->first();
        // 学期
        $semesters = array("第一学期", "第二学期", "寒假班", "暑假班", "资料库");
        // 返回列表视图
        return view('education/document/documentSubjectGrade', ['subject' => $subject, 'grade' => $grade, 'semesters' => $semesters]);
    }


    public function documentSubjectGradeSemester(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 教案科目
        if ($request->filled('subject_id')) {
            $subject_id = $request->input("subject_id");
        }else{
            return redirect("/education/document");
        }
        // 教案年级
        if ($request->filled('grade_id')) {
            $grade_id = $request->input("grade_id");
        }else{
            return redirect("/education/document");
        }
        // 教案学期
        if ($request->filled('semester')) {
            $semester = $request->input("semester");
        }else{
            return redirect("/education/document");
        }
        $subject = DB::table('subject')->where('subject_id', $subject_id)->first();
        $grade = DB::table('grade')->where('grade_id', $grade_id)->first();
        // 获取documents
        $documents = DB::table('document')
                       ->join('subject', 'document.document_subject', '=', 'subject.subject_id')
                       ->join('grade', 'document.document_grade', '=', 'grade.grade_id')
                       ->where('document_subject', '=', $subject_id)
                       ->where('document_grade', '=', $grade_id)
                       ->where('document_semester', '=', $semester)
                       ->get();
        // 返回列表视图
        return view('education/document/documentSubjectGradeSemester', ['subject' => $subject, 'grade' => $grade, 'semester' => $semester, 'documents' => $documents]);
    }


    /**
     * 教案上传视图
     * URL: GET /education/document/create
     */
    public function documentCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/education/document/create", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        $subject_id = 0;
        $grade_id = 0;
        $semester = "";
        // 教案科目
        if ($request->filled('subject_id')) {
            $subject_id = $request->input("subject_id");
        }
        // 教案年级
        if ($request->filled('grade_id')) {
            $grade_id = $request->input("grade_id");
        }
        // 教案学期
        if ($request->filled('semester')) {
            $semester = $request->input("semester");
        }
        // 获取年级、科目信息
        $grades = DB::table('grade')->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->orderBy('subject_id', 'asc')->get();
        return view('education/document/documentCreate', ['subject_id' => $subject_id,
                                                          'grade_id' => $grade_id,
                                                          'semester' => $semester,
                                                          'grades' => $grades,
                                                          'subjects' => $subjects]);
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
        $document_name = $file->getClientOriginalName();
        $document_subject = $request->input('document_subject');
        $document_grade = $request->input('document_grade');
        $document_semester = $request->input('document_semester');
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
