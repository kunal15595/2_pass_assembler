<?php

	$file = file_get_contents('python/config/opcodes.config', true);
	preg_match_all('/((OPCODE\s*.*\n)((\s*(?!OPEND).*\n*)*)(OPEND))/', $file, $matches, PREG_SET_ORDER);
	$opcode_array=array();
	foreach ($matches as $match) {
		preg_match_all('/(.*\n*)/', $match[1], $parts, PREG_SET_ORDER);
		preg_match_all('/(\S+)/', $parts[0][0], $name_and_para, PREG_SET_ORDER);
		$name=$name_and_para[1][0];
		$parameter=$name_and_para[2][0];
		// $parameter=str_replace("&", "\&", $parameter);

	    $code = '';
	    for ($i=1; $i < sizeof($parts)-2; $i++) { 
	    	$code.=$parts[$i][0];
	    	
	    	// $code.='<br>';
	    }
	    // $code=str_replace("&", "\&", $code);
	    $code=trim($code);
	    $single_opcode=array("name"=>$name,"par"=>$parameter,"code"=>$code);
	    array_push($opcode_array,$single_opcode);
	}
?>

<div class="col-span-3">
	<ul>
		<?php
			foreach($opcode_array as $row){
				// echo $row["code"];
				echo "<li>";
				$row["code"]=preg_replace("/\n/", "aa", $row["code"]);
				$onclick="edit_opcode('".$row["name"]."','".$row["par"]."','".$row["code"]."')";
				// $onclick="edit_opcode(1,2,3)";
				// echo $onclick;
				?>
				<input type="button" value="<?php echo $row["name"];?>" onclick="<?php echo $onclick; ?>">
				<?php
				echo "</li>";
			}	
		?>
	</ul>
</div>
<div class="col-span-9">
		<input type="text" id="name" readonly="true" name="name">
		<input type="text" id="para" name="para">
		<div id="edit_instruction_editor"></div>
		<input type="submit" value="Save" onclick="save_opcode()">
</div>

