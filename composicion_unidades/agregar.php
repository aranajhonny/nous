<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 43;
$_SESSION['acc']['form'] = 94;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$nom = filtrar_campo('string', 30, $_POST['nom']); 
$des = filtrar_campo('string', 200, $_POST['des']); 
$dep = filtrar_campo('int', 6, $_POST['dep']);
$conf = filtrar_campo('int', 6, $_POST['conf']);

if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre de la composición";
} else if(empty($des)){$_SESSION['mensaje1']="Debe indicar la Observación de la composición";
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe seleccionar un Tipo Unidad";
} else if(in_array(94,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else{ // si validar 


	$rs = pg_query($link, filtrar_sql("insert into composiciones (nombre, descripcion, id_confunid, id_dependencia) values('$nom','$des',$conf, $dep)"));
	if($rs){ 
		
		$rs = pg_query($link, filtrar_sql("select max(id_composicion) from composiciones"));
		$rs = pg_fetch_array($rs);
		Auditoria("Agrego Composición Unidades: $des",$rs[0]);
	
		$_SESSION['mensaje3']="Composición Agregada";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar la composición";
		Auditoria("Problema al registrar Composición de Unidad Error: ".pg_last_error($link),0);
	}

} // si validar
} else { 
	$des = $nom = $dep = $conf = "";
	Auditoria("Accedio Al Modulo Agregar Composicion Unidades",0);
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
<li><a href="#">Unidades</a></li>
<li><a href="#">Composiciones</a></li>
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

<div class="header">Agregar Composición<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre de la Composición" class="form-control" maxlength="60" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>
                     
<div class="form-group"><label>Observación</label>
<input id="des" name="des" type="text" placeholder="Breve Observación de la Composición" class="form-control" maxlength="200" value="<?php echo $des;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="form-group">
<label>Tipo de Unidad</label>
<div><select id="conf" name="conf" class="selectpicker">
	<option value="0" selected="selected">Seleccione un Tipo Unidad</option>
<?php $rs = pg_query($link, filtrar_sql("select id_confunid, codigo_principal from confunid order by codigo_principal asc"));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($conf==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div>

<div class="form-group"><label>Dependencia</label>
<div><select id="dep" name="dep" class="selectpicker">
	<option value="0" selected="selected">Seleccione una Dependencia de Poseer</option>
<?php include("../composiciones/composiciones_CompUnid.php");
$rs=pg_query($link, filtrar_sql("select id_composicion, nombre from composiciones where id_dependencia = 0 order by nombre asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<?php $qs=pg_query($link, filtrar_sql("select count(id_composicion) from composiciones where id_dependencia = ".$r[0])); $qs=pg_fetch_array($qs); if($qs[0]==0){ ?>
<option value="<?php echo $r[0];?>" <?php if($dep==$r[0]) echo "selected";?> ><?php echo $r[1];?></option>
<?php } else { ?>
<option value="<?php echo $r[0];?>" <?php if($dep==$r[0]) echo "selected";?> ><?php echo $r[1];?></option>	
<?php echo ComponerComboxCompUnid($r[0], $r[1], $dep, "&emsp;"); 
} ?> 
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
		mensaje("Debe indicar el nombre de la composición",1);
		
	} else if(document.getElementById('des').value.length<1){ 
		mensaje("Debe indicar la Observación de la composición",1);

	} else if(document.getElementById('conf').value=="0"){ 
		mensaje("Debe seleccionar el Tipo Unidad",1);
		
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

$("#conf").select2();
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