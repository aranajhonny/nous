<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
$_SESSION['acc']['mod'] = 22;
$_SESSION['acc']['form'] = 43;
include("../complementos/permisos.php");

if(isset($_REQUEST['sensor'])){ $_SESSION['sensor']=filtrar_campo('int', 6, $_REQUEST['sensor']); }

if(isset($_POST['guardar'])){ 
$tipo = filtrar_campo('int', 6, $_POST['tipo']); 
$control = filtrar_campo('int', 6, $_POST['control']);
$serial = filtrar_campo('todo', 30, $_POST['serial']);  
$unid = filtrar_campo('int', 6, $_POST['unid']);  
$est = filtrar_campo('int', 6, $_POST['est']); 
$des = filtrar_campo('todo', 250, $_POST['des']);

if(empty($tipo)){ $_SESSION['mensaje1']="Debe seleccionar el tipo de sensor";
} else if(empty($des)){ $_SESSION['mensaje1']="Debe indicar la Descripción";
} else if(empty($serial)){ $_SESSION['mensaje1']="Debe indicar el serial";
} else if(empty($unid)){ $_SESSION['mensaje1']="Debe seleccionar la unidad";
} else if(empty($est)){ $_SESSION['mensaje1']="Debe seleccionar el estatus";
} else if(in_array(221,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

	$rs = pg_query($link, filtrar_sql("update sensores set id_tipo_sensor=$tipo, id_control=$control, id_unidad=$unid, serial='$serial', id_estatus_operacion=$est, descripcion='$des' where id_sensor = ".$_SESSION['sensor']));
	if($rs){ 
		Auditoria("Actualizo Sensores: $serial $des ",$_SESSION['sensor']);
		$_SESSION['mensaje3']="Sensor Editado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar el sensor";
		Auditoria("Problema la actualizar el sensor Error: ".pg_last_error($link),$_SESSION['sensor']);
	}

} // si validar

} else if(isset($_SESSION['sensor'])){
$rs = pg_query($link, filtrar_sql("select * from sensores where id_sensor = ".$_SESSION['sensor']) );
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
	$serial = $rs[6]; 
	$est = $rs[7]; 
	$des = $rs[13];
	$control = $rs[3]; 
	$unid = $rs[4]; 
	$_SESSION['sensor_dispositivo'] = $rs[5]; 
	$_SESSION['sensor_cliente'] = $rs[2];
	Auditoria("Accedio Al Modulo Editar Sensores: $serial $des",$_SESSION['sensor']);
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
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Tipos de Sensor</label>
<div><select id="tipo" name="tipo" class="selectpicker">
<option value="0" selected="selected">Seleccione un Tipo de Sensor</option>
<?php $rs = pg_query($link, filtrar_sql("select id_tipo_sensor, descripcion from tipo_sensores order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($tipo==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div>

<div class="form-group"><label>Descripción</label>
<input id="des" name="des" type="text" placeholder="Descripción del Sensor" class="form-control" maxlength="240" value="<?php echo $des;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="form-group"><label>Serial</label>
<input id="serial" name="serial" type="text" placeholder="Numero del Serial" class="form-control" maxlength="50" value="<?php echo $serial;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /><p class="help-block">Ejemplo: #0001</p></div>

<?php $cli = "";
$rs = pg_query($link, filtrar_sql( "select rif, razon_social from clientes where id_cliente = ".$_SESSION['sensor_cliente'])); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Control</label>
<div><select id="control" name="control" class="selectpicker">
<option value="0" selected="selected">Seleccione una Control</option>
<?php $rs = pg_query($link, filtrar_sql( "select id_control, nombre from controles where id_cliente = ".$_SESSION['sensor_cliente']." order by nombre asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($control==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>
</select></div>
</div>

<?php $disp = "";
$rs = pg_query($link, filtrar_sql("select descripcion, serial from dispositivos, tipo_disp where tipo_disp.id_tipo_disp = dispositivos.id_tipo_disp and id_dispositivo = ".$_SESSION['sensor_dispositivo'])); $rs = pg_fetch_array($rs); $disp = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Dispositivo</label>
<input id="disp" name="disp" type="text" placeholder="Cliente" class="form-control" value="<?php echo $disp;?>" readonly="readonly" /></div>

<div class="form-group"><label>Unidad</label>
<div><select id="unid" name="unid" class="selectpicker">
<option value="0" selected="selected">Seleccione una Unidad</option>
<?php $rs = pg_query($link, filtrar_sql("select id_unidad, confunid.codigo_principal, unidades.codigo_principal from unidades, confunid where id_dispositivo = ".$_SESSION['sensor_dispositivo']." order by confunid.codigo_principal, unidades.codigo_principal asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($unid==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>
</select></div>
</div>

<div class="form-group"><label>Estatus</label>
<div><select id="est" name="est" class="selectpicker">
<option value="0" selected="selected">Seleccione un Estatus</option>
<?php $rs = pg_query($link, filtrar_sql("select id_estatu, nombre from estatus where tipo='Unidades Operación' order by nombre asc ")); while($r = pg_fetch_array($rs)){ ?>
<option value="<?php echo $r[0];?>" <?php if($est==$r[0])echo"selected";?>><?php echo $r[1];?></option>
<?php } ?>
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
	if(document.getElementById('tipo').value=="0"){ 
		mensaje("Debe seleccionar el tipo de sensor",1);
	
	} else if(document.getElementById('des').value.length<1){ 
		mensaje("Debe indicar la descripción",1);
		
	} else if(document.getElementById('serial').value.length<1){ 
		mensaje("Debe indicar el serial",1);
		
	} else if(document.getElementById('unid').value=="0"){ 
		mensaje("Debe seleccionar una unidad",1);
	
	} else if(document.getElementById('est').value=="0"){ 
		mensaje("Debe seleccionar el estatus",1);
		
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

$("#des").maxlength({ alwaysShow: true });
$("#serial").maxlength({ alwaysShow: true });

$("#tipo").select2();
$("#control").select2();
$("#unid").select2();
$("#est").select2();</script>
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