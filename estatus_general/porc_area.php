<?php session_start(); 
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$area = $cant = "";

$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select count(id_unidad) from unidades where estatus_control <> 'Estable' and  unidades.id_cliente=$c and (id_area=$a or $a < 1)"));
$rs = pg_fetch_array($rs);
$total = $rs[0];

if($total==0){
	$area .= "{ label: 'Bajo Control', value: 100 }";
} else {
$rs = pg_query($link, filtrar_sql("select count(id_unidad), areas.descripcion from unidades, areas where areas.id_area = unidades.id_area and estatus_control<>'Estable' and  
(unidades.id_cliente=$c and areas.id_cliente=$c) and (areas.id_area=$a and $a>1) group by areas.descripcion order by count desc"));
	while($r = pg_fetch_array($rs)){ 
		$cant = round(($r[0]*100)/$total);
		$area .= "{ label: '".$r[1]."', value: $cant },";
		
	}
$area = substr($area, 0, (strlen($area)-1));
}
include("../complementos/closdb.php");?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<title>% de Unidades Fuera de Control Segun Áreas</title>
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<?php echo '<link id="beyond-link" href="../beyondadmin/html/assets/css/beyond.min.css" rel="stylesheet" />';?>
</head>
<body style="margin:0px; padding:0px;">
<div style="border-bottom:2px solid #AAA; color:#AAA; font-size:12px; margin:0px; padding:0px; padding-bottom:5px; line-height:18px;"><strong style="font-size:20px;">&bull;</strong> % DE UNIDADES FUERA DE CONTROL SEGUN ÁREA</div>
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
                data: [<?php echo $area;?>],
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
