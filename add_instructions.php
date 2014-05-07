<?php
	// $opcodes = array('' => , );
	/*
	$name = array();
	$par = array();
	$exp = array();

	$file = file_get_contents('python/config/opcodes.config', true);
	// echo $file;
	$f = 'file.txt'; 
	
	preg_match_all('/((OPCODE\s*.*\n)((\s*(?!OPEND).*\n*)*)(OPEND))/', $file, $matches, PREG_SET_ORDER);
	// echo sizeof($matches);
	// echo $matches[0][1];
	foreach ($matches as $match) {
		// file_put_contents($f, $match);
		// var_dump($match);
	    // $lines = explode('\n', $match);
	    // var_dump($match[1]);
	    // echo "\n";
	    // echo "string";
	    // var_dump($lines[0]);
		preg_match_all('/(.*\n*)/', $match[1], $parts, PREG_SET_ORDER);
		// var_dump($parts[1]);
		preg_match_all('/(\S+)/', $parts[0][0], $sub_parts, PREG_SET_ORDER);
		
	    // $parts = explode('\r', $match[1]);
	    // var_dump($parts);
	    // var_dump($sub_parts[2]);
	    array_push($name, $sub_parts[1][0]);
	    array_push($par, $sub_parts[2][0]);
	    $code = '';
	    for ($i=1; $i < sizeof($parts)-2; $i++) { 
	    	$code.=$parts[$i][0];
	    	
	    	$code.='<br>';
	    }
	    array_push($exp, $code);
	    // var_dump($code);
	}
	*/
?>

<!DOCTYPE html>
<html>
<head>
	<title>Instructions</title>
	<link href="instyle.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="bodypanel">
	<br>
	<div id="rightpanel1">
	    	<?php
	    	echo '<form id="login_form" class="center_form" method="post" action="add_inst.php">
	    	<table>
	    	<tr>
	    		<td>Name:</td> 
	    		<td><input type="text" name="name" id="name"></td>
	    	</tr>
	    	<tr>
	    		<td>Parameter:</td> 
	    		<td><input type="text" name="para" id="para"></td>
	    	</tr>
	    	<tr>
	    		<td>code:</td>
	    		<td><textarea rows="12" cols="40" id="code" name="code" align="left"></textarea></td>
	    	</tr>
	    	<tr>
				<td></td>
				<td><input type="submit" name="submit" id="submit_1" value="Submit" class="submit-button"></td>
			</tr>
	    	</table>
	    	</form>'
	    	// $opcodes = array('' => , );
	    	/*
	    	for($x=0;$x<$arrlength;$x++)
	    	  {
	    	  echo $name[$x];
	    	  echo "<br>";
	    	  }
	    		//var_dump($name);
	    		//var_dump($par);
	    		//var_dump($exp);
	    	*/
	    	?> 
	</div>
</div>
</body>
</html>

