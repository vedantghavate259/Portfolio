<?php
//$con=mysqli_connect("localhost","root","","agent_vipra");
// database credentials
$db_host      = 'localhost';
$db_name      = 'viprabharat1';
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


$src="C:/Users/INSTADATA/Downloads/places2.csv";
$filename=fopen($src,"r");
while(!feof($filename))
  {
    $file = fgetcsv($filename);
    $query= " UPDATE places set
    userid                           = '$file[1]',
    lat                              = '$file[2]',
    lng                              = '$file[3]',
    place_name                       = '$file[4]',
    house_no                         = '$file[5]',
    road_name                        = '$file[6]',
    area_name                        = '$file[7]',
    main_area                        = '$file[8]',
    city_id                          = '$file[9]',
    state_id                         = '$file[10]',
    state                            = '$file[11]',
    postal_code                      = '$file[12]',
    phone                            = '$file[13]',
    other_phone_number               = '$file[14]',
    religion                         = '$file[15]',
    language_spoken                  = '$file[16]',
    website                          = '$file[17]',
    description                      = '$file[18]',
    specialities                     = '$file[19]',
    other_specialities               = '$file[20]',
    puja_specialities                = '$file[21]',
    other_puja_specialities          = '$file[22]',
    astrologer_specialities          = '$file[23]',
    other_astrologer_specialities    = '$file[24]',
    product                          = '$file[25]',
    other_product                    = '$file[26]'
    WHERE  place_id = $file[0] ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
}
?>