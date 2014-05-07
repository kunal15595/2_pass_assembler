<!DOCTYPE html>
<?php include_once "config/config.php";?>
<html>
<head>
	<meta charset="utf-8">
    <title>Template</title>
	<link rel="stylesheet" href="css/bootstrap.css"  type="text/css"/>
	<link rel="stylesheet" type="text/css" href="css/smoothness/jquery-ui.css">
	<link rel="stylesheet" href="css/jquery.fileupload.css">
	<link rel="stylesheet" href="css/jquery.fileupload-ui.css">
	<link rel="stylesheet" href="css/style.css">
	
	<style type="text/css" media="screen">
    #editor { 
        height: 590px;
    }
    #pass2,#linked,#loaded,#hex{
    	height: 590px;
    }
</style>
<style>
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 3px 3px 3px; padding: 0.4em; padding-left: 1.5em; font-size: 1em; height: 30px; }
  #sortable li span { position: absolute; margin-left: -1.3em; }
</style>
</head>
<body>
	<div class="container-fluid">
		<div class="row" role="header">
			<?php include_once "header.php";?> 
		</div>
		
		<div class="container-fluid">
		        <div class="container-fluid col-sm-6 col-md-6">
    				<div class="row" id="tabs">
			     		<ul class="nav nav-tabs" id="input_tabs">
						    <li class="active">
						    	<a href="#editor_pane" data-toggle="tab">Editor</a>
						    </li>
						    <li>
						    	<a href="#upload_pane" data-toggle="tab">Upload</a>
						    </li>
						    <li>
						    	<a href="#instructions_pane" data-toggle="tab">Instructions</a>
						    </li>
						</ul>
    				</div>
    				<div class="tab-content" id="tab_content">
    					<div class="tab-pane active" id="editor_pane">
    						<div class="row" id="toolbar">
    							<input type="button" value="Save/Upload" onclick="save_file_to_server()">
    						</div>
    						<div class="row" id="editor"></div>
    					</div>
	    				<div class="tab-pane" id="upload_pane">
	    					<div id="run_order_div" class="row" style="display : none;">
		    					Confirm loading order
		    					<form id="run_order_sort">
		    						<ul id="sortable"></ul>
		    						<input type="button" value="Confirm Order" onclick="run_order_confirmed()">
		    					</form>
	    					</div>
	    					<form id="fileupload" action="" method="POST" enctype="multipart/form-data">
						        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
						        <div class="row fileupload-buttonbar">
						            <div class="col-lg-7">
						                <!-- The fileinput-button span is used to style the file input field as button -->
						                <span class="btn btn-success fileinput-button">
						                    <i class="glyphicon glyphicon-plus"></i>
						                    <span>Add files...</span>
						                    <input type="file" name="files[]" multiple>
						                </span>
						                <button type="submit" class="btn btn-primary start">
						                    <i class="glyphicon glyphicon-upload"></i>
						                    <span>Start upload</span>
						                </button>
						                <button type="reset" class="btn btn-warning cancel">
						                    <i class="glyphicon glyphicon-ban-circle"></i>
						                    <span>Cancel upload</span>
						                </button>
						                <button type="button" class="btn btn-danger delete">
						                    <i class="glyphicon glyphicon-trash"></i>
						                    <span>Delete</span>
						                </button>
						                <button type="button" class="btn btn-success start" onclick="run()">
						                    <i class="glyphicon glyphicon-play"></i>
						                    <span>Run</span>
						                </button>
						                <button type="button" class="btn btn-primary start" onclick="download()">
						                    <i class="glyphicon glyphicon-download"></i>
						                    <span>Download</span>
						                </button>
						                <input type="checkbox" class="toggle">
						                <!-- The global file processing state -->
						                <span class="fileupload-process"></span>
						            </div>
						            <!-- The global progress state -->
						            <div class="col-lg-5 fileupload-progress fade">
						                <!-- The global progress bar -->
						                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
						                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
						                </div>
						                <!-- The extended global progress state -->
						                <div class="progress-extended">&nbsp;</div>
						            </div>
						        </div>
						        <!-- The table listing the files available for upload/download -->
						        <table id="file_list" role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    						</form>
       
	    				</div>
	    				<div class="tab-pane" id="instructions_pane">
	    					<?php include 'instructions.php';?>
	    					
	    				</div >
    				</div>
	    		</div>
	    		<div class="container-fluid col-sm-6 col-md-6">
	    			<div class="row" id="tabs">
			     		<ul class="nav nav-tabs" id="output_tabs">
						    <li class="active">
						    	<a href="#pass1" data-toggle="tab">Pass1</a>
						    </li>
						    <li>
						    	<a href="#pass2" data-toggle="tab">Pass2</a>
						    </li>
						    <li>
						    	<a href="#linked" data-toggle="tab">Linked</a>
						    </li>
						    <li>
						    	<a href="#loaded" data-toggle="tab">Loaded</a>
						    </li>
						    <li>
						    	<a href="#hex" data-toggle="tab">Hex</a>
						    </li>
						</ul>
    				</div>
    				<div class="tab-content" id="tab_content">
	    				<div class="tab-pane active" id="pass1">
	    					Macros
	    					<table id="macro_table"class="table table-striped"></table>
	    					Symbols
	    					<table id="symbol_table"class="table table-striped"></table>
	    					Variables
	    					<table id="variable_table"class="table table-striped"></table>
	    				</div>
	    				<div class="tab-pane" id="pass2"></div>
	    				<div class="tab-pane" id="linked"></div>
	    				<div class="tab-pane" id="loaded"></div>
	    				<div class="tab-pane" id="hex"></div>

    				</div>
	    		</div>
		</div>
	</div>
	<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">Processing...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>Start</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}" class="uploaded_files" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">Error</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
        <td>
            {% if (file.deleteUrl) { %}
                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" name="delete" value="1" run_sel_value="{%=file.name%}"  class="toggle">
            {% } else { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
    <script src="js/jquery.js"></script>
	<script type="text/javascript" src="js/bootstrap.js"></script>
	<script type="text/javascript" src="js/jquery-ui.js"></script>
	<script type="text/javascript" src="js/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="js/jszip.js"></script>
	<script type="text/javascript" src="js/FileSaver.js"></script>
	<script src="src/ace.js" type="text/javascript" ></script>
	<script type="text/javascript" src="js/custom.js"></script>
	<!-- // <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script> -->
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<!-- <script src="js/vendor/jquery.ui.widget.js"></script> -->
<!-- The Templates plugin is included to render the upload/download listings -->
<!-- <script src="http://blueimp.github.io/JavaScript-Templates/js/tmpl.min.js"></script> -->
<script type="text/javascript" src="js/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<!-- <script src="http://blueimp.github.io/JavaScript-Load-Image/js/load-image.min.js"></script> -->
<script type="text/javascript" src="js/load-image.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<!-- <script src="http://blueimp.github.io/JavaScript-Canvas-to-Blob/js/canvas-to-blob.min.js"></script> -->
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<!-- <script src="//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script> -->
<!-- blueimp Gallery script -->
<!-- <script src="http://blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script> -->
	<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="js/jquery.fileupload-image.js"></script>
<!-- The File Upload audio preview plugin -->
<script src="js/jquery.fileupload-audio.js"></script>
<!-- The File Upload video preview plugin -->
<script src="js/jquery.fileupload-video.js"></script>
<!-- The File Upload validation plugin -->
<script src="js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="js/main.js"></script>
	

</body>
</html>
