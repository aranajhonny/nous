<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 29;
$_SESSION['acc']['form'] = 66;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$rif = filtrar_campo('rif', 15, $_POST['rif']); 
$razon = filtrar_campo('string', 200, $_POST['razon']); 
$dir = filtrar_campo('string', 200, $_POST['dir']);
$tlf = filtrar_campo('tlf', 12, $_POST['tlf']); 
$correo = filtrar_campo('todo', 100, $_POST['correo']); 
$contacto = filtrar_campo('string', 200, $_POST['contacto']);
$tipo = filtrar_campo('int', 6, $_POST['tipo']);

if(empty($rif)){ $_SESSION['mensaje1']="Debe indicar el numero del RIF";
} else if(empty($razon)){ $_SESSION['mensaje1']="Debe indicar la razón social";
} else if(empty($dir)){ $_SESSION['mensaje1']="Debe indicar la dirección";
} else if(empty($tlf)){ $_SESSION['mensaje1']="Debe indicar el numero de télefono";
} else if(empty($tipo)){ $_SESSION['mensaje1']="Debe seleccionar el tipo de cliente";
} else if(in_array(87,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 
$rs = pg_query($link, filtrar_sql("select count(id_cliente) from clientes where rif='$rif'"));
$rs = pg_fetch_array($rs);
if($rs[0]>0){ 
	$_SESSION['mensaje1']="numero de rif ya registrado";
} else { // si rif 

	$rs = pg_query($link, filtrar_sql("insert into clientes(id_tipo_cliente, rif, razon_social, direccion, telefono, contacto, correo_contacto) values( $tipo, '$rif', '$razon', '$dir', '$tlf', '$contacto', '$correo')"));
	if($rs){ 
		
		$rs = pg_query($link, filtrar_sql("select max(id_cliente) from clientes "));
		$rs = pg_fetch_array($rs);
		Auditoria("Agrego cliente: $rif $razon",$rs[0]);
		
		$_SESSION['mensaje3']="Cliente Agregado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el cliente";
		Auditoria("Problema al registrar el cliente Error: ".pg_last_error($link),0);
	}
} // si rif
} // si validar
} else { 
	$rif = $razon = $correo = $contacto = $tlf = $dir = $tipo = "";
	Auditoria("Accedio Al Modulo Agregar Clientes",0);
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
<li><a href="#">Usuarios</a></li>
<li><a href="#">Clientes</a></li>
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

<div class="header">Agregar Cliente<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>RIF</label>
<input id="rif" name="rif" type="text" placeholder="Numero del RIF" class="form-control" maxlength="12" value="<?php echo $rif;?>" onkeypress="return permite(event,'rif')" onkeyup="mayu(this)" /><p class="help-block">Ejemplo: J-12345678-0  ó  V-20123456</p></div>
                                
<div class="form-group"><label>Razón Social</label>
<input id="razon" name="razon" type="text" placeholder="Razón Social" class="form-control" maxlength="200" value="<?php echo $razon;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="form-group"><label>Dirección</label>
<input id="dir" name="dir" type="text" placeholder="Dirección" class="form-control" maxlength="200" value="<?php echo $dir;?>" onkeypress="return permite(event,'todo')" />
<p class="help-block">Ejemplo: Estado / Ciudad / Municipio / Establecimiento</p></div>

<div class="form-group"><label>Télefono</label>
<input id="tlf" name="tlf" type="text" placeholder="Numero de Télefono" class="form-control" maxlength="12" value="<?php echo $tlf;?>" onkeypress="return permite(event,'telef')" /><p class="help-block">Ejemplo: 0243-1234567</p></div>

<div class="form-group"><label>Contacto</label>
<input id="contacto" name="contacto" type="text" placeholder="Nombre del Contacto" class="form-control" maxlength="200" value="<?php echo $contacto;?>" onkeypress="return permite(event,'car')" /></div>

<div class="form-group"><label>Correo del Contacto</label>
<input id="correo" name="correo" type="text" placeholder="Correo del Contacto" class="form-control" maxlength="100" value="<?php echo $correo;?>" onkeypress="return permite(event,'todo')"/><p class="help-block">Ejemplo: micorreo@gmail.com</p></div>


<div class="form-group"><label>Tipos de Cliente</label>
<div><select id="tipo" name="tipo" class="selectpicker">
<option value="0" selected="selected">Seleccione un Tipo de Cliente</option>
<?php $rs = pg_query($link, "select id_tipo_cliente, descripcion from tipo_clientes order by descripcion asc"); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($tipo==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div>
                                
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