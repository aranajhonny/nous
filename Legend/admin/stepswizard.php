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
        
<link href="assets/stepswizard/css/jquery.steps.css" rel="stylesheet"/>
<?php echo "
<link href='assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>
<link href='assets/css/styles.css' rel='stylesheet'/>"; ?>
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
					<li><a href="#">Wizards</a></li>
					<li><a href="#">Steps Wizard</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		                <div class="well">
<div class="header">Mapa del Recorrido<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
			
		                    <div id="wizard3">
		                        <h2>7 Días</h2>
		                        <section>
<iframe src="../../gmaps/examples/recorrido.php?id=2&fi=2014-06-07&ff=2014-06-14" name="recorrido" id="recorrido" height="600" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>
		                        <h2>15 Días</h2>
		                        <section>
<iframe src="../../gmaps/examples/recorrido.php?id=2&fi=2014-05-30&ff=2014-06-14" name="recorrido" id="recorrido" height="600" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>
		                        <h2>1 Mes</h2>
		                        <section>
<iframe src="../../gmaps/examples/recorrido.php?id=2&fi=2014-05-14&ff=2014-06-14" name="recorrido" id="recorrido" height="600" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>
		                        <h2>3 Meses</h2>
		                        <section>
<iframe src="../../gmaps/examples/recorrido.php?id=2&fi=2014-03-14&ff=2014-06-14" name="recorrido" id="recorrido" height="600" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>
                                <h2>6 Meses</h2>
		                        <section>
<iframe src="../../gmaps/examples/recorrido.php?id=2&fi=2013-12-14&ff=2014-06-14" name="recorrido" id="recorrido" height="600" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>
                                <h2>1 Año</h2>
		                        <section>
<iframe src="../../gmaps/examples/recorrido.php?id=2&fi=2013-06-14&ff=2014-06-14" name="recorrido" id="recorrido" height="600" width="915" scrolling="no" style="border:none;background:none;"></iframe>
		                        </section>
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
        
<script src="assets/stepswizard/js/jquery.steps.min.js"></script>
<script> function stepswizard() {
     $("#wizard3").steps({
         headerTag: "h2",
         bodyTag: "section",
         transitionEffect: "none",
         enableFinishButton: false,
         enablePagination: false,
         enableAllSteps: true,
         titleTemplate: "#title#",
         cssClass: "tabcontrol"
     });
 }
stepswizard();</script>
    </body>
</html>