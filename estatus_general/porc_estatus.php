<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$rs = pg_query($link, filtrar_sql("select count(id_unidad) from unidades"));
$rs = pg_fetch_array($rs);
$total = $rs[0];

$rs = pg_query($link, filtrar_sql("select count(id_unidad) from unidades where estatus_control = 'Estable'"));
$rs = pg_fetch_array($rs);
$atendida = $rs[0];

$rs = pg_query($link, filtrar_sql("select count(id_unidad) from unidades where estatus_control <> 'Estable'"));
$rs = pg_fetch_array($rs);
$sinatender = $rs[0];

$porc_aten = round(($atendida*100)/$total);
$porc_sina = round(($sinatender*100)/$total);


?><!DOCTYPE HTML>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Nous Technologies">
<link rel="shortcut icon" href="../img/icono.png">
<title>.:: NousTrack ::.</title>
<style>
* { font-family: sans-serif; }
</style>
<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">
$(function () {
    var chart;
    
    $(document).ready(function () {
    	
    	// Build the chart
        $('#container').highcharts({
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: ''
            },
            tooltip: {
        	    pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                }
            },
            series: [{
                type: 'pie',
				innerSize: '50%',
                name: 'Porcentaje',
                data: [
                    {
                        name: '<?php echo $porc_aten; ?>% Bajo Control',
                        y: <?php echo $porc_aten; ?>,
						color: '#00E600'
                    },
                    { 
						name: '<?php echo $porc_sina; ?>% Fuera de Control',
						y: <?php echo $porc_sina; ?>,
						color: '#F00'
					}
                ]
            }]
        });
    });
    
});
		</script>
  
</head>
<body>
<script src="../highcharts/js/highcharts.js"></script>
<script src="../highcharts/js/themes/grid-light.js"></script>
<div style="min-width:310px; border-bottom:1px solid #999; padding-bottom:4px; margin:0px;">
<label style="color:#999; font-size:24px; line-height:10px;">&bull;</label>
<label style="color:#999; font-size:12px; line-height:14px;"> % DE UNIDADES SEGUN ESTATUS</label>
</div>
<div id="container" style="min-width: 310px; height: 270px; margin: 0 auto"></div></body>
</html>