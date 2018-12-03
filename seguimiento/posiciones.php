<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");



$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php");




if(isset($_REQUEST['id'])==false){ 
	Auditoria("En Seguimiento de Unidad Especifico Acceso Invalido Archivo Posiciones",0);
	header("location: vacio.php");
	exit();
} else { 
	$id = filtrar_campo('int', 6, $_REQUEST['id']);
}



?><!DOCTYPE html>
<html lang="en">
    <head>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<title>.:: NousTrack ::.</title>
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'>"; ?>        
<link href="../Legend/admin/assets/bootstrapdatatables/css/DT_bootstrap.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'> "; ?>
        <!--[if lt IE 9]>
        <script src="assets/js/html5shiv.js"></script>
        <script src="assets/js/respond.min.js"></script>
        <![endif]-->
    </head>
    <body>
<section class="wrap">
<div class="container">
<div class="row">         
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<div class="panel-group accordion" id="accordion">

<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">
<img src="../img/reload.png" height="15" width="15" onClick="window.open('recorrido.php?id=<?php echo $id;?>','ctrl2');" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse2">1.- Recorrido</a></h4></div>
<div id="collapse2" class="panel-collapse collapse"><div class="panel-body">
<iframe src='recorrido.php?id=<?php echo $id;?>' name='ctrl2' id='ctrl2' height='680' width='830' scrolling='no' style='border:none;background:#none;'></iframe>
</div></div></div>

<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">
<img src="../img/reload.png" height="15" width="15" onClick="window.open('velocidades.php?id=<?php echo $id;?>','ctrl3');" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse3">2.- Velocidad</a></h4></div>
<div id="collapse3" class="panel-collapse collapse"><div class="panel-body">
<iframe src='velocidades.php?id=<?php echo $id;?>' name='ctrl3' id='ctrl3' height='600' width='830' scrolling='no' style='border:none;background:#none;'></iframe>
</div></div></div>

<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">
<img src="../img/reload.png" height="15" width="15" onClick="window.open('distancias.php?id=<?php echo $id;?>','ctrl4');" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse4">3.- Distancias</a></h4></div>
<div id="collapse4" class="panel-collapse collapse"><div class="panel-body">
<iframe src='distancias.php?id=<?php echo $id;?>' name='ctrl4' id='ctrl4' height='600' width='830' scrolling='no' style='border:none;background:#none;'></iframe>
</div></div></div>

</div>
</div>
</div>    
                </div>
            </div>
        </section>
<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../Legend/admin/assets/js/theme.js"></script>
<?php include("../complementos/closdb.php"); ?>
    </body>
</html>