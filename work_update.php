<?php

ob_start();
session_start();
if (!$_SESSION["login"])
{
	header("Location:index.php");
}

include("log_level.php");
include("connect.php");
$result = false;
if (isset($_POST["tamamla"]))
{
	$result = $conn->query("UPDATE work SET work_state='0' WHERE work_id=". (int) $_POST["tamamla"]);
}
elseif (isset($_POST["isleme_al"]))
{
	$result = $conn->query("UPDATE work SET work_state='2' WHERE work_id=". (int) $_POST["isleme_al"]);
}

if ($result)
{
	echo "<script>alert('İş durumu başarıyla güncellendi');</script>";
	//save the log msg
    $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'İş durumu başarıyla güncellendi','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
}
else
{
	echo "<script>alert('".mysqli_error($conn)."');</script>";
	//save the error msg
    $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_ERROR.",'".mysqli_error($conn)."','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
}
$conn->close();
?>