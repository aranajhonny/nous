<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>Legend</title>
        <link href="assets/humane/css/jackedup.css" rel="stylesheet">
<?php echo "
<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'>
<link href='assets/css/styles.css' rel='stylesheet'>"; ?>
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
            	<ol class="breadcrumb">
					<li><a href="#">Ui Features</a></li>
					<li><a href="#">Alerts</a></li>
					<li><a href="#">Humane Alerts</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
            	<div class="row">
            		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
		            	<div class="well">
	                        <div class="header">Humane JS</div>
	                        <p class="text-muted">
		                        <small>
			                        A simple, modern, framework-independent, well-tested, unobtrusive, notification system. 
		                        </small>
	                        </p>
	                    </div>
	                    <div class="well">
	                        <div class="header">Humane Bold Light</div>
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="boldlight" class="btn btn-default btn-block btn-gap">Bold Light</button>
	                        	</div>
	                        	<div class="clearfix visible-xs"></div>
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="boldlightsuccess" class="btn btn-success btn-block btn-gap">Bold Light Success</button>
	                        	</div>
	                        </div>
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="boldlightinfo" class="btn btn-info btn-block btn-gap">Bold Light Info</button>
	                        	</div>
	                        	<div class="clearfix visible-xs"></div>
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="boldlighterror" class="btn btn-danger btn-block btn-gap">Bold Light Error</button>
	                        	</div>
	                        </div>
	                    </div>
	                    <div class="well">
	                        <div class="header">Humane Big Box</div>
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="bigbox" class="btn btn-default btn-block btn-gap">Big Box</button>
	                        	</div>
	                        	<div class="clearfix visible-xs"></div>
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="bigboxsuccess" class="btn btn-success btn-block btn-gap">Big Box Success</button>
	                        	</div>
	                        </div>
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="bigboxinfo" class="btn btn-info btn-block btn-gap">Big Box Info</button>
	                        	</div>
	                        	<div class="clearfix visible-xs"></div>
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="bigboxerror" class="btn btn-danger btn-block btn-gap">Big Box Error</button>
	                        	</div>
	                        </div>
	                    </div>
	                </div>
	                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                    <div class="well">
	                        <div class="header">Humane Libnotify</div>
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="libnotify" class="btn btn-default btn-block btn-gap">Libnotify</button>
	                        	</div>
	                        	<div class="clearfix visible-xs"></div>
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="libnotifysuccess" class="btn btn-success btn-block btn-gap">Libnotify Success</button>
	                        	</div>
	                        </div>
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="libnotifyinfo" class="btn btn-info btn-block btn-gap">Libnotify Info</button>
	                        	</div>
	                        	<div class="clearfix visible-xs"></div>
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="libnotifyerror" class="btn btn-danger btn-block btn-gap">Libnotify Error</button>
	                        	</div>
	                        </div>
	                    </div>
	                    <div class="well">
	                        <div class="header">Humane Jacked Up</div>
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="jackedup" class="btn btn-default btn-block btn-gap">Jacked Up</button>
	                        	</div>
	                        	<div class="clearfix visible-xs"></div>
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="jackedupsuccess" class="btn btn-success btn-block btn-gap" onClick="humanealerts('Hola Mundo')">Jacked Up Success</button>
	                        	</div>
	                        </div>
	                        <div class="row">
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="jackedupinfo" class="btn btn-info btn-block btn-gap">Jacked Up Info</button>
	                        	</div>
	                        	<div class="clearfix visible-xs"></div>
	                        	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                        		<button type="button" id="jackeduperror" class="btn btn-danger btn-block btn-gap">Jacked Up Error</button>
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
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/humane/js/humane.min.js"></script>

        <script src="assets/js/theme.js"></script>
        <script src="assets/js/script.js"></script>
<script>
function mensaje(texto) {
         var notify = humane.create({
             timeout: 3000,
             baseCls: 'humane-jackedup',
             addnCls: 'humane-jackedup-info'
         });
         notify.log(''+texto);
}</script>
    </body>
</html>