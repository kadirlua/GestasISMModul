<?php

ob_start();
session_start();
if (!$_SESSION["login"])
{
	header("Location:index.php");
}

include("log_level.php");
include("connect.php");

if (isset($_GET["fileid"]))
{
	$file_id = $_GET["fileid"];
	$result = $conn->query("SELECT * FROM files WHERE files_id=".$file_id);
    if ($result)
    {
      if ($result->num_rows > 0)
      {
        $row = $result->fetch_assoc();
        if ($row)
        {
        	header('Content-Type: '.$row['content_type']);
    		header('Content-Disposition: attachment; filename="'.basename($row["file_name"]).'"');
    		echo $row["file"];
        }
      }
      mysqli_free_result($result);
    }
}

$conn->close();
?>