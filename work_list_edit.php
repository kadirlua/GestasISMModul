<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-İŞ LİSTESİ DÜZENLE</title>
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

$work_list_id = $_GET["worklistid"];

include("log_level.php");
include("connect.php");

$query_result = $conn->query("SELECT * FROM work_list,users,category,boat WHERE boat.boat_id=work_list.boat_id AND category.category_id=work_list.category_id AND users.user_id=work_list.who_created AND work_list.work_list_id='".$work_list_id."'");
if ($query_result)
{
  if ($query_result->num_rows > 0)
  {
    $query_row = $query_result->fetch_assoc();
    if ($query_row)
    {
      $form_adi = $query_row["form_name"];
      $kategori_id = $query_row["category_id"];
      $gemi_id = $query_row["boat_id"];
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

    	<h2>İş Listesi Düzenle</h2>

<form id="islistesiGuncelle">
<div class="container-new">
  <div><input type="hidden" name="is_durumu" value="<?php echo $is_durumu;?>" readonly></input></div>
  <div><input type="hidden" name="work_list_id" value="<?php echo $work_list_id;?>"/></div>
  <div class="row">
    <div class="col-25">
      <label>İş Listesi Durumu:</label>
    </div>
    <div class="col-75">
      <input type="text" name="durum" value="<?php echo $state;?>" readonly/>
    </div>
  </div>

  <div class="row">
    <div class="col-25">
      <label>Oluşturan:</label>
    </div>
    <div class="col-75">
      <input type="text" name="olusturan" value="<?php echo $olusturan;?>" readonly/>
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
          if($employee_arr->num_rows > 0)  {
              while($row = mysqli_fetch_assoc($employee_arr))
              {
                $is_equal = false;
                $result = $conn->query("SELECT * FROM employee_list WHERE work_list_id=".$work_list_id);
                if ($result)
                {
                  while ($result_row = $result->fetch_assoc())
                  {
                      if ($row["user_id"] == $result_row["employee_id"])
                      {
                        $is_equal = true;
                        break;
                      }
                  }
                  mysqli_free_result($result);
                }
                
                if ($is_equal)
                {
                  echo "<option value=\"".$row['user_id']."\" selected>".$row['name']."</option>";
                }
                else
                {
                  echo "<option value=\"".$row['user_id']."\">".$row['name']."</option>";
                }
                
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
              	if ($kategori_id == $row["category_id"])
              	{
              		echo "<option value=\"".$row['category_id']."\" selected>".$row['category_name']."</option>";
              	}
              	else
              	{
              		echo "<option value=\"".$row['category_id']."\">".$row['category_name']."</option>";
              	}
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
                if ($gemi_id == $row["boat_id"])
              	{
              		echo "<option value=\"".$row['boat_id']."\" selected>".$row['boat_name']."</option>";
              	}
              	else
              	{
              		echo "<option value=\"".$row['boat_id']."\">".$row['boat_name']."</option>";
              	}
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
      <input type="date" name="olusturma_tarihi" value="<?php echo $olusturma_tarihi; ?>" readonly/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Form Adı:</label>
    </div>
    <div class="col-75">
      <input type="text" name="form_adi" value="<?php echo $form_adi;?>" required/>
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
                        <?php
                        $result = $conn->query("SELECT * FROM work WHERE work_list_id='".$work_list_id."'");
                      		if ($result)
                      		{
                            if ($result->num_rows > 0)
                            {
                              $row_index = 1;
                               while ($row = $result->fetch_assoc())
                               {
                                $row_str_index = strval($row_index);
                                echo '<tr id="row'.$row_str_index.'"><td><input type="hidden" name="isler[]" value="'.$row["work_id"].'" readonly/><input type="text" name="is_tanimi[]" maxlength="500" placeholder="İş Tanımı" class="form-control name_list" value="'.$row["work_desc"].'"/></td><td><button type="button" name="remove" id="'.$row_str_index.'" class="btn btn-danger btn_remove">X</button></td></tr>';
                                $row_index++;
                               }
                            }
                             mysqli_free_result($result);
                      		}
                        ?>
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

    <!-- where the response will be displayed -->
    <div id='response'></div>
 
</div><!-- wrap bütün sütunları, satırları sar -->


</body>
</html>

<script>  
 $(document).ready(function(){  
      var i=document.getElementsByName("is_tanimi[]").length;
      $('#add').click(function(){
           i++;  
           $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="hidden" name="isler[]" value="-1" readonly/><input type="text" name="is_tanimi[]" maxlength="500" placeholder="İş Tanımı" class="form-control name_list" /></td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>');  
      });  
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
 });  

$(document).ready(function(){
    $('#islistesiGuncelle').submit(function(){
        $.ajax({
            type: 'POST',
            url: 'work_list_update.php', 
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

window.onbeforeunload = function (e) {
    var message = "Are you sure ?";
    var firefox = /Firefox[\/\s](\d+)/.test(navigator.userAgent);
    if (firefox) {
        //Add custom dialog
        //Firefox does not accept window.showModalDialog(), window.alert(), window.confirm(), and window.prompt() furthermore
        var dialog = document.createElement("div");
        document.body.appendChild(dialog);
        dialog.id = "dialog";
        dialog.style.visibility = "hidden";
        dialog.innerHTML = message;
        var left = document.body.clientWidth / 2 - dialog.clientWidth / 2;
        dialog.style.left = left + "px";
        dialog.style.visibility = "visible";
        var shadow = document.createElement("div");
        document.body.appendChild(shadow);
        shadow.id = "shadow";
        //tip with setTimeout
        setTimeout(function () {
            document.body.removeChild(document.getElementById("dialog"));
            document.body.removeChild(document.getElementById("shadow"));
        }, 0);
    }
    return message;
};

 </script>

<?php
 //close the db connection end of the file
  $conn->close();
 ?>