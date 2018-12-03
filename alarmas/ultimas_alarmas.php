<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

?><!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="author" content="Nous Technologies">
	<link rel="shortcut icon" href="../img/icono.png">
	<title>.:: NousTrack ::.</title>
<?php echo '<link href="../controlfrog/css/bootstrap.css" rel="stylesheet">
<link href="../controlfrog/css/controlfrog.css" rel="stylesheet" media="screen"> '; ?>  
	
	<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>    
	<script src="../controlfrog/js/bootstrap.js"></script>
    <script src="../controlfrog/js/controlfrog-plugins.js"></script>
    
    <!--[if lt IE 9]>
		<script src="../controlfrog/js/respond.min.js"></script>
		<script src="../controlfrog/js/excanvas.min.js"></script>
	<![endif]-->
    
    
	<script>
		var themeColour = 'white';
	</script>
    <script src="../controlfrog/js/controlfrog.js"></script>
</head>
<body class="white">


		<div class="row">
			<div class="col-sm-6 cf-item">
				<div class="row">
					<div class="col-sm-12">
						<div class="row">
							
                            <div class="col-sm-6 cf-item">
								<header>
									<p><span></span>Ultimas Alarmas</p>
								</header>
								<div class="content cf-rss">
									<div id="cf-rss-1" class="carousel slide" data-ride="carousel">
										<div class="carousel-inner" style="height:330px;">
<?php 
$a=filtrar_campo('int', 6, $_SESSION['miss'][0]);
$z=filtrar_campo('int', 6, $_SESSION['miss'][1]);
$t=filtrar_campo('int', 6, $_SESSION['miss'][2]);
$c=filtrar_campo('int', 6, $_SESSION['miss'][3]);

$rs = pg_query($link, filtrar_sql("select id_alarma, alarmas.darea, alarmas.dzona, alarmas.dconfunid, unidades.codigo_principal, dcontrol, fecha_evento, min, max, crimin, crimax, dato, estatus.nombre, duracion_min from alarmas, unidades, sensores, estatus where (unidades.id_cliente=$c and sensores.id_cliente=$c and alarmas.id_cliente=$c) and  ((alarmas.id_area = $a or $a < 1) and (alarmas.id_zona = $z or $z < 1) and (alarmas.id_confunid = $t or $t < 1)) and alarmas.id_unidad = unidades.id_unidad and alarmas.id_sensor = sensores.id_sensor and unidades.id_unidad = sensores.id_unidad and alarmas.id_estatus = estatus.id_estatu order by fecha_evento desc limit 36 ")); 
$r = pg_num_rows($rs);  $act="active";  $cont = $r;
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ ?>

<div class="item <?php echo $act;?>"><blockquote class="twitter-tweet" lang="en">
<p style="height:98px;"><?php echo "Unidad ".$r[3]." ".$r[4]." Se Encuentra ".$r[12];?></p>
<?php for($i=0; $i<2; $i++){ if ( $r = pg_fetch_array($rs) ) { ?>
<p style="height:98px;"><?php echo "Unidad ".$r[3]." ".$r[4]." Se Encuentra ".$r[12];?></p><?php } } ?></blockquote></div><?php $act=""; } } ?>  
                                  
</div><ol class="carousel-indicators">

<?php $i=0; $r = $cont; $act=" class='active'";
if($r!=false && $r>0){ $r /= 3; 
for($i=0; $i<$r; $i++){ ?><li data-target="#cf-rss-1" data-slide-to="<?php echo $i;?>" <?php echo $act;?> ></li>
<?php $act="";  } } ?>                                            
										</ol>
									</div>
								</div>
							</div> <!-- //end cf-item -->
                            
						</div> <!-- //end row -->						
					</div> <!-- //end col -->
				</div> <!-- //end row -->
			</div> <!-- //end col -->
		</div>


</body>
</html>