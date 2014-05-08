<?php
	// $opcodes = array('' => , );
	$name = array();
	$par = array();
	$exp = array();

	$file = file_get_contents('python/config/opcodes.config', true);
	preg_match_all('/(OPCODE\s*.*\n)(\s*(?!OPEND).*\s*\n*)*OPEND/', $file, $matches, PREG_SET_ORDER);
	// echo sizeof($matches);
	// print_r($matches);
?>

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
		
		
	?>
</div>
<div id="right_col">
	
</div>