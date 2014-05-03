<?php
require_once "config/config.php";
if(isset($_POST["file_list"])&&!empty($_POST["file_list"])){
	$file_list=$_POST["file_list"];
	$cmd_line_args="";
	foreach ($file_list as $file_name) {
		$cmd_line_args.=" ".constant("UPLOAD_LOC").$file_name;
	}
	
	$result_python=shell_exec("python python/main.py ".$cmd_line_args);
	// echo $result;
	$root_file_nm=explode(".",$file_list[0])[0];
	$result_c=shell_exec("./8085assembler ".constant("UPLOAD_LOC").$root_file_nm.".8085 -n > ".constant("UPLOAD_LOC").$root_file_nm.".hex");
	echo $result_c;
	
	// echo $root_file_nm;
	// echo "./8085assembler ".constant("UPLOAD_LOC").$root_file_nm.".8085 -n > ".constant("UPLOAD_LOC").$root_file_nm.".hex";
	echo 1;
}
?>