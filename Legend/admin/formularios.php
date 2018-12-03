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
		
        <link href="assets/select2/css/select2.css" rel="stylesheet">
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
					<li><a href="#">Components</a></li>
					<li><a href="#">Bootstrap Components</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
                        <div class="well">

<div class="header">Address Form <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            <form name="agregar" method="post" action="agregar.php">
                                <fieldset>
                                
<div class="form-group"><label> Full Name</label>
<input id="full-name" name="full-name" type="text" placeholder="full name" class="form-control" maxlength="200" /></div>

<div class="form-group"><label>Address Line 1</label>
<input id="address-line1" name="address-line1" type="text" placeholder="address line 1" class="form-control"><p class="help-block">Street address, P.O. box, company name, c/o</p></div>


<div class="form-group"><label> Country</label>
<div><select id="country" name="country" class="selectpicker">
	<option value="" selected="selected">Seleccione un Tipo de Cliente</option>
    
    <option value="AF">Afghanistan</option>
    
</select></div></div>
                                
                                </fieldset>
                            </form>
                        </div>
                        
 
                        
                    </div>
                </div>
            </div>
        </section>
        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
        <script src="assets/select2/js/select2.min.js"></script>

        <script src="assets/js/theme.js"></script>
        <script src="assets/js/script.js"></script>
        <script>
			var maxl = jQuery.noConflict();
			maxl("#full-name").maxlength({ alwaysShow: true });
			var selec = jQuery.noConflict();
			selec(".selectpicker").select2();
		 
        
        </script>
    </body>
</html>