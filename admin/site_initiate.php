<!----------------------------------------------------------------------------- 
  파일명 : site_initiate.php
  최초작업자 : jongsu
  최초작성일자 : 2022-01-03
  업데이트일자 : 2022-01-03
  
  기능: 
  최초, root 권한을 이용하여 사이트가 동작하는데 필요한 환경을 만든다.
  데이터베이스 생성, 애플리케이션용 계정 생성, 테이블 생성 등을 포함한다.
  이 코드는 납품시 최초 1 회만 실행하며, 세팅 후 회수 처리하여
  사용자의 우발적 시스템 초기화를 방지하여야 한다.
------------------------------------------------------------------------------>
<?php
// 프로젝트 시작 전 toyappname을 정한다.
// 두번째 toy부터는 현재폴더의 이름이 app의 이름이 디도록 수정하였다.
// substr("/currentfolername",1)는  /를 제외한 currentfoldername을 얻는다.
//=======================================================
$toyappname = 'demosite1';
// $toyappname = substr(dirname($_SERVER['PHP_SELF']), 1);  // $SERVER 디렉터리 네임을 가져와라 / = substr("/toy2_memoapp")와 동일 / 폴더의 이름에서 가져오겠다-의 의미 / 쉽게 말해 폴더 경로(이름)을 $변수로 바꿔서 쓰면 편하니까 이렇게 사용하라고...
//========================================================
$dbservername = 'localhost'; // 개발 및 테스트 환경에서는 localhost를 전제로 한다.
$dbusername = 'root';  // 현재 DBMS에 root계정을 이용하여 접속한다.
$dbpassword = '';  // 현재 DBMS root 계정의 패스워드를 적는다.
$dbname = $toyappname; // toy project의 이름으로 db와 사용자를 생성하도록 한다.

require_once "../util/utility.php"; // 메시지, 유틸리티 함수 등을 정의한 util/utility.php를 include 한다. 

// create connection
$conn = new mysqli($dbservername, $dbusername, $dbpassword);

// check connection : 연결 확인, 오류가 있으면 메시지 출력 후 프로세스 정료
if ($conn->connect_error) {
  echo outmsg(DBCONN_FAIL);
  die("연결실패 :" . $conn->connect_error);
} else {
  if (DBG) echo outmsg(DBCONN_SUCCESS);
}

// 데이터베이스가 있으면 삭제하고 새롭게 생성
$sql = "DROP DATABASE IF EXISTS " . $dbname . ";";
if ($conn->query($sql) == TRUE) {
  if (DBG) echo outmsg(DROPDB_SUCCESS);
}
$sql = "DROP USER IF EXISTS " . $dbname . ";";
if ($conn->query($sql) == TRUE) {
  if (DBG) echo outmsg(DROPUSER_SUCCESS);
}

// 애플리케이션이 사용할 계정을 생성하고 동일한 이름의  데이터베이스를 생성한다.
// $sql = "CREATE DATABASE IF NOT EXISTS toymembership";
// 여러개의 sql명령어를 하나의 sql 스티링으로 처리시 오류 발생함!!
// 다음의 4 단계로 나누어 처리하도록 수정함!!
// 1. 사용자 계정을 생성하고, 
// 2. 리소스 제한 없이 사용하도록 권한을 부여하고,
// 3. 데이터베이스를 생성하고,
// 4. 생성된 사용자 계정에 데이터베이스에 대한 모든 권한을 부여 

// 1. 사용자 계정을 생성하고, 
$sql = "CREATE USER IF NOT EXISTS '".$dbname."'@'%' IDENTIFIED BY '".$dbname."'";
if ($conn->query($sql) == TRUE) {
  if (DBG) echo outmsg(CREATEUSER_SUCCESS);
} else {
  echo outmsg(CREATEUSER_FAIL);
}
// 2. 리소스 제한 없이 사용하도록 권한을 부여하고,
$sql = "GRANT USAGE ON *.* TO '" . $dbname . "'@'%' REQUIRE NONE WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0";
if ($conn->query($sql) == TRUE) {
  if (DBG) echo outmsg(LIMITRSC_SUCCESS);
} else {
  echo outmsg(LIMITRSC_FAIL);
}
// 3. 데이터베이스를 생성하고,
$sql = "CREATE DATABASE IF NOT EXISTS `" . $dbname . "`";
if ($conn->query($sql) == TRUE) {
  if (DBG) echo outmsg(CREATEDB_SUCCESS);
} else {
  echo outmsg(CREATEDB_FAIL);
}
// 4. 생성된 사용자 계정에 데이터베이스에 대한 모든 권한을 부여 
$sql = "GRANT ALL PRIVILEGES ON `" . $dbname . "`.* TO '" . $dbname . "'@'%';  ";
if ($conn->query($sql) == TRUE) {
  if (DBG) echo outmsg(GRANTUSER_SUCCESS);
} else {
  echo outmsg(GRANTUSER_FAIL);
}

// 데이터베이스 연결 인터페이스 리소스를 반납한다.
$conn->close();

// 코드 완료 메시지 출력
if (DBG) echo outmsg(COMMIT_CODE);

// 프로세스 플로우를 인덱스 페이지로 돌려준다.
// header('Location: index.php');
// 작업 실행 단계별 메시지 확인을 위해 Confrim and return to back하도록 수정함!!
// 백그라운드로 처리되도록 할 경우 위의 원 코드로 대체 할 것!!
echo "<a href='../membership/app_initiate.php'>Confirm and Return to back</a>"; //
?>