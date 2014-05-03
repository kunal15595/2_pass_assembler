<?php
require_once "config/config.php";
if(isset($_POST["file_list"])&&!empty($_POST["file_list"])){
	$file_list=$_POST["file_list"];
	$cmd_line_args="";
	foreach ($file_list as $file_name) {
		$cmd_line_args.=" ".constant("UPLOAD_LOC").$file_name;
	}
	
	exec("python python/main.py ".$cmd_line_args);
	
	echo 1;
}
?>