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

//$server = $_SERVER['SERVER_NAME'];


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
$fp=fopen("sol.txt","w+");
$query = ("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'agent_vipra2' AND TABLE_NAME = 'places'") ;
$stmt = $conn->prepare($query);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
$info1="$".$row."\$row['".$row."']";
fwrite($fp,$info);
fclose($fp);
}
