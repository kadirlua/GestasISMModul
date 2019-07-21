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
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>

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

$work_list_id = $_GET["worklistid"];

if ($query_result = $conn->query("SELECT * FROM work_list,users,category,boat WHERE boat.boat_id=work_list.boat_id AND category.category_id=work_list.category_id AND users.user_id=work_list.who_created AND work_list.work_list_id='".$work_list_id."'"))
{
  if ($query_result)
  {
    if ($query_result->num_rows > 0)
    {
      $query_row = $query_result->fetch_assoc();
      if ($query_row)
      {
        $form_adi = $query_row["form_name"];
        $kategori = $query_row["category_name"];
        $gemi_adi = $query_row["boat_name"];
        $olusturma_tarihi = $query_row["created_date"];
        $olusturan = $query_row["name"];
        $is_durumu = $query_row["state"];
        
        switch ($is_durumu) {
          case 0:
            $state = "Kapalı";
              break;
          case 1:
            $state = "Açık";
              break;
          case 2:
            $state = "İşlemde";
              break;
          case 3:
            $state = "Tamamlandı";
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
        <button class="new_button" type="button" onclick="window.location.href = '<?php echo "work_list_edit.php?worklistid=".$work_list_id;?>'">Düzenle</button>
        <!-- our form -->  
      <form id='userForm'>
          <div><input type="hidden" name="is_durumu" value="<?php echo $is_durumu;?>" readonly></input></div>
          <div><input type='hidden' name='work_list_id' value="<?php echo $work_list_id;?>"/></div>
          <div><button class="new_button" type='submit'>Kapat</button></div>
      </form>
      </div>

<form method="POST">
<div class="container-new">
  <div class="row">
    <div class="col-25">
      <label>İş Listesi Durumu:</label>
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
      <label>Atanan:</label>
    </div>
    <div class="col-75">
    	<select name="atanan[]" multiple required disabled>
        <?php
          $result = $conn->query("SELECT * FROM employee_list,users WHERE users.user_id=employee_list.employee_id AND work_list_id=".$work_list_id);
          if ($result)
          {
            while ($row = $result->fetch_assoc())
            {
                echo "<option value=\"".$row['employee_list_id']."\">".$row['name']."</option>";
            }
            mysqli_free_result($result);
          }
        ?>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Kategori:</label>
    </div>
    <div class="col-75">
      <input type="text" name="kategori" value="<?php echo $kategori; ?>" disabled/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Gemi Adı:</label>
    </div>
    <div class="col-75">
      <input type="text" name="gemi_adi" value="<?php echo $gemi_adi; ?>" disabled/>
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
      <label>Form Adı:</label>
    </div>
    <div class="col-75">
      <input type="text" name="form_adi" value="<?php echo $form_adi;?>" disabled/>
    </div>
  </div>
  
</div>
</form>

<table id="customers">
      <caption>İşler</caption>
      <tr>
          <th>İş no</th>
          <th>Adı</th>
          <th>Durum</th>
          <th>İş Bitim Tarihi</th>
       </tr>

       <?php

        $sql_query = "SELECT * FROM work, users WHERE users.user_id=work.who_created AND work.work_list_id=".$work_list_id;
        $result = $conn->query($sql_query);
        if ($result)
        {
          if ($result->num_rows > 0)
          {
            while ($row = $result->fetch_assoc())
            {
              $varname = "work_dashboard.php?workid=".$row["work_id"];
              echo "<tr>
              <td><a href='".$varname."'>". $row["work_code"]."</a></td>
              <td>". $row["work_name"]."</td>";
              
              switch ($row["work_state"]) {
              case 0:
                echo "<td>Kapalı</td>";
                  break;
              case 1:
                  echo "<td>Açık</td>";
                  break;
              case 2:
                  echo "<td>İşlemde</td>";
                  break;
              case 3:
                  echo "<td>Tamamlandı</td>";
                  break;
              }
              echo "<td>N/A</td>";
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

       ?>
</table>
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
            url: 'work_list_close.php', 
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

 <?php
 //close the db connection end of the file
  $conn->close();
 ?>