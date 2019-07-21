<?php

ob_start();
session_start();
if (!$_SESSION["login"])
{
	header("Location:index.php");
}

if ($_POST["is_durumu"] == "0")
{
	echo "<script>alert('Durumu kapalı olan iş listesi güncellenemez!.');</script>";
}
else
{
	include("log_level.php");
	include("connect.php");
	if ($conn->query("UPDATE work_list SET state='0' WHERE work_list_id=". (int) $_POST["work_list_id"]))
	{
		echo "<script>alert('İş listesi durumu başarıyla güncellendi');</script>";

		//save the log msg
		$conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'İş listesi durumu başarıyla güncellendi','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
	}
	else
	{
		echo "<script>alert('".mysqli_error($conn)."');</script>";
		//save the error msg
	    $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_ERROR.",'".mysqli_error($conn)."','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
	}
	$conn->close();
}
?>