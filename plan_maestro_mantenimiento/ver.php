<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 45;
$_SESSION['acc']['form'] = 104;
include("../complementos/permisos.php");

if(isset($_REQUEST['master'])){ $_SESSION['master']=$_REQUEST['master']; }

if(isset($_SESSION['master'])){
$rs = pg_query("select * from planmaes, clientes where planmaes.id_cliente = clientes.id_cliente and id_planmaes = ".$_SESSION['master']);
$rs = pg_fetch_array($rs);

$des = $rs[1];
$est = $rs[2];
$cli = $rs[3];
$conf = $rs[4];
$res = $rs[5];
$sensor = $rs[6];
$unidmed = $rs[7];
$mod = $rs[8];
$porc = $rs[9];
$prom = $rs[10];
$prov = $rs[11];

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

if($mod==0){ $mod="- -"; } else { 
$rs = pg_query("select marcas.descripcion, modelos.descripcion from marcas, modelos where modelos.id_marca = marcas.id_marca and id_modelo = $mod");
$rs = pg_fetch_array($rs); $mod = $rs[0]." - ".$rs[1]; }

if($prov==0){ $prov="- -"; } else { 
$rs = pg_query("select rif, nombre_prov from provserv where id_provserv = $prov");
$rs = pg_fetch_array($rs); $prov = $rs[0]." ".$rs[1]; }


Auditoria("Accedio Al Modulo Ver Plan Maestro de Mantenimiento: $des",$_SESSION['master']);

} else { 
	$_SESSION['mensaje1']="No se identifico el Plan Maestro de Mantenimiento";
	header("location: listado.php");
	exit();
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><!-- InstanceBegin template="../Templates/marco.dwt" codeOutsideHTMLIsLocked="false" -->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<!-- InstanceBeginEditable name="doctitle" -->
<title>.:: NousTrack ::.</title>
<!-- InstanceEndEditable -->

<?php 
include("../complementos/panico.php");
if(isset($_SESSION['ptc'])){ ?>
<link href="../Legend/admin/assets/vex/css/vex.css" rel="stylesheet" />
<link href="../Legend/admin/assets/vex/css/vex-theme-top.css" rel="stylesheet" />
<?php } ?>

<!-- InstanceBeginEditable name="head" -->

<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>

<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<script src="../complementos/utilidades.js"></script>
<!-- InstanceEndEditable -->
</head>
<body>
<?php echo $_SESSION['miss'][4]; ?>          
<div class="overlay"></div>
<div class="controlshint" ><img src="../img/swipe.png" alt="Menu Help" /></div>
<section class="wrap">
<div class="container">
<img src="../img/logo.png" height="67" width="454" onclick="location.href='../inicio/principal.php'" /><br/>
<!-- InstanceBeginEditable name="panelsession" -->
<ol class="breadcrumb">
<li><a href="#">Mantenimiento</a></li>
<li><a href="#">Plan Maestro de Mantenimiento</a></li>
<li><a href="#">Ver</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->

<div class="well">

<div class="header">Ver Plan Maestro de Mantenimiento<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php" >
<fieldset>

<div class="form-group">
<label>Nombre</label>
<input id="des" name="des" type="text" placeholder="Descripción" class="form-control" value="<?php echo $des;?>" readonly="readonly" /></div>

<div class="form-group"><label>Cliente</label>
<input id="des" name="des" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $conf;?>" readonly="readonly" /></div>

<div class="form-group"><label>Marca - Modelo</label>
<input id="mod" name="mod" type="text" placeholder="Modelo" class="form-control" value="<?php echo $mod;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Sensor</label>
<input id="des" name="des" type="text" placeholder="Tipo de Sensor" class="form-control" value="<?php echo $sensor;?>" readonly="readonly" /></div>

<div class="form-group"><label>Magnitud / Unidad de Medida</label>
<input id="unidmed" name="unidmed" type="text" placeholder="Unidad de Medida" class="form-control" value="<?php echo $unidmed;?>" readonly="readonly" /></div>

<div class="form-group"><label>Valor Promedio Mensual</label>
<input id="prom" name="prom" type="text" placeholder="Valor Promedio Mensual" class="form-control"  value="<?php echo $prom;?>" readonly="readonly" /></div>

<div class="form-group"><label>Responsable</label>
<input id="res" name="res" type="text" placeholder="CI Apellido y Nombre" class="form-control" value="<?php echo $res;?>" readonly="readonly" /></div>

<div class="form-group"><label>Proveedor de Servicio</label>
<input id="prov" name="prov" type="text" placeholder="Proveedor de Servicios" class="form-control"  value="<?php echo $prov;?>" readonly="readonly" /></div>

</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='listado.php'"/></div>
</div>

</form>
</div>

<!-- InstanceEndEditable -->
</div>
</div>
</div>
</div>
</section>
<p>&nbsp;</p> <p>&nbsp;</p>  
<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">
$(document).bind("mobileinit", function(){
		$.extend($.mobile, {autoInitializePage:false} );
	}
);</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../Legend/admin/assets/js/leftmenu.js"></script>
<script src="../Legend/admin/assets/js/theme.js"></script>
<script src="../Legend/admin/assets/humane/js/humane.min.js"></script> 
<script> 
function mensaje(texto, tipo) { 
		 var notify = 0;
		 if(tipo==1){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
             addnCls: 'humane-jackedup-error'
         });
		 } else if(tipo==2){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-info'
         });
		 } else if(tipo==3){ 
		 notify = humane.create({
             timeout: 6000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-success'
         }); 
		 } 
         notify.log(''+texto);
}</script>
<!-- InstanceBeginEditable name="lib" -->
<!-- InstanceEndEditable -->

<?php 
if(isset($_SESSION['mensaje1'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje1']."',1);</script>"; 
	unset($_SESSION['mensaje1']);
}

if(isset($_SESSION['mensaje2'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje2']."',2);</script>"; 
	unset($_SESSION['mensaje2']);
}

if(isset($_SESSION['mensaje3'])){ 
	echo "<script>mensaje('".$_SESSION['mensaje3']."',3);</script>"; 
	unset($_SESSION['mensaje3']);
} ?>

<?php if(isset($_SESSION['ptc'])){ ?>
<script src="../Legend/admin/assets/vex/js/vex.js"></script>
<script src="../Legend/admin/assets/vex/js/vex.dialog.js"></script>
<script>
function mostrar_ptc() {
         vex.defaultOptions.className = 'vex-theme-top';
         vex.dialog.open({
message: 'Panico Activado para La Unidad <?php echo $_SESSION['ptc']['unid'];?>',
             buttons: [
                 $.extend({}, vex.dialog.buttons.YES, {
                     text: 'Atender',
click: function(){ location.href='../panico/atender.php?pan=<?php echo $_SESSION['ptc']['id'];?>'; }
                 }),
                 $.extend({}, vex.dialog.buttons.NO, { text: 'Ignorar' })
             ]
         });
}
setTimeout('mostrar_ptc();',5);
</script>
<?php unset($_SESSION['ptc']); } ?>

<?php include("../complementos/closdb.php"); ?>
</body>
<!-- InstanceEnd --></html>