<?php session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
	
$fecha = "2014-01-01 12:00:00";

$rs = pg_query($link, filtrar_sql("select fecha_hora_ultima_transmision()"));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
	$rs = pg_fetch_array($rs);
	$fecha = $rs[0];
} 

list($h,$m,$s) = explode(":",substr($fecha,11,8));
list($y,$M,$d) = explode("-",substr($fecha,0,10));
if(($h*1)>12){ $H="PM"; $h=$h-12; } else if(($h*1)==12){ $H="PM"; } else { $H="AM"; } 

$dias = array("Lunes", "Martes", "Miercoles", "Jueves", "Viernes", "Sabado","Domingo");
$meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");



$dia = $dias[(date('N', strtotime(substr($fecha,0,10)))-1)]; 

$M = $meses[($M-1)];

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">
    <title>Fecha y Hora de la Ultima Actualizacións</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<?php echo "<link href='../controlfrog/css/bootstrap.css' rel='stylesheet'>
<link href='../controlfrog/css/controlfrog.css' rel='stylesheet' media='screen'>";   ?>
</head>
<body class="white">
		<div class="row">
			<div class="col-sm-6 cf-item">
				<div class="row">
					<div class="col-sm-6 cf-item">
<header>
	<p><span></span>Ultima Actualización de la Data</p>
</header>
<div class="content">
	<div class="cf-td cf-td-12">
		<div class="cf-td-time metric"><?php echo $h.":".$m; ?><span><?php echo $H; ?></span></div>
		<div class="cf-td-dd">
			<p class="cf-td-day metric-small"><?php echo $dia; ?>  <?php echo $d; ?>  <?php echo $M; ?>, <?php echo $y; ?></p>
		</div>
	</div>
</div>
					</div> <!-- //end cf-item -->
				</div> <!-- //end row -->
			</div> <!-- //end col -->
		</div>
</body>
</html>