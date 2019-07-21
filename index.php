<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-OTURUM AÇ</title>
<link rel="shortcut icon" type="image/ico" href="img/gestas.ico"/>
<link rel="stylesheet" type="text/css" href="css/style.css" />
</head>
<body>
<?php 

include("log_level.php");
include("connect.php");
ob_start();
session_start();

if ($_POST)
{
	//sql injection prevention
	$username = mysqli_real_escape_string($conn, $_POST['kadi']); 
	$password = mysqli_real_escape_string($conn, $_POST['sifre']);
 
	if($username=="" or $password=="") {
	    echo "<center>Lütfen kullanıcı adı ya da şifreyi boş bırakmayınız..! <a href=javascript:history.back(-1)>Geri Dön</a></center>";
	}
	else
	{
		$sql_check = mysqli_query($conn, "select * from users where username='".$username."' and password='".$password."' ") or die(mysqli_error($conn));

		if ($sql_check)
		{
			if(mysqli_num_rows($sql_check))  {
		    $_SESSION["login"] = "true";
		    $_SESSION["user"] = $username;
		    $_SESSION["pass"] = $password;
			$row = mysqli_fetch_assoc($sql_check);
			$_SESSION["name"] = $row["name"];
			$_SESSION["employee_id"] = $row["user_id"];
			$_SESSION["employee_type"] = $row["user_type"];
			$_SESSION["employee_register_number"] = $row["register_number"];
			$employee_id = $_SESSION["employee_id"];
			//save the log msg
			$conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'Oturum açıldı.','".date('Y-m-d')."',$employee_id);");
			$conn->close();

		    header("Location:homepage.php");
			}
			else {
			    echo "<center>Kullanıcı Adı/Şifre Yanlış.<br><a href=javascript:history.back(-1)>Geri Dön</a></center>";
			}
			mysqli_free_result($sql_check);
		}
		else {
		    echo "<center>SQL Sorgu hatası.<br><a href=javascript:history.back(-1)>Geri Dön</a></center>";
		}
	}
 
	ob_end_flush();
}
else
{
	if (isset($_SESSION["login"]))
	{
		$conn->close();
		header("Location:homepage.php");
	}
	else
	{
		echo '<div class="login-page">
	  	<div class="form" id="loginForm">
	  	<img src="img/logo.png" align="top"></img>
		<form class="login-form" method="POST">
			<input type="text" name="kadi" placeholder="Kullanıcı adı" required/>
			<input type="password" name="sifre" placeholder="Parola" required/>
			<button>Oturum Aç</button>
		</form>
	  	</div>
		</div>';
	}
}

$conn->close();

?>
</body>
</html>