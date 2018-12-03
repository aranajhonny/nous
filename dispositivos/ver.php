<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 18;
$_SESSION['acc']['form'] = 28;
include("../complementos/permisos.php");

if(isset($_REQUEST['disp'])){ $_SESSION['dispositivo']=filtrar_campo('int', 6,$_REQUEST['disp']); }

if(isset($_SESSION['dispositivo'])){
$rs = pg_query($link, filtrar_sql("select serial, estatus, tipo_disp.descripcion, lotes.nro, id_cliente, ult_ip, ult_tlf from dispositivos, tipo_disp, lotes where tipo_disp.id_tipo_disp = dispositivos.id_tipo_disp and lotes.id_lote = dispositivos.id_lote and id_dispositivo = ".$_SESSION['dispositivo']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico El Dispositivo";
	Auditoria("Dispositivo No Identificado ",$_SESSION['dispositivo']);
	unset($_SESSION['dispositivo']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$tipo = $rs[2]; 
	$lote = $rs[3]; 
	$cli = $rs[4]; 
	$serial = $rs[0]; 
	$est = $rs[1]; 
	$ip = $rs[5];
	$tlf = $rs[6];
	if($rs[4]==0){ $cli="- -"; } else { 
	$qs=pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$rs[4]));
	$qs=pg_fetch_array($qs); $cli=$qs[0]." ".$qs[1]; }
	Auditoria("Accedio Al Modulo Ver Dispositivo: $tipo $serial",$_SESSION['dispositivo']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico el dispositivo";
	Auditoria("Dispositivo No Identificado ",$_SESSION['dispositivo']);
	unset($_SESSION['dispositivo']);
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
<li><a href="#">Dispositivos</a></li>
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

<div class="header">Ver Dispositivo<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php">
<fieldset>

<div class="form-group"><label>Serial</label>
<input id="serial" name="serial" type="text" placeholder="Numero del Serial del Dispositivo" class="form-control" value="<?php echo $serial;?>" readonly="readonly" /></div>
                                
<div class="form-group"><label>Tipo de Dispositivo</label>
<input id="tipo" name="tipo" type="text" placeholder="Tipo de Dispositivo" class="form-control" value="<?php echo $tipo;?>" readonly="readonly" /></div>

<div class="form-group"><label>Lote</label>
<input id="lote" name="lote" type="text" placeholder="Lote de Fabricación del Dispositivo" class="form-control"value="<?php echo $lote;?>" readonly="readonly" /></div>

<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control"  value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Estatus</label>
<input id="est" name="est" type="text" placeholder="Estatus del Dispositivo" class="form-control" value="<?php echo $est;?>" readonly="readonly" /></div>

<div class="form-group"><label>Dirección IP</label>
<input id="ip" name="ip" type="text" placeholder="Ultima Dirección IP del Dispositivo" class="form-control" value="<?php echo $ip;?>" readonly="readonly" /></div>

<div class="form-group"><label>Número Télefonico</label>
<input id="tlf" name="tlf" type="text" placeholder="Número Télefonico del Dispositivo" class="form-control" value="<?php echo $tlf;?>" readonly="readonly" /></div>
                                
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