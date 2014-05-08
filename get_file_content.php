<?php
require_once "config/config.php";
if(isset($_POST["file_list"])&&!empty($_POST["file_list"])){
	$file_list=$_POST["file_list"];
	$result_array=array();
	foreach ($file_list as $file_name) {
		$result_array[$file_name]=file_get_contents(constant("UPLOAD_LOC").$file_name);
	}
	echo json_encode($result_array);
}
?>