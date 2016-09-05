<?php


// print_r($_POST);
$file = $_FILES['file']['tmp_name'];

if(file_exists($file)) {
	move_uploaded_file($file, './uploaded');
	echo 'success';
} else {
	echo 'no file specified';
}