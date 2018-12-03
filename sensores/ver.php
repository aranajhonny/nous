<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 22;
$_SESSION['acc']['form'] = 44;
include("../complementos/permisos.php");

if(isset($_REQUEST['sensor'])){ $_SESSION['sensor']=filtrar_campo('int', 6, $_REQUEST['sensor']); }

if(isset($_SESSION['sensor'])){
$rs = pg_query($link, filtrar_sql("select * from sensores where id_sensor = ".$_SESSION['sensor']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico El Sensor";
	Auditoria("Sensor No Identificado ",$_SESSION['sensor']);
	unset($_SESSION['sensor']);
	header("location: listado.php");
	exit();
} else { 

	$rs = pg_fetch_array($rs);
	$tipo = $rs[1]; 
	$cli = $rs[2];
	$control = $rs[3]; 
	$unid = $rs[4]; 
	$disp = $rs[5];
	$serial = $rs[6]; 
	$est = $rs[7]; 
	$id_ult_alarma = $rs[8];
	$est_alarma = $rs[9];
	$ult_alarma = date3($rs[10]);
	$valor = $rs[11];
	$ult_act = date3($rs[12]);
	$des = $rs[13];
	
	$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = $cli"));
	$rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1];
	
	$rs = pg_query($link, filtrar_sql("select descripcion, id_unidmed from tipo_sensores where id_tipo_sensor = $tipo")); $rs = pg_fetch_array($rs); $tipo = $rs[0]; $unidmed = $rs[1];
	
	$rs = pg_query($link, filtrar_sql("select descripcion, serial from dispositivos, tipo_disp where dispositivos.id_tipo_disp = tipo_disp.id_tipo_disp and id_dispositivo = $disp"));
	$rs = pg_fetch_array($rs); $disp = $rs[0]." - ".$rs[1];
	
	$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and id_unidad = $unid"));
	$rs = pg_fetch_array($rs); $unid = $rs[0]." - ".$rs[1];
	
	$rs = pg_query($link, filtrar_sql("select nombre from controles where id_control = $control"));
	$rs = pg_fetch_array($rs); $control = $rs[0];
	
	if($est==0){ $est=""; } else { 
	$rs = pg_query($link, filtrar_sql("select nombre from estatus where id_estatu = $est"));
	$rs = pg_fetch_array($rs); $est = $rs[0]; }
	
	if($est_alarma==0){ $est_alarma="Apagado"; } else { 
	$rs = pg_query($link, filtrar_sql("select nombre from estatus where id_estatu = $est_alarma"));
	$rs = pg_fetch_array($rs); $est_alarma = $rs[0]; }
	
	if($id_ult_alarma==0){ $id_ult_alarma="- -"; } else { 
	$rs = pg_query($link, filtrar_sql("select nombre from alarmas, estatus where estatus.id_estatu = alarmas.id_estatus and id_alarma = $id_ult_alarma")); $rs = pg_fetch_array($rs);
		$id_ult_alarma = $rs[0];
	}
	
	Auditoria("Accedio Al Modulo Ver Sensores: $serial $des",$_SESSION['sensor']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico el Sensor";
	Auditoria("Sensor No Identificado ",$_SESSION['sensor']);
	unset($_SESSION['sensor']);
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
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
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
<li><a href="#">Configuración</a></li>
<li><a href="#">Dispositivos</a></li>
<li><a href="#">Sensores</a></li>
<li><a href="#">Agregar</a></li>
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

<div class="header">Editar Sensor<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php" >
<fieldset>

<div class="form-group"><label>Tipo de Sensor</label>
<input id="tipo" name="tipo" type="text" placeholder="Tipo de Sensor" class="form-control" value="<?php echo $tipo;?>" readonly="readonly" /></div>

<div class="form-group"><label>Descripción</label>
<input id="des" name="des" type="text" placeholder="Descripción del Sensor" class="form-control" value="<?php echo $des;?>" readonly="readonly" /></div>

<div class="form-group"><label>Serial</label>
<input id="serial" name="serial" type="text" placeholder="Numero del Serial" class="form-control" value="<?php echo $serial;?>" readonly="readonly" /></div>

<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Control</label>
<input id="control" name="control" type="text" placeholder="Control" class="form-control" value="<?php echo $control;?>" readonly="readonly" /></div>

<div class="form-group"><label>Dispositivo</label>
<input id="disp" name="disp" type="text" placeholder="Dispositivos" class="form-control" value="<?php echo $disp;?>" readonly="readonly" /></div>

<div class="form-group"><label>Unidad</label>
<input id="unid" name="unid" type="text" placeholder="Unidad" class="form-control" value="<?php echo $unid;?>" readonly="readonly" /></div>

<div class="form-group"><label>Estatus del Sensor</label>
<input id="est" name="est" type="text" placeholder="Estatus del Sensor" class="form-control" value="<?php echo $est;?>" readonly="readonly" /></div>

<div class="form-group"><label>Estatus de Alarma</label>
<input id="est_alarma" name="est_alarma" type="text" placeholder="Estatus de Alarma" class="form-control" value="<?php echo $est_alarma;?>" readonly="readonly" /></div>

<div class="form-group"><label>Ultima Alarma</label>
<input id="alarma" name="alarma" type="text" placeholder="Fecha de Ultima Alarma" class="form-control" value="<?php echo $id_ult_alarma;?>" readonly="readonly" /></div>

<div class="form-group"><label>Fecha Ultima Alarma</label>
<input id="alarma" name="alarma" type="text" placeholder="Fecha de Ultima Alarma" class="form-control" value="<?php echo $ult_alarma;?>" readonly="readonly" /></div>

<div class="form-group"><label>Ultimo Valor Reportado</label>
<input id="valor" name="valor" type="text" placeholder="Ultimo Valor Reportado" class="form-control" value="<?php echo $valor;?>" readonly="readonly" /></div>
 
<div class="form-group"><label>Fecha del Ultimo Reporte</label>
<input id="valor" name="valor" type="text" placeholder="Ultimo Valor Reportado" class="form-control" value="<?php echo $ult_act;?>" readonly="readonly" /></div>
                            
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>

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