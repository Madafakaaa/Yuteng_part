<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class DocumentController extends Controller
{
    /**
     * 显示所有档案记录
     * URL: GET /document
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 年级
     * @param  $request->input('filter3'): 科目
     * @param  $request->input('filter4'): 名称
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取数据
        $rows = DB::table('document')
                  ->join('department', 'document.document_department', '=', 'department.department_id')
                  ->join('grade', 'document.document_grade', '=', 'grade.grade_id')
                  ->join('subject', 'document.document_subject', '=', 'subject.subject_id');

        // 添加筛选条件
        // 校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('document_department', $request->input('filter1'));
        }
        // 年级
        if ($request->filled('filter2')) {
            $rows = $rows->where('document_grade', $request->input('filter2'));
        }
        // 科目
        if ($request->filled('filter3')) {
            $rows = $rows->where('document_subject', $request->input('filter3'));
        }
        // 名称
        if ($request->filled('filter4')) {
            $rows = $rows->where('document_file_name', 'like', '%'.$request->input('filter4').'%');
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('document_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('document/index', ['rows' => $rows,
                                      'currentPage' => $currentPage,
                                      'totalPage' => $totalPage,
                                      'startIndex' => $offset,
                                      'request' => $request,
                                      'totalNum' => $totalNum,
                                      'filter_departments' => $filter_departments,
                                      'filter_grades' => $filter_grades,
                                      'filter_subjects' => $filter_subjects]);
    }

    /**
     * 下载教案文件
     * URL: GET /document/{id}
     * @param  int  $document_id
     */
    public function show($document_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $document = DB::table('document')->where('document_id', $document_id)->get();
        if($document->count()!==1){
            // 未获取到数据
            return redirect("/document")->with(['notify' => true,
                                                 'type' => 'danger',
                                                 'title' => '教案下载失败',
                                                 'message' => '教案下载失败，请联系系统管理员']);
        }
        $document = $document[0];
        // 获取文件名和路径
        $file_path = "files/document/".$document->document_path;
        $file_name = $document->document_file_name;
        // 下载文件
        if (file_exists($file_path)) {// 文件存在
            return response()->download($file_path, $file_name ,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
        }else{ // 文件不存在
            return redirect("/document")->with(['notify' => true,
                                                 'type' => 'danger',
                                                 'title' => '教案下载失败',
                                                 'message' => '教案下载失败，请联系系统管理员']);
        }
    }

}
