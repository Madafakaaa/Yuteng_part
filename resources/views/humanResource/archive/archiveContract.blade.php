@include('layout.header')
<body>
  <div class="main-content" id="panel">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-9">
          <h2 class="text-center mt-3">{{$user->user_name}}-签约记录</h2>
          <div class="card my-3">
            <div class="table-responsive">
              <button type="button" class="btn btn-waring btn-block" onclick="table_export('table-1', '{{$user->user_name}}-签约记录')">导出表格</button>
              <table class="table text-left table-bordered" id="table-1">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>学生</th>
                    <th>类型</th>
                    <th>合计课时</th>
                    <th>实付金额</th>
                    <th>支付方式</th>
                    <th>购课日期</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($contracts as $contract)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $contract->student_name }}</td>
                      @if($contract->contract_type==0)
                        <td><span style="color:red;">首签</span></td>
                      @else
                        <td><span style="color:green;">续签</span></td>
                      @endif
                      <td><strong>{{ $contract->contract_total_hour }} 课时</strong></td>
                      <td><strong>{{ number_format($contract->contract_total_price, 1) }} 元</strong></td>
                      <td>{{ $contract->contract_payment_method }}</td>
                      <td>{{ $contract->contract_date }}</td>
                      <td><a href="/contract?id={{encode($contract->contract_id, 'contract_id')}}" target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Script files -->
  @include('layout.scripts')
</body>
</html>

