<?php
/*
Uploadify
Copyright (c) 2012 Reactive Apps, Ronnie Garcia
Released under the MIT License <http://www.opensource.org/licenses/mit-license.php> 
*/

// Define a destination
$userid = $_POST['user_id'];
/*$targetFolder = '/uploads'; // Relative to the root
if (!file_exists('../uploads/thumbs')) {
    mkdir('../uploads/thumbs', 0777, true);
}
$targetThumb = '/uploads/thumbs'; //Thumbs folder*/
if (!file_exists('../../../uploads/'.$userid)) {
    mkdir('../../../uploads/'.$userid, 0777, true);
}
$targetFolder = '/../../../uploads/'.$userid;// Relative to the root
if (!file_exists('../../../uploads/'.$userid.'/thumbs')) {
    mkdir('../../../uploads/'.$userid.'/thumbs', 0777, true);
}
//$targetThumb = '/uploads/'.$userid.'/thumbs'; //Thumbs folder

$verifyToken = md5('unique_salt' . $_POST['timestamp']);

if (!empty($_FILES) && $_POST['token'] == $verifyToken) {
	$tempFile = $_FILES['Filedata']['tmp_name'];
	$targetPath = $_SERVER['DOCUMENT_ROOT'] . $targetFolder;
    $filename = $_FILES['Filedata']['name'];
    $path_parts = pathinfo($_FILES["Filedata"]["name"]);
    $image_path = $path_parts['filename'].'_'.time().'.'.$path_parts['extension'];
	$targetFile = rtrim($targetPath,'/') . '/' . $image_path;
	
	// Validate the file type
	//$fileTypes = array('jpg','jpeg','gif','png'); // File extensions
	$fileParts = pathinfo($_FILES['Filedata']['name']);

//	if (in_array($fileParts['extension'],$fileTypes)) {
//		move_uploaded_file($tempFile,$targetFile);
//        $arr = array(
//            'filename'=>$image_path,
//            'filepath'=>$targetFile
//        );
//        echo json_encode($arr);
//		//echo '1';
//	} else {
//		echo 'Invalid file type.';
//	}

    move_uploaded_file($tempFile,$targetFile);
    $arr = array(
        'filename'=>$image_path,
        'filepath'=>$targetFile,
        'fileext'=>$fileParts['extension']
    );
    echo json_encode($arr);
}
?>