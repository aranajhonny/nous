<?php 

print_r($_FILES);


?><!DOCTYPE html>
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
        <title>Legend</title><?php echo "
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
					<li><a href="#">Form Elements</a></li>
					<li><a href="#">Upload</a></li>
					<li><a href="#">Input File Style</a></li>
					<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
				</ol>
            	<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		                <div class="well">
		                	<div class="header">Input File Style <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
                            
<form name="agregar" method="post" action="inputfilestyle.php" enctype="multipart/form-data">
<fieldset>                            
                            
		                	<p class="text-muted">
		                		<small>This turns a file input into a bootstrap styled input.</small>
		                	</p>
		                	<div class="row">
                            
		                		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>1.- Foto</label><input id="foto1" name="foto1" accept="image/*" type="file" class="filestyle" data-classButton="btn btn-default btn-lg" data-input="false"></div>

<div class="form-group"><label>2.- Foto</label><input id="foto2" name="foto2" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-primary btn-lg" data-input="false"></div>

<div class="form-group"><label>3.- Foto</label><input id="foto3" name="foto3" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-info btn-lg" data-input="false"></div>

<div class="form-group"><label>4.- Foto</label><input id="foto4" name="foto4" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-success btn-lg" data-input="false"></div>
                                    
<div class="form-group"><label>5.- Foto</label><input id="foto5" name="foto5" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-warning btn-lg" data-input="false"></div>

<div class="form-group"><label>6.- Foto</label><input id="foto6" name="foto6" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-danger btn-lg" data-input="false"></div>
		                		</div>
		                		<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">	      	
<div class="form-group"><label>Descripción</label><input id="des1" name="des1" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des1;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des2" name="des2" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des2;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des3" name="des3" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des3;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des4" name="des4" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des4;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des5" name="des5" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des5;?>" onkeypress="return permite(event,'todo')" /></div>

<div class="form-group"><label>Descripción</label><input id="des6" name="des6" type="text" placeholder="Breve Descripción de la Foto" class="form-control" maxlength="120" value="<?php echo $des6;?>" onkeypress="return permite(event,'todo')" /></div>
		                		</div>
                                
		                	</div>
             <input type="submit" value="Prueba"/>               
</fieldset></form>
                            
		                </div>
                	</div>
                </div>
            </div>
        </section>
        <script src="../../jquery/development-bundle/jquery-1.10.2.js"></script>
        <script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
        <script src="../../jquerymobile/jquery.mobile.custom.js"></script>
        <script src="assets/bootstrap/js/bootstrap.min.js"></script>
        <script src="assets/bootstrapfilestyle/js/bootstrap-filestyle.js"></script>

    </body>
</html>