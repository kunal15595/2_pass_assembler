<?php
if(isset($_POST["file_list"])&&!empty($_POST["file_list"])){
	$file_list=$_POST["file_list"];
	exec("python ./python/main.py");
	exec("print 'hello world'");
	echo 1;
}
?>