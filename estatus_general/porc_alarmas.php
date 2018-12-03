<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$dias = array();
$dias[0] = date("Y-m-d"); 

$qs=pg_query($link, filtrar_sql("select (date '".$dias[0]."' - interval '1 day')::date, (date '".$dias[0]."' - interval '2 day')::date, (date '".$dias[0]."' - interval '3 day')::date, (date '".$dias[0]."' - interval '4 day')::date, (date '".$dias[0]."' - interval '5 day')::date, (date '".$dias[0]."' - interval '6 day')::date "));  
$qs = pg_fetch_array($qs);
$dias[1] = $qs[0]; 
$dias[2] = $qs[1]; 
$dias[3] = $qs[2]; 
$dias[4] = $qs[3]; 
$dias[5] = $qs[4]; 
$dias[6] = $qs[5]; 

$fecha = $atendidos = $sinatender = "";

$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

for($i=0; $i<7; $i++){ 
$aten = $sina = $total = 0;
$rs = pg_query($link, filtrar_sql("select count(id_alarma) from alarmas, unidades where unidades.id_unidad = alarmas.id_unidad and (alarmas.id_cliente=$c and unidades.id_cliente=$c) and ((unidades.id_area=$a or $a < 1) and (unidades.id_zona=$z or $z < 1) and (unidades.id_confunid=$t or $t < 1)) and id_estatus_servicio = 29 and fecha_evento between '".$dias[$i]." 00:00:00' and '".$dias[$i]." 23:59:59'"));
$rs = pg_fetch_array($rs); $aten = $rs[0];

$rs = pg_query($link, filtrar_sql("select count(id_alarma) from alarmas, unidades where unidades.id_unidad = alarmas.id_unidad and (alarmas.id_cliente=$c and unidades.id_cliente=$c) and ((unidades.id_area=$a or $a < 1) and (unidades.id_zona=$z or $z < 1) and (unidades.id_confunid=$t or $t < 1)) and id_estatus_servicio = 31 and fecha_evento between '".$dias[$i]." 00:00:00' and '".$dias[$i]." 23:59:59'") );
$rs = pg_fetch_array($rs); $sina = $rs[0];

$total = $aten + $sina;
if($total<1){
	$sina = $total = 0;
} else { 
	$aten = round(($aten*100)/$total);
	$sina = round(($sina*100)/$total);
}

$fecha .= "'".date1($dias[$i])."',";
$atendidos .= "$aten,";
$sinatender .= "$sina,";
}

$fecha = substr($fecha,0,strlen($fecha)-1);
$atendidos = substr($atendidos,0,strlen($atendidos)-1);
$sinatender = substr($sinatender,0,strlen($sinatender)-1);

?><!DOCTYPE HTML>
<html>
	<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Alarmas Atendidas - Ultimos 7 Días</title>

<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
		<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'Alarmas Atendidas<br/>Ultimos 7 Días'
            },
            xAxis: {
                categories: [<?php echo $fecha;?>]
            },
            yAxis: {
                min: 0,
				max: 100,
                title: {
                    text: 'Porcentajes'
                },
                stackLabels: {
                    enabled: true,
                    style: {
                        fontWeight: 'bold',
                        color: (Highcharts.theme && Highcharts.theme.textColor) || 'gray'
                    }
                }
            },
            legend: {
                reversed: true
            },
			tooltip: {
                formatter: function() {
                    return '<b>'+ this.x +'</b><br/>'+
                        this.series.name +': '+ this.y +'%<br/>'+
                        'Total: 100%';
                }
            },
            plotOptions: {
                series: {
                    stacking: 'normal',
                    dataLabels: {
                        enabled: true,
                        color: (Highcharts.theme && Highcharts.theme.dataLabelsColor) || 'white',
                        style: {
                            textShadow: '0 0 3px black, 0 0 3px black'
                        }
                    }
                }
            },
            series: [{
                name: 'Atendidas',
                data: [<?php echo $atendidos;?>], 
				color: '#00E600'
            }, {
                name: 'Sin Atender',
                data: [<?php echo $sinatender;?>],
				color: '#F00'
            }]
        });
    });
    

		</script>
	</head>
	<body>
<script src="../highcharts/js/highcharts.js"></script>
<div id="container" style="min-width: 310px; max-width: 800px; height: 440px; width:400px; margin: 0 auto"></div>
	</body>
</html>
