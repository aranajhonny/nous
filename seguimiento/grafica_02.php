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

$unidad ="";
$v = $vm = $vM = $vcm = $vcM = "";
$rs = pg_query($link, filtrar_sql("select dato, fecha_num from log_sensor where id_unidad = $unid and id_sensor = $sen order by fecha_num asc")); 

while( $r = pg_fetch_array($rs)) { 
	$v   .= "[".$r[1].",".$r[0]."],";
} 

$v   = substr($v,0,(strlen($v)-1));

$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and id_unidad = $unid "));
$rs = pg_fetch_array($rs);
$unidad = $rs[0]." ".$rs[1];

$rs = pg_query($link, filtrar_sql("select nombre from controles where id_control = $ctrl"));
$rs = pg_fetch_array($rs);
$control = $rs[0];


?><!DOCTYPE HTML>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>.:: NousTrack ::.</title>
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />

<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<style type="text/css">${demo.css}</style>
<script type="text/javascript">	

var v   = [<?php echo $v; ?>];
		
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
				text : '<?php echo $control;?> Unidad <?php echo $unidad;?>'
			},
			
			series : [
				{ name : 'Valor',
				data : v,
				color: '#0C0',
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

<div id="container" style="height: 600px; min-width: 310px; width:820px;"></div>
	</body>
</html>
<?php } include("../complementos/closdb.php"); ?>