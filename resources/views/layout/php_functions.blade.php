<?php

// Generate page links for tables
function pageLink($currentPage, $totalPage)
{
  $prevPage=$currentPage-1;
  $nextPage=$currentPage+1;

  echo '<div class="card-footer">';
  echo '<nav>';
  echo '<ul class="pagination justify-content-center">';
  // 上一页按钮
  if($currentPage==1){
      echo '<li class="page-item disabled">';
  }else{
      echo '<li class="page-item">';
  }
  echo "<a class='page-link' href='?page={$prevPage}'>";
  echo '<i class="fas fa-angle-left"></i>';
  echo '<span class="sr-only">Previous</span>';
  echo '</a>';
  echo '</li>';
  // 第一页链接
  if($currentPage==1){
      echo '<li class="page-item active"><a class="page-link" href="?page=1">1</a></li>';
  }else{
      echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
  }
  // 省略图标
  if($currentPage>=5){
      echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
  }
  // 页数导航
  for($i=$currentPage-2;$i<=$currentPage+2;$i++){
      if($i>1&$i<$totalPage){
          if($i==$currentPage){
              echo "<li class='page-item active'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
          }else{
              echo "<li class='page-item'><a class='page-link' href='?page={$i}'>{$i}</a></li>";
          }
      }
  }
  // 省略图标
  if($currentPage<=($totalPage-4)){
      echo '<li class="page-item disabled"><a class="page-link">...</a></li>';
  }
  // 最后一页链接
  if($totalPage!=1){
      if($currentPage==$totalPage){
          echo "<li class='page-item active'><a class='page-link' href='?page={$totalPage}'>{$totalPage}</a></li>";
      }else{
          echo "<li class='page-item'><a class='page-link' href='?page={$totalPage}'>{$totalPage}</a></li>";
      }
  }
  // 下一页按钮
  if($currentPage==$totalPage){
      echo '<li class="page-item disabled">';
  }else{
      echo '<li class="page-item">';
  }
  echo "<a class='page-link' href='?page={$nextPage}'>";
  echo '<i class="fas fa-angle-right"></i>';
  echo '<span class="sr-only">Next</span>';
  echo '</a>';
  echo '</li>';

  echo '</ul>';
  echo '</nav>';
  echo '</div>';
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
