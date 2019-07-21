<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-YENİ TAMİM/SİRKÜLER OLUŞTURMA</title>
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

<style type="text/css">
  
.upload-btn-wrapper {
  position: relative;
  overflow: hidden;
  display: inline-block;
}

.btn {
  border: 2px solid gray;
  color: gray;
  background-color: white;
  padding: 8px 20px;
  border-radius: 8px;
  font-size: 20px;
  font-weight: bold;
}

.upload-btn-wrapper input[type=file] {
  font-size: 100px;
  position: absolute;
  left: 0;
  top: 0;
  opacity: 0;
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

if ($_POST)
{
  $form_tipi = $_POST["form_tipi"];
  $yayinlayan_departman = $_POST["yayinlayan_departman"];
  $form_konu = $_POST["form_konu"];
  $form_icerik = $_POST["form_icerik"];
  $olusturma_tarihi = $_POST["olusturma_tarihi"];
  $employee_id = $_SESSION["employee_id"];

  $form_no = "T01-01";
  if ($form_tipi == "1")
    $form_no = "S01-01";

  $form_icerik = htmlentities ($form_icerik);
  
  $sql_query = "INSERT INTO circular_list VALUES(NULL, '$form_no',$form_tipi,1,'".$employee_id."',$yayinlayan_departman,'$olusturma_tarihi','$form_konu','$form_icerik');";
    if ($conn->query($sql_query))
    {
        $last_insert_id = mysqli_insert_id($conn);
        // Check if any option is selected
        if(isset($_POST["atanan"]))  
        { 
            // Retrieving each selected option 
            foreach ($_POST["atanan"] as $atanan)
            {
              $conn->query("INSERT INTO employee_circular_list VALUES(NULL,1,$last_insert_id, $atanan);");
            }
        }

        if($_FILES["dosyalar"])  
        { 
          $file_ary = reArrayFiles($_FILES["dosyalar"]);

          foreach ($file_ary as $file) {
            if ($file['name'] != "")
            {
              $file_name = mysqli_real_escape_string($conn, $file['name']);
              $file_data = addslashes(file_get_contents($file["tmp_name"]));
              $file_type = $file["type"];
              $sql_query = "INSERT INTO files VALUES(NULL,'{$file_data}','$file_type','$file_name',$last_insert_id);";
              $conn->query($sql_query);
            }
          }
        }
        //save the log msg
        $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'Tamim/Sirküler listesi başarıyla veritabanına kaydedildi.','".date('Y-m-d')."',$employee_id);");
    }
    else
    {
      //save the error msg
      $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_ERROR.",'".mysqli_error($conn)."','".date('Y-m-d')."',$employee_id);");
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
    	<h2>Yeni Tamim/Sirküler Oluşturma</h2>

<form enctype="multipart/form-data" method="POST">
<div class="container-new">
  
  <div class="row">
    <div class="col-25">
      <label>Form Tipi:</label>
    </div>
    <div class="col-75">
      <select name="form_tipi" required>
        <option value="0">Tamim</option>
        <option value="1">Sirküler</option>
      </select>
    </div>
  </div>

  <div class="row">
    <div class="col-25">
      <label>Oluşturan:</label>
    </div>
    <div class="col-75">
      <input type="text" name="olusturan" value="<?php echo $_SESSION["name"]; ?>" readonly/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Atanan:</label>
    </div>
    <div class="col-75">
    	<select name="atanan[]" multiple required>
        <?php
        $employee_arr = $conn->query("SELECT * FROM users WHERE user_type='1'");
        if ($employee_arr)
        {
          if(mysqli_num_rows($employee_arr))  {
              while($row = mysqli_fetch_assoc($employee_arr))
              {
                echo "<option value=\"".$row['user_id']."\">".$row['name']."</option>";
              }
            }
          mysqli_free_result($employee_arr);
        }
        ?>
    	</select>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Yayınlayan Departman:</label>
    </div>
    <div class="col-75">
      <select name="yayinlayan_departman" required>
        <?php
        $department_arr = $conn->query("SELECT * FROM department");
        if ($department_arr)
        {
          if(mysqli_num_rows($department_arr))  {
              while($row = mysqli_fetch_assoc($department_arr))
              {
                echo "<option value=\"".$row['department_id']."\">".$row['department_name']."</option>";
              }
            }
          mysqli_free_result($department_arr);
        }
        ?>
      </select>
    </div>
  </div>
  
  <div class="row">
    <div class="col-25">
      <label for="subject">Oluşturma Tarihi:</label>
    </div>
    <div class="col-75">
      <input type="date" name="olusturma_tarihi" value="<?php echo date('Y-m-d'); ?>" readonly/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Konu:</label>
    </div>
    <div class="col-75">
      <input type="text" name="form_konu" maxlength="500" required/>
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
      <textarea id="icerik" name="form_icerik" maxlength="1000"></textarea>
      </span>
    </div>
  </div>

  <br />
  <div class="upload-btn-wrapper">
    <button class="btn" id="dosyaekle" >Dosya Ekle</button>
    <input type="file" name="dosyalar[]" id="dosya" multiple/>
  </div>

  <div id="fileResult"></div>

  <div class="row">
  	<input type="submit" value="Kaydet"/>
    <input type="button" onclick="javascript:history.back(-1);" value="Geri"/>
  </div>
  
</div>
</form>
    </div><!--  İçerik -->
     
    <div class="clear"></div>
     
    <div class="footer">
    </div><!-- Alt bilgi -->
 
</div><!-- wrap bütün sütunları, satırları sar -->


</body>
</html>

<script>  

 $(document).ready(function(){
        $('input[type="file"]').change(function(){
            var file_list = document.getElementById("dosya");
            
            $('#fileResult').empty();

            for (var i = 0; i < file_list.files.length; i++)
            {
              var file = file_list.files[i];
              
              $('#fileResult').append('<tr><td><label class="form-control name_list">'+file.name+'</label></td></tr>')
            }
            
        });
    }); 
 </script>

 <!-- Initialize the editor. -->
<script>
  new FroalaEditor('textarea');
</script>

 <?php

 function reArrayFiles(&$file_post) {

    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i=0; $i<$file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

 //close the db connection end of the file
  $conn->close();
 ?>