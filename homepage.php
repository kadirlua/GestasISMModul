<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-ANA SAYFA</title>
<link rel="shortcut icon" type="image/ico" href="img/gestas.ico"/>
<link rel="stylesheet" href="css/form.css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
</head>
<body>
<?php
ob_start();
session_start();
if (!$_SESSION["login"])
{
	header("Location:index.php");
}
?>
<div class="wrap">
 
    <div class="header">
    	<div class="header_left">
    		<a href="homepage.php"><img src="img/logo.png" alt="Gestas Logo" /></a>
    	</div>
    	<div class="header_right">
    		<p style="color:blue">
    			<?php
					echo $_SESSION["name"]."(".$_SESSION["employee_register_number"].")";
				?>
    		</p>
    		<a href="logout.php">Çıkış yap</a>

    	</div>
    </div><!-- Başlık -->

    <div class="sidebar">
        <div class="container">
        <ul>
        <li><a href="homepage.php">Ana sayfa</a></li>
        <li><a href="#">İnsan Kaynakları</a></li>
        <li><a href="#">İdari İşler</a></li>
        <li><a href="#">İş Takip Modulü</a></li>
        <li><a href="#">ISM Modulü</a>
            <ul>
                <li><a href="dashboard.php">Gösterge Paneli</a></li>
                <li><a href="work_list.php">İş Listeleri</a></li> 
                <li><a href="circular_list_1.php">Tamim Listesi</a></li>
                <li><a href="circular_list_2.php">Sirküler Listesi</a></li> 
            </ul>
        </li>
        <li><a href="#">Yağ Yakıt Sistemi</a></li>
        </ul>
        </div>
    </div><!-- kenar menü -->
 
    <div class="content">
    </div><!--  İçerik -->
     
    <div class="clear"></div>
     
    <div class="footer">
    </div><!-- Alt bilgi -->
 
</div><!-- wrap bütün sütunları, satırları sar -->


</body>
</html>