<?php
require_once "config/config.php";
if(isset($_POST["file_content"])&&isset($_POST["file_name"])&&!empty($_POST["file_content"])&&!empty($_POST["file_name"])){
	file_put_contents(constant("UPLOAD_LOC").$_POST["file_name"],$_POST["file_content"],FILE_USE_INCLUDE_PATH);
	echo 1;
}
?>