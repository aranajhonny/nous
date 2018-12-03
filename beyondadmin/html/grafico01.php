<?php session_start(); 
include("../../complementos/condb2.php");

$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);
$rs = pg_query("select count(id_unidad) from unidades where id_cliente = $c or $c = -1 ");
$rs = pg_fetch_array($rs);
$total = $rs[0];

$rs = pg_query("select * from unidades_estables ");
$rs = pg_fetch_array($rs);
$bajo_control = $rs[0];
$porc_bajo = round(($bajo_control*100)/$total);

$rs = pg_query("select * from unidades_inestables");
$rs = pg_fetch_array($rs);
$fuera_control = $rs[0];
$porc_fuera = round(($fuera_control*100)/$total);

include("../../complementos/closdb.php");?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>


<?php echo '    <!--Basic Styles-->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet" />
    <link id="bootstrap-rtl-link" href="" rel="stylesheet" />
    <link href="assets/css/font-awesome.min.css" rel="stylesheet" />
    <link href="assets/css/weather-icons.min.css" rel="stylesheet" />'; ?>

    <!--Fonts-->
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,400,600,700,300" rel="stylesheet" type="text/css">

<?php echo '    <!--Beyond styles-->
    <link id="beyond-link" href="assets/css/beyond.min.css" rel="stylesheet" type="text/css" />
    <link href="assets/css/demo.min.css" rel="stylesheet" />
    <link href="assets/css/typicons.min.css" rel="stylesheet" />
    <link href="assets/css/animate.min.css" rel="stylesheet" />
    <link id="skin-link" href="" rel="stylesheet" type="text/css" />'; ?>
    

    <!--Skin Script: Place this script in head to load scripts for skins and rtl support-->
    <script src="assets/js/skins.min.js"></script>
</head>
<!-- /Head -->
<!-- Body -->
<body>

                        

                            <div class="databox databox-xxlg databox-vertical databox-shadowed bg-white radius-bordered padding-5" style="max-width:900px; max-height:300px;">
                                <div class="databox-top">
                                    <div class="databox-row row-12">
                                        <div class="databox-cell cell-3 text-center">
                                        
                                        
<div class="databox-number number-xxlg sonic-silver"><?php echo $total;?></div>
<div class="databox-text storm-cloud">Unidades</div>


                                        </div>
                                        <div class="databox-cell cell-9 text-align-center">
                                            <div class="databox-row row-6 text-left">
<span class="badge badge-palegreen badge-empty margin-left-5"></span>
<span class="databox-inlinetext uppercase darkgray margin-left-5"><?php echo $bajo_control;?> - Unidades Bajo Control</span>
<span class="badge badge-yellow badge-empty margin-left-5"></span>
<span class="databox-inlinetext uppercase darkgray margin-left-5"><?php echo $fuera_control;?> - Fuera de Control</span>
                                            </div>
                                            <div class="databox-row row-6">
                                                <div class="progress bg-yellow progress-no-radius">
                                                
<div class="progress-bar progress-bar-palegreen" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $porc_bajo;?>%">

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="databox-bottom">
                                    <div class="databox-row row-12">
                                        <div class="databox-cell cell-7 text-center  padding-5">
                                            <div id="dashboard-pie-chart-sources" class="chart"></div>
                                        </div>
                                        <div class="databox-cell cell-5 text-center no-padding-left padding-bottom-30">

<div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
<span class="databox-text sonic-silver pull-left no-margin">Porcentaje</span>
<span class="databox-text sonic-silver pull-right no-margin uppercase">Valor</span>
</div>
                                            
                                            
                                            <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                                <span class="badge badge-green badge-empty pull-left margin-5"></span>

<span class="databox-text darkgray pull-left no-margin hidden-xs">Bajo Control</span>
<span class="databox-text darkgray pull-right no-margin uppercase"><?php echo $porc_bajo;?>%</span>

                                            </div>
                                            <div class="databox-row row-2 bordered-bottom bordered-ivory padding-10">
                                                <span class="badge badge-yellow badge-empty pull-left margin-5"></span>
                                                
<span class="databox-text darkgray pull-left no-margin hidden-xs">Fuera de Control</span>
<span class="databox-text darkgray pull-right no-margin uppercase"><?php echo $porc_fuera;?>%</span>

                                            </div>
                                            
                                            
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                     

                  

    <!--Basic Scripts-->
    <script src="assets/js/jquery-2.0.3.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>

    <!--Beyond Scripts-->
    <script src="assets/js/beyond.min.js"></script>


    <!--Page Related Scripts-->
    <!--Sparkline Charts Needed Scripts-->
    <script src="assets/js/charts/sparkline/jquery.sparkline.js"></script>
    <script src="assets/js/charts/sparkline/sparkline-init.js"></script>

    <!--Easy Pie Charts Needed Scripts-->
    <script src="assets/js/charts/easypiechart/jquery.easypiechart.js"></script>
    <script src="assets/js/charts/easypiechart/easypiechart-init.js"></script>

    <!--Flot Charts Needed Scripts-->
    <script src="assets/js/charts/flot/jquery.flot.js"></script>
    <script src="assets/js/charts/flot/jquery.flot.resize.js"></script>
    <script src="assets/js/charts/flot/jquery.flot.pie.js"></script>
    <script src="assets/js/charts/flot/jquery.flot.tooltip.js"></script>
    <script src="assets/js/charts/flot/jquery.flot.orderBars.js"></script>

    <script>
        // If you want to draw your charts with Theme colors you must run initiating charts after that current skin is loaded
        $(window).bind("load", function () {

            /*Sets Themed Colors Based on Themes*/
            themeprimary = getThemeColorFromCss('themeprimary');
            themesecondary = getThemeColorFromCss('themesecondary');
            themethirdcolor = getThemeColorFromCss('themethirdcolor');
            themefourthcolor = getThemeColorFromCss('themefourthcolor');
            themefifthcolor = getThemeColorFromCss('themefifthcolor');

            //Sets The Hidden Chart Width
            $('#dashboard-bandwidth-chart')
                .data('width', $('.box-tabbs')
                    .width() - 20);

            //-------------------------Visitor Sources Pie Chart----------------------------------------//
            var data = [
                {
                    data: [[1, <?php echo $bajo_control;?>]],
                    color: '#a0d468'
                },
                {
                    data: [[1, <?php echo $fuera_control;?>]],
                    color: '#ffce55'
                }
            ];
            var placeholder = $("#dashboard-pie-chart-sources");
            placeholder.unbind();

            $.plot(placeholder, data, {
                series: {
                    pie: {
                        innerRadius: 0.45,
                        show: true,
                        stroke: {
                            width: 4
                        }
                    }
                }
            });

            //------------------------------Visit Chart------------------------------------------------//
            var data2 = [{
                color: themesecondary,
                label: "Direct Visits",
                data: [[3, 2], [4, 5], [5, 4], [6, 11], [7, 12], [8, 11], [9, 8], [10, 14], [11, 12], [12, 16], [13, 9],
                [14, 10], [15, 14], [16, 15], [17, 9]],

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
                    label: "Referral Visits",
                    data: [[3, 10], [4, 13], [5, 12], [6, 16], [7, 19], [8, 19], [9, 24], [10, 19], [11, 18], [12, 21], [13, 17],
                    [14, 14], [15, 12], [16, 14], [17, 15]],
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
                    color: themethirdcolor,
                    label: "Search Engines",
                    data: [[3, 14], [4, 11], [5, 10], [6, 9], [7, 5], [8, 8], [9, 5], [10, 6], [11, 4], [12, 7], [13, 4],
                    [14, 3], [15, 4], [16, 6], [17, 4]],
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
                    content: " <b>%x May</b> , <b>%s</b> : <span>%y</span>",
                }
            };
            var placeholder = $("#dashboard-chart-visits");
            var plot = $.plot(placeholder, data2, options);

            //------------------------------Real-Time Chart-------------------------------------------//
            var data = [],
                totalPoints = 300;

            function getRandomData() {

                if (data.length > 0)
                    data = data.slice(1);

                // Do a random walk

                while (data.length < totalPoints) {

                    var prev = data.length > 0 ? data[data.length - 1] : 50,
                        y = prev + Math.random() * 10 - 5;

                    if (y < 0) {
                        y = 0;
                    } else if (y > 100) {
                        y = 100;
                    }

                    data.push(y);
                }

                // Zip the generated y values with the x values

                var res = [];
                for (var i = 0; i < data.length; ++i) {
                    res.push([i, data[i]]);
                }

                return res;
            }
            // Set up the control widget
            var updateInterval = 100;
            var plot = $.plot("#dashboard-chart-realtime", [getRandomData()], {
                yaxis: {
                    color: '#f3f3f3',
                    min: 0,
                    max: 100,
                    tickFormatter: function (val, axis) {
                        return "";
                    }
                },
                xaxis: {
                    color: '#f3f3f3',
                    min: 0,
                    max: 100,
                    tickFormatter: function (val, axis) {
                        return "";
                    }
                },
                colors: [themeprimary],
                series: {
                    lines: {
                        lineWidth: 0,
                        fill: true,
                        fillColor: {
                            colors: [{
                                opacity: 0.5
                            }, {
                                opacity: 0
                            }]
                        },
                        steps: false
                    },
                    shadowSize: 0
                },
                grid: {
                    hoverable: true,
                    clickable: false,
                    borderWidth: 0,
                    aboveData: false
                }
            });

            function update() {

                plot.setData([getRandomData()]);

                plot.draw();
                setTimeout(update, updateInterval);
            }
            update();


            //-------------------------Initiates Easy Pie Chart instances in page--------------------//
            InitiateEasyPieChart.init();

            //-------------------------Initiates Sparkline Chart instances in page------------------//
            InitiateSparklineCharts.init();
        });

    </script>
</body>
</html>
