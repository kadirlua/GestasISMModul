<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-İŞ DETAYI</title>
<link rel="shortcut icon" type="image/ico" href="img/gestas.ico"/>
<link rel="stylesheet" href="css/form.css" />
<link rel="stylesheet" href="css/style.css" />
<link rel="stylesheet" href="css/bootstrap.min.css" />
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
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

$work_id = $_GET["workid"];

if ($query_result = $conn->query("SELECT * FROM work_list,work,users,category,boat WHERE boat.boat_id=work_list.boat_id AND category.category_id=work_list.category_id AND users.user_id=work_list.who_created AND work.work_list_id=work_list.work_list_id AND work.work_id='".$work_id."'"))
{
  if ($query_result)
  {
    if ($query_result->num_rows > 0)
    {
      $query_row = $query_result->fetch_assoc();
      if ($query_row)
      {
        $form_kodu = $query_row["form_code"];
        $is_kodu = $query_row["work_code"];
        $olusturan = $query_row["name"];
        $is_detayi = $query_row["work_desc"];
        $kategori = $query_row["category_name"];
        $gemi_adi = $query_row["boat_name"];
        $olusturma_tarihi = $query_row["created_date"];
        $olusturan = $query_row["name"];
        $work_list_id = $query_row["work_list_id"];
        
        switch ($query_row["work_state"]) {
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
         <form id='İslemeAl'>
          <div><input type='hidden' name='isleme_al' value="<?php echo $work_id;?>"/></div>
          <div><button class="new_button" type='submit'>İşleme Al</button></div>
          </form>

          <form id='Tamamla'>
          <div><input type='hidden' name='tamamla' value="<?php echo $work_id;?>"/></div>
          <div><button class="new_button" type='submit'>Tamamla</button></div>
          </form>
      </div>

<form method="POST">
<div class="container-new">
	<div class="row">
    <div class="col-25">
      <label>Bağlı olduğu iş listesi:</label>
    </div>
    <div class="col-75">
      <input type="text" name="durum" value="<?php echo $form_kodu; ?>" readonly/>
    </div>
  </div>

<div class="row">
    <div class="col-25">
      <label>İş Kodu:</label>
    </div>
    <div class="col-75">
      <input type="text" name="durum" value="<?php echo $is_kodu; ?>" readonly/>
    </div>
  </div>

  <div class="row">
    <div class="col-25">
      <label>İş Durumu:</label>
    </div>
    <div class="col-75">
      <input type="text" name="durum" value="<?php echo $state; ?>" readonly/>
    </div>
  </div>

  <div class="row">
    <div class="col-25">
      <label>Oluşturan:</label>
    </div>
    <div class="col-75">
      <input type="text" name="olusturan" value="<?php echo $olusturan; ?>" readonly/>
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
      <input type="text" name="kategori" value="<?php echo $kategori; ?>" readonly/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Gemi Adı:</label>
    </div>
    <div class="col-75">
      <input type="text" name="gemi_adi" value="<?php echo $gemi_adi; ?>" readonly/>
    </div>
  </div>
  
  <div class="row">
    <div class="col-25">
      <label for="subject">Oluşturma Tarihi:</label>
    </div>
    <div class="col-75">
      <input type="date" name="olusturma_tarihi" value="<?php echo $olusturma_tarihi;?>" readonly/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>İş Detayı:</label>
    </div>
    <div class="col-75">
      <textarea readonly><?php echo $is_detayi;?></textarea>
    </div>
  </div>
  
</div>
</form>

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
    $('#Tamamla').submit(function(){
     
        // show that something is loading
        //$('#response').html("<b>Loading response...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: 'work_update.php', 
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

$(document).ready(function(){
    $('#İslemeAl').submit(function(){
     
        // show that something is loading
        //$('#response').html("<b>Loading response...</b>");
         
        /*
         * 'post_receiver.php' - where you will pass the form data
         * $(this).serialize() - to easily read form data
         * function(data){... - data contains the response from post_receiver.php
         */
        $.ajax({
            type: 'POST',
            url: 'work_update.php', 
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