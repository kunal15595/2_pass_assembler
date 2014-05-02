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
	var file_list=[];
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