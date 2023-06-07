<?php
ini_set('memory_limit', '-1');
$fileList1 = glob('C:/Users/INSTADATA/Downloads/agent1 data images/*');
foreach($fileList1 as $filename1)
{
$nfilename1=str_replace("agent1 data images","agent1 data images_2",$filename1);
echo $nfilename1."<br>";
$fileList = glob($filename1.'/*');
foreach($fileList as $filename)
{
$old_path=$filename;
$nfilename=str_replace("agent1 data images","agent1 data images_2",$filename);
$new_path=$nfilename;
$t = imagecreatefromjpeg($old_path);
$x = imagesx($t);
$y = imagesy($t);
$ratio = $x/$y;
echo "width".$x."<br>";
echo "height".$y."<br>";
if($x>$y)
{
    if($x>640)
    {
        $max=640;
    }
    else
    {
        $max=$x;
    }
}
else if($y>$x)
{
    if($y>640)
    {
        $max=640;
    }
    else
    {
        $max=$y;
    }
}
else if($y==$x)
{
    if($y>640)
    {
        $max=640;
    }
    else
    {
        $max=$y;
    }
  
}
if( $ratio > 1) 
{
    $width = $max;
    $height = $max/$ratio;
}
else 
{
    $width = $max*$ratio;
    $height = $max;
}
$dst = imagecreatetruecolor($max,$max);
$bgcolor = imagecolorallocate($dst, 255, 255, 255);
imagefill($dst, 0, 0, $bgcolor);
if ($ratio > 1)
{
    imagecopyresampled($dst, $t, 0, ($max - $height) / 2, 0, 0, $width, $height, $x, $y);
}
else
{
    imagecopyresampled($dst, $t, ($max - $width) / 2, 0, 0, 0, $width, $height, $x, $y);
}
imagejpeg($dst, $nfilename,60);
imagedestroy($dst);
imagedestroy($t);
echo $nfilename."<br>";
}
}
?>