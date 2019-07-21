<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-YENİ İŞ LİSTESİ</title>
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

if ($_POST)
{
  $form_adi = $_POST["form_adi"];
  $kategori = $_POST["kategori"];
  $gemi_adi = $_POST["gemi_adi"];
  $olusturma_tarihi = $_POST["olusturma_tarihi"];
  $employee_id = $_SESSION["employee_id"];
  $result = $conn->query("SELECT category_name FROM category WHERE category_id='".$kategori."'");
  $form_code = "";
  if ($result)
  {
    if ($result->num_rows > 0)
    {
      $row = $result->fetch_assoc();
      $form_code = $row["category_name"]."-2019-01";
    }
    mysqli_free_result($result);
  }

  $sql_query = "INSERT INTO work_list VALUES(NULL, '$form_adi','".$form_code."', 1,$kategori,$gemi_adi,'$olusturma_tarihi',$employee_id);";
    if ($conn->query($sql_query))
    {
        $last_insert_id = mysqli_insert_id($conn);
        // Check if any option is selected
        if(isset($_POST["is_tanimi"]))  
        { 
            $work_code = $form_code;
            $work_index = 1;
            // Retrieving each selected option 
            foreach ($_POST["is_tanimi"] as $is_tanimi)
            {
              $work_str_index = strval($work_index);
              $form_code = $work_code."-".$work_str_index;
              $sql_query = "INSERT INTO work VALUES(NULL,'".$form_code."','İş ".$work_str_index."',1,'$is_tanimi', $employee_id, $last_insert_id);";
              $conn->query($sql_query);
              $work_index++;
            }
        }

        if(isset($_POST["atanan"]))  
        { 
            // Retrieving each selected option 
            foreach ($_POST["atanan"] as $atanan)
            {
              $conn->query("INSERT INTO employee_list VALUES(NULL,$last_insert_id, $atanan);");
            }
        }
        
        //save the log msg
        $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'Yeni iş listesi başarıyla veritabanına kaydedildi.','".date('Y-m-d')."',$employee_id);");
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
    	<h2>Yeni İş Listesi Ekle</h2>

<form method="POST">
<div class="container-new">
  
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
      <label>Kategori:</label>
    </div>
    <div class="col-75">
      <select name="kategori" required>
        <?php
        $category_arr = $conn->query("SELECT * FROM category");
        if ($category_arr)
        {
          if(mysqli_num_rows($category_arr))  {
              while($row = mysqli_fetch_assoc($category_arr))
              {
                echo "<option value=\"".$row['category_id']."\">".$row['category_name']."</option>";
              }
            }
          mysqli_free_result($category_arr);
        }
        ?>
      </select>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Gemi Adı:</label>
    </div>
    <div class="col-75">
      <select name="gemi_adi" required>
        <?php
        $boat_arr = $conn->query("SELECT * FROM boat");
        if ($boat_arr)
        {
          if(mysqli_num_rows($boat_arr))  {
              while($row = mysqli_fetch_assoc($boat_arr))
              {
                echo "<option value=\"".$row['boat_id']."\">".$row['boat_name']."</option>";
              }
            }
          mysqli_free_result($boat_arr);
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
      <label>Form Adı:</label>
    </div>
    <div class="col-75">
      <input type="text" name="form_adi" required/>
    </div>
  </div>
  <br />
	<div class="form-group">  
         <form name="add_name" id="add_name">  
              <div class="table-responsive">  
                   <table class="table table-bordered" id="dynamic_field">  
                        <tr>  
                          <td><button type="button" name="add" id="add" class="btn btn-success">İş Ekle</button></td>
                          <td></td>  
                        </tr>  
                   </table>
              </div>  
         </form>  
    </div>

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
      var i=document.getElementsByName("is_tanimi[]").length; 
      $('#add').click(function(){  
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="text" name="is_tanimi[]" maxlength="500" placeholder="İş Tanımı" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
 });  
 </script>

 <?php
 //close the db connection end of the file
  $conn->close();
 ?>