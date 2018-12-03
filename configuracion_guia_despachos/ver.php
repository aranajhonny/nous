<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 51;
$_SESSION['acc']['form'] = 112;
include("../complementos/permisos.php");

if(isset($_REQUEST['confguia'])){ $_SESSION['confguia']=filtrar_campo('int',6,$_REQUEST['confguia']); }

if(isset($_SESSION['confguia'])){
$rs = pg_query($link, filtrar_sql("select * from confguia where id_confguia = ".$_SESSION['confguia']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico La Configuración Guía de Despacho";
	Auditoria("Configuración Guía de Despacho No Identificada ",$_SESSION['confguia']);
	unset($_SESSION['confguia']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$cli = $rs[1]; 
	$nom=$rs[2]; 
	$cod=$rs[3]; 
	$conf1=$rs[4]; 
	$conf2=$rs[5]; 
	$conf3=$rs[6]; 
	$conf4=$rs[7];
	$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = $cli")); 
	$rs = pg_fetch_array($rs);  $cli = $rs[0]." ".$rs[1];
	Auditoria("Accedio Al Modulo Ver Configuración Guia de Despacho: $cod",$_SESSION['confguia']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico la configuración de la guía de despacho";
	Auditoria("Configuración Guía de Despacho No Identificada",$_SESSION['confguia']);
	unset($_SESSION['confguia']);
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
<li><a href="#">Unidades</a></li>
<li><a href="#">Configuración de la Guía de Despacho</a></li>
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

<div class="header">Ver Configuración de la Guía de Despacho<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" maxlength="50" value="<?php echo $cli;?>" readonly="readonly" /></div>
                           
<div class="form-group"><label>Nombre del Identificador</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Identificador" class="form-control" value="<?php echo $nom;?>" readonly="readonly" /></div>

<div class="form-group"><label>Guía</label>
<input id="cod" name="cod" type="text" placeholder="Tipo de Unidad" class="form-control"  value="<?php echo $cod;?>" readonly="readonly" /></div>

<div class="form-group"><label>Primera Caracteristica</label>
<input id="conf1" name="conf1" type="text" placeholder="Primera Caracteristica" class="form-control" value="<?php echo $conf1;?>" readonly="readonly" />
</div>

<div class="form-group"><label>Segunda Caracteristica</label>
<input id="conf2" name="conf2" type="text" placeholder="Segunda Caracteristica" class="form-control" value="<?php echo $conf2;?>" readonly="readonly" />
</div>

<div class="form-group"><label>Tercera Caracteristica</label>
<input id="conf3" name="conf3" type="text" placeholder="Tercera Caracteristica" class="form-control"  value="<?php echo $conf3;?>" readonly="readonly" />
</div>
                        
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