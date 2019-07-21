<?php

ob_start();
session_start();
if (!$_SESSION["login"])
{
	header("Location:index.php");
}

include("log_level.php");
include("connect.php");
if ($conn->query("UPDATE employee_circular_list SET state='0' WHERE employee_circular_id=".$_SESSION["employee_id"]." AND circular_list_id=". (int) $_POST["circular_list_id"]))
{
	echo "<script>alert('Tamim/Sirküler listesi okundu olarak değiştirildi.');</script>";

	//save the log msg
	$conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'Tamim/Sirküler listesi okundu olarak değiştirildi.','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
}
else
{
	echo "<script>alert('".mysqli_error($conn)."');</script>";
	//save the error msg
    $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_ERROR.",'".mysqli_error($conn)."','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
}
$conn->close();
?>