<?php 
session_start();
include("../complementos/condb.php");



if(isset($_SESSION['progmant']['planmant'])){
$rs = pg_query("select * from planmant where id_planmant = ".$_SESSION['progmant']['planmant']);
$rs = pg_fetch_array($rs);
$cli = $rs[1];
$res = $rs[2];
$conf = $rs[3]; 
$sensor = $rs[4]; 
$unidmed = $rs[5];
$maestro = $rs[7]; 
$mod = $rs[8]; 
$tipo = $rs[9];
$des = $rs[10];
$porc = $rs[11];
$val = 1*$rs[12]; 
$prom = 1*$rs[13]; 
$val_min = 1*$rs[14]; 
$val_max = 1*$rs[15];
$tiempo = $rs[16]." Días"; 
$tiempo_min = $rs[17]." Días"; 
$tiempo_max = $rs[18]." Días";
$prog = $rs[19];
$prov = $rs[20];

$rs = pg_query("select rif, razon_social from clientes where id_cliente = $cli");
$rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1];

$rs = pg_query("select codigo_principal from confunid where id_confunid = $conf");
$rs = pg_fetch_array($rs); $conf = $rs[0];

$rs = pg_query("select ci, nombre from personal where id_personal = $res");
$rs = pg_fetch_array($rs); $res = $rs[0]." ".$rs[1];

if($sensor==0){ $sensor="- -"; } else { 
$rs = pg_query("select descripcion, nombre from tipo_sensores where id_tipo_sensor = $sensor");
$rs = pg_fetch_array($rs); $sensor = $rs[0]." ".$rs[1]; }

if($unidmed==0){ $unidmed="- -"; } else { 
$rs = pg_query("select magnitudes.nombre, unidmed.nombre from magnitudes, unidmed where unidmed.id_magnitud = magnitudes.id_magnitud and  id_unidmed = $unidmed");
$rs = pg_fetch_array($rs); $unidmed = $rs[0]." ".$rs[1]; }

if($maestro==0){ $maestro="- -"; } else { 
$rs = pg_query("select nombre from planmaes where id_planmaes = $maestro");
$rs = pg_fetch_array($rs); $maestro = $rs[0]; }

if($mod==0){ $mod="- -"; } else { 
$rs = pg_query("select marcas.descripcion, modelos.descripcion from marcas, modelos where modelos.id_marca = marcas.id_marca and id_modelo = $mod");
$rs = pg_fetch_array($rs); $mod = $rs[0]." - ".$rs[1]; }

if($prov==0){ $prov="- -"; } else { 
$rs = pg_query("select rif, nombre_prov from provserv where id_provserv = $prov");
$rs = pg_fetch_array($rs); $prov = $rs[0]." ".$rs[1]; }



} else { 
	$_SESSION['mensaje1']="No se identifico el plan de mantenimiento";
	header("location: listado.php");
	exit();
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />

<title>.:: NousTrack ::.</title>

<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<script src="../complementos/utilidades.js"></script>

</head>
<body style="max-width:915px;">
        

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">


<div class="form-group">
<label>Nombre</label>
<input id="des" name="des" type="text" placeholder="Descripción" class="form-control" value="<?php echo $des;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $conf;?>" readonly="readonly" /></div>

<div class="form-group"><label>Marca - Modelo</label>
<input id="mod" name="mod" type="text" placeholder="Modelo" class="form-control" value="<?php echo $mod;?>" readonly="readonly" /></div>

<div class="form-group"><label>Plan Maestro de Mantenimiento</label>
<input id="maestro" name="maestro" type="text" placeholder="Plan Maestro de Mantenimiento" class="form-control" value="<?php echo $maestro;?>" readonly="readonly" /></div>

<div class="form-group"><label>Valor</label>
<input id="val" name="val" type="text" placeholder="Valor" class="form-control"  value="<?php echo $val;?>" readonly="readonly" /></div>

<div class="form-group"><label>Valor Promedio Mensual</label>
<input id="prom" name="prom" type="text" placeholder="Valor Promedio Mensual" class="form-control"  value="<?php echo $prom;?>" readonly="readonly" /></div>

<div class="form-group"><label>Limite de Tiempo</label>
<input id="tiempo" name="tiempo" type="text" placeholder="Días" class="form-control"  value="<?php echo $tiempo;?>" readonly="readonly" /></div>

<div class="form-group"><label>Porcentaje de Tolerancia:
<input type="text" name="porc" id="porc" value="<?php echo $porc;?>"  readonly="readonly" size="4" />%</label>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Mínimo</label>
<input id="val_min" name="val_min" type="text" placeholder="Valor Mínimo" class="form-control" value="<?php echo $val_min;?>" readonly="readonly" /></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Maximo</label>
<input id="val_max" name="val_max" type="text" placeholder="Valor Maximo" class="form-control" value="<?php echo $val_max;?>" readonly="readonly" /></div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo Mínimo</label>
<input id="tiempo_min" name="tiempo_min" type="text" placeholder="Días" class="form-control"  value="<?php echo $tiempo_min;?>" readonly="readonly" /></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo Máximo</label>
<input id="tiempo_max" name="tiempo_max" type="text" placeholder="Días" class="form-control"  value="<?php echo $tiempo_max;?>" readonly="readonly" /></div>
</div>
<p>&nbsp;</p>

<div class="form-group"><label>Proveedor de Servicio</label>
<input id="prov" name="prov" type="text" placeholder="Proveedor de Servicio" class="form-control" value="<?php echo $prov;?>" readonly="readonly" /></div>

<div class="form-group"><label>Responsable</label>
<input id="res" name="res" type="text" placeholder="CI Apellido y Nombre" class="form-control" value="<?php echo $res;?>" readonly="readonly" /></div>

</div>
</div>
</div>
</body>
</html>