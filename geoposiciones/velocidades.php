<?php session_start(); 
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$id = filtrar_campo('int', 6,$_SESSION['seg_unidad']);

$v = $vm = $vM = $vcm = $vcM = "";
$rs = pg_query($link, filtrar_sql("select velocidad_gps, fecha_num, valor_min, valor_max, valor_critico_min, valor_critico_max from log_gps where id_unidad = $id order by fecha_hora_gps asc ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
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
	$vcm = substr($vcm,0,(strlen($vcm)-1));
} 
$rs = pg_query($link, filtrar_sql("select dconfunid, unidades.codigo_principal from unidades  where id_unidad = $id ")); 
$rs = pg_fetch_array($rs);
$unidad = $rs[0]." ".$rs[1];

?><!DOCTYPE HTML>
<html>
	<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>.:: NousTrack ::.</title>
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />

<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<style type="text/css">${demo.css}</style>
<script type="text/javascript">	
var vcM = [<?php echo $vcM; ?>];
var vM  = [<?php echo $vM; ?>];
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
				text : 'Velocidad de la Unidad <?php echo $unidad;?>'
			},
			
			series : [
				{ name : 'Critico Máx',
				data : vcM,
				color: '#06C',
				tooltip: {
					valueDecimals: 2
				} }, 
				
				{ name : 'Máx',
				data : vM,
				color: '#009CE8',
				tooltip: {
					valueDecimals: 2
				} }, 
				
				{ name : 'Valor',
				data : v,
				color: '#0C0',
				tooltip: {
					valueDecimals: 2
				} }, 
				
				{ name : 'Mín',
				data : vm,
				color: '#FC0',
				tooltip: {
					valueDecimals: 2
				} },
				
				{ name : 'Critico Mín',
				data : vcm,
				color: '#FF6464',
				tooltip: {
					valueDecimals: 2
				} }
			]
		});


});

		</script>
	</head>
	<body>
<script src="../highstock/js/highstock.js"></script>
<script src="../highstock/js/modules/exporting.js"></script>

<div id="container" style="height: 620px; min-width: 310px; width:905px;"></div>
	</body>
</html>
