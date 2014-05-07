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
	// echo $result_c;
	$pass1=file_get_contents(constant("UPLOAD_LOC").$root_file_nm.".table");
	$pass2=file_get_contents(constant("UPLOAD_LOC").$root_file_nm.".s");
	$linked=file_get_contents(constant("UPLOAD_LOC").$root_file_nm.".l.8085");
	$loaded=file_get_contents(constant("UPLOAD_LOC").$root_file_nm.".s.8085");
	$hex=file_get_contents(constant("UPLOAD_LOC").$root_file_nm.".hex");
	// $hex="";
	$result_array=array("pass1"=>$pass1,"pass2"=>$pass2,"linked"=>$linked,"loaded"=>$loaded,"hex"=>$hex);
	echo json_encode($result_array);
	// echo $root_file_nm;
	// echo "./8085assembler ".constant("UPLOAD_LOC").$root_file_nm.".8085 -n > ".constant("UPLOAD_LOC").$root_file_nm.".hex";
	// echo 1;
}
?>