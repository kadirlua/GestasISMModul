<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>GESTAŞ-VIYA-TAMİM/SİRKÜLER DÜZENLEME</title>
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

</head>
<body>
<?php
ob_start();
session_start();
if (!$_SESSION["login"])
{
	header("Location:index.php");
}

$circular_list_id = $_GET["circularlistid"];

include("log_level.php");
include("connect.php");

$query_result = $conn->query("SELECT * FROM circular_list,users,department WHERE department.department_id=circular_list.department_id AND users.user_id=circular_list.who_created AND circular_list.circular_list_id='".$circular_list_id."'");

if ($query_result)
{
  if ($query_result->num_rows > 0)
  {
    $query_row = $query_result->fetch_assoc();
    if ($query_row)
    {
      $form_tipi = $query_row["form_type"];
      $departman_id = $query_row["department_id"];
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

    	<h2>Tamim/Sirküler Düzenle</h2>

<form id="TamimlistesiGuncelle">
<div class="container-new">
  <div><input type="hidden" name="form_durumu" value="<?php echo $form_durumu;?>" readonly></input></div>
  <div><input type="hidden" name="circular_list_id" value="<?php echo $circular_list_id;?>"/></div>

  <div class="row">
    <div class="col-25">
      <label>Form Tipi:</label>
    </div>
    <div class="col-75">
      <select name="form_tipi" required>
        <?php
          if ($form_tipi == "0")
          {
            echo "<option value='0' selected>Tamim</option>
                  <option value='1'>Sirküler</option>";
          }
          else
          {
            echo "<option value='0'>Tamim</option>
                  <option value='1' selected>Sirküler</option>";
          }
        ?>
      </select>
    </div>
  </div>

  <div class="row">
    <div class="col-25">
      <label>Form Durumu:</label>
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
      <label>Departman Adı:</label>
    </div>
    <div class="col-75">
      <select name="departman_adi" required>
        <?php
        $department_arr = $conn->query("SELECT * FROM department");
        if ($department_arr)
        {
          if(mysqli_num_rows($department_arr))  {
              while($row = mysqli_fetch_assoc($department_arr))
              {
                if ($departman_id == $row["department_id"])
                {
                  echo "<option value=\"".$row['department_id']."\" selected>".$row['department_name']."</option>";
                }
                else
                {
                  echo "<option value=\"".$row['department_id']."\">".$row['department_name']."</option>";
                }
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
      <label>Oluşturma Tarihi:</label>
    </div>
    <div class="col-75">
      <input type="date" name="olusturma_tarihi" value="<?php echo $olusturma_tarihi; ?>" readonly/>
    </div>
  </div>
  <div class="row">
    <div class="col-25">
      <label>Konu:</label>
    </div>
    <div class="col-75">
      <input type="text" name="form_konu" value="<?php echo $form_konu;?>" required/>
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
  <br />
	<div class="form-group">  
         <form name="add_name" id="add_name"> 
          <button type="button" name="add" id="add" class="btn btn-success">Yeni Atama Ekle</button> 
              <div class="table-responsive">  
                   <table class="table table-bordered" id="dynamic_field">

                        <tr>  
                          <th>Atanan</th>
                          <th>Durumu</th>
                          <th>İşlemler</th>  
                        </tr>
                        <?php
                        $result = $conn->query("SELECT * FROM employee_circular_list,users WHERE employee_circular_list.employee_circular_id=users.user_id AND circular_list_id=".$circular_list_id);
                        	
                    		if ($result)
                    		{
                          if ($result->num_rows > 0)
                          {
                            $row_index = 1;
                             while ($row = $result->fetch_assoc())
                             {
                              $row_str_index = strval($row_index);

                              switch ($row["state"]) {
                                case 0:
                                  $user_state = "Okundu";
                                    break;
                                case 1:
                                  $user_state = "Açık";
                                    break;
                                }
                              echo '<tr id="row'.$row_str_index.'"><td><input type="hidden" name="atananlar[]" value="'.$row["employee_circular_id"].'" readonly/>'.$row["name"].'</td><td>'.$user_state.'</td><td><button type="button" name="remove" id="'.$row_str_index.'" class="btn btn-danger btn_remove">X</button></td></tr>';
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
    
      <?php
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
              <td><a href='download.php?fileid=$file_id' id='saveFileData'><label value='".$file_id."'>". $row["file_name"]."</label></a></td></tr>";
            }
          }
          mysqli_free_result($result);
        }

      echo "</table>";
    ?>

    <!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span class="close">&times;</span>
    <div class="row">
    <div class="col-75">
      <select id="atanan_personel" onchange="onSelectedUser()">
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
  </div>

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
      $(document).on('click', '.btn_remove', function(){  
           var button_id = $(this).attr("id");   
           $('#row'+button_id+'').remove();  
      });  
 });  

$(document).ready(function(){
    $('#TamimlistesiGuncelle').submit(function(){
        $.ajax({
            type: 'POST',
            url: 'circular_list_update.php', 
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

 <!-- Initialize the editor. -->
<script>
  new FroalaEditor('textarea');
</script>

<script type="text/javascript">
  // Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("add");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

function onSelectedUser() {
  var users = document.getElementById("atanan_personel");
  var name = users.options[users.selectedIndex].innerHTML;
  var val = users.value;
  modal.style.display = "none";

  var i= document.getElementsByName("atananlar[]").length;

  i++;
  $('#dynamic_field').append('<tr id="row'+i+'"><td><input type="hidden" name="atananlar[]" value="'+val+'" readonly/>'+name+'</td><td>Açık</td><td><button type="button" name="remove" id="'+i+'" class="btn btn-danger btn_remove">X</button></td></tr>'); 
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

</script>

<?php
 //close the db connection end of the file
  $conn->close();
 ?>