<?php session_start(); 
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$tot = $ven = $tra = array(0,0,0,0,0,0,0,0,0,0,0,0,0);

$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select count(id_permiso), date_part('month' , fecha_vencimiento) from permisos where id_cliente=$c and (id_zona=$z or $z < 1) and (id_area=$a or $a < 1) and fecha_vencimiento between '".date('Y')."-01-01' and '".date('Y')."-12-31' group by date_part order by date_part asc"));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ 
	$ven[$r[1]] = $r[0];
	$tot[$r[1]] = $r[0];
} }

$rs = pg_query($link, filtrar_sql("select count(id_permiso), date_part('month', fecha_vencimiento  - ( dias_gestion * interval '1 day' )) as ft2 from permisos, tipo_permisos 
where (tipo_permisos.id_cliente=$c and permisos.id_cliente=$c) and 
(id_zona=$z or $z < 1) and (id_area=$a or $a < 1) and permisos.id_tipo_permiso = tipo_permisos.id_tipo_permiso and (fecha_vencimiento - ( dias_gestion * interval '1 day' ))::date between '".date('Y')."-01-01' and '".date('Y')."-12-31' group by ft2 order by ft2 asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ 
	$tra[$r[1]] = $r[0];
	if($tot[$r[1]] < $r[0]) $tot[$r[1]] = $r[0];
} }

$ven = $ven[1].",".$ven[2].",".$ven[3].",".$ven[4].",".$ven[5].",".$ven[6].",".$ven[7].",".$ven[8].",".$ven[9].",".$ven[10].",".$ven[11].",".$ven[12]; 

$tra = $tra[1].",".$tra[2].",".$tra[3].",".$tra[4].",".$tra[5].",".$tra[6].",".$tra[7].",".$tra[8].",".$tra[9].",".$tra[10].",".$tra[11].",".$tra[12];

$tot = $tot[1].",".$tot[2].",".$tot[3].",".$tot[4].",".$tot[5].",".$tot[6].",".$tot[7].",".$tot[8].",".$tot[9].",".$tot[10].",".$tot[11].",".$tot[12];

?><!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>.:: NousTrack ::.</title>
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<script type="text/javascript" src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">
$(function () {
        $('#container').highcharts({
            chart: {
                type: 'column'
            },
            title: {
                text: 'Carga de Trabajo Anual para Permisos'
            },
            xAxis: {
categories: ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic']
            },
            yAxis: {
                min: 0,
                title: { text: 'Nro de Permisos a Gestionar' }
            },
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                    '<td style="padding:0"><b>{point.y} </b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                }
            },
            series: [{
                name: 'Total de Permisos Pendientes',
				color: '#9900FF',
                data: [<?php echo $tot; ?>]
    
            }, {
                name: 'Permisos Por Tramitar',
				color: '#FFCC00',
                data: [<?php echo $tra; ?>]
    
            }, {
                name: 'Permisos Próximos a Vencer',
				color: '#CC3333',
                data: [<?php echo $ven; ?>]
    
            }]
        });
    });
    

		</script>
	</head>
	<body>
<script src="../highcharts/js/highcharts.js"></script>
<script src="../highcharts/js/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto;"></div>

	</body>
<?php include("../complementos/closdb.php"); ?>
</html>