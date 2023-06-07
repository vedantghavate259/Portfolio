<?php
$fileList1 = glob('C:/Users/INSTADATA/Downloads/agent1 data images_2 1000res/*');
foreach($fileList1 as $filename1)
{
$nfilename1=str_replace("agent1 data images_2 1000res","agent1 data images_5",$filename1);
echo $nfilename1."<br>";
$fileList = glob($filename1.'/*');
foreach($fileList as $filename)
{
$old_path=$filename;
$nfilename=str_replace("agent1 data images_2 1000res","agent1 data images_5",$filename);
echo $nfilename;
$new_path=$nfilename;
$t = imagecreatefromjpeg($old_path);
$x = imagesx($t);
$y = imagesy($t);
$s = imagecreatetruecolor(1000,1000);
imagecopyresampled($s, $t, 0, 0, 0, 0, 640, 640,$x, $y);
imagejpeg($s, $new_path,60);
}
}
?>