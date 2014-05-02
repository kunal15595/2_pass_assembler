<div class="navbar navbar-inverse " role="navigation">
    <div class="container-fluid">
        <div class="navbar-header">
            <!--the button is the button which appear when the page is resized to smaller width-->
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="index.php" class="navbar-brand">
            Assembler</a>
        </div>
        <!--the collapsable content-->
        <div class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li>
                    <a href="#" class="custom_icon_link" onclick="run()">
                        <span class="glyphicon glyphicon-play custom_navbar_icon"></span>
                    Run</a>
                </li>
            </ul>
            
        </div>
    </div>
</div>
<!--Search suggestion.js also contain the code for colour change on hover of custom_navbar_icon-->
<!--Search_suggestion.js also contains the code for popover notification-->

<style>
.custom_navbar_icon{
    color:#aaa;
    font-size: 1.2em;
}
</style>


