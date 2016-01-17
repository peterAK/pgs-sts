<?php
/**
 * Created by PhpStorm.
 * User: Delly
 * Date: 31/12/13
 * Time: 22:52
 */
$localSource = $_GET['file_path'];
$filename = $_GET['file_name'];
$userid = $_GET['user_id'];
$targetThumb = '../uploads/'.$userid.'/thumbs';
$imgsize = getimagesize($localSource);

switch(strtolower(substr($localSource, -3))){
    case "jpg":
        $image = imagecreatefromjpeg($localSource);
        break;
    case "png":
        $image = imagecreatefrompng($localSource);
        break;
    case "gif":
        $image = imagecreatefromgif($localSource);
        break;
    default:
        exit;
        break;
}

$targetFile = $localSource;
$width = 100;
$height = $imgsize[1]/$imgsize[0]*$width;

$src_w = $imgsize[0];
$src_h = $imgsize[1];

$picture = imagecreatetruecolor($width, $height);
imagealphablending($picture, false);
imagesavealpha($picture, true);
$bool = imagecopyresampled($picture, $image, 0, 0, 0, 0, $width, $height, $src_w, $src_h);

if($bool){
    switch(strtolower(substr($targetFile, -3))){
        case "jpg":
            header("Content-Type: image/jpeg");
            $bool2 = imagejpeg($picture,rtrim($targetThumb, '/').'/thumb_'.$filename,80);
            break;
        case "png":
            header("Content-Type: image/png");
            imagepng($picture,rtrim($targetThumb, '/').'/thumb_'.$filename);
            break;
        case "gif":
            header("Content-Type: image/gif");
            imagegif($picture,rtrim($targetThumb, '/').'/thumb_'.$filename);
            break;
    }
}

imagedestroy($picture);
imagedestroy($image);

$temp = array("status"=>1);
$encoded 	= json_encode($temp);
echo $encoded;