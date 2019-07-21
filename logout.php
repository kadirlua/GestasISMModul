<?php
session_start();
ob_start();

include("log_level.php");
include("connect.php");

$employee_id = $_SESSION["employee_id"];
//save the log msg
$conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'Oturum sonlandırıldı.','".date('Y-m-d')."',$employee_id);");
$conn->close();

session_destroy();
echo "<center>Çıkış Yaptınız. Ana Sayfaya Yönlendiriliyorsunuz.</center>";
header("Refresh: 2; url=index.php");
ob_end_flush();
?>