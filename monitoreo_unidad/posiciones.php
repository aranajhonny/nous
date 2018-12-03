<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("util.php");

$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
include("../complementos/permisos.php");


if(isset($_REQUEST['id'])==false){ 
	Auditoria("En Seguimiento de Unidad General Acceso Invalido al Archivo Posiciones",0);
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
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'>"; ?>        

<style>
.wrap {
	margin: 0 auto;
	height: 100%;
	padding: 5px 10px;
	position: relative; }
 
p { font-size: 14px; }

/* Link Styles */
a { color: #0092ff; }
a:hover { 
	color: #45afff;
	text-decoration: none; }

a.dark { color: #555; }
a.dark:hover { 
	color: #999;
	text-decoration: none; }

/* Dynamic Grid Styles */
.wrap > .container {
	max-width: 960px;
	width: 100%;
	height: 100%;
	position: relative;
	padding: 15px 0 15px; }

/* Well Styles */
.well {
	border: 0;
	padding: 20px;
	min-height: 63px;
	background: #fff;
	box-shadow: none;
	border-radius: 3px;
	position: relative;
	max-height: 100000px;
	border-bottom: 2px solid #ccc;
	transition: max-height 0.5s ease;
	-o-transition: max-height 0.5s ease;
	-ms-transition: max-height 0.5s ease;	
	-moz-transition: max-height 0.5s ease;
	-webkit-transition: max-height 0.5s ease;}

.well.no-padding { padding: 0; }

/* Well Controls */
.well .header {
	color: #bbbbbb;
	font-size: 16px;
	margin-bottom: 10px; 
	transition: margin-bottom 0.5s ease; 
	-o-transition: margin-bottom 0.5s ease;
	-ms-transition: margin-bottom 0.5s ease;
	-moz-transition: margin-bottom 0.5s ease;
	-webkit-transition: margin-bottom 0.5s ease; }


.well .header a { 
	color: #bbbbbb;
	cursor: pointer; }

.well .header a i { margin-left: 10px; }

.well .header a:hover {
	color: #aaaaaa;
	text-decoration: none; }

.well .widgetrefresh {
	top: 0;
	left: 0;
	z-index: 98;
	width: 100%;
	height: 100%;
	background: #fff;
	position: absolute; }

.well .widgetrefresh span {
	top: 0;
	left: 0;
	right: 0;
	bottom: 0;
	width: 20px;
	height: 20px;
	margin: auto;
	position: absolute; }

.well.shrinkwell { 
	overflow: hidden;
	max-height: 63px; }

.well.shrinkwell .header { margin-bottom: 25px; }

.well.primarybg { background: #428bca; }

.well.successbg { background: #5cb85c; }

.well.infobg { background: #5bc0de; }

.well.dangerbg { background: #d9534f; }

.well.warningbg { background: #f0ad4e; }
/* accordion */
.accordion .panel-default > .panel-heading {
	color: #555;
	border-color: #dddddd;
	background-color: #fff; }
	
.accordion .panel-heading {
	border-radius: 0;
	padding: 10px 15px;
	border-bottom: 1px solid transparent; }

.accordion .panel-title { font-size: 14px; }

.accordion .panel-title > a:hover {
	color: #aaa;
	text-decoration: none; }

.accordion .panel-group .panel-heading { border-bottom: 1px solid transparent; }

.accordion .panel {
	border-radius: 0;
	box-shadow: none;
	border: 0 !important;
	border-bottom: 1px solid transparent !important; }
	
.accordion .panel-default { border-color: #dddddd; }
	
.accordion .panel-primary { border-color: #428bca; }

.accordion .panel-success { border-color: #5cb85c; }

.accordion .panel-info { border-color: #5bc0de; }

.accordion .panel-warning { border-color: #f0ad4e; }

.accordion .panel-danger { border-color: #d9534f; }

/* ---------- Panel Styles ---------- */

.panel {
	box-shadow: none;
	border: 1px solid #eee;
	background-color: #ffffff;
	border-bottom: 2px solid #ccc; }

.panel-heading { background: #fff !important; }

.item .panel { margin: 10px; }

.panel-title { font-size: 14px; }

.panel-primary > .panel-heading { color: #428bca; }

.list-group-item {
	border-left: 0;
	border-right: 0; }

.list-group-item:first-child,
.list-group-item:last-child { border-radius: 0; }
</style>
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
<img src="../img/reload.png" height="15" width="15" onClick="window.open('ultima_semana.php?id=<?php echo $id;?>','ctrl2');" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse2">1.- Recorrido</a></h4></div>
<div id="collapse2" class="panel-collapse collapse"><div class="panel-body">
<iframe src='ultima_semana.php?id=<?php echo $id;?>' name='ctrl2' id='ctrl2' height='700' width='780' scrolling='no' style='border:none;background:#none;'></iframe>
</div></div></div>

<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">
<img src="../img/reload.png" height="15" width="15" onClick="" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse3">2.- Velocidad</a></h4></div>
<div id="collapse3" class="panel-collapse collapse"><div class="panel-body">
<div id="veloz" style="height: 500px; min-width: 310px; width:770px;"></div>
</div></div></div>

<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title">
<img src="../img/reload.png" height="15" width="15" onClick="" style="margin-right:20px;"/>
<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse4">3.- Distancias</a></h4></div>
<div id="collapse4" class="panel-collapse collapse"><div class="panel-body">
<div id="distanc" style="height: 500px; min-width: 310px; width:770px;"></div>
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

<!-- Graficos -->
<script src="../highstock/js/highstock.js"></script>
<script src="../highstock/js/modules/exporting.js"></script>

<?php 
$unidad  = "";
$v = $vm = $vM = $vcm = $vcM = "";
$rs = pg_query($link, filtrar_sql("select sum(distancia_gps), fecha_hora_gps::date from log_gps where id_unidad = $id group by fecha_hora_gps::date order by fecha_hora_gps::date asc")); 
while( $r = pg_fetch_array($rs)) { 
	$v   .= "[".formato($r[1]).",".$r[0]."],";
} 

$v   = substr($v,0,(strlen($v)-1));

$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and id_unidad = $id ")); 
$rs = pg_fetch_array($rs);
$unidad = $rs[0]." ".$rs[1];


function formato($fecha){ 
	if(strlen($fecha)>=10){ 
$fecha = date('Y-m-d H:i:s',strtotime('-30 minute',strtotime('-4 hour',strtotime($fecha))));
list($h,$m,$s) = explode(":",substr($fecha,11,8));
list($y,$M,$d) = explode("-",substr($fecha,0,10));
$M--;
		return "Number(new Date($y,$M,$d,$h,$m,$s))";
	}
} ?>
<script type="text/javascript">
function distancia() {
var distancias = [<?php echo $v; ?>];
$('#distanc').highcharts('StockChart', {
rangeSelector:{ 
inputEnabled: $('#distanc').width() > 480, 
selected:1,
enabled:true,
buttons:[ {type:'day', count:1, text:'1d'}, {type:'day', count:7, text:'7d'}, {type:'day', count:15, text:'15d'}, {type:'month', count:1, text:'1m'}, {type:'month', count:2, text:'2m'}, {type:'month', count:3, text:'3m'}, {type:'all', text:'All'} ] 
},
title:{ text:'Distancias Recorridas Unidad <?php echo $unidad;?>' },
series:[ {name:'KM', color:'#009CE8', data:distancias, step:true, tooltip:{ valueDecimals:2 } }]
}); }
distancia();</script>

<?php 
$v = $vm = $vM = $vcm = $vcM = "";
$rs = pg_query($link, filtrar_sql("select velocidad_gps, fecha_num, valor_min, valor_max, valor_critico_min, valor_critico_max from log_gps where id_unidad = $id order by fecha_hora_gps asc ")); 
while( $r = pg_fetch_array($rs)) { 
	$vcM .= "[".$r[1].",".$r[5]."],"; 
	$vM  .= "[".$r[1].",".$r[3]."],";
	$v   .= "[".$r[1].",".$r[0]."],";
	$vm  .= "[".$r[1].",".$r[2]."],"; 
	$vcm .= "[".$r[1].",".$r[4]."],";
} 

$vcM = substr($vcM,0,(strlen($vcM)-1));
$vM  = substr($vM,0,(strlen($vM)-1));
$v   = substr($v,0,(strlen($v)-1));
$vm  = substr($vm,0,(strlen($vm)-1));
$vcm = substr($vcm,0,(strlen($vcm)-1));?>
<script type="text/javascript">	
var vcM = [<?php echo $vcM; ?>];
var vM  = [<?php echo $vM; ?>];
var v   = [<?php echo $v; ?>];
var vm  = [<?php echo $vm; ?>];
var vcm = [<?php echo $vcm; ?>];	
function velocidades() {
$('#veloz').highcharts('StockChart', {
rangeSelector: { selected : 1, inputEnabled: $('#veloz').width() > 480, enabled: true, buttons: [{type: 'day', count: 1, text: '1d'},{type: 'day', count: 7, text: '7d'}, {type: 'day', count: 15, text: '15d'}, {type: 'month', count: 1, text: '1m'}, {type: 'month', count: 2, text: '2m'}, {type: 'month', count: 3, text: '3m'}, {type: 'all', text: 'All'}] },	
title: {text : 'Velocidad de la Unidad <?php echo $unidad;?>' }, 
series: [ { name:'Critico Máx', data:vcM, color:'#06C', tooltip:{ valueDecimals: 2 } }, { name:'Máx', data:vM, color:'#009CE8', tooltip:{ valueDecimals: 2 } }, { name:'Valor', data:v, color:'#0C0', tooltip: { valueDecimals: 2 } }, { name:'Mín', data:vm, color:'#FC0', tooltip: { valueDecimals: 2 } }, { name : 'Critico Mín', data: vcm, color: '#FF6464', tooltip: { valueDecimals: 2 } } ] });
}   velocidades();</script>


<?php include("../complementos/closdb.php"); ?>
    </body>
</html>