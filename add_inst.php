
<?php

	define("HOST11", "http://localhost/biswas/php_assembler");
	// Grab User submitted information
	if (isset($_POST['name']) && isset($_POST['para']) && isset($_POST['code'])) {
		# code...
		$name = $_POST["name"];
		$par = $_POST["para"];
		$code = $_POST["code"];
	}
	
	function update($name, $par, $code)
	{
		var_dump($code);
		// echo "hjasd"; 
		$file = file_get_contents('python/config/opcodes.config', true);
		// $file = file_get_contents('input.txt', true);

		preg_match_all('/OPCODE\s\s*'.$name.'\s\s*/', $file, $matches, PREG_SET_ORDER);
		// echo sizeof($matches);	
		// var_dump($matches);
		if (sizeof($matches) > 0) {
			// echo sizeof($matches);
			// preg replace
			// var_dump("\t".$code);
			preg_replace('\n','\n\t',"\t" . $code);
			var_dump($code);
			// echo str_replace(array("\r\n","\r","\n"),'<br>',$code);
			$replace = "OPCODE ".$name." ".$par."\n\t".$code."\n"."OPEND";
			// var_dump($replace);
			$file = preg_replace('/((OPCODE\s\s*'.$name.'\s*.*\n)((\s*(?!OPEND).*\n*)*)(OPEND))/', $replace, $file);
			echo str_replace(array("\r\n","\r","\n"),'<br>',$file);
			// var_dump($file);
		}else{
			//insert new
			preg_replace('\n','\n\t',"\t" . $code);
			var_dump($code);
			$add = "OPCODE ".$name." ".$par."\n".$code."\n"."OPEND";
			$file.="\n\n".$add;

		}
		$ofile = fopen("output.txt","w");
		echo fwrite($ofile, $file);
		fclose($ofile);
	}

	update($name, $par, $code);
	// header("Location: ".constant("HOST11")."/index.php");
	echo $name,$par,$code;
?>