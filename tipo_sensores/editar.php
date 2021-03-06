<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 24;
$_SESSION['acc']['form'] = 47;
include("../complementos/permisos.php");

if(isset($_REQUEST['tipo_sensor'])){ $_SESSION['tipo_sensor']=$_REQUEST['tipo_sensor']; }

if(isset($_POST['guardar'])){ 
$nom = filtrar_campo('todo', 120, $_POST['nom']);  
$des = filtrar_campo('todo', 120, $_POST['des']);  
$naturaleza = filtrar_campo('string',20,$_POST['naturaleza']);
$puerto = filtrar_campo('int',10,$_POST['puerto']);  
$unid = filtrar_campo('int',6,$_POST['unid']);


if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre";
} else if(empty($des)){ $_SESSION['mensaje1']="Debe indicar la descripcion";
} else if(empty($naturaleza)){ $_SESSION['mensaje1']="Debe seleccionar la naturaleza del tipo sensor";
} else if(empty($puerto)){ $_SESSION['mensaje1']="Debe indicar el puerto de acceso";
} else if(empty($unid)){ $_SESSION['mensaje1']="Debe seleccionar la unidad de medición";
} else if(in_array(222,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

	$rs = pg_query($link, filtrar_sql("update tipo_sensores set id_unidmed=$unid, nombre='$nom', descripcion='$des', naturaleza='$naturaleza', puerto=$puerto where id_tipo_sensor = ".$_SESSION['tipo_sensor']));
	if($rs){ 
		Auditoria("Actualizo Tipo Sensores: $des ",$_SESSION['tipo_sensor']);

		$_SESSION['mensaje3']="Tipo de Sensor Editado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar el tipo de sensor";
		Auditoria("Problema al actualizar El Tipo de Sensor Error: ".pg_last_error($link),$_SESSION['tipo_sensor']);
	}

} // si validar

} else if(isset($_SESSION['tipo_sensor'])){
$rs = pg_query($link, filtrar_sql("select * from tipo_sensores where id_tipo_sensor = ".$_SESSION['tipo_sensor']));  
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico El Tipo de Sensor";
	Auditoria("Tipo de Sensor No Identificado ",$_SESSION['tipo_sensor']);
	unset($_SESSION['tipo_sensor']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$unid = $rs[1]; $nom = $rs[2]; $des=$rs[3]; $naturaleza=$rs[4]; 
	$puerto=$rs[5]; $_SESSION['tipo_sensor_magnitud']=$rs[8];
	Auditoria("Accedio Al Modulo Editar Tipo Sensores: $des ",$_SESSION['tipo_sensor']);
} 

} else { 
	$_SESSION['mensaje1']="No se identifico el tipo de sensor";
	Auditoria("Tipo de Sensor No Identificado ",$_SESSION['tipo_sensor']);
	unset($_SESSION['tipo_sensor']);
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
<li><a href="#">Configuraciones</a></li>
<li><a href="#">Dispositivos</a></li>
<li><a href="#">Tipo de Sensor</a></li>
<li><a href="#">Editar</a></li>
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

<div class="header">Editar Tipo de Sensor<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Tipo de Sensor" class="form-control" maxlength="120" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /><p class="help-block">Ejemplo: Sensor Temp G2</p></div>
                                
<div class="form-group"><label>Descripción</label>
<input id="des" name="des" type="text" placeholder="Descripción del Tipo de Sensor" class="form-control" maxlength="120" value="<?php echo $des;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /><p class="help-block">Ejemplo: Sensor de Temparatura</p></div>

<div class="form-group"><label>Naturaleza</label>
<div><select id="naturaleza" name="naturaleza" class="selectpicker">
<option value="0" selected="selected">Seleccione La Naturaleza</option>
<option <?php if(strcmp($naturaleza,"Analógico")==0) echo "selected";?> >Analógico</option> 
<option <?php if(strcmp($naturaleza,"Digital")==0) echo "selected";?> >Digital</option>    
</select></div>
</div>

<div class="form-group"><label>Puerto de Comunicación</label>
<input id="puerto" name="puerto" type="text" placeholder="Puerto de Acceso al Tipo de Sensor" class="form-control" maxlength="10" value="<?php echo $puerto;?>" onkeypress="return permite(event,'num')"/><p class="help-block">Ejemplo: 8080</p></div>

<?php $mag = "";
$rs = pg_query($link, filtrar_sql("select nombre from magnitudes where id_magnitud = ".$_SESSION['tipo_sensor_magnitud'])); $rs = pg_fetch_array($rs); $mag = $rs[0]; ?>
<div class="form-group"><label>Magnitud</label>
<input id="mag" name="mag" type="text" placeholder="Magnitud" class="form-control" value="<?php echo $mag;?>" readonly="readonly" /></div>

<div class="form-group"><label>Unidades de Medida</label>
<div><select id="unid" name="unid" class="selectpicker">
	<option value="0" selected="selected">Seleccione una Unidad de Medición</option>
<?php $rs = pg_query($link, filtrar_sql("select id_unidmed, nombre from unidmed where id_magnitud = ".$_SESSION['tipo_sensor_magnitud']." order by nombre asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($unid==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
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
	if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar el nombre del tipo de sensor",1);
		
	} else if(document.getElementById('des').value.length<1){ 
		mensaje("Debe indicar la descripción del tipo de sensor",1);
		
	} else if(document.getElementById('naturaleza').value=="0"){ 
		mensaje("Debe seleccionar la naturaleza del sensor",1);
		
	} else if(document.getElementById('puerto').value.length<1){ 
		mensaje("Debe indicar el numero de acceso del tipo de sensor",1);
		
	} else if(document.getElementById('unid').value=="0"){ 
		mensaje("Debe seleccionar la unidad de mnedida",1);
		
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

$("#nom").maxlength({ alwaysShow: true });
$("#des").maxlength({ alwaysShow: true });
$("#puerto").maxlength({ alwaysShow: true });

$("#naturaleza").select2();
$("#unid").select2();</script>
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