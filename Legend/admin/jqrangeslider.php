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
        <?php echo '<link href="assets/bootstrap/css/bootstrap.css" rel="stylesheet">
        <link href="assets/jqrangeslider/css/iThing-min.css" rel="stylesheet">
        <link href="assets/css/styles.css" rel="stylesheet">
        <link href="assets/css/leftmenu.css" rel="stylesheet">'; ?>
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
					<li><a href="#">Range Sliders</a></li>
					<li><a href="#">jQuery Range Slider</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
                <div class="row">
                	<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                    <div class="well">
	                    	<div class="header">jQRangeSlider <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<p class="text-muted">
	                    		<small>A powerful slider for selecting value ranges, supporting dates and more.</small>
	                    	</p>
	                    </div>
	                    <div class="well">
	                    	<div class="header">jQRangeSlider Advance Examples <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="form-group">
	                    		<p class="text-muted">
	                    			<small>Range slider with ruler</small>
	                    		</p>
	                    		<div id="slider4"></div>
	                    	</div>
	                    	<div class="form-group">
	                    		<p class="text-muted">
	                    			<small>A date slider with months</small>
	                    		</p>
	                    		<div id="slider5"></div>
	                    	</div>
	                    </div>
	                </div>
	                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                    <div class="well">
	                    	<div class="header">jQRangeSlider Examples <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	                    	<div class="form-group">
	                    		<p class="text-muted">
	                    			<small>A basic range slider</small>
	                    		</p>
	                    		<div id="slider"></div>
	                    	</div>
	                    	<div class="form-group">
	                    		<p class="text-muted">
	                    			<small>A date range slider</small>
	                    		</p>
	                    		<div id="slider2"></div>
	                    	</div>
	                    	<div class="form-group">
	                    		<p class="text-muted">
	                    			<small>An editable slider</small>
	                    		</p>
	                    		<div id="slider3"></div>
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
        <script src="assets/jqrangeslider/js/jQAllRangeSliders-min.js"></script>
        <script src="assets/jqrangeslider/js/jQAllRangeSliders-withRuler-min.js"></script>
  
        <script src="assets/js/theme.js"></script>
        <script src="assets/js/script.js"></script>
        <script>jqrangesliders();</script>
    </body>
</html>