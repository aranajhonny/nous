<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 31;
$_SESSION['acc']['form'] = 71;
include("../complementos/permisos.php");

if(isset($_REQUEST['pers'])){ $_SESSION['personal']=filtrar_campo('int', 6, $_REQUEST['pers']); }

if(isset($_POST['guardar'])){ 
$ci = filtrar_campo('ci', 10, $_POST['ci']); 
$nom = filtrar_campo('string', 250, $_POST['nom']); 
$tlf = filtrar_campo('tlf', 12, $_POST['tlf']); 
$correo = filtrar_campo('todo', 120, $_POST['correo']); 
$cli = filtrar_campo('int', 6, $_POST['cli']); 
$cargo = filtrar_campo('int', 6, $_POST['cargo']); 
$zona = filtrar_campo('int', 6, $_POST['zona']); 
$area = filtrar_campo('int', 6, $_POST['area']);  
$aviso = filtrar_campo('string', 20, $_POST['aviso']);
$conf = filtrar_campo('cadena', 120, $_POST['conf']);

if(empty($ci)){ $_SESSION['mensaje1']="Debe indicar el numero de CI";
} else if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre del personal";
} else if(empty($tlf)){ $_SESSION['mensaje1']="Debe indicar el numero de télefono";
} else if(empty($correo)){ $_SESSION['mensaje1']="Debe indicar el correo del personal";
} else if(empty($cargo)){ $_SESSION['mensaje1']="Debe seleccionar el cargo";
} else if(empty($aviso)){ $_SESSION['mensaje1']="Debe seleccionar El Tipo de Aviso";
} else if(in_array(228,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

$rs = pg_query($link, filtrar_sql("select count(id_personal) from personal where ci='$ci' and id_personal <> ".$_SESSION['personal']));
$rs = pg_fetch_array($rs);
if($rs[0]>0){ 
	$_SESSION['mensaje1']="Numero de CI ya registrado";
} else { // si rif 


if($_POST['conf']==-1){ $conf=-1; } else { 
$conf = explode(":::",$_POST['conf']); 
$conf = filtrar_campo('int', 6, $conf[0]); }


	$rs = pg_query($link, filtrar_sql("update personal set ci='$ci', nombre='$nom', telefono='$tlf', email='$correo', id_cargo=$cargo, id_area=$area, id_zona=$zona, id_confunid=$conf, tipo_aviso='$aviso' where id_personal = ".$_SESSION['personal']));
	if($rs){ 
		Auditoria("Actualizo Personal: $ci $nom",$_SESSION['personal']);
		$_SESSION['mensaje3']="Personal Editado";
		pg_query($link, filtrar_sql("update usuarios set id_area = $area, id_zona = $zona, id_confunid = $conf where id_usuario = ".$_SESSION['personal']));
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar el Personal";
	}
} // si rif
} // si validar

} else if(isset($_SESSION['personal'])){
$rs = pg_query($link, filtrar_sql("select * from personal where id_personal = ".$_SESSION['personal']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico El Personal";
	Auditoria("Personal No Identificado ",$_SESSION['personal']);
	unset($_SESSION['personal']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$cargo = $rs[1]; $ci = $rs[2]; $nom = $rs[3]; $tlf = $rs[4]; $correo = $rs[5];
	$_SESSION['pers_cliente'] = $rs[6]; $area = $rs[7]; $zona = $rs[8]; $conf = $rs[9];
	$aviso = $rs[10];
	Auditoria("Accedio Al Modulo Editar Personal: $ci $nom",$_SESSION['personal']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico el personal";
	Auditoria("Personal No Identificado ",$_SESSION['personal']);
	unset($_SESSION['personal']);
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
<li><a href="#">Usuarios</a></li>
<li><a href="#">Personal</a></li>
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

<div class="header">Editar Personal<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>C.I.</label>
<input id="ci" name="ci" type="text" placeholder="Cédula de Identidad" class="form-control" maxlength="10" value="<?php echo $ci;?>" onkeypress="return permite(event,'ci')" onkeyup="mayu(this)" /><p class="help-block">Ejemplo: V-20123456 o E-20123456</p></div>
                                
<div class="form-group"><label>Primer Nombre y Apellido</label>
<input id="nom" name="nom" type="text" placeholder="Nombre y Apellido del Personal" class="form-control" maxlength="250" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="form-group"><label>Télefono</label>
<input id="tlf" name="tlf" type="text" placeholder="Numero de Télefono" class="form-control" maxlength="12" value="<?php echo $tlf;?>" onkeypress="return permite(event,'telef')" /><p class="help-block">Ejemplo: 0412-1234567</p></div>

<div class="form-group"><label>Correo del Contacto</label>
<input id="correo" name="correo" type="text" placeholder="Correo del Personal" class="form-control" maxlength="120" value="<?php echo $correo;?>" onkeypress="return permite(event,'todo')"/><p class="help-block">Ejemplo: micorreo@gmail.com</p></div>

<?php $cli = "";
$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$_SESSION['pers_cliente'])); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Área</label>
<div><select id="area" name="area" class="selectpicker">
<option value="0" selected="selected">Seleccione un Área</option>
<?php include("../composiciones/composiciones_areas.php");
$rs=pg_query($link, filtrar_sql("select id_area, descripcion from areas where id_dependencia = 0 and  id_cliente = ".$_SESSION['pers_cliente']." order by descripcion asc ") ); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<?php $qs=pg_query($link, filtrar_sql("select count(id_area) from areas where id_dependencia = ".$r[0])); $qs=pg_fetch_array($qs); if($qs[0]==0){ ?>
<option value="<?php echo $r[0];?>" <?php if($area==$r[0]) echo "selected";?> ><?php echo $r[1];?></option>
<?php } else { ?>
<option value="<?php echo $r[0];?>" <?php if($area==$r[0]) echo "selected";?> ><?php echo $r[1];?></option>	
<?php echo ComponerComboxAreas2($r[0], $r[1], $area, "&emsp;"); } ?>
<?php } } ?>
<option value="-1" <?php if($area==-1) echo "selected";?>>TODAS LAS AREAS</option>
</select></div>
</div>

<div class="form-group"><label>Zona Geográfica</label>
<div><select id="zona" name="zona" class="selectpicker">
<option value="0" selected="selected">Seleccione una Zona</option>
<?php $rs = pg_query($link, filtrar_sql("select id_zongeo, nombre from zongeo where id_cliente = ".$_SESSION['pers_cliente']." order by nombre asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($zona==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>
<option value="-1" <?php if($zona==-1) echo "selected";?>>TODAS LAS ZONAS GEOGRAFICAS</option>
</select></div>
</div>

<div class="form-group"><label>Tipo de Unidad</label>
<div><select id="conf" name="conf" class="selectpicker" onchange="CargarConfUnid();">
<option value="0" selected="selected">Seleccione un Tipo Unidad</option>
<?php $rs = pg_query($link, filtrar_sql("select id_confunid, nombre, n_configuracion_01, n_configuracion_02, n_configuracion_03, n_configuracion_04, codigo_principal from confunid where id_cliente = ".$_SESSION['pers_cliente']." order by nombre asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ 
$id = $r[0].":::".$r[2].":::".$r[3].":::".$r[4].":::".$r[5].":::".$r[1];?>    
<option value="<?php echo $id;?>" <?php if($conf==$r[0]) echo "selected";?> ><?php echo $r[6];?></option> 
<?php } } ?>
<option value="-1" <?php if($conf==-1) echo "selected";?>>TODOS LOS TIPO DE UNIDAD</option>
</select></div>
</div>

<div class="form-group"><label>Cargos</label>
<div><select id="cargo" name="cargo" class="selectpicker">
<option value="0" selected="selected">Seleccione un Cargo</option>
<?php $rs = pg_query($link, filtrar_sql("select id_cargo, descripcion from cargos order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($cargo==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div>
             
<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<label>Tipo de Aviso</label>
<div><select id="aviso" name="aviso" class="selectpicker">
<option value="0" selected="selected">Seleccione</option>
<option <?php if(strcmp($aviso,"SMS")==0) echo "selected";?>>SMS</option>
<option <?php if(strcmp($aviso,"Correo")==0) echo "selected";?>>Correo</option>
<option <?php if(strcmp($aviso,"Ambos")==0) echo "selected";?>>Ambos</option>
</select></div><p>&nbsp;</p></div>
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
	if(document.getElementById('ci').value.length<10){ 
		mensaje("Debe indicar el numero de ci y debe contener al menos 10 digitos",1);
		
	} else if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar el nombre del personal",1);
		
	} else if(document.getElementById('tlf').value.length<10){ 
		mensaje("Debe indicar el numero de telefono y debe contener al menos 11 digitos",1);
	
	} else if(document.getElementById('correo').value.length<1){ 
		mensaje("Debe indicar el correo del personal",1);
		
	} else if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar un cliente",1);
	
	} else if(document.getElementById('cargo').value=="0"){ 
		mensaje("Debe seleccionar un cargo",1);
		
	} else if(document.getElementById('aviso').value=="0"){ 
		mensaje("Debe seleccionar El Tipo de Aviso",1);	
		
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
$("#tlf").maxlength({ alwaysShow: true });
$("#correo").maxlength({ alwaysShow: true });
$("#ci").maxlength({ alwaysShow: true });


$("#area").select2();
$("#zona").select2();
$("#conf").select2();
$("#cargo").select2();
$("#aviso").select2();</script>
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