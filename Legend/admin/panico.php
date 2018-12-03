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
<link href="assets/bootstrapmodal/css/bootstrap-modal-bs3patch.css" rel="stylesheet" />
<link href="assets/bootstrapmodal/css/bootstrap-modal.css" rel="stylesheet" />      
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
<a class="btn btn-danger btn-block btn-gap" data-toggle="modal" href="#static">Static Modal</a>  

<input type="button" onClick="activar_panico();" value="Activar" />
                                </div>  
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        
        
        
        
        
<div id="static" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false" style="display: none;">
	<div class="modal-body">
		<p>Would you like to continue with some arbitrary task?</p>
	</div>
	<div class="modal-footer">
<button type="button" data-dismiss="modal" class="btn btn-default">Cancel</button>
<button type="button" data-dismiss="modal" class="btn btn-primary">Continue Task</button>
	</div>
</div>
        
        
        
 
        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        
        <script src="assets/bootstrapmodal/js/bootstrap-modalmanager.js"></script>
        <script src="assets/bootstrapmodal/js/bootstrap-modal.js"></script>


    </body>
</html>