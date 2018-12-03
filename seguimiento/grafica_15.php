<?php session_start(); 
include("../complementos/condb.php"); 
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 41;
$_SESSION['acc']['form'] = 148;
include("../complementos/permisos.php"); 

if(isset($_REQUEST['unid'])==false || isset($_REQUEST['ctrl'])==false || isset($_REQUEST['sen'])==false || isset($_REQUEST['med'])==false ){ 

Auditoria("En Seguimiento de Unidad General Acceso Invalido a Control ",0);
header("location: vacio.php");
exit();

} else {
	
$unid = filtrar_campo('int', 6,$_REQUEST['unid']);
$ctrl = filtrar_campo('int', 6,$_REQUEST['ctrl']);
$sen  = filtrar_campo('int', 6,$_REQUEST['sen']);
$unimedcli = filtrar_campo('int', 6,$_REQUEST['med']);

$unidad = "";
$v = $vm = $vM = $vcm = $vcM = "";
$rs = pg_query($link, filtrar_sql("select SUM(tiempo_horometro(id_log_sensor, fecha_evento)), fecha_evento::date from log_sensor where id_sensor = $sen and dato=2 group by fecha_evento::date order by fecha_evento::date asc"));
 
while( $r = pg_fetch_array($rs)) { 
	$v   .= "[".formato($r[1]).",".round(($r[0]/60),2)."],";
} 

$v   = substr($v,0,(strlen($v)-1));

$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and id_unidad = $unid ")); 
$rs = pg_fetch_array($rs);
$unidad = $rs[0]." ".$rs[1];

$rs = pg_query($link, filtrar_sql("select magnitudes.nombre, unidmed.nombre from unimedcli, magnitudes, unidmed where id_unimedcli = $unimedcli and unimedcli.id_magnitud = magnitudes.id_magnitud and unimedcli.id_unidmed = unidmed.id_unidmed"));

$rs = pg_fetch_array($rs);
$mag = $rs[0];
$unid = $rs[0]."(".$rs[1].")"; 


function formato($fecha){ 
	if(strlen($fecha)>=10){ 
$fecha = date('Y-m-d H:i:s',strtotime('-30 minute',strtotime('-4 hour',strtotime($fecha))));
list($h,$m,$s) = explode(":",substr($fecha,11,8));
list($y,$M,$d) = explode("-",substr($fecha,0,10));
$M--;
		return "Number(new Date($y,$M,$d,$h,$m,$s))";
	}
} ?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>.:: NousTrack ::.</title>
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />

<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function() {

var horas = [<?php echo $v; ?>];
		
		// Create the chart
		$('#container').highcharts('StockChart', {

		    rangeSelector: {
				inputEnabled: $('#container').width() > 480,
		        selected: 1,
				enabled: true,
				buttons: [{type: 'day', count: 1, text: '1d'},
						  {type: 'day', count: 7, text: '7d'},
						  {type: 'day', count: 15, text: '15d'},
						  {type: 'month', count: 1, text: '1m'}, 
						  {type: 'month', count: 2, text: '2m'},
						  {type: 'month', count: 3, text: '3m'},  
						  {type: 'all', text: 'All'}]
		    },

		    title: {
		        text: 'Hora de Motor Encendido Unidad <?php echo $unidad;?>'
		    },
		    
		    series: [{
		        name: 'Hrs',
				color: '#009CE8',
		        data: horas,
		        step: true,
		        tooltip: {
		        	valueDecimals: 2
		        }
		    }]
		});

});
		</script>
	</head>
	<body>
<script src="../highstock/js/highstock.js"></script>
<script src="../highstock/js/modules/exporting.js"></script>


<div id="container" style="height: 600px; min-width: 310px; width:820px;"></div>
	</body>
</html>
<?php include("../complementos/closdb.php"); } ?>