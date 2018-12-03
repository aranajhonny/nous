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
<?php echo "<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>"; ?>        
        <link href="assets/clockface/css/clockface.css" rel="stylesheet"/>
<?php echo "<link href='assets/css/styles.css' rel='stylesheet'/>"; ?>        

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
					<li><a href="#">Form Elements</a></li>
					<li><a href="#">Components</a></li>
					<li><a href="#">Date & Time Pickers</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
                <div class="row">
                	
	                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
	                    <div class="well">
	                        <div class="header">Clock Face <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                            	<div class="input-group">
<input type="text" id="t1" value="2:30 PM" data-format="hh:mm A" readonly="" class="form-control"><span class="input-group-btn"><button class="btn btn-default btn-lg" type="button" id="toggle-btn2"><i class="fa fa-clock-o"></i></button></span>
								</div>
</div></div> 
	        
<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                            
	                            <div class="input-group">
<input type="text" id="t2" value="2:30 PM" data-format="hh:mm A" readonly="" class="form-control"><span class="input-group-btn"><button class="btn btn-default btn-lg" type="button" id="toggle-btn"><i class="fa fa-clock-o"></i></button></span>
	                            </div>
                                
</div></div>

	                        
	                    </div>
	                </div>
	            </div>
            </div>
        </section>
        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>



<script src="assets/clockface/js/clockface.js"></script>
<script>
     $('#t1').clockface({
         format: 'HH:mm',
         trigger: 'manual'
     });
	 $('#toggle-btn2').click(function (e) {
         e.stopPropagation();
         $('#t1').clockface('toggle');
     });
     $('#t2').clockface({
         format: 'HH:mm',
         trigger: 'manual'
     });
     $('#toggle-btn').click(function (e) {
         e.stopPropagation();
         $('#t2').clockface('toggle');
     });
</script>
    </body>
</html>