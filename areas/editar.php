<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 27;
$_SESSION['acc']['form'] = 59;
include("../complementos/permisos.php");

if(isset($_REQUEST['area'])){ $_SESSION['area']=filtrar_campo('int', 6, $_REQUEST['area']); }

if(isset($_POST['guardar'])){ 
$des = filtrar_campo('todo',250,$_POST['des']); 
$cli = filtrar_campo('int',6,$_POST['cli']); 
$dep = filtrar_campo('int',6,$_POST['dep']); 
$resp = filtrar_campo('int',6,$_POST['resp']);
if(empty($dep)) $dep=0;
if(empty($resp)) $resp=0;

if(empty($des)){ $_SESSION['mensaje1']="Debe indicar la descripción del área";
//} else if(empty($resp)){ $_SESSION['mensaje1']="Debe seleccionar un responsable del área";
} else if(in_array(85,$_SESSION['acl'])==false){$_SESSION['mensaje']= "no posee permiso para guardar este registro";

} else { // si validar 

	$rs = pg_query($link, filtrar_sql("update areas set id_dependencia=$dep, descripcion='$des', id_responsable = $resp where id_area = ".$_SESSION['area']));
	if($rs){ 
		Auditoria("Actualizo Area: $des",$_SESSION['area']);
		$_SESSION['mensaje3']="Área Editada";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar el área";
		Auditoria("Problema al actualizar el Área Error: ".pg_last_error($link),$_SESSION['area']);
	}

} // si validar

} else if(isset($_SESSION['area'])){
$rs = pg_query($link, filtrar_sql("select * from areas where id_area = ".$_SESSION['area']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico el área";
	Auditoria("Ärea No Identificada ",$_SESSION['area']);
	unset($_SESSION['area']);
	header("location: listado.php");
	exit();
} else { 
	$rs = pg_fetch_array($rs);
	$_SESSION['area_cliente'] = $rs[1]; 
	$des = $rs[2]; 
	$dep = $rs[3];
	$resp = $rs[4];
	
	
	Auditoria("Accedio Al Modulo Editar Áreas: $des",$_SESSION['area']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico el área";
	Auditoria("Ärea No Identificada ",$_SESSION['area']);
	unset($_SESSION['area']);
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
<li><a href="#">Áreas</a></li>
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

<div class="header">Editar Área<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>
                                
<div class="form-group"><label>Descripción</label>
<input id="des" name="des" type="text" placeholder="Breve Descripción o Denominación del Área" class="form-control" maxlength="250" value="<?php echo $des;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<?php $cli = "";
$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$_SESSION['area_cliente'])); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Responsable</label>
<div><select id="resp" name="resp" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable</option>
<?php $rs = pg_query($link, filtrar_sql("select id_personal, ci, nombre from personal where id_cliente = ".$_SESSION['area_cliente']." order by ci asc ")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($resp==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>    
</select></div></div>

<div class="form-group"><label>Dependencia</label>
<div><select id="dep" name="dep" class="selectpicker">
<option value="0" selected="selected">Seleccione una Dependencia de poseer</option>
<?php include("../composiciones/composiciones_areas.php");
$rs=pg_query($link, filtrar_sql("select id_area, descripcion from areas where id_dependencia = 0 and id_cliente = ".$_SESSION['area_cliente']." and id_area <> ".$_SESSION['area']." order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<?php $qs=pg_query($link, filtrar_sql("select count(id_area) from areas where id_dependencia = ".$r[0])); $qs=pg_fetch_array($qs); if($qs[0]==0){ ?>
<option value="<?php echo $r[0];?>" <?php if($dep==$r[0]) echo "selected";?> ><?php echo $r[1];?></option>
<?php } else { ?>
<option value="<?php echo $r[0];?>" <?php if($dep==$r[0]) echo "selected";?> ><?php echo $r[1];?></option>	
<?php echo ComponerComboxAreas2($r[0], $r[1], $dep, "&emsp;"); } ?> 
<?php } } ?>  
</select></div>
<p class="help-block">Área de la que depende organizacionalmente esta Área</p>
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
	if(document.getElementById('des').value.length<3){ 
		mensaje("Debe indicar la descripción",1);
		
	} else if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar un cliente",1);
	
	//} else if(document.getElementById('resp').value=="0"){ 
	//	mensaje("Debe seleccionar un responsable del área",1);
	
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
$("#resp").select2();
$("#dep").select2();</script>
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