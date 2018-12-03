<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

?><!DOCTYPE html>
<html lang="en"><head>
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<title>.:: NousTrack ::.</title>
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'>"; ?>        

<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'> "; ?>
        <!--[if lt IE 9]>
        <script src="../Legend/admin/assets/js/html5shiv.js"></script>
        <script src="../Legend/admin/assets/js/respond.min.js"></script>
        <![endif]-->
</head>
<body style="background:#FFF;">
<div class="row">
          
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<div class="header" style="color:#F00;">Permisos Vencidos</div>
<div class="panel-group accordion" id="accordion">
            
<?php 
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select id_unidad, tipo_permisos.nombre, serial, fecha_vencimiento from permisos, tipo_permisos where (tipo_permisos.id_cliente=$c and permisos.id_cliente=$c) and 
(id_zona=$z or $z<1) and (id_area=$a or $a<1) and permisos.id_tipo_permiso = tipo_permisos.id_tipo_permiso and fecha_vencimiento < '".date('Y-m-d')."' and id_estatus = 11 order by fecha_vencimiento asc")); 
$r = pg_num_rows($rs);
if($r<>false && $r>0){ $i=1;
	while ($r = pg_fetch_array($rs)){ 

$qs=pg_query($link, filtrar_sql("select confunid.codigo_principal, UPPER(n_configuracion_01), UPPER(n_configuracion_02), UPPER(n_configuracion_03), UPPER(n_configuracion_04), unidades.codigo_principal, n_configuracion1, n_configuracion2, n_configuracion3, n_configuracion4, ult_act, areas.descripcion, zongeo.nombre from unidades, confunid, areas, zongeo where unidades.id_zona = zongeo.id_zongeo and areas.id_area = unidades.id_area and unidades.id_confunid = confunid.id_confunid and id_unidad = ".$r[0])); 
$qs = pg_fetch_array($qs); 
$titulo = $r[1]." ".$r[2]; 
$texto  = "<strong style='color:#F00;'>Fecha de Vencimiento:. ".date1($r[3])." "."</strong><br/><strong style='color:#0093D9;'>Unidad: ".$qs[0]." ".$qs[5]."</strong><br/>";
$texto .= "<strong style='color:#0093D9;'>Área: ".$qs[11]."</strong><br/>";
$texto .= "<strong style='color:#0093D9;'>Zona Geográfica: ".$qs[12]."</strong><br/>";
$texto .= $qs[1].": ".$qs[6]."<br/>";
$texto .= $qs[2].": ".$qs[7]."<br/>";
$texto .= $qs[3].": ".$qs[8]."<br/>";
$texto .= $qs[4].": ".$qs[9]."<br/>";?>            
<div class="panel panel-default">
<div class="panel-heading"><h4 class="panel-title"><a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $i;?>">
<?php echo $i;?>°  <?php echo $titulo;?></a></h4></div>
<div id="collapse<?php echo $i;?>" class="panel-collapse collapse"><div class="panel-body">
<?php echo $texto; ?>
</div></div></div>
<?php $i++; } } ?>                                

</div></div></div>

<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>

<script src="../Legend/admin/assets/js/theme.js"></script>
<?php include("../complementos/closdb.php"); ?>
</body>
</html>