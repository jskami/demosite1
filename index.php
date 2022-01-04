<!-- 
  파일명 : index.php
  최초작업자 : jongsu
  최초작성일자 : 2022-01-03
  업데이트일자 : 2022-01-03
  
  기능: 
  demosite1 프로젝트 폴더의 최상위 index 파일로써,
  하위 app 폴더를 연결하는 역할을 한다.
  훈련생들에게 일반적인 웹사이트 프로젝트의
  구조를 설명하도록 한다.
-->
<!--
  session 관리 목적 추가
-->
<?php
require_once './util/utility.php'; // 나 자신을 기준으로 ../는 위로 찾고 ./는 아래로 찾는거야
require_once './util/loginchk.php'; //
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Membership Management</title>
  <link rel="stylesheet" href="./css/style.css">
</head>

<body>
  <!-- Logo, Memga menu's, Introduction Video link, and Login Button -->
  <header>
    <logo></logo>
    <mgmnu></mgmnu>
    <videos></videos>
    <login></login>
  </header>
  <!-- -->
  <nav>
    <ul>
      <li>navmenu1</li>
      <li>navmenu1</li>
      <li>navmenu1</li>
      <?php
      if (!$chk_login) {  // 로그인 상태가 아니라면
      ?>
      
        <li id='trglgnModal'>login</li>
        <!-- login modal -->
        <div id='lgnModal' class='modal'>
          <div class="modal-content">
            <span class="close">&times;</span>
            <form action="./membership/user_loginprocess.php" method="POST" class="loginbox">
              <label>사용자 아이디 : </label><input type="text" name="username" placeholder="사용자 아이디를 입력해주세요." required /><br>
              <label>비밀번호 : </label><input type="password" name="passwd" placeholder="비밀번호를 입력해주세요." required /><br>
              <br>
              <input type="checkbox" value="yes" name="chkbox">로그인 상태 유지
              <input type=submit value="로그인">
              <a href="./membership/user_regist.php">회원가입</a>
            </form>
          </div>
        </div>
      <?php } else {
        echo $_SESSION['username']; ?>
        <button?><a href="./membership/user_logout.php">logout</a></button>
        <?php
      }
        ?>
    </ul>
  </nav>
  <!-- -->
  <main>
  </main>
  <footer>
  </footer>
  <script src='./js/login.js'></script>
</body>

</html>