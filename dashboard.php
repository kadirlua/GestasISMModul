<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-GÖSTERGE PANELİ</title>
<link rel="shortcut icon" type="image/ico" href="img/gestas.ico"/>
<link rel="stylesheet" href="css/form.css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/bootstrap.min.css" />

<style>
#customers {
  font-family: "Trebuchet MS", Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}

#customers tr:nth-child(even){background-color: #f2f2f2;}

#customers tr:hover {background-color: #ddd;}

#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #4CAF50;
  color: white;
}
</style>

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
    	<div>
    		<button class="new_button" type="button" onclick="window.location.href = 'work_list_new.php';">Yeni İş Listesi</button>
    		<button class="new_button" type="button" onclick="window.location.href = 'circular_list_new.php';">Yeni Tamim/Sirküler</button>
    	</div>

    		<table id="customers">
			<caption>Tüm Listeler</caption>

			 <?php
			 	include("connect.php");
				
				$sql_query = "SELECT * FROM work_list INNER JOIN circular_list LIMIT 4;";
				$result = $conn->query($sql_query);
				if ($result)
				{
					if ($result->num_rows > 0)
					{
						while ($row = $result->fetch_assoc())
						{
							echo "<tr><td>". $row["form_code"]."</td><td>".$row["form_name"]."</td></tr>";
							echo "<tr><td>". $row["form_number"]."</td><td>".$row["subject"]."</td></tr>";
						}
					}
					mysqli_free_result($result);
				}
				//close the connection
				$conn->close();
			 ?>
			 
			</table>

			<br/>
    	
    		<table id="customers">
			<caption>İş Listeleri</caption>

			<tr>
			    <th>Form No</th>
			    <th>Adı</th>
			 </tr>
			
			 <?php
			 	include("connect.php");
				
				$sql_query = "SELECT * FROM work_list Order By work_list_id DESC LIMIT 8";
				$result = $conn->query($sql_query);
				if ($result)
				{
					if ($result->num_rows > 0)
					{
						while ($row = $result->fetch_assoc())
						{
							echo "<tr><td>". $row["form_code"]."</td><td>".$row["form_name"]."</td></tr>";
						}
					}
					mysqli_free_result($result);
				}
				//close the connection
				$conn->close();
			 ?>
			 
			</table>

			<br/>

			<table id="customers">
			<caption>Tamim Listesi</caption>

			<tr>
			    <th>Sayı</th>
			    <th>Konu</th>
			 </tr>
			
			 <?php
			 	include("connect.php");
				
				$sql_query = "SELECT * FROM circular_list WHERE form_type=0 Order By circular_list_id DESC LIMIT 8";
				$result = $conn->query($sql_query);
				if ($result)
				{
					if ($result->num_rows > 0)
					{
						while ($row = $result->fetch_assoc())
						{
							echo "<tr><td>". $row["form_number"]."</td><td>".$row["subject"]."</td></tr>";
						}
					}
					mysqli_free_result($result);
				}
				//close the connection
				$conn->close();
			 ?>
			 
			</table>

			<br/>

			<table id="customers">
			<caption>Sirküler Listesi</caption>

			<tr>
			    <th>Sayı</th>
			    <th>Konu</th>
			 </tr>
			
			 <?php
			 	include("connect.php");
				
				$sql_query = "SELECT * FROM circular_list WHERE form_type=1 Order By circular_list_id DESC LIMIT 8";
				$result = $conn->query($sql_query);
				if ($result)
				{
					if ($result->num_rows > 0)
					{
						while ($row = $result->fetch_assoc())
						{
							echo "<tr><td>". $row["form_number"]."</td><td>".$row["subject"]."</td></tr>";
						}
					}
					mysqli_free_result($result);
				}
				//close the connection
				$conn->close();
			 ?>
			 
			</table>

			<br/>
			<br/>
    	
    </div><!--  İçerik -->
     
    <div class="clear"></div>
     
    <div class="footer">
    </div><!-- Alt bilgi -->
 
</div><!-- wrap bütün sütunları, satırları sar -->


</body>
</html>