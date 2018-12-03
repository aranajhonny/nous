<?php session_start(); 
include("../complementos/condb.php");
include_once("../complementos/auditoria.php"); 

$id = filtrar_campo('int', 6, $_REQUEST['ctrl']);

$v = $control = "";
$rs = pg_query($link, filtrar_sql("select avg(dato), fecha_evento::date from log_sensor where id_control = $id group by fecha_evento::date order by fecha_evento::date asc")); 
while( $r = pg_fetch_array($rs)) { 
	$v   .= "[".formato($r[1]).",".round($r[0],2)."],";
} 
$v   = substr($v,0,(strlen($v)-1));

$rs = pg_query($link, filtrar_sql("select nombre from controles where id_control = $id"));
$rs = pg_fetch_array($rs);
$control = $rs[0];


function formato($fecha){ 
	if(strlen($fecha)>=10){ 
		list($y,$M,$d) = explode("-",substr($fecha,0,10));
$M -= 1;
		return "Number(new Date($y,$M,$d))";
	}
} 
?><!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highstock Example</title>

		<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
		
$(function() {
	
		var dato= [<?php echo $v; ?>];
		// Create the chart
		$('#container').highcharts('StockChart', {

		    rangeSelector: {
				inputEnabled: $('#container').width() > 480,
		        selected: 1
		    },

		    title: {
		        text: 'Promedio de Lecturas [ <?php echo $control?> ]'
		    },
		    
		    series: [{
		        name: 'AAPL Stock Price',
		        data: dato,
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


<div id="container" style="height: 400px; min-width: 310px"></div>
	</body>
</html>
