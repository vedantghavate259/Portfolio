
  
<?php
$fileName = 'myfile.jpg';
$filenameCt = generateRandomString();
echo "<p> <font color=white>OCR output<br>";
echo "upload start";

if (isset($_POST['submit']) && isset($_FILES)) {
//require __DI  R__ . '/vendor/autoload.php';
$target_dir =   'C:/xampp3/htdocs/receipt/uploads2/';
  $uploadOk = 1;
  $FileType = strtolower(pathinfo($_FILES["attachment"]["name"], PATHINFO_EXTENSION));
  $target_file = $target_dir . $filenameCt . '.' . $FileType;
  echo ("Target File: " . $target_file . " ");

  //$target_dir = $target_dir.basename( $_FILES['attachment']['name']);
  echo "upload start 1";
  // Check file size
  if ($_FILES["attachment"]["size"] > 100000000) {
    header('HTTP/1.0 403 Forbidden');
    echo "Sorry, your file is too large.";
    $uploadOk = 0;
  }
  if ($FileType != "pdf" && $FileType != "png" && $FileType != "jpg" && $FileType != "jpeg") {
    header('HTTP/1.0 403 Forbidden');
    echo "Sorry, please upload a pdf/png/jpg file";
    $uploadOk = 0;
  }
  if ($uploadOk == 1) {

    if (move_uploaded_file($_FILES["attachment"]["tmp_name"], $target_file)) {
      uploadToApi($target_file);
    } else {
      header('HTTP/1.0 403 Forbidden');
      echo "Sorry, there was an error uploading your file.";
    }
  }
} else {
  header('HTTP/1.0 403 Forbidden');
  echo "Sorry, please upload a pdf file";
}



function uploadToApi($target_file)
{
  echo  "call function";
  // Name of application you created
  $applicationId = '85f01ad1-0ae4-4e0f-a94f-4f9c5a3848e8';
  // Password should be sent to your e-mail after application was created
  $password = 'xmfvjRRhSbaW0RO3Jx5ehniX';

  // URL of the processing service. Change to http://cloud-westus.ocrsdk.com
  // if you created your application in US location
  $serviceUrl = 'https://cloud-westus.ocrsdk.com';
  $filePath = $target_file;


  if (!file_exists($filePath)) {
    die('File ' . $filePath . ' not found.');
  }
  if (!is_readable($filePath)) {
    die('Access to file ' . $filePath . ' denied.');
  }

  // Recognizing with English language to rtf
  // You can use combination of languages like ?language=english,russian or
  // ?language=english,french,dutch
  // For details, see API reference for processImage method
  $url = $serviceUrl . '/processImage?language=english&exportFormat=txt';

  echo "start request";
  // Send HTTP POST request and ret xml response
  $curlHandle = curl_init();
  echo $curlHandle;
  curl_setopt($curlHandle, CURLOPT_URL, $url);
  curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($curlHandle, CURLOPT_USERPWD, "$applicationId:$password");
  curl_setopt($curlHandle, CURLOPT_POST, 1);
  curl_setopt($curlHandle, CURLOPT_USERAGENT, "PHP Cloud OCR SDK Sample");
  curl_setopt($curlHandle, CURLOPT_FAILONERROR, true);

  $post_array = array();

  if ((version_compare(PHP_VERSION, '5.5') >= 0)) {
    $post_array["my_file"] = new CURLFile($filePath);
  } else {
    $post_array["my_file"] = "@" . $filePath;
  }

  curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $post_array);
  echo "call response";
  $response = curl_exec($curlHandle);
  if ($response == FALSE) {
    $errorText = curl_error($curlHandle);
    curl_close($curlHandle);
    die($errorText);
  }
  $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
  curl_close($curlHandle);

  // Parse xml response
  $xml = simplexml_load_string($response);
  if ($httpCode != 200) {
    if (property_exists($xml, "message")) {
      die($xml->message);
    }
    die("unexpected response " . $response);
  }

  $arr = $xml->task[0]->attributes();
  $taskStatus = $arr["status"];
  if ($taskStatus != "Queued") {
    die("Unexpected task status " . $taskStatus);
  }

  // Task id
  $taskid = $arr["id"];

  // 4. Get task information in a loop until task processing finishes
  // 5. If response contains "Completed" staus - extract url with result
  // 6. Download recognition result (text) and display it

  $url = $serviceUrl . '/getTaskStatus';
  // Note: a logical error in more complex surrounding code can cause
  // a situation where the code below tries to prepare for getTaskStatus request
  // while not having a valid task id. Such request would fail anyway.
  // It's highly recommended that you have an explicit task id validity check
  // right before preparing a getTaskStatus request.
  if (empty($taskid) || (strpos($taskid, "00000000-0") !== false)) {
    die("Invalid task id used when preparing getTaskStatus request");
  }
  $qry_str = "?taskid=$taskid";

  // Check task status in a loop until it is finished

  // Note: it's recommended that your application waits
  // at least 2 seconds before making the first getTaskStatus request
  // and also between such requests for the same task.
  // Making requests more often will not improve your application performance.
  // Note: if your application queues several files and waits for them
  // it's recommended that you use listFinishedTasks instead (which is described
  // at https://ocrsdk.com/documentation/apireference/listFinishedTasks/).
  while (true) {
    sleep(5);
    $curlHandle = curl_init();
    curl_setopt($curlHandle, CURLOPT_URL, $url . $qry_str);
    curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curlHandle, CURLOPT_USERPWD, "$applicationId:$password");
    curl_setopt($curlHandle, CURLOPT_USERAGENT, "PHP Cloud OCR SDK Sample");
    curl_setopt($curlHandle, CURLOPT_FAILONERROR, true);
    $response = curl_exec($curlHandle);
    $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
    curl_close($curlHandle);

    // parse xml
    $xml = simplexml_load_string($response);
    if ($httpCode != 200) {
      if (property_exists($xml, "message")) {
        die($xml->message);
      }
      die("Unexpected response " . $response);
    }
    $arr = $xml->task[0]->attributes();
    $taskStatus = $arr["status"];
    if ($taskStatus == "Queued" || $taskStatus == "InProgress") {
      // continue waiting
      continue;
    }
    if ($taskStatus == "Completed") {
      // exit this loop and proceed to handling the result
      break;
    }
    if ($taskStatus == "ProcessingFailed") {
      die("Task processing failed: " . $arr["error"]);
    }
    die("Unexpected task status " . $taskStatus);
  }

  // Result is ready. Download it

  $url = $arr["resultUrl"];
  $curlHandle = curl_init();
  curl_setopt($curlHandle, CURLOPT_URL, $url);
  curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
  // Warning! This is for easier out-of-the box usage of the sample only.
  // The URL to the result has https:// prefix, so SSL is required to
  // download from it. For whatever reason PHP runtime fails to perform
  // a request unless SSL certificate verification is off.
  curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($curlHandle);
  curl_close($curlHandle);

  // Let user donwload rtf result
  //header('Content-type: application/text');
  //header('Content-Disposition: attachment; filename="file.txt"');
  $target_dir = "uploads2/";
  $uploadOk = 1;
  $FileType = "txt";
  global $filenameCt;
  $target_file = $target_dir . $filenameCt . '.' . $FileType;
  echo ("Target File: " . $target_file . " ");
  $my_file = $target_file;
  $handle = fopen($my_file, 'w') or die('Cannot open file:  ' . $my_file); //implicitly creates file
  fwrite($handle, $response);

  fclose($handle);
  $file = fopen($my_file, "r");

  $pattern = "/(([\w+ :+)(@%])+\s+((\d+\s+\d+\.\d+|\d+\.\d+)\s+\d+\.\d+)\s+(\d+\.\d+))|([\w+ : -.]+\s+\d+\.\d+)/";

  while (!feof($file)) {
    //if(completelyMatchesPattern(fgets($file),$pattern)){
    echo fgets($file) . "<br />";
    //  }
    //echo completelyMatchesPattern(fgets($file),$pattern);
  }

  fclose($file);
}

function completelyMatchesPattern($str, $pattern)
{
  return preg_match($pattern, $str, $matches) === 1 && $matches[0] === $str;
}

function generateRandomString($length = 10)
{
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
echo "upload start 2";
echo "</font> </p>";
?>


