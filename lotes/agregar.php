<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 17;
$_SESSION['acc']['form'] = 22;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
include("../complementos/util.php");
$nro = filtrar_campo('todo', 20, $_POST['nro']); 
$fecha = filtrar_campo('datetime', 25,$_POST['fecha']);  
$cant = filtrar_campo('int', 6,$_POST['cant']); 
$serial = filtrar_campo('int', 10,$_POST['serial']);  
$tmp = date2($fecha);

if(empty($nro)){ $_SESSION['mensaje1']="Debe indicar el nro del lote";
} else if(empty($fecha)){ $_SESSION['mensaje1']="Debe seleccionar la fecha del lote";
} else if(empty($cant)){ $_SESSION['mensaje1']="Debe indicar la cantidad ";
} else if(empty($serial)){ $_SESSION['mensaje1']="Debe indicar el inicio del serial";
} else if(in_array(76,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

	$rs = pg_query($link, filtrar_sql("insert into lotes(nro, fecha_creacion, cantidad, serial_inicio) values('$nro', '$tmp', $cant, $serial)"));
	if($rs){ 
	
		$rs = pg_query($link, filtrar_sql("select max(id_lote) from lotes "));
		$rs = pg_fetch_array($rs);
		Auditoria("Agrego Lotes: $nro",$rs[0]);
		
		$_SESSION['mensaje3']="Lote Agregado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el lote";
		Auditoria("Problema al actualizar el Lote Error: ".pg_last_error($link),$_SESSION['lote']);
	}

} // si validar
} else { 
	$nro = $cant = $fecha = $serial = "";
	
Auditoria("Accedio Al Modulo Agregar Lotes",0);

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
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet"/>
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
<li><a href="#">Configuraciones</a></li>
<li><a href="#">Dispositivos</a></li>
<li><a href="#">Lotes</a></li>
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

<div class="header">Agregar Lote<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Nro</label>
<input id="nro" name="nro" type="text" placeholder="Nro del Lote" class="form-control" maxlength="12" value="<?php echo $nro;?>" onkeypress="return permite(event,'rif')" onkeyup="mayu(this)" /><p class="help-block">Ejemplo: N# 001</p></div>
                                
<div class="form-group"><label>Fecha de Creación</label>
<input id="fecha" name="fecha" type="text" placeholder="Fecha de Creación del Lote" class="form-control" maxlength="12" value="<?php echo $fecha;?>"  /><p class="help-block">Ejemplo: 01/01/2014</p></div>

<div class="form-group"><label>Cantidad</label>
<input id="dir" name="cant" type="cant" placeholder="Cantidad de Dispositivods del Lote" class="form-control" maxlength="4" value="<?php echo $cant;?>" onkeypress="return permite(event,'num')" /></div>

<div class="form-group"><label>Inicio del Serial</label>
<input id="serial" name="serial" type="text" placeholder="Numero de Inicio del Serial" class="form-control" maxlength="10" value="<?php echo $serial;?>" onkeypress="return permite(event,'num')" /><p class="help-block">Ejemplo: 0001</p></div>
                                
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
	if(document.getElementById('rif').value.length<10){ 
		mensaje("Debe indicar el numero de rif y debe contener al menos 10 digitos",1);
		
	} else if(document.getElementById('razon').value.length<1){ 
		mensaje("Debe indicar la razon social o nombre del cliente",1);
		
	} else if(document.getElementById('dir').value.length<1){ 
		mensaje("Debe indicar la direccion",1);
		
	} else if(document.getElementById('tlf').value.length<10){ 
		mensaje("Debe indicar el numero de telefono y debe contener al menos 11 digitos",1);
		
	} else if(document.getElementById('tipo').value=="0"){ 
		mensaje("Debe seleccionar el tipo de cliente",1);
		
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
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>

$("#rif").maxlength({ alwaysShow: true });
$("#razon").maxlength({ alwaysShow: true });
$("#contacto").maxlength({ alwaysShow: true });
$("#correo").maxlength({ alwaysShow: true });
$("#tlf").maxlength({ alwaysShow: true });
$("#dir").maxlength({ alwaysShow: true });

$("#tipo").select2();</script>
<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> var cal = jQuery.noConflict();
cal(function() {
	cal( "#fecha" ).datepicker({ 
		minDate: <?php echo date("d/m/Y");?>
	});
});</script>
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