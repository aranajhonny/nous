<?php session_start(); 
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$zona = $cant = "";

$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select count(id_unidad) from unidades where estatus_control <> 'Estable' and  unidades.id_cliente=$c and (id_zona=$z or $z < 1)"));
$rs = pg_fetch_array($rs);
$total = $rs[0];

if($total==0){
	$zona .= "{ label: 'Bajo Control', value: 100 }";
} else {
$rs = pg_query($link, filtrar_sql("select count(id_unidad), zongeo.nombre from unidades, zongeo where zongeo.id_zongeo = unidades.id_zona and estatus_control <> 'Estable' and   (unidades.id_cliente=$c and zongeo.id_cliente=$c) and (zongeo.id_zongeo=$z or $z < 1) group by zongeo.nombre order by count desc"));
	while($r = pg_fetch_array($rs)){ 
		$cant = round(($r[0]*100)/$total);
		$zona .= "{ label: '".$r[1]."', value: $cant },";
		
	}
$zona = substr($zona, 0, (strlen($zona)-1));
}
include("../complementos/closdb.php");?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>% de Unidades Fuera de Control Segun Zona Geográfica</title>
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<?php echo '<link id="beyond-link" href="../beyondadmin/html/assets/css/beyond.min.css" rel="stylesheet" />';?>
</head>
<body style="margin:0px; padding:0px;">
<div style="border-bottom:2px solid #AAA; color:#AAA; font-size:12px; margin:0px; padding:0px; padding-bottom:5px; line-height:18px;"><strong style="font-size:20px;">&bull;</strong> % DE UNIDADES FUERA DE CONTROL SEGUN ZONAS</div>
<div id="donut-chart" class="chart chart-lg"></div>
<script src="../beyondadmin/html/assets/js/jquery-2.0.3.min.js"></script>
<script src="../beyondadmin/html/assets/js/bootstrap.min.js"></script>
<script src="../beyondadmin/html/assets/js/beyond.min.js"></script>
<script src="../beyondadmin/html/assets/js/charts/morris/raphael-2.0.2.min.js"></script>
<script src="../beyondadmin/html/assets/js/charts/morris/morris.js"></script>
<script type="text/javascript">
var InitiateDonutChart = function () {
    return {
        init: function () {
            Morris.Donut({
                element: 'donut-chart',
                data: [<?php echo $zona;?>],
                colors: [themeprimary, themesecondary, themethirdcolor, themefourthcolor],
                formatter: function (y) { return y + "%" }
            });
        }
    };
}();
$(window).bind("load", function () { InitiateDonutChart.init(); });
</script>
</body>
</html>
