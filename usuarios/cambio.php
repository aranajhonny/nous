<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");




if(isset($_POST['guardar'])){ 
 
$_POST['clave'] = strtr(strtoupper($_POST['clave']), array("é" => "É", "í" => "Í", "ó" => "Ó", "u" => "Ú", "á" => "Á", "ç" => "Ç", "ñ" => "Ñ", ));
$clave = filtrar_campo('todo', 20, $_POST['clave']);

$_POST['new'] = strtr(strtoupper($_POST['new']), array("é" => "É", "í" => "Í", "ó" => "Ó", "u" => "Ú", "á" => "Á", "ç" => "Ç", "ñ" => "Ñ", ));
$new = filtrar_campo('todo', 20, $_POST['new']);

$_POST['conf'] = strtr(strtoupper($_POST['conf']), array("é" => "É", "í" => "Í", "ó" => "Ó", "u" => "Ú", "á" => "Á", "ç" => "Ç", "ñ" => "Ñ", ));
$conf = filtrar_campo('todo', 20, $_POST['conf']);


$pers = filtrar_campo('int', 6, $_SESSION['miss'][8]);

if(empty($pers)){ $_SESSION['mensaje1']="Usuario No Definido";
} else if(empty($clave)){ $_SESSION['mensaje1']="Debe Indicar La Clave Actual";
} else if(empty($new)){ $_SESSION['mensaje1']="Debe Indicar La Nueva Clave";
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe Indicar La Confirmación";
} else if(strcmp($new,$conf)!=0){ $_SESSION['mensaje1']="Nueva Clave y Confirmación NO COINCIDEN";
} else { // si validar 

$rs = pg_query($link, filtrar_sql("select md5('$clave'), md5('$new')"));
$rs = pg_fetch_array($rs);
$clave = $rs[0];
$new = $rs[1];

$rs = pg_query($link, filtrar_sql("select count(id_usuario) from usuarios where clav='$clave' and id_usuario = $pers"));
$rs = pg_fetch_array($rs);

if($rs[0]==1){ // si validar 2 

$rs=pg_query($link, filtrar_sql("update usuarios set clav='$new' where id_usuario=$pers"));
if($rs){ 
Auditoria("Cambio de Clave del Usuario ".$_SESSION['miss'][6]." Completo", $_SESSION['miss'][8]);
	$_SESSION['mensaje3'] = "Cambio de Clave Completo...";
	header("location: ../inicio/principal.php");
	exit();
} else { 
	$_SESSION['mensaje1']="No Se Logro Guardar El Cambio de Clave";
	Auditoria("Problema al Cambiar La Clave Error: ".pg_last_error($link),0);
}

} // si validar 2
} // si validar 
} else { 
	Auditoria("Accedio Al Modulo de Cambio de Clave",0);
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
<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
<link href="../Legend/admin/assets/icheck/skins/square/_all.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<link rel="stylesheet" type="text/css" href="../stickytableheaders/css/component.css" />
		<!--[if IE]>
  		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
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
<li><a href="#">Administración de Sistema</a></li>
<li><a href="#">Usuarios</a></li>
<li><a href="#">Cambio de Clave</a></li>
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

<div class="header">Cambio de Clave<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="cambio.php" onsubmit="return validar();">
<fieldset>  
                         


<div class="form-group"><label>Clave Actual del Usuario</label>
<input id="clave" name="clave" type="password" placeholder="Clave de Usuario" class="form-control" maxlength="20" value="" onkeypress="return permite(event,'clav')" onkeyup="mayu(this)" /></div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group"><label>Nueva Clave</label>
<input id="new" name="new" type="password" placeholder="Nueva Clave" class="form-control" maxlength="20" value="" onkeypress="return permite(event,'clav')" onkeyup="mayu(this)" /></div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group"><label>Confirme Nueva Clave</label>
<input id="conf" name="conf" type="password" placeholder="Confirme Nueva Clave" class="form-control" maxlength="20" value="" onkeypress="return permite(event,'clav')" onkeyup="mayu(this)" /></div>


                                
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>

</form>
</div>

<script>function validar(){ 
val = false;
	if(document.getElementById('clav').value.length<8){ 
		mensaje("Debe indicar la clave de usuario y debe contener al menos 8 digitos",1);
		
	} else if(document.getElementById('new').value.length<8){ 
mensaje("Debe indicar la nueva clave de usuario y debe contener al menos 8 digitos",1);
		
	} else if(document.getElementById('conf').value.length<8){ 
mensaje("Debe indicar la Confimación de la Nueva Clave de usuario y debe contener al menos 8 digitos",1);
	
	} else if(document.getElementById('new').value!=document.getElementById('conf').value){ 
		mensaje("Clave de Confirmación NO COINCIDE",1);
	
	} else { 
		val = true;
	}
	
return val; }</script>

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
<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script>
$("#new").maxlength({ alwaysShow: true });
$("#conf").maxlength({ alwaysShow: true });
$("#clav").maxlength({ alwaysShow: true });
</script>
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