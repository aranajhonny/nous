<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");



if(isset($_POST['guardar'])){ 
$det = filtrar_campo('todo', 0, $_POST['det']); 
$dep = filtrar_campo('todo', 30, $_POST['dep']); 


if(empty($det)){ $_SESSION['mensaje1']="Debe indicar una Breve Decripción de la Situación";
} else if(empty($dep)){ $_SESSION['mensaje1']="Debe seleccionar El Departamento";
} else { // si validar 

$f1 = Preparar_Imagen($_FILES['foto1']); 
$f2 = Preparar_Imagen($_FILES['foto2']);

if(isset($_SESSION['mensaje1'])==false){ // si hay archivos cargados y estan bien

$rs = pg_query($link, filtrar_sql("insert into soportes(fr, id_usuario, est, departamento) values('".date('Y-m-d H:i:s')."', ".$_SESSION['miss'][8].", 'Pendiente', '$dep')"));
	if($rs){ 
		
		$rs = pg_query($link, filtrar_sql("select max(id) from soportes "));
		$rs = pg_fetch_array($rs);
		$id = $rs[0];
		
		$rs = pg_query($link, filtrar_sql("insert into respuestas(id_soporte, id_usuario, fr, detalle) values ($id, ".$_SESSION['miss'][8].", '".date('Y-m-d H:i:s')."', '$det')"));
		if($rs){ 
			$rs = pg_query($link, filtrar_sql("select max(id) from respuestas "));
			$rs = pg_fetch_array($rs);
			$id2 = $rs[0];
			
			if(empty($f1['name'])==false){ pg_query($link, "insert into soporte_img values($id, $id2, '".$f1['arc']."', '".$f1['name']."', '".$f1['ext']."')"); }
			if(empty($f2['name'])==false){ pg_query($link, "insert into soporte_img values($id, $id2, '".$f2['arc']."', '".$f2['name']."', '".$f2['ext']."')"); }
		} else { 
			$_SESSION['mensaje1']="No se logro agregar el ticked";
			Auditoria("Problema al registrar La Respuesta del Ticked Error: ".pg_last_error($link),$id);
		}

// =========================== CORREO ===============================================
//===================================================================================

		Auditoria("Agrego Ticked para Soporte: Nro #$id",$id);
		$_SESSION['mensaje3']="Ticked Abierto";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el ticked";
		Auditoria("Problema al registrar el ticked Error: ".pg_last_error($link),0);
	}
	
} // si archivos
} // si validar


} else { 
	$det = $dep = "";
	Auditoria("Accedio Al Modulo Agregar Ticked",0);
}





function Preparar_Imagen($file){ 
	if(empty($file['tmp_name'])){  // SI ARCHIVO VACIO
		$tmp = "";
	} else { 
$tipos = array("image/gif","image/jpeg","image/bmp","image/png","image/tiff");
$maximo = 3145728; //3Mb
if (is_uploaded_file($file['tmp_name'])){ // Se ha cargado el archivo
if (in_array($file['type'],$tipos)){ // si tipo de archivo valido
if ($file['size'] <= $maximo){ // si tamaño del archivo correcto
$fp = fopen($file['tmp_name'], 'r'); //Abrimos el archivo
$imagen = fread($fp, filesize($file['tmp_name'])); //Extraemos el contenido del archivo
//$imagen = addslashes($imagen); // NO FUNCIONA PARA POSTGRES ALTERA LA CADENA DE BYTES
fclose($fp); //Cerramos el archivo

$tmp['name'] = filtrar_campo('todo', 250, $file['name']);
$tmp['ext']  = filtrar_campo('todo', 250, $file['type']);
$tmp['arc'] = pg_escape_bytea($imagen);

} else { $tmp=""; $_SESSION['mensaje1'] = "Tamaño del Archivo (".$file['name'].") No puede ser mayor a 3Mb"; 
$tmp['name'] = '';
$tmp['ext']  = '';
$tmp['arc'] = 'null';
}
} else { $tmp=""; $_SESSION['mensaje1'] = "El Formato del Archivo no es Correcto"; 
$tmp['name'] = '';
$tmp['ext']  = '';
$tmp['arc'] = 'null'; 
}
} else { $tmp=""; $_SESSION['mensaje1'] = "El Archivo (".$file['name'].") No ha Sido Cargado";$tmp['name'] = '';
$tmp['ext']  = '';
$tmp['arc'] = 'null'; 
}
}
return $tmp;
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
<li><a href="#">Mi Cuenta</a></li>
<li><a href="#">Centro de Soporte</a></li>
<li><a href="#">Agregar Ticked</a></li>
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

<div class="header">Agregar Ticked<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();" enctype="multipart/form-data">
<fieldset>

<div class="form-group"><label>Descripción de la Situación</label><textarea rows="6" name="det" id="det"  class="form-control" onkeypress="return permite('todo', event);" ><?php echo $det; ?></textarea></div>

<div class="form-group"><label>Departamento</label>
<div><select id="dep" name="dep" class="selectpicker">
<option value="0" selected="selected">Seleccione un Departamento</option>
<option <?php if(strcmp($dep,"Hardware")==0) echo "selected";?>>Hardware</option>
<option <?php if(strcmp($dep,"Sistema")==0) echo "selected";?>>Sistema</option>
<option <?php if(strcmp($dep,"Soporte")==0) echo "selected";?>>Soporte</option>
<option <?php if(strcmp($dep,"Ventas")==0) echo "selected";?>>Ventas</option>
</select></div>
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group"><label>1.- Foto</label><input id="foto1" name="foto1" accept="image/*" type="file" class="filestyle" data-classButton="btn btn-info btn-lg" data-input="false"></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 form-group"><label>2.- Foto</label><input id="foto2" name="foto2" accept="image/*"  type="file" class="filestyle" data-classButton="btn btn-primary btn-lg" data-input="false"></div>
                                
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
	if(document.getElementById('des').value.length<1){ 
		mensaje("Debe indicar una breve descripción de la situación",1);
		
	} else if(document.getElementById('dep').value=="0"){ 
		mensaje("Debe seleccionar el departamento",1);
		
	} else { 
		val = true;
	}
	
return val; 
}</script>

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
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>$("#dep").select2();</script>
<script src="../Legend/admin/assets/bootstrapfilestyle/js/bootstrap-filestyle.js"></script>
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