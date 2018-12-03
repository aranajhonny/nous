<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 11;
$_SESSION['acc']['form'] = 2;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$cli = filtrar_campo('int', 6,$_POST['cli']); 
$cod = filtrar_campo('string', 50,   $_POST['cod']); 
$nom = filtrar_campo('string', 100,  $_POST['nom']); 
$conf4 = filtrar_campo('string', 50, $_POST['conf4']);
$conf1 = filtrar_campo('string', 50, $_POST['conf1']);  
$conf2 = filtrar_campo('string', 50, $_POST['conf2']);   
$conf3 = filtrar_campo('string', 50, $_POST['conf3']); 

if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(empty($cod)){ $_SESSION['mensaje1']="Debe indicar el tipo de unidad";
} else if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre identificador";
} else if(empty($conf1)){ $_SESSION['mensaje1']="Debe indicar la primera caracteristica";
} else if(empty($conf2)){ $_SESSION['mensaje1']="Debe indicar la segunda caracteristica";
} else if(in_array(71,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 
 
	$rs = pg_query($link, filtrar_sql("insert into confunid(id_cliente, nombre, codigo_principal, n_configuracion_01, n_configuracion_02, n_configuracion_03, n_configuracion_04) values( $cli, '$nom', '$cod', '$conf1', '$conf2', '$conf3', '$conf4')"));
	if($rs){ 
		
		$rs = pg_query($link, filtrar_sql("select max(id_confunid) from confunid"));
		$rs = pg_fetch_array($rs);
		Auditoria("Agrego configuracion unidades: $nom",$rs[0]);

		$_SESSION['mensaje3']="Configuración de Unidad Agregado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar la configuracion de la unidad";
		Auditoria("Problema al registrar La Configuración de Unidad Error: ".pg_last_error($link),0);
	}


} // si validar
} else { 
	$cli = $cod = $nom = $conf1 = $conf2 = $conf3 = $conf4 = "";
 	Auditoria("Accedio Al Modulo Agregar Configuracion unidades",0);

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
<li><a href="#">Configuración de Unidades</a></li>
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

<div class="header">Agregar Configuración de Unidades<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Cliente</label>
<div>
<?php if(isset($_SESSION['miss'][3]) && $_SESSION['miss'][3]==-1) {?>
<select id="cli" name="cli" class="selectpicker">
<option value="0" selected="selected">Seleccione un Cliente</option>
<?php $rs = pg_query($link, filtrar_sql("select id_cliente, rif, razon_social from clientes order by rif asc"));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($cli==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>    
</select>
<?php } else { 
$rs = pg_query($link,"select rif, razon_social from clientes where id_cliente = ".$_SESSION['miss'][3]); $rs = pg_fetch_array($rs); ?>

<input id="cli" name="cli" type="hidden" value="<?php echo $_SESSION['miss'][3];?>" readonly="readonly"/><input id="dcli" name="dcli" type="text" placeholder="Cliente Actual" class="form-control" value="<?php echo $rs[0]." ".$rs[1];?>" readonly="readonly" />

<?php } ?>

</div></div>

<div class="form-group"><label>Tipo de Unidad</label>
<input id="cod" name="cod" type="text" placeholder="Tipo de Unidad" class="form-control" maxlength="50" value="<?php echo $cod;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>
            
<div class="form-group"><label>Nombre del Identificador</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Identificador" class="form-control" maxlength="100" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="form-group"><label>Primera Caracteristica</label>
<input id="conf1" name="conf1" type="text" placeholder="Primera Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf1;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" />
</div>

<div class="form-group"><label>Segunda Caracteristica</label>
<input id="conf2" name="conf2" type="text" placeholder="Segunda Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf2;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" />
</div>

<div class="form-group"><label>Tercera Caracteristica</label>
<input id="conf3" name="conf3" type="text" placeholder="Tercera Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf3;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" />
</div>

<div class="form-group"><label>Cuarta Caracteristica</label>
<input id="conf4" name="conf4" type="text" placeholder="Cuarta Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf4;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" />
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
	if(document.getElementById('cod').value.length<1){ 
		mensaje("Debe indicar el tipo de unidad",1);
		
	} else if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar el nombre identificador",1);
		
	} else if(document.getElementById('conf1').value.length<1){ 
		mensaje("Debe indicar la Primera Caracteristica",1);
		
	} else if(document.getElementById('conf2').value.length<1){ 
		mensaje("Debe indicar la Segunda Caracteristica",1);
		
	} else if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar el cliente",1);
		
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

$("#cod").maxlength({ alwaysShow: true });
$("#nom").maxlength({ alwaysShow: true });
$("#conf1").maxlength({ alwaysShow: true });
$("#conf2").maxlength({ alwaysShow: true });
$("#conf3").maxlength({ alwaysShow: true });
$("#conf4").maxlength({ alwaysShow: true });

$("#cli").select2();
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