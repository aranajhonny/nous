<?php session_start(); 
include("../complementos/condb.php"); 
include_once("../complementos/auditoria.php"); 

$id = filtrar_campo('int', 6,$_SESSION['seg_unidad']);

$v = $vm = $vM = $vcm = $vcM = "";
$rs = pg_query($link, filtrar_sql("select sum(distancia_gps), fecha_hora_gps::date from log_gps where id_unidad = $id group by fecha_hora_gps::date order by fecha_hora_gps::date asc")); 
while( $r = pg_fetch_array($rs)) { 
	$v   .= "[".formato($r[1]).",".$r[0]."],\n";
} 

$v   = substr($v,0,(strlen($v)-1));

$rs = pg_query($link, filtrar_sql("select dconfunid, unidades.codigo_principal from unidades where id_unidad = $id ")); 
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
$(function() {

var distancias = [<?php echo $v; ?>];
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
		        text: 'Distancias Recorridas Unidad <?php echo $unidad;?>'
		    },
		    
		    series: [{
		        name: 'KM',
				color: '#009CE8',
		        data: distancias,
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


<div id="container" style="height: 500px; min-width: 310px; width:905px;"></div>
	</body>
</html>
