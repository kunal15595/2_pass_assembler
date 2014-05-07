<!DOCTYPE html>
<html>
<head>
	<title>Instructions</title>

</head>
<body>

</body>
</html>
<div id="left_col">
	<?php
		$name = array();
		$par = array();
		$exp = array();

		$fh = fopen('python/config/opcodes.config','r');
		while ($line = fgets($fh)) {
			if (strpos($line,'OPCODE') !== false) {
			    $def = explode(" ", $line);
			    echo $def;
			    array_push($name, $def[1]);
			    array_push($par, $def[2]);
			    
			}
		}
		fclose($fh);
		echo $name[0];
	?>
</div>
<div id="right_col">
	
</div>