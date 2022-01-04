<?php
  session_start();
  if(isset($_SESSION['username'])) { // 세션 변수에 해당 유저네임의 세션이 만들어졌는가?를 묻는 것
    $chk_login = TRUE;
  }else { 
    $chk_login = FALSE;
  }
?>