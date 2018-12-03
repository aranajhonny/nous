<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="assets/ico/apple-touch-icon-144-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="assets/ico/apple-touch-icon-114-precomposed.png">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="assets/ico/apple-touch-icon-72-precomposed.png">
        <link rel="apple-touch-icon-precomposed" href="assets/ico/apple-touch-icon-57-precomposed.png">
        <link rel="shortcut icon" href="assets/ico/favicon.png">
        <title>Legend</title>
<?php echo "<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'>"; ?>
        <link href="assets/vex/css/vex.css" rel="stylesheet" />
        <link href="assets/vex/css/vex-theme-default.css" rel="stylesheet" />
        <link href="assets/vex/css/vex-theme-os.css" rel="stylesheet" />
        <link href="assets/vex/css/vex-theme-top.css" rel="stylesheet" />
        <link href="assets/vex/css/vex-theme-plain.css" rel="stylesheet" />
        <link href="assets/vex/css/vex-theme-flat-attack.css" rel="stylesheet" />
        <link href="assets/vex/css/vex-theme-wireframe.css" rel="stylesheet" />
<?php echo "<link href='assets/css/styles.css' rel='stylesheet'>"; ?>
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>

        <div class="overlay"></div>
        <div class="controlshint"><img src="assets/img/swipe.png" alt="Menu Help"></div>
        <section class="wrap">
            <div class="container">
                <div class="row">
	                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                    <div class="well">
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<button type="button" data-theme="vex-theme-flat-attack" class="btn btn-primary btn-block btn-gap">Flat Attack Theme</button>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                
                </div>
            </div>
        </section>
        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>
        <script src="assets/bootstrapui/js/jquery-ui-1.9.2.custom.min.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/vex/js/vex.js"></script>
        <script src="assets/vex/js/vex.dialog.js"></script>
        <script src="assets/js/leftmenu.js"></script>
        <script src="assets/js/theme.js"></script>
        <script src="assets/js/script.js"></script>
        <script>
		
function vexalerts() {
     vex.defaultOptions.className = 'vex-theme-os';
     $('[data-theme]').each(function () {
         $(this).click(function (e) {
             e.preventDefault();
             vex.dialog.alert({
                 message: '',
                 className: $(this).data('theme')
             });
             return false;
         });
     });
}
 
 
 vexalerts();</script>
    </body>
</html>