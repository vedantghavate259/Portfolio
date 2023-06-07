<?php
//$con=mysqli_connect("localhost","root","","agent_vipra");
// database credentials
$db_host      = 'localhost';
$db_name      = 'agent_vipra2';
$db_user      = 'root';
$db_user_pass = '';

// base url, where the script will be installed
// *please do not include a trailing slash*
//$baseurl = 'http://110.227.249.167:9010/dirlist';
//$baseurl = 'http://here.instadatasystems.com:9010/dirlist';

$server = $_SERVER['SERVER_NAME'];


$dsn = 'mysql:host=' . $db_host . ';dbname=' . $db_name;

// Create PDO object
$options = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4');
try {
	$conn = new PDO($dsn, $db_user, $db_user_pass, $options);
	// setAttribute(ATTRIBUTE, OPTION);
	// default is silent error mode. Changing to throw exceptions
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	// Leave column names as returned by the database driver. Some PDO extensions return them in uppercase
	$conn->setAttribute(PDO::ATTR_CASE, PDO::CASE_NATURAL);
	// This is so as to use native prepare, which doesn't have problems with numeric params in LIMIT clause
	$conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
}
catch(PDOException $e) {
	echo "<h2>Error</h2>";
	echo nl2br(htmlspecialchars($e->getMessage()));
	exit();
}


$query="SELECT place_id,filename from photos";
$stmt = $conn->prepare($query);
$stmt->execute();
$src="C:/xampp/htdocs/agent2/place_pic_full/1";
$mainpath="C:/xampp/htdocs/agent2/place_pic_full/images";
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
	$dir = NULL;
	$des = NULL;
$place_id     = $row['place_id'];
$filename   = $row['filename'];
//echo $place_id.' ';
//echo $filename.'<br>';
	//die();
	//$filepath=$filename;
	$placep=$place_id;
	$srcimg=$src.'/'.$filename;
	//echo $srcimg.'<br>';
	$dir=$mainpath.'/'.$placep;
	$des=$dir.'/'.$filename;
	echo $dir."<br>";
	if(is_dir($dir) ===false )
	{
		mkdir($dir);
		echo "if part.<br>";
		if (!copy($srcimg,$des))
		{ 
			echo "File cannot be copied! \n"; 
		} 
		else
		{ 
			echo "File has been copied!"; 
		} 
	}
	else
	{
		copy($srcimg,$des);
		echo "else part.<br>";
	}
}
?>