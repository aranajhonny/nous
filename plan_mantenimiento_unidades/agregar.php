<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");


$_SESSION['acc']['mod'] = 47;
$_SESSION['acc']['form'] = 144;
include("../complementos/permisos.php");

Auditoria("Accedio Al Modulo de Asignar Plan de Mantenimiento a Unidades",0);

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
<li><a href="#">Mantenimiento</a></li>
<li><a href="#">Planes de Mantenimeinto</a></li>
<li><a href="#">Asignar</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<!-- InstanceEndEditable -->
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">
<!-- InstanceBeginEditable name="formulario" -->



<div class="header">Asignar Planes de Mantenimiento <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Cliente</label>
<div><select id="cli" name="cli" class="selectpicker">
<option value="0" selected="selected">Seleccione un Cliente</option>
<!-- LLENADO POR JAVASCRIPT -->    
</select></div></div>

         
<div class="form-group"><label>Zona Geográfica</label>
<div><select id="zona" name="zona" class="selectpicker">
<option value="0" selected="selected">Seleccione una Zona</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Área</label>
<div><select id="area" name="area" class="selectpicker">
<option value="0" selected="selected">Seleccione un Área</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>          
         
<div class="form-group"><label>Tipo de Unidad</label>
<div><select id="unid" name="unid" class="selectpicker">
<option value="0" selected="selected">Seleccione una Tipo de Unidad</option>
<!-- LLENADO POR JAVASCRIPT --> 
</select></div>
</div>   

<div class="form-group">
<label>Planes de Mantenimiento</label>
<div><select id="plan" name="plan" class="selectpicker" onchange="fechas();">
<option value="0" selected="selected">Seleccione un Plan de Mantenimiento</option>
<!-- LLENADO POR JAVASCRIPT --> 
</select></div>
</div>

<div class="form-group">
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha de Inicio de la Programación</label>
<input id="fi" name="fi" type="text" placeholder="Fecha de Inicio de la Programación" class="form-control" maxlength="12" value="<?php echo $fi;?>" disabled="disabled" /><p class="help-block">Ejemplo: 01/01/2014</p>
</div>
<div class='col-xs-12 col-sm-6 col-md-6 col-lg-6'>
<label>Fecha de Finalización de la Programación</label>
<input id="ff" name="ff" type="text" placeholder="Fecha de Finalización de la Programación" class="form-control" maxlength="12" value="<?php echo $ff;?>" disabled="disabled"  /><p class="help-block">Ejemplo: 31/12/2014</p>
</div>      
</div>                       
                                
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="buscar" value="Buscar Unidades" class="btn btn-primary btn-block" onclick="CargarUnidades();"/></div></div>

</form>
</div>

<div class="well">
<div class="header">Lista de Unidades<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
	
<iframe src="vacio.php" name="list" id="list" height="660" width="915" scrolling="no" style="border:none;background:none;"></iframe>

</div>


<script>
function fechas(){ 
	var pl = document.getElementById('plan').value.split("--");
	if(Number(pl[1])==1){ 
		document.getElementById('fi').disabled=true;
		document.getElementById('ff').disabled=true;
	} else if(Number(pl[1])==0){ 
		document.getElementById('fi').disabled=false;
		document.getElementById('ff').disabled=false;
	} else { 
		alert('Fallo');
	}
}
</script>
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
<script>

$("#cli").select2();
$("#plan").select2();
$("#zona").select2();
$("#area").select2();
$("#unid").select2();
</script>
<script>

$(document).ready(function(){
	cargar_clientes();
	$("#cli").change(function(){ 
		dependencia_planes();
		dependencia_zonas();
		dependencia_areas();
		dependencia_tipounidad(); 
	});
	$("#unid").change(function(){ 
		dependencia_planes2();
	});
	$("#plan").attr("disabled",true);
	$("#zona").attr("disabled",true);
	$("#area").attr("disabled",true);
	$("#unid").attr("disabled",true);
});
function cargar_clientes(){
	$.get("../combox/cargar_clientes.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ $('#cli').append(resultado);	}
	});	
}
function dependencia_tipounidad(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_confunid.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#unid").attr("disabled",false);
				document.getElementById("unid").options.length=0;
				$('#unid').append(resultado);			
			}
		}
	);
}

function dependencia_zonas(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_zonas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#zona").attr("disabled",false);
				document.getElementById("zona").options.length=0;
				$('#zona').append(resultado);			
			}
		}
	);
}

function dependencia_areas(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_areas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#area").attr("disabled",false);
				document.getElementById("area").options.length=0;
				$('#area').append(resultado);			
			}
		}
	);
}

function dependencia_planes(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_planes_y_maestros.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#plan").attr("disabled",false);
				document.getElementById("plan").options.length=0;
				$('#plan').append(resultado);			
			}
		}
	);
}

function dependencia_planes2(){
	var code = $("#unid").val();
	$.get("../combox/dependencia_planes_y_maestros2.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#plan").attr("disabled",false);
				document.getElementById("plan").options.length=0;
				$('#plan').append(resultado);			
			}
		}
	);
}
</script>

<script>
function CargarUnidades(){ 
var cl = document.getElementById('cli').value;
var pl = document.getElementById('plan').value;
var tu = document.getElementById('unid').value.split(":::");
var zo = document.getElementById('zona').value;
var ae = document.getElementById('area').value;
var fi = document.getElementById('fi').value;
var ff = document.getElementById('ff').value;

if(cl!="0" && pl!="0"){ 	
	document.getElementById('list').src="cargar_unidades.php?data="+cl+":::"+pl+":::"+tu[0]+":::"+zo+":::"+ae+":::"+fi+":::"+ff+"&limpiar=true";
	
}
}

</script>

<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
$(function() {
	$( "#fi" ).datepicker({ 
		defaultDate: "0",
		minDate: 0,
		maxDate: "+36M +1D",
		onClose: function( selectedDate ) {
			$( "#ff" ).datepicker( "option", "minDate", selectedDate );
		}
	});
	$( "#ff" ).datepicker({
		defaultDate: "0",
		minDate: 0,
		maxDate: "+36M +1D",
		onClose: function( selectedDate ) {
			$( "#fi" ).datepicker( "option", "maxDate", selectedDate );
		}
	});
});
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