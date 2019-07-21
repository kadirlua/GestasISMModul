<?php
$host="localhost";
$db="ism_db";
$user="root";
$pass="";
$conn=mysqli_connect($host,$user,$pass) or die("Mysql Baglanamadi");
mysqli_select_db($conn, $db) or die("Veritabanina Baglanilamadi");
//mysqli_query($conn,"SET NAMES 'utf8'");
mysqli_set_charset($conn,"utf8");
?>