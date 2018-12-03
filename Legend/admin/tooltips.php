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

<?php echo "
<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'>
<link href='assets/css/styles.css' rel='stylesheet'>
<link href='assets/css/leftmenu.css' rel='stylesheet'>"; ?>
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
					<li><a href="#">Components</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                    
                        <div class="well">
                            <div class="header">Tooltips <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                                    <a class="btn btn-default btn-block btn-gap" rel="tooltip" data-placement="top" title="Tooltip, you can change the position.">
                                    Top tooltip
                                    </a>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 primary-tooltip">
                                    <a class="btn btn-primary btn-block btn-gap" rel="tooltip" data-placement="left" title="Tooltip, you can change the position.">
                                    Left tooltip
                                    </a>
                                </div>
                            </div>
                            <div class="row">

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 info-tooltip">
<a class="btn btn-info btn-block btn-gap" rel="tooltip" data-placement="right" title="Tooltip, you can change the position.">Right tooltip</a>
</div>

                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 success-tooltip">
                                    <a class="btn btn-success btn-block btn-gap" rel="tooltip" data-placement="bottom" title="Tooltip, you can change the position.">
                                    Bottom tooltip
                                    </a>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 warning-tooltip">
                                    <a class="btn btn-warning btn-block btn-gap" rel="tooltip" data-placement="top" title="Tooltip, you can change the position.">
                                    Top tooltip
                                    </a>
                                </div>
                                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 danger-tooltip">
                                    <a class="btn btn-danger btn-block btn-gap" rel="tooltip" data-placement="right" title="Tooltip, you can change the position.">
                                    Right tooltip
                                    </a>
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
        <script src="assets/js/theme.js"></script>
        
        
        <script>$('[rel=tooltip]').tooltip();</script> 
    </body>
</html>