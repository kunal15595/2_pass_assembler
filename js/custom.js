var editor = ace.edit("editor");
// editor.setTheme("ace/theme/monokai");
editor.setTheme("ace/theme/chrome");
editor.getSession().setTabSize(4);
editor.getSession().setUseSoftTabs(true);
editor.getSession().setMode("ace/mode/assembly_x86");
document.getElementById('editor').style.fontSize='20px';

$("document").ready(function(){
	$(".custom_icon_link").hover(function(){
        $(this).find(".custom_navbar_icon").css("color","#fff");
    });
    $(".custom_icon_link").on("mouseleave",function(){
        $(this).find(".custom_navbar_icon").css("color","#aaa");
    });

	$("#file_list").on("click","a",function(event_var){
		file_path=$(this).attr("href");
		$.get(file_path,function(data){
			editor.setValue(data);
			editor.gotoLine(1);

		});
		
		event_var.preventDefault();
	});

	$( "#sortable" ).sortable();
    $( "#sortable" ).disableSelection();
    
});


function save_file_to_server(){
	file_content=editor.getSession().getValue();
	var file_name=prompt("Enter file name","default");
	// alert(file_content);
	$.ajax({
	  type: "POST",
	  url: "upload.php",
	  data: {
	  			file_content : file_content,
	  			file_name : file_name
	  		},
	  success: function(){
	  	alert("saved");
	  }
	});
}

function run(){
	var run_order_list=$("#sortable");
	run_order_list.html("");
	$("#run_order_div").css("display","none");
	var file_list=[];
	$("#file_list").find("input:checkbox").each(function(){
		if($(this).prop("checked"))
			file_list.push($(this).attr("run_sel_value"));
	});
	// console.log(file_list);
	if(file_list.length>0){
		for (var file_nm in file_list){
			var run_order_element=$("<li>");
			run_order_element.addClass("ui-state-default");
			var run_order_element_span=$("<span>");
			run_order_element_span.addClass("ui-icon ui-icon-arrowthick-2-n-s");
			run_order_element_span.appendTo(run_order_element);
			run_order_element.append(file_list[file_nm]);
			run_order_element.attr("value",file_list[file_nm]);
			run_order_element.appendTo(run_order_list);
		}
		$("#run_order_div").css("display","block");
	}else{
		alert("Please choose atleast 1 file to run");
	}
	
}

function run_order_confirmed(){
	var file_list=[];
	var run_order_list=$("#sortable");
	run_order_list.find("li").each(function(){
		file_list.push($(this).attr("value"));
	});
	macro_table=$("#macro_table");
  	symbol_table=$("#symbol_table");
  	variable_table=$("#variable_table");
  	$("#pass2").html("");
  	$("#linked").html("");
  	$("#loaded").html("");
  	$("#hex").html("");
  	macro_table.html("");
  	symbol_table.html("");
  	variable_table.html("");
	$.ajax({
	  	type: "POST",
	  	url: "run.php",
	  	dataType: "json",
	  	data: {
	  			file_list : file_list
	  		},
	  	success: function(data){
		  	
		  	if(data["pass1"]){
		  		var pass1=data["pass1"];
		  		var pass1_array=pass1.split("\n");
		  		var macro_start_index=0;
		  		var macro_end_index=0;
		  		var symbol_start_index=0;
		  		var symbol_end_index=0;
		  		var variable_start_index=0;
		  		var variable_end_index=0;
		  		var j=0;
		  		var k=0;
		  		// console.log(pass1_array);
		  		for (var i in pass1_array){
		  			// console.log(pass1_array[i]);
		  			if(j<2){
		  				if(pass1_array[i]=="-------------MACROS-------------"){
		  					// alert(2);
		  					if(j==0)
		  						macro_start_index=k;
		  					else
		  						macro_end_index=k;
		  					j++;
		  				}
		  			}else if(j<4){
		  				// alert(4);
		  				if(pass1_array[i]=="-------------SYMBOL-------------"){
		  					if(j==2)
		  						symbol_start_index=k;
		  					else
		  						symbol_end_index=k;
		  					j++;
		  				}
		  			}else{
		  				if(pass1_array[i]=="------------VARIABLE------------"){
		  					// alert("variable"+k+" "+j);
		  					if(j==4)
		  						variable_start_index=k;
		  					else
		  						variable_end_index=k;
	  						j++;
	  						// alert(variable_start_index);
		  				}
		  			}
		  			k++;
		  			// alert(j);
		  		}
		  		// alert("end");
		  		// alert(variable_start_index);
		  		// alert(variable_end_index);
		  		num_macros=macro_end_index - macro_start_index -1;
		  		num_symbols=symbol_end_index - symbol_start_index -1;
		  		num_variables=variable_end_index - variable_start_index -1;
		  		// alert(symbol_start_index);
		  		// alert(num_macros);
		  		// alert(num_variables);
		  		if( num_macros>0 ){
		  			for(var i=macro_start_index+1;i<macro_end_index;i++){
		  				// alert("macro");
		  				var tb_row=$("<tr>");
		  				var string_row_array=pass1_array[i].split(/[\s]+/);
		  				for(var j in string_row_array){
		  					var tb_col=$("<td>");
		  					tb_col.text(string_row_array[j]);
		  					tb_col.appendTo(tb_row);
		  				}
		  				tb_row.appendTo(macro_table);
		  				
		  			}
		  		}

		  		if( num_symbols>0 ){
		  			for(var i=symbol_start_index+1;i<symbol_end_index;i++){
		  				var tb_row=$("<tr>");
		  				var string_row_array=pass1_array[i].split(/[\s]+/);
		  				// console.log(string_row_array);
		  				for(var j in string_row_array){
		  					var tb_col=$("<td>");
		  					tb_col.text(string_row_array[j]);
		  					tb_col.appendTo(tb_row);
		  				}
		  				tb_row.appendTo(symbol_table);
		  				
		  			}
		  		}

		  		if( num_variables>0 ){
		  			for(var i=variable_start_index+1;i<variable_end_index;i++){
		  				// alert("varialbe");
		  				var tb_row=$("<tr>");
		  				var string_row_array=pass1_array[i].split(/[\s]+/);
		  				for(var j in string_row_array){
		  					var tb_col=$("<td>");
		  					tb_col.text(string_row_array[j]);
		  					tb_col.appendTo(tb_row);
		  				}
		  				tb_row.appendTo(variable_table);
		  				
		  			}
		  		}

		  	}

			var pass2_editor = ace.edit("pass2");
			alert("hello");
			pass2_editor.setTheme("ace/theme/chrome");
			pass2_editor.getSession().setTabSize(4);
			pass2_editor.getSession().setUseSoftTabs(true);
			pass2_editor.getSession().setMode("ace/mode/assembly_x86");  
			document.getElementById('pass2').style.fontSize='20px';
			pass2_editor.setValue(data["pass2"]);
			pass2_editor.gotoLine(1);
			pass2_editor.setReadOnly(true); 

			var linked_editor = ace.edit("linked");
			linked_editor.setTheme("ace/theme/chrome");
			linked_editor.getSession().setTabSize(4);
			linked_editor.getSession().setUseSoftTabs(true);
			linked_editor.getSession().setMode("ace/mode/assembly_x86");  
			document.getElementById('linked').style.fontSize='20px';
			linked_editor.setValue(data["linked"]);
			linked_editor.gotoLine(1);
			linked_editor.setReadOnly(true); 

			var loaded_editor = ace.edit("loaded");
			loaded_editor.setTheme("ace/theme/chrome");
			loaded_editor.getSession().setTabSize(4);
			loaded_editor.getSession().setUseSoftTabs(true);
			loaded_editor.getSession().setMode("ace/mode/assembly_x86"); 
			document.getElementById('loaded').style.fontSize='20px';
			loaded_editor.setValue(data["loaded"]);
			loaded_editor.gotoLine(1); 
			loaded_editor.setReadOnly(true); 

			var hex_editor = ace.edit("hex");
			hex_editor.setTheme("ace/theme/chrome");
			hex_editor.getSession().setTabSize(4);
			hex_editor.getSession().setUseSoftTabs(true);
			hex_editor.getSession().setMode("ace/mode/assembly_x86"); 
			document.getElementById('hex').style.fontSize='20px';
			hex_editor.setValue(data["hex"]);
			hex_editor.gotoLine(1); 	
			hex_editor.setReadOnly(true); 
	  	},
	  	error: function (request, textStatus, error) {
            if(request.readyState==4){// 4 means complete
                if(request.status!=200){
                    alert(textStatus);
                    alert(request.status);
                    alert(error);        
                }else{
                    //no error
                }    
            }
        }
	  
	});
}