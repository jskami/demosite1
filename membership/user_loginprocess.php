<!-- 
  파일명 : user_loginprocess.php
  최초작업자 : jongsu
  최초작성일자 : 2022-01-03
  업데이트일자 : 2022-01-03
  
  기능: 
  user_login.php 로그인 화면에서 입력된 값을 받아 
  유저명과 비밀번호를 확인, 등록된 사용자임을 확인한다.
-->

<?php
// 여기부터는 로그인 성공시 세션관리를 위한 코드 추가
session_start();  // ★★★★세션 관리를 하려면 반드시 세션 시작이 되어야 한다.
// 세션을 만드는 거니까 로그인체크는 못하는거야-
// db연결 준비
require_once "../util/dbconfig.php";

// 데이터베이스 작업 전, 로그인 화면으로 부터 값을 전달 받고
//$username = $_POST['username'];
//$passwd = $_POST['passwd'];
$username = $_REQUEST['username']; // REQUEST는 겟,포스트 등을 포함해서 좀 더 광범위하게 불러오는 키워드이다. 
$passwd = $_REQUEST['passwd'];
// 세션관리를 위하여 클라이언트 정보 수집
$userip = get_client_ip();

// 사용자 계정 존재 여부 확인을 위한 질의 구성
$stmt = $conn->prepare("SELECT * FROM users WHERE username = ? and passwd = sha2(?,256)");
$stmt->bind_param("ss", $username, $passwd);

$stmt->execute();
$result = $stmt->get_result();
$row = mysqli_fetch_array($result);

if (!empty($row['username'])) {
  echo outmsg(LOGIN_SUCCESS);
  // 여기부터 로그인 성공시 세션관리를 위한 추가 코드
  //session_start();
  echo outmsg('SESSION_CREATE');
  //echo outmsg($userip);
  if(isset($_REQUEST['chkbox'])){  //'로그인상태 유지'에 대한 체크박스이다. 로그인화면에서 확인 할 수 있다.
    $a = setcookie('username', $username, time() + 60);
    $b = setcookie('passwd', $passwd, time() + 60); // 타임 함수 + 60의 의미는 분 단위로 추가하여 유지하는 쿠키를 만들어라-의 의미이다. (세션도 마찬가지로 24시간 유지할 수 있게 할 수 있다.)
  }
  $_SESSION['username']=$username;  // 사용자명이라는 세션변수를 만든 것(사용자 이름이 아니라 ID를 말하는거야, 즉 ID는 중복될 수 없으니까 한 사람만 불러오는게 가능하지)
  $_SESSION['userip']=$userip;  // 사용자ip라는 세션변수를 만든 것, 세션변수는 필요에 따라 여러개 만들 수 있다. ★★★★★ 이 두 변수가 실질적인 session이다. session 생성/
  // 여기까지 로그인 성공시 세션관리를 위한 추가 코드
  $conn->close();
  //header('Location: user_list.php');
  echo "<a href='./user_userlist.php'>목록보기</a>";
} else {
  echo outmsg(LOGIN_FAIL);
  $conn->close();
  //header('Location: index.php');
  echo "<a href='../index.php'>index 페이지로</a>";
}

// 로그인 프로세스는 사용자가 입력한 id, pw 등의 정보가 일치하면 세션을 만들어 준다.
?>