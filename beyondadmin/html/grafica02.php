<?php session_start(); 
include("../../complementos/condb.php");

$id = $_REQUEST['ctrl'];


$v = $min = $max = $cont = "";
$rs = pg_query("select count(dato), avg(dato), min(dato), max(dato), fecha_evento::date from log_sensor where id_control = $id and fecha_evento < '2014-09-16 00:00:01'
group by fecha_evento::date order by fecha_evento::date asc"); 

while( $r = pg_fetch_array($rs) ) { 
	$v    .= "[".formato($r[4]).",".round($r[1],2)."],";
	$cont .= "[".formato($r[4]).",".round($r[0],2)."],";
	$min  .= "[".formato($r[4]).",".round($r[2],2)."],";
	$max  .= "[".formato($r[4]).",".round($r[3],2)."],";
} 

$v = substr($v,0,(strlen($v)-1));
$cont = substr($cont,0,(strlen($cont)-1));
$min = substr($min,0,(strlen($min)-1));
$max = substr($max,0,(strlen($max)-1));


$rs = pg_query("select nombre from controles where id_control = $id");
$rs = pg_fetch_array($rs);
$control = $rs[0];

function formato($fecha){ 
if(strlen($fecha)>10){ 
list($h,$m,$s) = explode(":",substr($fecha,11,8));
list($y,$M,$d) = explode("-",substr($fecha,0,10));
if(($h*1)>12){ $H="PM"; $h=$h-12; } else if(($h*1)==12){ $H="PM"; } else { $H="AM"; }
if(empty($fecha)) return ""; else return 1*$d;
} else { 
list($year,$mes,$dia) = explode("-",$fecha);
if(empty($fecha)){ return ""; } else { return 1*$dia; } 
} 

}

include("../../complementos/closdb.php");?><!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="description" content="flot charts" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="shortcut icon" href="assets/img/favicon.png" type="image/x-icon">

<?php echo '    <!--Basic Styles-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link id="bootstrap-rtl-link" href="" rel="stylesheet" />
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/weather-icons.min.css" rel="stylesheet" />'; ?>

<?php echo '<link id="beyond-link" href="assets/css/beyond.min.css" rel="stylesheet" />'; ?>

    <!--Skin Script: Place this script in head to load scripts for skins and rtl support-->
    <script src="assets/js/skins.min.js"></script>
</head>
<!-- /Head -->
<!-- Body -->
<body>


    <h4 align="center">Resumen de los Ultimos 15 Días<br/><?php echo $control;?></h4>
        <div id="bar-chart" class="chart chart-lg"></div>


    <!--Basic Scripts-->
    <script src="assets/js/jquery-2.0.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--Beyond Scripts-->
    <script src="assets/js/beyond.min.js"></script>

    <!--Page Related Scripts-->
    <script src="assets/js/charts/flot/jquery.flot.js"></script>
    <script src="assets/js/charts/flot/jquery.flot.orderBars.js"></script>
    <script src="assets/js/charts/flot/jquery.flot.tooltip.js"></script>
    <script src="assets/js/charts/flot/jquery.flot.resize.js"></script>

    <script type="text/javascript">
var gridbordercolor = "#eee";

var InitiateFlotBarChart = function () {
    return {
        init: function () {
            var data2 = [{
                color: themesecondary,
                label: "Cantidad de Lecturas",
                data: [<?php echo $cont;?>],

                lines: {
                    show: true,
                    fill: true,
                    lineWidth: .1,
                    fillColor: {
                        colors: [{
                            opacity: 0
                        }, {
                            opacity: 0.4
                        }]
                    }
                },
                points: {
                    show: false
                },
                shadowSize: 0
            },
                {
                    color: themeprimary,
                    label: "Valor Promedio Diario",
                    data: [<?php echo $v;?>],
                    bars: {
                        order: 1,
                        show: true,
                        borderWidth: 0,
                        barWidth: 0.4,
                        lineWidth: .5,
                        fillColor: {
                            colors: [{
                                opacity: 0.4
                            }, {
                                opacity: 1
                            }]
                        }
                    }
                },
                {
                    color: themefifthcolor,
                    label: "Valor Mas Bajo Diario",
                    data: [<?php echo $min;?>],
                    lines: {
                        show: true,
                        fill: false,
                        fillColor: {
                            colors: [{
                                opacity: 0.3
                            }, {
                                opacity: 0
                            }]
                        }
                    },
                    points: {
                        show: true
                    }
                },
                {
                    color: themefourthcolor,
                    label: "Valor Mas Alto Diario",
                    data: [<?php echo $max;?>],
                    lines: {
                        show: true,
                        fill: false,
                        fillColor: {
                            colors: [{
                                opacity: 0.3
                            }, {
                                opacity: 0
                            }]
                        }
                    },
                    points: {
                        show: true
                    }
                }
            ];
            var options = {
                legend: {
                    show: false
                },
                xaxis: {
                    tickDecimals: 0,
                    color: '#f3f3f3'
                },
                yaxis: {
                    min: 0,
                    color: '#f3f3f3',
                    tickFormatter: function (val, axis) {
                        return "";
                    },
                },
                grid: {
                    hoverable: true,
                    clickable: false,
                    borderWidth: 0,
                    aboveData: false,
                    color: '#fbfbfb'

                },
                tooltip: true,
                tooltipOpts: {
                    defaultTheme: false,
                    content: " <b>%x Sep</b> , <b>%s</b> : <span>%y</span>",
                }
            };
            var placeholder = $("#bar-chart");
            var plot = $.plot(placeholder, data2, options);
        }
    };

}();
	
$(window).bind("load", function () {

	themeprimary = getThemeColorFromCss('themeprimary');
	themesecondary = getThemeColorFromCss('themesecondary');
	themethirdcolor = getThemeColorFromCss('themethirdcolor');
	themefourthcolor = getThemeColorFromCss('themefourthcolor');
	themefifthcolor = getThemeColorFromCss('themefifthcolor');
	
	InitiateFlotBarChart.init();
});
    </script>
</body>
<!--  /Body -->
</html>
