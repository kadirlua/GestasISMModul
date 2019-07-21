<?php

ob_start();
session_start();
if (!$_SESSION["login"])
{
	header("Location:index.php");
}

if ($_POST["is_durumu"] == "0")
{
    echo "<script>alert('Durumu kapalı olan iş listesi güncellenemez!.');</script>";
}
else
{
    include("log_level.php");
    include("connect.php");
    $work_list_id = $_POST["work_list_id"];
    $result = $conn->query("UPDATE work_list SET form_name='".$_POST["form_adi"]."',category_id='".$_POST["kategori"]."', boat_id='".$_POST["gemi_adi"]."' WHERE work_list_id=". (int) $work_list_id);

    if(isset($_POST["atanan"]))  
    {
        $sql_result = $conn->query("SELECT * FROM employee_list WHERE work_list_id=".(int)$work_list_id);
        if ($sql_result)
        {
            while ($row = $sql_result->fetch_assoc())
            {
                $bFound = false;
                foreach ($_POST["atanan"] as $atanan)
                {
                    if ((int) $row["employee_id"] == $atanan)
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
                    $conn->query("DELETE FROM employee_list WHERE employee_list_id=".(int) $row["employee_list_id"]);
                }
            }
            mysqli_free_result($sql_result);
        }

        // Retrieving each selected option 
        foreach ($_POST["atanan"] as $atanan)
        {
            $sql_result = $conn->query("SELECT * FROM employee_list WHERE work_list_id=".(int)$work_list_id." AND employee_id=".(int)$atanan);
            if ($sql_result)
            {
                if ($sql_result->num_rows <= 0)
                {
                    $conn->query("INSERT INTO employee_list VALUES(NULL,$work_list_id, $atanan);");
                }
                mysqli_free_result($sql_result);
            }
        }
    }
    else
    {
        $conn->query("DELETE FROM employee_list WHERE work_list_id=".(int) $work_list_id);
    }

    if(isset($_POST["is_tanimi"]))  
    {
        $sql_result = $conn->query("SELECT * FROM work WHERE work_list_id=".(int)$work_list_id);
        if ($sql_result)
        {
            while ($row = $sql_result->fetch_assoc())
            {
                $bFound = false;
                foreach ($_POST["isler"] as $isler)
                {
                    if ((int) $row["work_id"] == $isler)
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
                    $conn->query("DELETE FROM work WHERE work_id=".(int) $row["work_id"]);
                }
            }
            mysqli_free_result($sql_result);
        }


        $sql_result = $conn->query("SELECT * FROM work_list WHERE work_list_id=".(int)$work_list_id);
        if ($sql_result)
        {
            if ($sql_result->num_rows > 0)
            {
                $sql_row = $sql_result->fetch_assoc();
            }
            mysqli_free_result($sql_result);
        }

        $arr_count = count($_POST["is_tanimi"]);

        // Retrieving each selected option 
        for ($i = 0; $i < $arr_count; $i++)
        {
            if ($_POST["isler"][$i] != "-1")
            {
                $conn->query("UPDATE work SET work_desc='".$_POST["is_tanimi"][$i]."' WHERE work_id=".(int)$_POST["isler"][$i]);
            }
            else
            {
                $conn->query("INSERT INTO work VALUES(NULL,'".$sql_row["form_code"]."','".$sql_row["form_name"]."',1,'".$_POST["is_tanimi"][$i]."','".$_SESSION["employee_id"]."',$work_list_id);");
            }
        }
    }
    else
    {
        $conn->query("DELETE FROM work WHERE work_list_id=".(int) $work_list_id);
    }

    if ($result)
    {
        echo "<script>alert('İş listesi başarıyla güncellendi');</script>";
        //save the log msg
        $conn->query("INSERT INTO log_msg VALUES(NULL,".ISM_LOG_SUCCESS.",'İş listesi başarıyla güncellendi','".date('Y-m-d')."',".$_SESSION["employee_id"].");");
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