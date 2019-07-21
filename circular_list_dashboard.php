<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-İŞ LİSTESİ DETAYI</title>
<link rel="shortcut icon" type="image/ico" href="img/gestas.ico"/>

<link rel="stylesheet" href="css/form.css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
 <!-- Include Editor style. -->
<link href="css/froala_editor.pkgd.min.css" rel="stylesheet" type="text/css" />

<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<!-- Include Editor JS files. -->
<script type="text/javascript" src="js/froala_editor.pkgd.min.js"></script> 

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

include("log_level.php");
include("connect.php");

$circular_list_id = $_GET["circularlistid"];

if ($query_result = $conn->query("SELECT * FROM circular_list,users,department WHERE department.department_id=circular_list.department_id AND users.user_id=circular_list.who_created AND circular_list.circular_list_id='".$circular_list_id."'"))
{
  if ($query_result)
  {
    if ($query_result->num_rows > 0)
    {
      $query_row = $query_result->fetch_assoc();
      if ($query_row)
      {
      	$form_tipi = "Tamim";
      	if ($query_row["form_type"] == "1")
      	{
      		$form_tipi = "Sirküler";
      	}
        $departman_adi = $query_row["department_name"];
        $form_konu = $query_row["subject"];
        $form_icerik = $query_row["content"];
        $olusturma_tarihi = $query_row["created_date"];
        $olusturan = $query_row["name"];
        $form_durumu = $query_row["form_state"];
        
        switch ($form_durumu) {
          case 0:
            $state = "Kapalı";
              break;
          case 1:
            $state = "Açık";
              break;
          }
      }
    }
    mysqli_free_result($query_result);
  }
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
    	<div class="row">
        <button class="new_button" type="button" onclick="window.location.href = '<?php echo "circular_list_edit.php?circularlistid=".$circular_list_id;?>'">Düzenle</button>
      </div>

<form method="POST">
<div class="container-new">
	<div class="row">
    <div class="col-25">
      <label>Form Tipi:</label>
    </div>
    <div class="col-75">
      <input type="text" name="form_tipi" value="<?php echo $form_tipi; ?>" disabled/>
    </div>
  </div>

  <div class="row">
    <div class="col-25">
      <label>Form Durumu:</label>
    </div>
    <div class="col-75">
      <input type="text" name="durum" value="<?php echo $state; ?>" disabled/>
    </div>
  </div>

  <div class="row">
    <div class="col-25">
      <label>Oluşturan:</label>
    </div>
    <div class="col-75">
      <input type="text" name="olusturan" value="<?php echo $olusturan; ?>" disabled/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Yayınlayan Departman:</label>
    </div>
    <div class="col-75">
      <input type="text" name="yayinlayan_departman" value="<?php echo $departman_adi; ?>" disabled/>
    </div>
  </div>
  
  <div class="row">
    <div class="col-25">
      <label for="subject">Oluşturma Tarihi:</label>
    </div>
    <div class="col-75">
      <input type="date" name="olusturma_tarihi" value="<?php echo $olusturma_tarihi; ?>" disabled/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Konu:</label>
    </div>
    <div class="col-75">
      <input type="text" name="form_konu" value="<?php echo $form_konu;?>" disabled/>
    </div>
  </div>

  <div class="row">
    <div class="col-25">
      <label>İçerik:</label>
    </div>
    <div class="col-75">
      <span>
        <!-- Create a tag that we will use as the editable area. -->
      <!-- You can use a div tag as well. -->
      <textarea id="icerik" name="form_icerik" maxlength="1000"><?php echo $form_icerik; ?></textarea>
      </span>
    </div>
  </div>
  
</div>
</form>



<?php

   if ($_SESSION["employee_type"] == "1")
   {
    
    echo "<table id='customers'>
          <caption>Dosyalar</caption>";
    $sql_query = "SELECT * FROM files WHERE circular_list_id=".$circular_list_id;
        $result = $conn->query($sql_query);
        if ($result)
        {
          if ($result->num_rows > 0)
          {
              while ($row = $result->fetch_assoc())
            {
              $file_id = $row["files_id"];
              echo "<tr>
              <td><a href='download.php?fileid=$file_id' id='saveFileData'>". $row["file_name"]."</a></td></tr>";
            }
          }
          mysqli_free_result($result);
        }

    echo "</table>";

    echo "<form id='userForm'>
          <div><input type='hidden' name='circular_list_id' value='$circular_list_id'/></div>
          <div><button class='new_button' type='submit'>Okudum</button></div>
      </form>";
   }
   else
   {
		  echo "<table id='customers'>
            <caption>Atananlar</caption>
		        <tr>
  		      <th>Atanan</th>
  		      <th>Durumu</th>
		        </tr>";

   		$sql_query = "SELECT * FROM employee_circular_list,users WHERE users.user_id=employee_circular_list.employee_circular_id AND circular_list_id=".$circular_list_id;
        $result = $conn->query($sql_query);
        if ($result)
        {
          if ($result->num_rows > 0)
          {
            while ($row = $result->fetch_assoc())
            {
              echo "<tr>
              <td>". $row["name"]."</td>";
              
              switch ($row["state"]) {
              case 0:
                echo "<td>Okundu</td>";
                  break;
              case 1:
                  echo "<td>Açık</td>";
                  break;
              }
              echo "</tr>";
            }
          }
          mysqli_free_result($result);
        }
        else
        {
          //save the error msg
            $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_ERROR.",'".mysqli_error($conn)."','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
        }

        echo "</table>";
   }
?>

<br/>

    </div><!--  İçerik -->
     
    <div class="clear"></div>
     
    <div class="footer">
    </div><!-- Alt bilgi -->

    <!-- where the response will be displayed -->
    <div id='response'></div>
 
</div><!-- wrap bütün sütunları, satırları sar -->


</body>
</html>

<script>
$(document).ready(function(){
    $('#userForm').submit(function(){
     
        // show that something is loading
        //$('#response').html("<b>Loading response...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: 'circular_list_done.php', 
            data: $(this).serialize()
        })
        .done(function(data){
             
            // show the response
            $('#response').html(data);
             
        })
        .fail(function() {
         
            // just in case posting your form failed
            alert( "Posting failed." );
             
        });
 
        // to prevent refreshing the whole page page
        return false;
 
    });
});
</script>

<!-- Initialize the editor. -->
<script>
  new FroalaEditor('textarea');
</script>

 <?php

 //close the db connection end of the file
  $conn->close();
 ?>