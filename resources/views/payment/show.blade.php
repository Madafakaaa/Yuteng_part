@extends('print')

@section('content')
<div class="row justify-content-center">
  <div class="col-12">
    <div class="card m-4">
      <div class="card-header m-1 p-2">
        <div class="row m-0 p-0">
          <div class="col-3"><h2 class="m-0">育藤教育</h2></div>
          <div class="col-6 text-center"><span style="font-family: 华文中宋; font-weight:normal;">快乐学习的第三课堂</span></div>
          <div class="col-3 text-right"><h3 class="m-0">合同号：<span style="color:red;">{{ $contract->contract_id }}</span></h3></div>
        </div>
      </div>
      <div class="card-body m-1 p-2">
        <div class="row m-2 p-2">
          <div class="col-12 text-center">
            <h1 class="m-0">
              <span style="font-family: 华文中宋;">
                育藤教育个性化辅导委托协议
              </span>
            </h1>
          </div>
        </div>
        <div class="row m-1 px-2">
          <div class="col-4">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                甲方：上海育藤培训学校有限公司{{ $contract->department_name }}
              </span>
            </h3>
          </div>
          <div class="col-6">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                甲方地址：{{ $contract->department_location }}
              </span>
            </h3>
          </div>
        </div>
        <div class="row m-1 px-2">
          <div class="col-4">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                乙方：{{ $contract->student_name }}
              </span>
            </h3>
          </div>
          <div class="col-6">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                乙方法定监护人：{{ $contract->student_guardian }}
              </span>
            </h3>
          </div>
        </div>
        <hr>
        <div class="row m-1 px-2 pt-2">
          <div class="col-12 mb-2">
            <h3 class="m-0">
              <span style="font-family: 华文中宋;">
                第一条、相关内容及费用
              </span>
            </h3>
          </div>
          <div class="col-12 mb-4">
            <table class="table" style="border:1px solid #32325d;">
              <thead style="border:1px solid #32325d;">
                <th style="border:1px solid #32325d; width:18%;">课程</th>
                <th style="border:1px solid #32325d; width:9%;">类型</th>
                <th style="border:1px solid #32325d; width:10%;">单价</th>
                <th style="border:1px solid #32325d; width:9%;">购买课时</th>
                <th style="border:1px solid #32325d; width:12%;">合计</th>
                <th style="border:1px solid #32325d; width:42%;">备注</th>
              </thead>
              <tbody>
                @if(count($contract_courses)==0)
                <tr class="text-center"><td colspan="6">没有购买课程！</td></tr>
                @endif
                @foreach ($contract_courses as $contract_course)
                <tr @if($contract_course->contract_course_status==0) style="text-decoration:line-through;" @endif>
                  <td style="border:1px solid #32325d;">{{ $contract_course->course_name }}</td>
                  <td style="border:1px solid #32325d;">{{ $contract_course->course_type }}</td>
                  <td style="border:1px solid #32325d;">{{ number_format($contract_course->contract_course_original_unit_price, 1) }} 元</td>
                  <td style="border:1px solid #32325d;">{{ number_format($contract_course->contract_course_original_hour) }} 课时</td>
                  <td style="border:1px solid #32325d;">{{ number_format($contract_course->contract_course_total_price, 1) }} 元</td>
                  <td style="border:1px solid #32325d;">
                    @if($contract_course->contract_course_discount_rate!=1)
                      折扣 ：{{ numberToCh($contract_course->contract_course_discount_rate*10/1) }}@if($contract_course->contract_course_discount_rate*100%10!=0){{ numberToCh($contract_course->contract_course_discount_rate*100%10) }}@endif 折.
                    @endif
                    @if($contract_course->contract_course_discount_amount!=0)
                      金额优惠：{{ number_format($contract_course->contract_course_discount_amount, 2) }} 元.
                    @endif
                    @if($contract_course->contract_course_free_hour!=0)
                      赠送：{{ number_format($contract_course->contract_course_free_hour) }} 课时.
                    @endif
                    @if(0)
                      合计优惠：{{ number_format($contract_course->contract_course_discount_total, 2) }} 元.
                    @endif
                  </td>
                </tr>
                @endforeach
                <tr>
                  <td style="border:1px solid #32325d;" colspan="3"><strong>合计</strong></td>
                  <td style="border:1px solid #32325d;"><strong>{{ number_format($contract->contract_original_hour) }} 课时</strong></td>
                  <td style="border:1px solid #32325d;"><strong>{{ number_format($contract->contract_total_price, 1) }} 元</strong></td>
                  <td style="border:1px solid #32325d;"><strong>共计优惠：{{ number_format($contract->contract_discount_price, 1) }} 元.</strong></td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="col-12 mb-2">
            <h3 class="m-0">
              <span style="font-family: 华文中宋;">
                第二条、甲方责任和义务
              </span>
            </h3>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                一、自双方签订本协议之后，甲方即为乙方建立档案。档案包括学生咨询记录表、委托辅导协议、授课方案和计划、学员成长手册等资料。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                二、乙方向甲方按照约定缴清费用后，甲方根据乙方的实际情况、测试结果和相关分析，为其选派优秀的面授教师，选派时间为3-5天，如乙方、乙方监护人对授课教师的辅导不满意，甲方接到乙方通知后须即时调整或更换。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                三、甲方向乙方提供约定课时的课外辅导，根据乙方的学习情况，如双方认为需要增加课时的，可续签协议，费用由乙方承担。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                四、甲方负责培训、指导和管理授课教师，乙方须配合授课教师按计划完成授课，该计划存入学生档案。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                五、每次授课前，甲方授课教师检查上次给学生布置的作业，并将完成情况记录在教案中，作为授课教师授课质量的反馈。甲方严格管理授课教师，教务处指派班主任专门负责教师授课的质量，认真对待家长的投诉，及时妥善处理。
              </span>
            </h4>
          </div>
          <div class="col-12 mb-4">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                六、甲方须综合乙方以上所存在的问题和实际情况，适时优化调整授课计划、师资安排和辅导内容，最大程度保证授课教师的稳定性。
              </span>
            </h4>
          </div>
          <div class="col-12 mb-2">
            <h3 class="m-0">
              <span style="font-family: 华文中宋;">
                第三条、乙方（乙方监护人）责任和义务
              </span>
            </h3>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                一、乙方及其监护人须提供甲方所需要的一切真实建档信息，以供甲方为乙方制定合理的辅导方案和授课计划。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                二、乙方在收到甲方上课通知后，如有异议，需第一时间与班主任联系，若上课前12小时内甲方班主任未收到乙方通知，视为默认。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                三、甲方为乙方安排班级并选派授课老师为乙方进行辅导后，乙方监护人应对授课教师的辅导质量如实评价。如对教师的授课质量有疑议，需在授课48小时之内通知甲方；如授课48小时后，甲方未接到乙方通知，则视为对该授课教师满意。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                四、乙方及其监护人应从以下几个方面考察授课教师：是否准时到达，着装是否整洁、普通话是否标准、是否具有亲和力、思路是否清晰、辅导方式是否能接受、解答问题是否熟练综合素质如何等方面。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                五、乙方需要接受甲方安排的学习内容和进度，如需变换须及时通知甲方，甲方认同后方可进行。乙方监护人须及时反映乙方在学习过程中出现的新问题，以利于甲方进行个性化辅导。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                六、<u>为保证教学质量及其他同学学习效果，甲方正常排课后，乙方不得中途请假或者改变时间，如遇特殊原因未能到校上课，甲方正常扣除课时费后，甲乙双方协商与周中时间安排补课，补课时间不再安排在周末，如乙方不参与补课，视同放弃。
              </u></span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                七、<u>周中补课需提前一天与班主任预约，便于老师备课，乙方在甲方晚托陪读或上课期间需遵守甲方纪律，如果乙方学员扰乱陪读秩序或者影响其他同学学习，甲方有权取消乙方陪读资格。
              </u></span>
            </h4>
          </div>
          <div class="col-12 mb-4">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                八、<u>为保证乙方学员上学放学途中安全，学生离校需家长亲自接送，若乙方需单独上学放学，需乙方监护人同意，甲方不承担任何路途安全责任。
              </u></span>
            </h4>
          </div>
        </div>
      </div>
    </div>
    <div style="page-break-after: always;"></div>
    <div class="card m-4">
      <div class="card-header m-1 p-2">
        <div class="row m-0 p-0">
          <div class="col-3"><h2 class="m-0">育藤教育</h2></div>
          <div class="col-6 text-center"><span style="font-family: 华文中宋; font-weight:normal;">快乐学习的第三课堂</span></div>
          <div class="col-3 text-right"><h3 class="m-0">合同号：<span style="color:red;">{{ $contract->contract_id }}</span></h3></div>
        </div>
      </div>
      <div class="card-body m-1 p-2">
        <div class="row m-1 px-2 pt-2">
          <div class="col-12 mb-2">
            <h3 class="m-0">
              <span style="font-family: 华文中宋;">
                第四条、乙方（乙方监护人）责任和义务
              </span>
            </h3>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                 一、学生档案作为本协议的一个重要组成部分，由甲方保管，直到双方协议终止。乙方需要提供真实情况，双方均不得随意更改档案内容，如果乙方需要可以进行部分复印。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                二、在协议执行期间，乙方如对甲方服务不满或有其他原因等情况下，可在3次课内申请变更协议，甲方应根据乙方要求调整个性化辅导方案，适当更换乙方授课教师，已达到最佳辅导效果。如果甲方调整后仍不能让乙方满意，乙方可提出解除协议。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                三、退费处理，甲方将扣除乙方一次性建档费、税费、拉卡手续费和已消耗原价课时费用（其计算方法为：原价收费标准 * 已完成辅导的课时数量），退费时，已完成课时数不再享受任何折扣优惠，赠送课时不退费，剩余费用将在7-30个工作日全部退还给乙方。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                四、本协议签署后，乙方交纳的定金概不退还。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                五、若由于乙方提供不真实资料签订本协议的或若由于乙方私下与甲方授课教师达成任何异于本协议规定的辅导计划、或乙方向甲方教授教师垂询任何薪资数额行为，导致影响教学计划和效果的；以上行为导致后果由乙方负责，甲方可无条件解除协议，甲方不承担任何违约责任，并有权要求乙方赔偿损失。
              </span>
            </h4>
          </div>
          <div class="col-12 mb-4">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                六、若因自然灾害等不可抗力或政府政策变化等外部因素导致本协议无法继续履行，双方均不承担责任。
              </span>
            </h4>
          </div>
          <div class="col-12 mb-2">
            <h3 class="m-0">
              <span style="font-family: 华文中宋;">
                第五条、附则
              </span>
            </h3>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                 一、本协议一式两份，双方各执一份，自签订之日起生效。协议结束后，双方责任和义务即自动停止。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                 二、本协议无新增条款，任何口头承诺均无效。本协议解释权归甲方所有。本协议履行期间如发生争议，由双方友好协商解决，若协商不成，依法向甲方所在地的人民法院起诉。
              </span>
            </h4>
          </div>
          <div class="col-12">
            <h4 class="mb-1 mx-4">
              <span style="font-family: 华文中宋; font-weight:normal;">
                三、对本协议的未尽事宜，经双方协商，在此说明：
              </span>
            </h4>
          </div>
          <div class="col-12">
            <hr class="mt-4 mx-4" style="border-top:1px solid #32325d;">
          </div>
          <div class="col-12">
            <hr class="mt-3 mx-4" style="border-top:1px solid #32325d;">
          </div>
          <div class="col-12">
            <hr class="mt-3 mx-4" style="border-top:1px solid #32325d;">
          </div>
          <div class="col-12 mb-6">
            <hr class="mt-3 mx-4" style="border-top:1px solid #32325d;">
          </div>
        </div>
        <div class="row mx-1 my-6 px-2">
          <div class="col-6">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                甲方：上海育藤培训学校有限公司{{ $contract->department_name }}
              </span>
            </h3>
          </div>
          <div class="col-6">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                乙方学员签字：
              </span>
            </h3>
          </div>
        </div>
        <div class="row mx-1 my-6 px-2">
          <div class="col-6">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                甲方代表签字：
              </span>
            </h3>
          </div>
          <div class="col-6">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                乙方监护人签字：
              </span>
            </h3>
          </div>
        </div>
        <div class="row mx-1 my-3 px-2">
          <div class="col-6"></div>
          <div class="col-6">
            <h3 class="m-0">
              <span style="font-family: 华文中宋; font-weight:normal;">
                本协议于
                <u>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</u>年
                <u>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</u>月
                <u>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</u>日
                签订
              </span>
            </h3>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

