<?php

ob_start();
session_start();
if (!$_SESSION["login"])
{
	header("Location:index.php");
}

if ($_POST["form_durumu"] == "0")
{
    echo "<script>alert('Durumu kapalı olan Tamim/Sirküler listesi güncellenemez!.');</script>";
}
else
{
    include("log_level.php");
    include("connect.php");
    $circular_list_id = $_POST["circular_list_id"];

    $result = $conn->query("UPDATE circular_list SET form_type='".$_POST["form_tipi"]."',department_id='".$_POST["departman_adi"]."', subject='".$_POST["form_konu"]."',content='".$_POST["form_icerik"]."' WHERE circular_list_id=". (int) $circular_list_id);

    if(isset($_POST["atananlar"]))  
    {
        $sql_result = $conn->query("SELECT * FROM employee_circular_list WHERE circular_list_id=".(int)$circular_list_id);
        if ($sql_result)
        {
            while ($row = $sql_result->fetch_assoc())
            {
                $bFound = false;
                foreach ($_POST["atananlar"] as $atanan)
                {
                    if ((int) $row["employee_circular_id"] == $atanan)
                    {
                        $bFound = true;
                        break;
                    }
                }

                if ($bFound)
                {
                    //nothing to do
                }
                else
                {
                    $conn->query("DELETE FROM employee_circular_list WHERE employee_circular_list_id=".(int) $row["employee_circular_list_id"]);
                }
            }
            mysqli_free_result($sql_result);
        }

        // Retrieving each selected option 
        foreach ($_POST["atananlar"] as $atanan)
        {
            $sql_result = $conn->query("SELECT * FROM employee_circular_list WHERE circular_list_id=".(int)$circular_list_id." AND employee_circular_id=".(int)$atanan);
            if ($sql_result)
            {
                if ($sql_result->num_rows <= 0)
                {
                    $conn->query("INSERT INTO employee_circular_list VALUES(NULL,1,$circular_list_id, $atanan);");
                }
                mysqli_free_result($sql_result);
            }
        }
    }
    else
    {
        $conn->query("DELETE FROM employee_circular_list WHERE circular_list_id=".(int) $circular_list_id);
    }

    if ($result)
    {
        echo "<script>alert('Tamim/Sirküler listesi başarıyla güncellendi');</script>";
        //save the log msg
        $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'Tamim/Sirküler listesi başarıyla güncellendi','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
    }
    else
    {
        echo "<script>alert('".mysqli_error($conn)."');</script>";
        //save the error msg
        $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_ERROR.",'".mysqli_error($conn)."','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
    }
    $conn->close();
}
?>