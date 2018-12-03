<?php session_start(); 
include("../complementos/condb.php"); 
/*
$unid = $_REQUEST['unid'];
$ctrl = $_REQUEST['ctrl'];
$sen  = $_REQUEST['sen'];
$unimedcli = $_REQUEST['med'];

$v = $vm = $vM = $vcm = $vcM = "";
$rs = pg_query("select dato, fecha_num from log_sensor where id_unidad = $unid and id_sensor = $sen order by fecha_num asc"); 
while( $r = pg_fetch_array($rs)) { 
	$v   .= "[".$r[1].",".$r[0]."],";
} 

$v   = substr($v,0,(strlen($v)-1));

$rs = pg_query("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and id_unidad = $unid "); 
$rs = pg_fetch_array($rs);
$unidad = $rs[0]." ".$rs[1];

$rs = pg_query("select nombre from controles where id_control = $ctrl");
$rs = pg_fetch_array($rs);
$control = $rs[0];
*/
?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>.:: NousTrack ::.</title>
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<!-- 
<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function() {

var distancias = [<?php //echo $v; ?>];
		
		// Create the chart
		$('#container').highcharts('StockChart', {

		    rangeSelector: {
				inputEnabled: $('#container').width() > 480,
		        selected: 0,
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
		        text: '<?php //echo $control;?> Unidad <?php //echo $unidad;?>'
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
		</script>-->
	</head>
	<body>
<!-- <script src="../highstock/js/highstock.js"></script>
<script src="../highstock/js/modules/exporting.js"></script> 


<div id="container" style="height: 600px; min-width: 310px; width:820px;"></div>-->

<img src="../img/por_definir.jpg" width="960" height="720" />
	</body>
</html>
