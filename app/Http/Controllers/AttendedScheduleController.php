<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class AttendedScheduleController extends Controller
{
    /**
     * 显示所有上课记录
     * URL: GET /schedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_attended', '=', 1);
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('attendedSchedule/index', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_departments' => $filter_departments,
                                               'filter_students' => $filter_students,
                                               'filter_classes' => $filter_classes,
                                               'filter_grades' => $filter_grades,
                                               'filter_users' => $filter_users]);
    }

    /**
     * 显示本校上课记录
     * URL: GET /departmentSchedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function department(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_attended', '=', 1)
                  ->where('schedule_department', '=', Session::get('user_department'));
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('attendedSchedule/department', ['rows' => $rows,
                                                   'currentPage' => $currentPage,
                                                   'totalPage' => $totalPage,
                                                   'startIndex' => $offset,
                                                   'request' => $request,
                                                   'totalNum' => $totalNum,
                                                   'filter_departments' => $filter_departments,
                                                   'filter_students' => $filter_students,
                                                   'filter_classes' => $filter_classes,
                                                   'filter_grades' => $filter_grades,
                                                   'filter_users' => $filter_users]);
    }

    /**
     * 显示我的上课记录
     * URL: GET /mySchedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function my(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_attended', '=', 1)
                  ->where('schedule_teacher', '=', Session::get('user_id'));
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('attendedSchedule/my', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_departments' => $filter_departments,
                                           'filter_students' => $filter_students,
                                           'filter_classes' => $filter_classes,
                                           'filter_grades' => $filter_grades,
                                           'filter_users' => $filter_users]);
    }

    /**
     * 删除上课记录
     * URL: DELETE /attendedSchedule/{id}
     * @param  int  $schedule_id
     */
    public function destroy($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 删除数据
        DB::beginTransaction();
        try{
            // 获取上课成员信息
            $participants = DB::table('participant')
                              ->where('participant_schedule', $schedule_id)
                              ->get();
            // 恢复学生课时
            foreach($participants as $participant){
                // 获取剩余课时信息
                $hour = DB::table('hour')
                          ->where('hour_id', $participant->participant_hour)
                          ->first();
                if($hour->hour_used_free>=$participant->participant_amount){ // 全部使用赠送课时
                    // 增加学生赠送课时
                    DB::table('hour')
                      ->where('hour_id', $participant->participant_hour)
                      ->increment('hour_remain_free', $participant->participant_amount);
                    // 扣除已用赠送课时数
                    DB::table('hour')
                      ->where('hour_id', $participant->participant_hour)
                      ->decrement('hour_used_free', $participant->participant_amount);
                }else if($hour->hour_used_free==0){ // 全部使用正常课时
                    // 增加学生正常课时
                    DB::table('hour')
                      ->where('hour_id', $participant->participant_hour)
                      ->increment('hour_remain', $participant->participant_amount);
                    // 扣除已用正常课时数
                    DB::table('hour')
                      ->where('hour_id', $participant->participant_hour)
                      ->decrement('hour_used', $participant->participant_amount);
                }else{ // 使用赠送课时和正常课时
                    // 增加赠送学生课时
                    DB::table('hour')
                      ->where('hour_id', $participant->participant_hour)
                      ->increment('hour_remain_free', $hour->hour_used_free);
                    // 扣除已用赠送课时数
                    DB::table('hour')
                      ->where('hour_id', $participant->participant_hour)
                      ->decrement('hour_used_free', $hour->hour_used_free);
                    // 计算赠送课时使用量
                    $participant_amount = $participant->participant_amount - $hour->hour_used_free;
                    // 增加学生正常课时
                    DB::table('hour')
                      ->where('hour_id', $participant->participant_hour)
                      ->increment('hour_remain', $participant_amount);
                    // 扣除已用赠送课时数
                    DB::table('hour')
                      ->where('hour_id', $participant->participant_hour)
                      ->decrement('hour_used', $participant_amount);
                }
            }
            // 删除上课成员信息
            DB::table('participant')
              ->where('participant_schedule', $schedule_id)
              ->delete();
            // 获取教案信息
            $document = DB::table('schedule')
                          ->join('document', 'schedule.schedule_document', '=', 'document.document_id')
                          ->where('schedule_id', $schedule_id)
                          ->first();
            // 删除教案文件
            $document_path = "files/document/".$document->document_path;
            // 如果文件存在，删除文件
            if (file_exists($document_path)) {
                unlink($document_path);
            }
            // 删除教案记录
            DB::table('document')->where('document_id', $document->document_id)->delete();
            // 删除上课记录
            DB::table('schedule')->where('schedule_id', $schedule_id)->delete();
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/departmentAttendedSchedule")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '上课记录删除失败',
                                                         'message' => '上课记录删除失败，请联系系统管理员']);
        }
        DB::commit();
        return redirect("/departmentAttendedSchedule")->with(['notify' => true,
                                                     'type' => 'success',
                                                     'title' => '上课记录删除成功',
                                                     'message' => '上课记录删除成功!']);
    }
}
