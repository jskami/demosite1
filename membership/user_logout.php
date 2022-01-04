<?php
    session_start();
    unset($_SESSION["username"]);  // unset : 없애라-
    unset($_SESSION["userip"]);
    setcookie("username", "");  // 유저네임(ID)의, ""(value없음) 값을 없애라 / 쿠키(대상,value값);
    setcookie("passwd", "");
    header("Location: ../index.php");
?>