<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-SİRKÜLER LİSTESİ</title>
<link rel="shortcut icon" type="image/ico" href="img/gestas.ico"/>
<link rel="stylesheet" href="css/form.css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<script lang="javascript" src="js/xlsx.full.min.js"></script>
<script lang="javascript" src="js/FileSaver.min.js"></script>

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
    	<a href="#" id="savexls" onClick="exporttoExcel('xlsx');">Excel olarak kaydet</a>
    	<!--<input type="button" id="savexls" onclick="javascript:fnExcelReport();">Kaydet</button>-->
    	<form method="POST">
    	<div>
			<div class="col-75">
				<input type="text" name="search_val" placeholder="Ara"></input>
			</div>
			<div class="col-25">
		    	<select name="search_type">
		    		<option value="form_number">Sayı</option>
		    		<option value="subject">Konu</option>
		    		<option value="created_date">Tarih</option>
		    		<option value="department_id">Departman</option>
		    		<option value="form_state">Durum</option>
		    		<option value="who_created">Atayan</option>
		    		<option value="employee_circular_id">Atanan</option>
		    	</select>
	    	</div>
    	</div>
    	</form>
    	<table id="customers">
			<caption>Sirküler Listesi</caption>
			<tr>
			    <th>Sayı</th>
			    <th>Konu</th>
			    <th>Oluşturma Tarihi</th>
			    <th>Yayınlayan Departman</th>
			 </tr>
			
			 <?php
			 	include("log_level.php");
			 	include("connect.php");

			 	$sql_query = "SELECT * FROM circular_list,department WHERE department.department_id=circular_list.department_id AND form_type=1 Order By circular_list_id DESC LIMIT 25;";
			 	if ($_POST)
			 	{
			 		$search_value = $_POST["search_val"];
			 		$search_type = $_POST["search_type"];
			 		switch ($search_type) {
					    case "department_id":
					    	$sql_query = "SELECT * FROM circular_list,department WHERE department.department_name LIKE '%".$search_value."%' AND department.department_id = circular_list.department_id AND form_type=1 Order By circular_list.circular_list_id DESC";
					        break;
					    case "who_created":
					        $sql_query = "SELECT * FROM circular_list,users,department WHERE users.name LIKE '%".$search_value."%' AND users.user_id = circular_list.who_created AND department.department_id=circular_list.department_id AND form_type=1 Order By circular_list.circular_list_id DESC";
					        break;
					    case "employee_circular_id":
					        $sql_query = "SELECT * FROM circular_list,employee_circular_list,users,department WHERE users.name LIKE '%".$search_value."%' AND users.user_id = employee_circular_list.employee_circular_id AND employee_circular_list.circular_list_id = circular_list.circular_list_id AND department.department_id=circular_list.department_id AND form_type=1 Order By circular_list.circular_list_id DESC";
					        break;
					    default:
					        $sql_query = "SELECT * FROM circular_list,department WHERE ".$search_type." LIKE '%".$search_value."%' AND department.department_id=circular_list.department_id AND form_type=1 Order By circular_list_id DESC";
					}
			 	}
				
				$result = $conn->query($sql_query);
				if ($result)
				{
					if ($result->num_rows > 0)
					{
						while ($row = $result->fetch_assoc())
						{
							$varname = "circular_list_dashboard.php?circularlistid=".$row["circular_list_id"];
							echo "<tr>
							<td><a href='".$varname."'>". $row["form_number"]."</a></td>
							<td>". $row["subject"]."</td>
							<td>". $row["created_date"]."</td>
							<td>". $row["department_name"]."</td>";
							echo "</a></tr>";
						}
					}
					$totalcols = $result->num_rows;
					mysqli_free_result($result);
				}
				else
				{
					//save the error msg
     				$conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_ERROR.",'".mysqli_error($conn)."','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
				}
				//close the connection
				$conn->close();
			 ?>
			 
			</table>
			<br/>
			<?php
				if (isset($totalcols))
				{
					echo $totalcols." Sonuç sıralandı.";
				}
				else
					echo "0 Sonuç sıralandı.";
			?>
    </div><!--  İçerik -->
     
    <div class="clear"></div>
     
    <div class="footer">
    </div><!-- Alt bilgi -->
 
</div><!-- wrap bütün sütunları, satırları sar -->


</body>
</html>

<script type="text/javascript">
	function exporttoExcel(type, fn, dl) {
	var elt = document.getElementById('customers');
	var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
	return dl ?
		XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
		XLSX.writeFile(wb, fn || ('TamimSirkuler_Listesi.' + (type || 'xlsx')));
}

</script>