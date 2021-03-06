<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">

<link href="assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet" />

<?php echo "<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'>
<link href='assets/css/styles.css' rel='stylesheet'>"; 

?>
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
					<li><a href="#">jQuery Ui Slider</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
                <div class="row">
  

	                    
	                <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
	                    <div class="well">
	                    	<div class="header">jQuery Ui Horizontal Sliders <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            
                            
	                    	<div class="row">
	                            <div class="col-xs-5 col-sm-4 col-md-4 col-lg-4">
<small class="text-muted">
Porcentaje de Tolerancia:<input type="text" name="porc" id="porc" value="0" readonly="readonly" />%</small>
	                            </div>
	                            <div class="col-xs-7 col-sm-8 col-md-8 col-lg-8">
	                                <div id="slider" class="ui-slider-primary"></div>
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

        <script src="assets/js/theme.js"></script>
<script> var barra = jQuery.noConflict();
barra("#slider").slider({
	animate: true,
	range: "min",
	value: 0,
	min: 1,
	max: 100,
	slide: function (event, ui) {
		document.getElementById('porc').value=ui.value;
	}
});
</script>
    </body>
</html>