<?php 
session_start();
include("../complementos/condb.php");

if(isset($_REQUEST['id'])){ $_SESSION['progmant']['unidad'] = $_REQUEST['id']; }

if(isset($_SESSION['progmant']['unidad'])){
$rs = pg_query("select * from unidades where id_unidad = ".$_SESSION['progmant']['unidad']);
$rs = pg_fetch_array($rs);
$disp = $rs[1]; 
$unid = $rs[2]; 
$cli = $rs[3];
$_SESSION['progmant']['cli'] = $rs[3];
$conf = $rs[4]; 
$_SESSION['progmant']['confunid'] = $rs[4];
$zona = $rs[5]; 
$area = $rs[6]; 
$cod = $rs[7]; 
$conf1 = $rs[8];
$conf2 = $rs[9]; 
$conf3 = $rs[10]; 
$conf4 = $rs[13]; 
$prop = $rs[11]; 
$prin = $rs[12];
$est_control = $rs[15];
$obs = $rs[16];
$resp = $rs[18];

if($prin==true){ $prin="on"; } else { $prin="off"; }

$rs = pg_query("select rif, razon_social from clientes where id_cliente = $cli"); 
$rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; 

$rs = pg_query("select nombre from zongeo where id_zongeo = $zona");
$rs = pg_fetch_array($rs); $zona = $rs[0];

$rs = pg_query("select descripcion from areas where id_area = $area");
$rs = pg_fetch_array($rs); $area = $rs[0];

$rs = pg_query("select marcas.descripcion,  modelos.descripcion from marcas, modelos where modelos.id_marca = marcas.id_marca and id_modelo = $mod");
$rs = pg_fetch_array($rs); $mod = $rs[0]." - ".$rs[1];

if($disp==0){ $disp="No Posee"; } else { 
$rs = pg_query("select descripcion, serial from dispositivos, tipo_disp where dispositivos.id_tipo_disp = tipo_disp.id_tipo_disp and id_dispositivo = $disp");
$rs = pg_fetch_array($rs); $disp = $rs[0]." - ".$rs[1]; }

$rs = pg_query("select nombre, codigo_principal, n_configuracion_01, n_configuracion_02, n_configuracion_03, n_configuracion_04 from confunid where id_confunid = $conf"); 
$rs = pg_fetch_array($rs); $conf = $rs[1]; $eti5 = $rs[0];  $eti1 = $rs[2]; 
$eti2 = $rs[3]; $eti3 = $rs[4]; $eti4 = $rs[5];

$rs = pg_query("select nombre from tipo_unidades where id_tipo_unidad = $unid");
$rs = pg_fetch_array($rs); $unid = $rs[0];

if(empty($resp)){ $resp="- -"; } else { 
$rs = pg_query("select ci, nombre from personal where id_personal = $resp"); 
$rs = pg_fetch_array($rs); $resp = $rs[0]." ".$rs[1]; } 

} else { 
	$_SESSION['mensaje1']="No se identifico la Unidad";
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


<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<link href="../Legend/admin/assets/boxer/css/jquery.fs.boxer.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<script src="../complementos/utilidades.js"></script>

</head>
<body style="max-width:915px;">

<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">

<div class="form-group"><label>Zona Geográfica</label>
<input id="zona" name="zona" type="text" placeholder="Zona Geográfica" class="form-control" value="<?php echo $zona;?>" readonly="readonly" /></div>

<div class="form-group"><label>Área</label>
<input id="area" name="area" type="text" placeholder="Área" class="form-control" value="<?php echo $area;?>" readonly="readonly" /></div>

<div class="form-group"><label>Dispositivo</label>
<input id="disp" name="disp" type="text" placeholder="Dispositivo" class="form-control" value="<?php echo $disp;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $conf;?>" readonly="readonly" /></div>

<div class="form-group"><label id="eti5"><?php echo $eti5;?></label>
<input id="cod" name="cod" type="text" placeholder="Código Principal" class="form-control"  value="<?php echo $cod;?>" readonly="readonly" /></div>

<div class="form-group"><label>Responsable</label>
<input id="resp" name="resp" type="text" placeholder="Responsable de la Unidad" class="form-control" value="<?php echo $resp;?>" readonly="readonly" /></div>

<div class="form-group"><label>Observaciones</label>
<textarea rows="8" name="obs" id="obs" readonly="readonly" class="form-control"><?php echo $obs; ?></textarea>
</div>

<p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='unidades.php'"/></div>
</div>

</div></div></div>
</body>
