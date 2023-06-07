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


$src="C:/Users/INSTADATA/Downloads/adddata.csv";
$filename=fopen($src,"r");
while(!feof($filename))
  {
    $file = fgetcsv($filename);

    $place_id = $file[0];
    $userid = 0;
    $lat = 0.00000000;
    $lng = 0.00000000;
    $place_name =ucwords(strtolower($file[3]));
    $house_no =ucwords(strtolower($file[4]));
    $road_name =ucwords(strtolower($file[5]));
    $area_name =ucwords(strtolower($file[6]));
    $main_area =ucwords(strtolower($file[7]));
    $cname =ucwords(strtolower($file[8]));
    $state_id =0;
    $city_id =0 ;
    $query="SELECT * from cities where city_name = '$cname' ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
    $city_id =$row['city_id'];
    $state_id =$row['state_id'];
    }
    $query="SELECT state_name from states where state_id = '$state_id' ";
    $stmt = $conn->prepare($query);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
    $state =$row['state_name'];
    }

    $postal_code =$file[10];
    $phone =$file[11];
    $other_phone_number =$file[12];
    $website =$file[13];
    $religion = ucwords(strtolower($file[14]));
    $language_spoken = ucwords(strtolower($file[15]));
    $j=0;
    for($i=0;$i<=8;$i++)
    {
        $sp=16+$i;
        if($file[$sp]=='Y')
        {
        $spec[$j]=$i;
        $j++;
        }
    }
    $specialities =implode(",",$spec);
    $other_specialities =ucwords($file[25]);

    $j=0;
    for($i=0;$i<=9;$i++)
    {
        $sp=26+$i;
        if($file[$sp]=='Y')
        {
        $pujaspec[$j]=$i+1;
        $j++;
        }
    }
    $puja_specialities =implode(",",$pujaspec);
    $other_puja_specialities =ucwords(strtolower($file[36]));

    $j=0;
    for($i=0;$i<=7;$i++)
    {
        $sp=37+$i;
        if($file[$sp]=='Y')
        {
        $astrospec[$j]=$i+1;
        $j++;
        }
    }
    $astrologer_specialities =implode(",",$astrospec);
    $other_astrologer_specialities  =ucwords(strtolower($file[45]));

    $j=0;
    for($i=0;$i<=4;$i++)
    {
        $sp=46+$i;
        if($file[$sp]=='Y')
        {
        $kathspec[$j]=$i+1;
        $j++;
        }
    }
    $kathavachak_specialities =implode(",",$kathspec);
    $other_kathavachak_specialities =ucwords(strtolower($file[51]));

    $query = "INSERT INTO places(
    place_id,
    userid,
    lat,
    lng,
    place_name,
    house_no,
    road_name,
    area_name,
    main_area,
    city_id,
    state_id,
    state,
    postal_code,
    phone,
    other_phone_number,
    religion,
    language_spoken,
    website,
    specialities,
    other_specialities,
    puja_specialities,
    other_puja_specialities,
    astrologer_specialities,
    other_astrologer_specialities,
    kathavachak_specialities,
    other_kathavachak_specialities)
    VALUES(
    :place_id,
    :userid,
    :lat,
    :lng,
    :place_name ,
    :house_no ,
    :road_name ,
    :area_name ,
    :main_area ,
    :city_id,
    :state_id,
    :state,
    :postal_code,
    :phone,
    :other_phone_number,
    :religion,
    :language_spoken,
    :website,
    :specialities,
    :other_specialities,
    :puja_specialities,
    :other_puja_specialities,
    :astrologer_specialities,
    :other_astrologer_specialities,
    :kathavachak_specialities,
    :other_kathavachak_specialities)";
    $stmt = $conn->prepare($query);
    $stmt->bindValue(':place_id', $place_id);
    $stmt->bindValue(':userid', $userid);
    $stmt->bindValue(':lat', $lat);
	$stmt->bindValue(':lng', $lng);
	$stmt->bindValue(':place_name', $place_name);
	$stmt->bindValue(':house_no', $house_no);
	$stmt->bindValue(':road_name', $road_name);
	$stmt->bindValue(':area_name', $area_name);
	$stmt->bindValue(':main_area', $main_area);
	$stmt->bindValue(':city_id', $city_id);
	$stmt->bindValue(':state_id', $state_id);
	$stmt->bindValue(':state', $state);
	$stmt->bindValue(':postal_code', $postal_code);
	$stmt->bindValue(':phone', $phone);
	$stmt->bindValue(':other_phone_number', $other_phone_number);
	$stmt->bindValue(':religion', $religion);
	$stmt->bindValue(':language_spoken', $language_spoken);
	$stmt->bindValue(':website', $website);
    $stmt->bindValue(':specialities', $specialities);
    $stmt->bindValue(':other_specialities', $other_specialities);
    $stmt->bindValue(':puja_specialities', $puja_specialities);
    $stmt->bindValue(':other_puja_specialities', $other_puja_specialities);
    $stmt->bindValue(':astrologer_specialities', $astrologer_specialities);
    $stmt->bindValue(':other_astrologer_specialities', $other_astrologer_specialities);
    $stmt->bindValue(':kathavachak_specialities', $kathavachak_specialities);
    $stmt->bindValue(':other_kathavachak_specialities', $other_kathavachak_specialities);
    $stmt->execute();
}
?>