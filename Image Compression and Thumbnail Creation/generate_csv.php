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




$col_name = ["Place_ID","User_ID","User_name","Place_Name","House No","Road Name","Sub_Area","Main_Area",
"City","State","Postal Code","Phone No","Alt_Phone_No","Specialities","","","","","","","","","","Other_Specialities"
,"Puja Specialities","","","","","","","","","",
"Astrologer_Specialities","","","","","","","","Other Astrologer Specialities","kathavachak_specialities","","","",""
,"Other Kathavachak Specialites","product","other_product","submission_date","Plan","Temple Timings"];
$col_2 =["","","","","","","","","","","","","","Home_Opening","Vastu_Shanti",
"navagrah", "janma", "naam_karan","jawal", "threading","marriage","bhajan","last_rituals","","Satya_Narayan","Ganesh_Puja","Hawan_Yagna","Jagran","Lakshmi_Puja","Vaibhav_Lakshmi_Puja",
"Durga_Puja","Kali_Puja","Sashthi_Puja","Vishwakarma_Puja","janam_kundli","hororscope",
"Face_reading","hand_reading","gems_stones", "Kundli_dosh", "numerology_ankjyotish",
"vashi_kara","","shreemad_Bhagwatkatha","shivpuran_katha","Vishnu_purankatha","Devi_Bhagwatkatha",
"Ramayana_katha","","","","","","Morning_Opening_Time","Morning_Closing_Time","Evening_Opening_Time","Evening_Closing_Time","Aarti_Time","Other_Timing_Info"];
$curtime= date("Y-m-d");
$prevtime= date('Y-m-d', strtotime('-1 days', strtotime($curtime)));
$fname="C:/xampp/htdocs/viprabharat/agent/report/"."Report for ".$prevtime.".csv";
$fpname="Report for ".$prevtime.".csv";
ini_set('allow_url_fopen', 'On');
$file=fopen($fname,"w+");
fputcsv($file, $col_name);
fputcsv($file, $col_2);
$query = "SELECT p.place_id,p.userid, concat(u.first_name,' ',u.last_name) AS user_name, p.place_name,p.house_no,p.road_name,p.sub_area,p.main_area,p.city,p.state,p.postal_code,p.phone_no,p.alt_phone,
CASE WHEN FIND_IN_SET(1,`specialities`) THEN 'Y' ELSE 'N' END AS Home_Opening,
CASE WHEN FIND_IN_SET(2,`specialities`) THEN 'Y' ELSE 'N' END AS Vastu_Shanti, 
CASE WHEN FIND_IN_SET(3,`specialities`) THEN 'Y' ELSE 'N' END AS navagrah, 
CASE WHEN FIND_IN_SET(4,`specialities`) THEN 'Y' ELSE 'N' END AS janma, 
CASE WHEN FIND_IN_SET(5,`specialities`) THEN 'Y' ELSE 'N' END AS naam_karan, 
CASE WHEN FIND_IN_SET(6,`specialities`) THEN 'Y' ELSE 'N' END AS jawal, 
CASE WHEN FIND_IN_SET(7,`specialities`) THEN 'Y' ELSE 'N' END AS threading, 
CASE WHEN FIND_IN_SET(8,`specialities`) THEN 'Y' ELSE 'N' END AS marriage, 
CASE WHEN FIND_IN_SET(9,`specialities`) THEN 'Y' ELSE 'N' END AS bhajan, 
CASE WHEN FIND_IN_SET(10,`specialities`) THEN 'Y' ELSE 'N' END AS last_rituals ,
p.other_specialities,
CASE WHEN FIND_IN_SET(1,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Satya_Narayan,
CASE WHEN FIND_IN_SET(2,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Ganesh_Puja,
CASE WHEN FIND_IN_SET(3,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Hawan_Yagna,
CASE WHEN FIND_IN_SET(4,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Jagran,
CASE WHEN FIND_IN_SET(5,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Lakshmi_Puja,
CASE WHEN FIND_IN_SET(6,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Vaibhav_Lakshmi_Puja,
CASE WHEN FIND_IN_SET(7,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Durga_Puja,
CASE WHEN FIND_IN_SET(8,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Kali_Puja,
CASE WHEN FIND_IN_SET(9,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Sashthi_Puja,
CASE WHEN FIND_IN_SET(10,`puja_specialities`) THEN 'Y' ELSE 'N' END AS Vishwakarma_Puja,
CASE WHEN FIND_IN_SET(1,`astrologer_specialities`) THEN 'Y' ELSE 'N' END AS janam_kundli,
CASE WHEN FIND_IN_SET(2,`astrologer_specialities`) THEN 'Y' ELSE 'N' END AS hororscope,
CASE WHEN FIND_IN_SET(3,`astrologer_specialities`) THEN 'Y' ELSE 'N' END AS Face_reading,
CASE WHEN FIND_IN_SET(4,`astrologer_specialities`) THEN 'Y' ELSE 'N' END AS hand_reading,
CASE WHEN FIND_IN_SET(5,`astrologer_specialities`) THEN 'Y' ELSE 'N' END AS gems_stones, 
CASE WHEN FIND_IN_SET(6,`astrologer_specialities`) THEN 'Y' ELSE 'N' END AS Kundli_dosh, 
CASE WHEN FIND_IN_SET(7,`astrologer_specialities`) THEN 'Y' ELSE 'N' END AS numerology_ankjyotish,
CASE WHEN FIND_IN_SET(8,`astrologer_specialities`) THEN 'Y' ELSE 'N' END AS vashi_karan,
p.other_astrologer_specialities,
CASE WHEN FIND_IN_SET(1,`kathavachak_specialities`) THEN 'Y' ELSE 'N' END AS shreemad_Bhagwatkatha,
CASE WHEN FIND_IN_SET(2,`kathavachak_specialities`) THEN 'Y' ELSE 'N' END AS shivpuran_katha,
CASE WHEN FIND_IN_SET(3,`kathavachak_specialities`) THEN 'Y' ELSE 'N' END AS Vishnu_purankatha,
CASE WHEN FIND_IN_SET(4,`kathavachak_specialities`) THEN 'Y' ELSE 'N' END AS Devi_Bhagwatkatha,
CASE WHEN FIND_IN_SET(5,`kathavachak_specialities`) THEN 'Y' ELSE 'N' END AS Ramayana_katha,
p.other_kathavachak_specialities,p.product,p.other_product,p.submission_date,
CASE WHEN plan='1' THEN 'Priests/Katha_Vachaks/Astrologer_Name'
 WHEN plan='2' THEN 'Temple'
WHEN plan='3' THEN 'Shop/Service' ELSE 'N' END AS Plan_Name,

t.m_opn, t.m_cls, t.e_opn, t.e_cls, t.e_aarti_time, t.other_time_info

FROM places p 
left join users u on p.userid = u.id
left join plans pl on  p.plan = pl.plan_id
left join temple_timings t on p.place_id = t.place_id
WHERE p.submission_date LIKE '".$prevtime."%' " ;

$stmt = $conn->prepare($query);
$stmt->execute();
while($row = $stmt->fetch(PDO::FETCH_ASSOC))
{
fputcsv($file, $row);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'phpmailer/phpmailer/src/Exception.php';
require 'phpmailer/phpmailer/src/PHPMailer.php';
require 'phpmailer/phpmailer/src/SMTP.php';

require 'vendor/autoload.php';
$bodytext="Report for ".$prevtime;
$email = new PHPMailer();
$email->IsSMTP();                                      // Set mailer to use SMTP
$email->Host = 'smtp.gmail.com';                 // Specify main and backup server
$email->Port = 587;                                    // Set the SMTP port
$email->SMTPAuth = true;                               // Enable SMTP authentication
$email->Username = 'nickyghavate@gmail.com';                // SMTP username
$email->Password = '241618rty';                  // SMTP password                            // Enable encryption, 'ssl' also accepted
$email->SetFrom('vedantghavate259@gmail.com', 'Vedant');
$email->Subject   = 'Message Subject';
$email->Body      = $bodytext;
$email->AddAddress( 'vedantghavate259@gmail.com' );
$file_to_attach = 'C:/xampp/htdocs/viprabharat/agent/report/'.$fpname;
$email->addAttachment( $file_to_attach , $fpname );
if(!$email->Send()) 
{
	echo 'Message could not be sent.';
}