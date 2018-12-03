<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 18;
$_SESSION['acc']['form'] = 26;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$tipo = filtrar_campo('int', 6,$_POST['tipo']); 
$lote = filtrar_campo('int', 6,$_POST['lote']); 
$cli =  filtrar_campo('int', 6,$_POST['cli']); 
$est = filtrar_campo('string', 20,$_POST['est']); 
$serial = filtrar_campo('todo', 20,$_POST['serial']);

if(empty($serial)){ $_SESSION['mensaje1']="Debe indicar el numero del serial";
} else if(empty($tipo)){ $_SESSION['mensaje1']="Debe seleccionar el tipo de dispositivo";
} else if(empty($lote)){ $_SESSION['mensaje1']="Debe seleccionar un lote";
} else if(empty($est)){ $_SESSION['mensaje1']="Debe seleccionar el estatus del dispositivo";
} else if(in_array(77,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

	$rs = pg_query($link, filtrar_sql("insert into dispositivos(id_tipo_disp, id_cliente, id_lote, serial, estatus) values($tipo, $cli, $lote, '$serial', '$est')"));
	if($rs){ 
	
		$rs = pg_query($link, filtrar_sql("select max(id_dispositivo) from dispositivos "));
		$rs = pg_fetch_array($rs);
 		Auditoria("Agrego dispositivo: $serial", $rs[0]);
		
		$_SESSION['mensaje3']="Dispositivo Agregado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el dispositivo";
		Auditoria("Problema al registrar el dispositivo Error: ".pg_last_error($link),0);
	}

} // si validar
} else { 
	$est = $serial = "";
	$tipo = $cli = $lote = 0;
	Auditoria("Accedio Al Modulo Agregar diposistivos",0);
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
<li><a href="#">Dispositivos</a></li>
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

<div class="header">Agregar Dispositivo<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Serial</label>
<input id="serial" name="serial" type="text" placeholder="Numero del Serial del Dispositivo" class="form-control" maxlength="20" value="<?php echo $serial;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /><p class="help-block">Ejemplo: #0001</p></div>
                                
<div class="form-group"><label>Tipo de Dispositivo</label>
<div><select id="tipo" name="tipo" class="selectpicker">
   
</select></div>
</div>

<div class="form-group"><label>Cliente</label>
<div><select id="cli" name="cli" class="selectpicker">
   
</select></div>
</div>

<div class="form-group"><label>Lote</label>
<div><select id="lote" name="lote" class="selectpicker">
   
</select></div>
</div>

<div class="form-group"><label>Estatus</label>
<div><select id="est" name="est" class="selectpicker">
<option value="0" selected="selected">Seleccione un Estatus</option>
<option <?php if(strcmp($est,"Almacen")==0)echo"selected";?>>Almacen</option>
<option <?php if(strcmp($est,"Desincorporado")==0)echo"selected";?>>Desincorporado</option>
<option <?php if(strcmp($est,"Operación")==0)echo"selected";?>>Operación</option>
<option <?php if(strcmp($est,"Reparación")==0)echo"selected";?>>Reparación</option>
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
	if(document.getElementById('serial').value.length<3){ 
		mensaje("Debe indicar el numero del serial",1);
		
	} else if(document.getElementById('tipo').value=="0"){ 
		mensaje("Debe seleccionar el tipo de dispositivo",1);
		
	} else if(document.getElementById('lote').value=="0"){ 
		mensaje("Debe seleccionar un lote",1);
		
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

$("#serial").maxlength({ alwaysShow: true });

$("#est").select2();</script>


<script>
$(document).ready(function(){
	cargar_clientes();
	cargar_lotes();
	cargar_tipodisp();
});
function cargar_clientes(){
	$.get("../combox/cargar_clientes.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ 
			$('#cli').append(resultado);	
			document.getElementById('cli').value = <?php echo $cli; ?>;
			$("#cli").select2();
		}
	});	
}
function cargar_lotes(){
	$.get("../combox/cargar_lotes.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ 
			$('#lote').append(resultado);	
			document.getElementById('lote').value = <?php echo $lote; ?>;
			$("#lote").select2();
		}
	});	
}
function cargar_tipodisp(){
	$.get("../combox/cargar_tipodisp.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ 
			$('#tipo').append(resultado);	
			document.getElementById('tipo').value = <?php echo $tipo; ?>;
			$("#tipo").select2();
		}
	});	
}
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