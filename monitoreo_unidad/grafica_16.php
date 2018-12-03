<?php session_start(); 
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 42;
$_SESSION['acc']['form'] = 149;
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

$v = $vm = $vM = $vcm = $vcM = "";
$rs = pg_query($link, filtrar_sql("select fecha_evento::date, COUNT(id_log_sensor), SUM(tiempo_puerta(id_log_sensor, fecha_evento)), SUM(tiempo_puerta(id_log_sensor, fecha_evento))/COUNT(id_log_sensor) from log_sensor 
where id_sensor = $sen and dato=2 group by fecha_evento::date order by fecha_evento::date asc ")); 
while( $r = pg_fetch_array($rs)) { 

	$v   .= "[".formato($r[0]).",".$r[1]."],";
	$vm  .= "[".formato($r[0]).",".$r[2]."],"; 
	$vcm .= "[".formato($r[0]).",".$r[3]."],";
} 

$v   = substr($v,0,(strlen($v)-1));
$vm  = substr($vm,0,(strlen($vm)-1));
$vcm = substr($vcm,0,(strlen($vcm)-1));

$rs = pg_query($link, filtrar_sql("select dconfunid, unidades.codigo_principal from unidades where id_unidad = $unid ")); 
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
<style type="text/css">${demo.css}</style>
<script type="text/javascript">	

var v   = [<?php echo $v; ?>];
var vm  = [<?php echo $vm; ?>];
var vcm = [<?php echo $vcm; ?>];	
		
$(function() {
		$('#container').highcharts('StockChart', {
		
			rangeSelector : {
				selected : 0,
				inputEnabled: $('#container').width() > 480,
				enabled: true,
				buttons: [{type: 'day', count: 1, text: '1d'},
						  {type: 'day', count: 7, text: '7d'},
						  {type: 'day', count: 15, text: '15d'},
						  {type: 'month', count: 1, text: '1m'}, 
						  {type: 'month', count: 2, text: '2m'},
						  {type: 'month', count: 3, text: '3m'},  
						  {type: 'all', text: 'All'}]
			},

			
			title : {
				text : 'Puerta de Cava de la Unidad <?php echo $unidad;?>'
			},
			
			series : [
				{ name : 'Nro de Veces Abierta',
				data : v,
				color: '#0C0',
				tooltip: {
					valueDecimals: 0
				} }, 
				
				{ name : 'Prom. Minutos Abierta',
				data : vcm,
				color: '#FC0',
				tooltip: {
					valueDecimals: 0
				} },
				
				{ name : 'Total Minutos Abierta',
				data : vm,
				color: '#FF6464',
				tooltip: {
					valueDecimals: 0
				} }
			]
		});


});

		</script>
	</head>
	<body>
<script src="../highstock/js/highstock.js"></script>
<script src="../highstock/js/modules/exporting.js"></script>

<div id="container" style="height: 500px; min-width: 310px; width:800px;"></div>
	</body>
</html>
<?php } ?>