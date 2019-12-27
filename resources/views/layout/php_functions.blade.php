<?php

// Generate page links for tables
function pageLink($currentPage, $totalPage, $request, $totalNum)
{
  // 获取上一页、下一页页码
  $prevPage = $currentPage-1;
  $nextPage = $currentPage+1;
  // 生成请求URL参数
  $request_str = "";
  $requests = $request->all();
  foreach($requests as $key => $value){
      if($key!="page"){
          $request_str .= "&".$key."=".$value;
      }
  }
  // 输出HTML
  echo "<div class='card-footer'>";
  // 第一行：页码
  echo "<div class='row'>";
  echo "<div class='col-12'>";
  echo "<nav>";
  echo "<ul class='pagination justify-content-center'>";
  // 上一页按钮
  if($currentPage==1){
      echo "<li class='page-item disabled'>";
  }else{
      echo "<li class='page-item'>";
  }
  echo "<a class='page-link' href='?page={$prevPage}{$request_str}'>";
  echo "<i class='fas fa-angle-left'></i>";
  echo "<span class='sr-only'>Previous</span>";
  echo "</a>";
  echo "</li>";
  // 第一页链接
  if($currentPage==1){
      echo "<li class='page-item active'><a class='page-link' href='#'>1</a></li>";
  }else{
      echo "<li class='page-item'><a class='page-link' href='?page=1{$request_str}'>1</a></li>";
  }
  // 省略图标
  if($currentPage>=5){
      echo "<li class='page-item disabled'><a class='page-link'>...</a></li>";
  }
  // 页数导航
  for($i = $currentPage-2; $i <= $currentPage+2; $i++){
      if($i>1&$i<$totalPage){
          if($i == $currentPage){
              echo "<li class='page-item active'><a class='page-link' href='#'>{$i}</a></li>";
          }else{
              echo "<li class='page-item'><a class='page-link' href='?page={$i}{$request_str}'>{$i}</a></li>";
          }
      }
  }
  // 省略图标
  if($currentPage<=($totalPage-4)){
      echo "<li class='page-item disabled'><a class='page-link'>...</a></li>";
  }
  // 最后一页链接
  if($totalPage!=1){
      if($currentPage==$totalPage){
          echo "<li class='page-item active'><a class='page-link' href='#'>{$totalPage}</a></li>";
      }else{
          echo "<li class='page-item'><a class='page-link' href='?page={$totalPage}{$request_str}'>{$totalPage}</a></li>";
      }
  }
  // 下一页按钮
  if($currentPage==$totalPage){
      echo "<li class='page-item disabled'>";
  }else{
      echo "<li class='page-item'>";
  }
  echo "<a class='page-link' href='?page={$nextPage}{$request_str}'>";
  echo "<i class='fas fa-angle-right'></i>";
  echo "<span class='sr-only'>Next</span>";
  echo "</a>";
  echo "</li>";
  echo "</ul>";
  echo "</nav>";
  echo "</div>";
  echo "</div>";
  // 第二行： 记录数量
  echo "<div class='row justify-content-center'>";
  echo "<div class='col-4 text-center'>";
  echo "<h5 class='m-0 p-0'>共 {$totalNum} 条记录</h5>";
  echo "</div>";
  echo "</div>";
  echo "</div>";
}

function deleteConfirm($id,$messages){
  echo "<button type='button' class='btn btn-sm btn-outline-danger' data-toggle='modal' data-target='#modal-{$id}'>删除</button>
        <div class='modal fade' id='modal-{$id}' tabindex='-1' role='dialog' aria-labelledby='modal-{$id}' aria-hidden='true'>
          <div class='modal-dialog modal-dialog-centered' role='document'>
            <div class='modal-content'>
              <div class='modal-header'>
                <h6 class='modal-title ml-4 mt-4' id='modal-title-default'>确认删除本记录?</h6>
              </div>
              <div class='modal-body text-left ml-4'>";
  for($i=0;$i<count($messages);$i++){
      echo "<p>{$messages[$i]}</p>";
  }
  echo "      </div>
              <div class='modal-footer mt--4'>
                <input type='submit' class='btn btn-sm btn-outline-danger' value='确认删除'>
                <button type='button' class='btn btn-link' data-dismiss='modal'>关闭</button>
              </div>
            </div>
          </div>
        </div>";
}
?>
