var editor = ace.edit("editor");
// editor.setTheme("ace/theme/monokai");
editor.setTheme("ace/theme/chrome");
editor.getSession().setTabSize(4);
editor.getSession().setUseSoftTabs(true);
editor.getSession().setMode("ace/mode/assembly_x86");

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
	console.log(file_list);
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
	$.ajax({
	  type: "POST",
	  url: "run.php",
	  data: {
	  			file_list : file_list
	  		},
	  success: function(data){
	  	alert("saved");
	  }
	});
}