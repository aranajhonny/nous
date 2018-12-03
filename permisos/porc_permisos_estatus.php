<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select count(id_permiso) from permisos where id_cliente=$c and (id_area=$a or $a < 1) and (id_zona=$z or $z < 1) "));
$rs = pg_fetch_array($rs); $total = $rs[0];

$rs = pg_query($link, filtrar_sql("select count(id_permiso) from permisos where id_estatus = 9 and id_cliente=$c and (id_area=$a or $a < 1) and (id_zona=$z or $z < 1) "));
$rs = pg_fetch_array($rs); $vigente = $rs[0];

$rs = pg_query($link, filtrar_sql("select count(id_permiso) from permisos where id_estatus = 10 and id_cliente=$c and (id_area=$a or $a < 1) and (id_zona=$z or $z < 1) "));
$rs = pg_fetch_array($rs); $tramitando = $rs[0];

$rs = pg_query($link, filtrar_sql("select count(id_permiso) from permisos where id_estatus = 11 and id_cliente=$c and (id_area=$a or $a < 1) and (id_zona=$z or $z < 1) "));
$rs = pg_fetch_array($rs); $vencidos = $rs[0];

$porc_vig = round(($vigente*100)/$total);
$porc_tra = round(($tramitando*100)/$total);
$porc_ven = round(($vencidos*100)/$total);

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
        	    pointFormat: '{series.name}'
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
                        name: 'Vigentes <?php echo $porc_vig; ?>%',
                        y: <?php echo $porc_vig; ?>,
						color: '#00E600'
                    },
					{
                        name: 'Tranmitando <?php echo $porc_tra; ?>%',
                        y: <?php echo $porc_tra; ?>,
						color: '#FFCC00'
                    },
                    { 
						name: 'Vencidos <?php echo $porc_ven; ?>%',
						y: <?php echo $porc_ven; ?>,
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
<label style="color:#999; font-size:12px; line-height:14px;"> % DE PERMISOS SEGUN ESTATUS</label>
</div>
<div id="container" style="min-width: 310px; height: 420px; margin: 0 auto;"></div></body>
</html>