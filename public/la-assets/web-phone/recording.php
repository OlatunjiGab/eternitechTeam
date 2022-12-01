<?php   

 function handlevoicefile()
{		
  					
	$target_dir =  dirname(__DIR__, 2).'/storage/app/public/'; //target directory. make sure that PHP has write permission to this folder!!!
	$target_file = $target_dir . basename($_REQUEST['web_projectID']."_".$_FILES["file"]["name"]);	//this is the file name suggested by the webhone (normalize/sanitize it or use a file name generetad by you instead of this)
	
	if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) //the most important code line
	{
		echo "OK: The file ". basename( $_FILES["file"]["name"]). " has been uploaded successfully.";
	
	$servername = "crmeternitechdb.cnqirr1humw0.eu-west-1.rds.amazonaws.com";
	$username = "crm_root";
	$password = "crm_eternitech";
	$dbname = "crm_eternitech";
	// Create connection
	$conn = new mysqli($servername, $username, $password, $dbname);
	// Check connection
	if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
	}
   
    $calllog = "Type : Call-log <br>".$_REQUEST['lstcalldata']."<i class='fa fa-download' aria-hidden='true'></i> Download recording: <a href='https://crm.eternitech.com/storage/".$_REQUEST['web_projectID']."_".$_FILES["file"]["name"]."' download> download </a>";

	$sql = "INSERT INTO project_messages (project_id, message,channel)VALUES ('".$_REQUEST['web_projectID']."','" . mysqli_real_escape_string($conn,$calllog) . "','call-log')";

		if ($conn->query($sql) === TRUE) {
		echo "New record created successfully";
		} else {
		echo "Error: " . $sql . "<br>" . $conn->error;
		}

	} 
	else 
	{
		echo "ERROR: there was an error uploading your file tmp:".basename( $_FILES["file"]["name"])."dst:".basename( $_FILES["file"]["name"]);
	}				
}
	handlevoicefile();	
?>