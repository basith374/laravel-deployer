<?php
error_reporting(E_ERROR);
set_time_limit(120);

$zipfile = 'upload.zip';
$public_convert = true;

$file = $_FILES['file']['tmp_name'];
if(file_exists($file)) {

    move_uploaded_file($file, './' . $zipfile);

    $extract_path = './extract/';

    if(!file_exists($extract_path)) {
    	mkdir($extract_path);
    }

    $zip = new ZipArchive;
    if ($zip->open($zipfile) === true) {
		// $uploaded_pass = fopen('zip://' . $zipfile . '#deployer', 'r');
		// if($uploaded_pass === false) {
		// 	abort('no credentials provided');
		// }
		// $local_pass = fopen($extract_path . 'deployer', 'r');
		// if($local_pass === false) {
		// 	abort('local credentials not found');
		// }
		// if(fgets($uploaded_pass) === fgets($local_pass)) {
			
		    for($i = 0; $i < $zip->numFiles; $i++) {
		        $filename = $zip->getNameIndex($i);
		        $fileinfo = pathinfo($filename);
		        $target = $filename;
		        // extract "public" in "public_path" dir
		        if($public_convert && startsWith($filename, 'public/')) {
				    $target = 'public_html' . substr($filename, 6);
		        }
		        if ( substr( $target, -1 ) == '/' ) {
		        	$target = $extract_path . $target;
		        	if(!file_exists($target)) {
		        		mkdir($target);
		        	}
		        	continue; // skip directories 
		        }
		       	copy("zip://" . $zipfile . "#" . $filename, $extract_path . $target);
		    }
		    
		// } else {
			// fclose($uploaded_pass);
			// fclose($local_pass);
		 //    $zip->close();
			// abort('invalid credentials');
		// }
		fclose($uploaded_pass);
		fclose($local_pass);
        $zip->close();
        echo 'deployed';
    }
} else {
    abort('no file specified');
}


function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

function abort($message) {
	header($_SERVER['SERVER_PROTOCOL'] . ' 400 Bad Request', true, 400);
	die($message);
}


?>
