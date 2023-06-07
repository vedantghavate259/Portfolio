<?php
ini_set('memory_limit', '-1');
$fileList1 = glob('C:/Users/INSTADATA/Downloads/agent1 data images_2 1000res/*');
$wtrmrk_file = 'C:/Users/INSTADATA/Downloads/viprabharat-final-logo-web.v1.png';
foreach($fileList1 as $filename1)
{
$fileList = glob($filename1.'/*');
foreach($fileList as $filename)
{
$old_path=$filename;
$watermark = imagecreatefrompng($wtrmrk_file);
imagealphablending($watermark, false);
imagesavealpha($watermark, true);
$img = imagecreatefromjpeg($old_path);
$img_w = imagesx($img);
$img_h = imagesy($img);
$wtrmrk_w = imagesx($watermark);
$wtrmrk_h = imagesy($watermark);
$dst_x = ($img_w / 2) - ($wtrmrk_w / 2); // For centering the watermark on any image
$dst_y = ($img_h / 3) - ($wtrmrk_h / 3); // For centering the watermark on any image
imagecopy($img, $watermark, $dst_x, $dst_y, 0, 0, $wtrmrk_w, $wtrmrk_h);
imagejpeg($img, $old_path, 100);
imagedestroy($img);
imagedestroy($watermark);
}
}
?>