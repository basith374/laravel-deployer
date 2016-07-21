<?php

$path = 'upload.zip';

move_uploaded_file($_FILES['file']['tmp_name'], './' . $path);

$extract_path = '/home/nexthome/';

if(!file_exists($extract_path)) {
	mkdir($extract_path);
}

function startsWith($haystack, $needle) {
    // search backwards starting from haystack length characters from the end
    return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== false;
}

$zip = new ZipArchive;
if ($zip->open($path) === true) {
    for($i = 0; $i < $zip->numFiles; $i++) {
        $filename = $zip->getNameIndex($i);
        $fileinfo = pathinfo($filename);
        $target = $filename;
        if(startsWith($filename, 'public/')) {
		$target = 'public_html' . substr($filename, 6);
        }
        if ( substr( $target, -1 ) == '/' ) {
        	$target = $extract_path . $target;
        	if(!file_exists($target)) {
        		mkdir($target);
        	}
        	continue; // skip directories 
        }
//	echo $filename . '<br>';
//	echo $fileinfo['basename'] . '<br>';
       	copy("zip://" . $path . "#" . $filename, $extract_path . $target);
//        copy("zip://".$path."#".$filename, $extract_path.$fileinfo['basename']);
    }                   
    $zip->close();                   
}

?>