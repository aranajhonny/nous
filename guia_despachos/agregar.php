<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");


$_SESSION['acc']['mod'] = 52;
$_SESSION['acc']['form'] = 114;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$cli = filtrar_campo('int', 6,$_POST['cli']); 
$ruta = filtrar_campo('int', 6,$_POST['ruta']); 
$est = filtrar_campo('string', 20,$_POST['est']); 
$unid = filtrar_campo('int', 6,$_POST['unid']); 
$fact = filtrar_campo('num', 50,$_POST['fact']); 
$fe = filtrar_campo('date', 10,$_POST['fe']);
$cod = filtrar_campo('todo', 50,$_POST['cod']); 
$conf1 = filtrar_campo('todo', 50,$_POST['conf1']); 
$obs = filtrar_campo('todo', 250,$_POST['obs']);
$conf2 = filtrar_campo('todo', 50,$_POST['conf2']);  
$conf3 = filtrar_campo('todo', 50,$_POST['conf3']); 
$conf = explode(":::",filtrar_campo('cadena', 120,$_POST['conf'])); 
$conf = filtrar_campo('int', 6,$conf[0]);


if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(empty($unid)){ $_SESSION['mensaje1']="Debe seleccionar la unidad";
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe seleccionar la tipo de despacho";
} else if(empty($ruta)){ $_SESSION['mensaje1']="Debe seleccionar la ruta";
} else if(empty($fact)){ $_SESSION['mensaje1']="Debe indicar la Factura";
} else if(empty($fe)){ $_SESSION['mensaje1']="Debe seleccionar la fecha de entrega";
} else if(empty($cod)){ $_SESSION['mensaje1']="Debe indicar el Código Principal";
} else if(empty($conf1)){ $_SESSION['mensaje1']="Debe indicar la Primera Caracteristica";
} else if(empty($conf2)){ $_SESSION['mensaje1']="Debe indicar la Segunda Caracteristica";
} else if(empty($conf3)){ $_SESSION['mensaje1']="Debe indicar la Tercera Caracteristica";
} else if(empty($est)){ $_SESSION['mensaje1']="Debe Seleccionar el Estatus";
} else if(in_array(99,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar


	$rs = pg_query($link, filtrar_sql("insert into guiadesp(id_cliente, id_unidad, id_confguia, id_ruta, factura, fecha_entrega,  codigo_principal, n_configuracion_01, n_configuracion_02, n_configuracion_03, posicion, fecha_inicio, estatus, observaciones) values ($cli, $unid, $conf, $ruta, '$fact', '".date2($fe)."', '$cod', '$conf1', '$conf2', '$conf3', null,  '".date("Y-m-d")."', '$est', '$obs')"));
	if($rs){ 
		
		$rs = pg_query($link, filtrar_sql("select max(id_guiadesp) from guiadesp "));
		$rs = pg_fetch_array($rs);
	 	Auditoria("Agrego Guia Despacho: $cod",$rs[0]);
		
		$_SESSION['mensaje3']="Guías de Despacho Agregada";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar la Guías de Despacho";
		Auditoria("Problema al registrar La Guía de Despacho Error: ".pg_last_error($link),$_SESSION['guia']);
	}

} // si validar

} else { 
$cod = $ruta = $cli = $conf1 = $conf2 = $conf3 = $unid = $est = $obs = $fe = $facty = "";
	Auditoria("Accedio Al Modulo Agregar Guia Despachos",0);

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
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet"/>
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
<li><a href="#">Guías</a></li>
<li><a href="#">Guías de Despacho</a></li>
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

<div class="header">Agregar Guía de Despacho<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Cliente</label>
<div><select id="cli" name="cli" class="selectpicker">
<option value="0" selected="selected">Seleccione un Cliente</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Unidades</label>
<div><select id="unid" name="unid" class="selectpicker">
<option value="0" selected="selected">Seleccione una Unidad</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group">
<label>Tipo de Despacho</label>
<div><select id="conf" name="conf" class="selectpicker" onchange="CargarConfUnid();">
<option value="0" selected="selected">Seleccione una Configuración de Guía de Despacho</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Ruta</label>
<div><select id="ruta" name="ruta" class="selectpicker">
<option value="0" selected="selected">Seleccione un Ruta</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Factura</label>
<input id="fact" name="fact" type="text" placeholder="Nro, Código o Identificador de la Factura" class="form-control" maxlength="50" value="<?php echo $fact;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div>

<div class="form-group"><label>Fecha de Entrega</label>
<input id="fe" name="fe" type="text" placeholder="Fecha de Entrega" class="form-control" maxlength="12" value="<?php echo $fe;?>"  /></div>


<div class="form-group"><label id="eti4"></label>
<input id="cod" name="cod" type="text" placeholder="Código Principal" class="form-control" maxlength="60" value="<?php echo $cod;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div>

<div class="form-group"><label id="eti1"></label>
<input id="conf1" name="conf1" type="text" placeholder="Primera Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf1;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div> 

<div class="form-group"><label id="eti2"></label>
<input id="conf2" name="conf2" type="text" placeholder="Segunda  Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf2;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div> 

<div class="form-group"><label id="eti3"></label>
<input id="conf3" name="conf3" type="text" placeholder="Tercera  Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf3;?>" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" /></div> 

    
<div class="form-group"><label>Estatus</label>
<div><select id="est" name="est" class="selectpicker">
<option value="0" selected="selected">Seleccione un Estatus</option>
<option <?php if(strcmp($est,"Por Despachar")==0)echo"selected";?>>Por Despachar</option>
<option <?php if(strcmp($est,"Retenido")==0)echo"selected";?>>Retenido</option>
<option <?php if(strcmp($est,"Reenviar")==0)echo"selected";?>>Reenviar</option>
<option <?php if(strcmp($est,"Despachado Parcial")==0)echo"selected";?>>Despachado Parcial</option>
<option <?php if(strcmp($est,"Despachado Completo")==0)echo"selected";?>>Despachado Completo</option>
<option <?php if(strcmp($est,"Devolución Parcial")==0)echo"selected";?>>Devolución Parcial</option>
<option <?php if(strcmp($est,"Devolución Total")==0)echo"selected";?>>Devolución Total</option>
</select></div>
</div>

<div class="form-group"><label>Observaciones</label>
<textarea rows="8" class="form-control" id="obs" name="obs" onkeyup="mayu(this)" onkeypress="return permite(event, 'todo')" ><?php echo $obs;?></textarea></div>

                          
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
	if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar el cliente",1);
		
	} else if(document.getElementById('conf').value=="0"){ 
		mensaje("Debe seleccionar el tipo de despacho",1);

	} else if(document.getElementById('ruta').value=="0"){ 
		mensaje("Debe seleccionar una Ruta",1);
	
	} else if(document.getElementById('fact').value.length<1){ 
		mensaje("Debe indicar la Factura ",1);
	
	} else if(document.getElementById('fe').value=="0"){ 
		mensaje("Debe seleccionar la fecha de entrega",1);
				
	//} else if(document.getElementById('unid').value=="0"){ 
		//mensaje("Debe seleccionar El tipo de unidad ",1);
		

	} else if(document.getElementById('cod').value.length<1){ 
		mensaje("Debe indicar la "+label5,1);
			
	} else if(document.getElementById('conf1').value.length<1){ 
		mensaje("Debe indicar la "+label1,1);
	
	} else if(document.getElementById('conf2').value.length<1){ 
		mensaje("Debe indicar la "+label2,1);
		
	} else if(document.getElementById('conf3').value.length<1){ 
		mensaje("Debe indicar la "+label3,1);		
	
	} else if(document.getElementById('est').value=="0"){ 
		mensaje("Debe seleccionar el Estatus",1);
		
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

//$("#prop").maxlength({ alwaysShow: true });
$("#conf1").maxlength({ alwaysShow: true });
$("#conf2").maxlength({ alwaysShow: true });
$("#conf3").maxlength({ alwaysShow: true });
$("#cod").maxlength({ alwaysShow: true });
$("#fact").maxlength({ alwaysShow: true });
$("#obs").maxlength({ alwaysShow: true });

$("#cli").select2();
$("#unid").select2();
$("#conf").select2();
$("#ruta").select2();
$("#est").select2();</script>

<script>

$(document).ready(function(){
	cargar_clientes();
	$("#cli").change(function(){ 
		dependencia_rutas(); 
		dependencia_confguia();
		dependencia_unidades();
	});
	$("#ruta").attr("disabled",true);
	$("#conf").attr("disabled",true);
	$("#unid").attr("disabled",true);
});
function cargar_clientes(){
	$.get("../combox/cargar_clientes.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ $('#cli').append(resultado);	}
	});	
}
function dependencia_rutas(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_rutas.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#ruta").attr("disabled",false);
				document.getElementById("ruta").options.length=0;
				$('#ruta').append(resultado);			
			}
		}
	);
}
function dependencia_unidades(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_unidades.php", { code: code },
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
function dependencia_confguia(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_confguia.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#conf").attr("disabled",false);
				document.getElementById("conf").options.length=0;
				$('#conf').append(resultado);			
			}
		}
	);
}
</script>

<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
$(function() {
	$( "#fe" ).datepicker();
});

var label1="", label2="", label3="", label4="";
function CargarConfUnid(){ 
	var id = document.getElementById('conf').value;
	if(id=='0'){ 
		document.getElementById('eti1').innerHTML="";
		document.getElementById('eti2').innerHTML="";
		document.getElementById('eti3').innerHTML="";
		document.getElementById('eti4').innerHTML="";
	} else { 
		id = id.split(":::");
		label1 = id[1];
		label2 = id[2];
		label3 = id[3];
		label4 = id[4];
		document.getElementById('eti1').innerHTML=id[1];
		document.getElementById('eti2').innerHTML=id[2];
		document.getElementById('eti3').innerHTML=id[3];
		document.getElementById('eti4').innerHTML=id[4];
	}
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