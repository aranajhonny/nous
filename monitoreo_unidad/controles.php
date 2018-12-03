<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
include("../complementos/permisos.php");


if(isset($_REQUEST['id'])==false){ 
	header("location: vacio.php");
	exit();
} else { 
	$id = filtrar_campo('int', 6, $_REQUEST['id']);
}


function vistas($unid, $ctrl, $sen, $med, $vista){ 
	switch($vista){ 
		case -1: $dir = "grafica_0.php?unid=$unid&ctrl=$ctrl&med=$med"; break;
		case -2: $dir = "grafica_00.php?unid=$unid&ctrl=$ctrl&med=$med"; break;
		case 1:  $dir = "grafica_01.php?unid=$unid&ctrl=$ctrl&sen=$sen&med=$med"; break;
		case 3: $dir = "grafica_03.php?unid=$unid&ctrl=$ctrl&sen=$sen&med=$med"; break;
		case 5: $dir = "grafica_05.php?unid=$unid&ctrl=$ctrl&sen=$sen&med=$med"; break;
		case 7: $dir = "grafica_07.php?unid=$unid&ctrl=$ctrl&sen=$sen&med=$med"; break;
		case 15: $dir = "grafica_15.php?unid=$unid&ctrl=$ctrl&sen=$sen&med=$med"; break;
		case 16: $dir = "grafica_16.php?unid=$unid&ctrl=$ctrl&sen=$sen&med=$med"; break;
		case 17:  $dir = "mapa_puerta.php?unid=$unid&sen=$sen"; break;
		default: $dir = "vacio.php";
	}
	return $dir;
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

        <div class="overlay"></div>
        <div class="controlshint"><img src="assets/img/swipe.png" alt="Menu Help"></div>
        <section class="wrap">
            <div class="container">
                <div class="row">         
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<div class="panel-group accordion" id="accordion">

<?php $rs = pg_query($link, filtrar_sql("select id_vista, controles.id_control, sensores.id_sensor, controles.id_unimedcli, controles.nombre, tipo_sensores.descripcion from controles, sensores, tipo_sensores where controles.id_control <> 30 and sensores.id_unidad = ".$id." and ( controles.id_cliente = 3 and sensores.id_cliente = 3 ) and ( id_vista <> 4 and id_vista <> 6 ) and tipo_sensores.id_tipo_sensor = sensores.id_tipo_sensor and sensores.id_control = controles.id_control order by nombre asc")); 
$r = pg_num_rows($rs); 
if($r!=false && $r>0){ $i=1; 

$qs = pg_query($link, filtrar_sql("select id_vista, controles.id_control, id_unidad, count(id_unidad) from controles, sensores, tipo_sensores where controles.id_control <> 30 and sensores.id_unidad = ".$id." and ( controles.id_cliente = 3 and sensores.id_cliente = 3 ) and ( id_vista <> 4 and id_vista <> 6 ) and tipo_sensores.id_tipo_sensor = sensores.id_tipo_sensor and sensores.id_control = controles.id_control group by id_vista, controles.id_control, id_unidad"));

$qs = pg_fetch_array($qs);

if($r == $qs[3] && $r>1){ 
$r = pg_fetch_array($rs);?>
<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">
<img src="../img/reload.png" height="15" width="15" onClick="window.open('<?php echo vistas($id, $r[1], 0, $r[3], -1);?>','ctrl<?php echo $i;?>');" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>"><?php echo $i.".- Resumen del ".$r[4];?></a></h4></div>
<div id="collapse<?php echo $i;?>" class="panel-collapse collapse"><div class="panel-body">
<iframe src='<?php echo vistas($id, $r[1], 0, $r[3], -1);?>' name='ctrl<?php echo $i;?>' id='ctrl<?php echo $i;?>' height='620' width='820' scrolling='no' style='border:none;background:#none;'></iframe>
</div></div></div>
<?php $i++; ?>
<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">
<img src="../img/reload.png" height="15" width="15" onClick="window.open('<?php echo vistas($id, $r[1], 0, $r[3], -2);?>','ctrl<?php echo $i;?>');" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>"><?php echo $i.".- Promedio General del ".$r[4];?></a></h4></div>
<div id="collapse<?php echo $i;?>" class="panel-collapse collapse"><div class="panel-body">
<iframe src='<?php echo vistas($id, $r[1], 0, $r[3], -2);?>' name='ctrl<?php echo $i;?>' id='ctrl<?php echo $i;?>' height='620' width='820' scrolling='no' style='border:none;background:#none;'></iframe>
</div></div></div>


<?php } else { 
while($r = pg_fetch_array($rs)){ ?>                               
<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">
<img src="../img/reload.png" height="15" width="15" onClick="window.open('<?php echo vistas($id, $r[1], $r[2], $r[3], $r[0]);?>','ctrl<?php echo $i;?>');" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>"><?php echo $i.".- ".$r[4]." / ".$r[5];?></a></h4></div>
<div id="collapse<?php echo $i;?>" class="panel-collapse collapse"><div class="panel-body">
<iframe src='<?php echo vistas($id, $r[1], $r[2], $r[3], $r[0]);?>' name='ctrl<?php echo $i;?>' id='ctrl<?php echo $i;?>' height='620' width='780' scrolling='no' style='border:none;background:#none;'></iframe>
</div></div></div>
<?php $i++; } } } ?>
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