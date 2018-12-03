<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");


$_SESSION['acc']['mod'] = 52;
$_SESSION['acc']['form'] = 115;
include("../complementos/permisos.php");

if(isset($_REQUEST['guia'])){ $_SESSION['guia']=filtrar_campo('int', 6,$_REQUEST['guia']); }

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
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe seleccionar el tipo de despacho";
} else if(empty($ruta)){ $_SESSION['mensaje1']="Debe seleccionar la ruta";
} else if(empty($fact)){ $_SESSION['mensaje1']="Debe indicar la Factura";
} else if(empty($fe)){ $_SESSION['mensaje1']="Debe seleccionar la fecha de entrega";
} else if(empty($cod)){ $_SESSION['mensaje1']="Debe indicar el Código Principal";
} else if(empty($conf1)){ $_SESSION['mensaje1']="Debe indicar la Primera Caracteristica";
} else if(empty($conf2)){ $_SESSION['mensaje1']="Debe indicar la Segunda Caracteristica";
} else if(empty($conf3)){ $_SESSION['mensaje1']="Debe indicar la Tercera Caracteristica";
} else if(empty($est)){ $_SESSION['mensaje1']="Debe Seleccionar el Estatus";
} else if(in_array(239,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";

} else { // si validar


	$rs = pg_query($link, filtrar_sql("update guiadesp set id_unidad = $unid, id_confguia = $conf, id_ruta = $ruta, factura = '$fact', fecha_entrega='".date2($fe)."',  codigo_principal='$cod', n_configuracion_01='$conf1', n_configuracion_02='$conf2', n_configuracion_03='$conf3', estatus='$est', observaciones='$obs' where id_guiadesp = ".$_SESSION['guia']));
	if($rs){ 
		Auditoria("Actualizo Guia de Despacho: $cod",$_SESSION['guia']);
		$_SESSION['mensaje3']="Guías de Despacho Editado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar la Guías de Despacho";
		Auditoria("Problema al actualizar La Guía de Despacho Error: ".pg_last_error($link),$_SESSION['guia']);
	}

} // si validar

} else if(isset($_SESSION['guia'])){
$rs = pg_query($link, filtrar_sql("select * from guiadesp where id_guiadesp = ".$_SESSION['guia']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico La Guía de Despacho";
	Auditoria("Guía de Despacho No Identificada ",$_SESSION['guia']);
	unset($_SESSION['guia']);
	header("location: listado.php");
	exit();
} else { 
	$rs = pg_fetch_array($rs); 
	$unid = $rs[1]; 
	$conf = $rs[2]; 
	$_SESSION['guia_cliente'] = $rs[3];
	$ruta = $rs[4]; 
	$fact = $rs[5]; 
	$fe = date1($rs[6]); 
	$cod = $rs[7]; 
	$conf1 = $rs[8];
	$conf2 = $rs[9]; 
	$conf3 = $rs[10]; 
	$est = $rs[13]; 
	$obs = $rs[14];
	Auditoria("Accedio Al Modulo Editar Guia de Despacho: $cod",$_SESSION['guia']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico la Guía de Despacho";
	Auditoria("Guía de Despacho No Identificada",$_SESSION['guia']);
	unset($_SESSION['guia']);
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

<div class="header">Editar Guía de Despacho<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>

<?php $cli = "";
$rs = pg_query($link, filtrar_sql("select rif, razon_social from clientes where id_cliente = ".$_SESSION['guia_cliente'])); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Unidades</label>
<div><select id="unid" name="unid" class="selectpicker">
<option value="0" selected="selected">Seleccione una Unidad</option>
<?php $rs = pg_query($link, filtrar_sql("select id_unidad, unidades.codigo_principal, confunid.codigo_principal from unidades, confunid where unidades.id_confunid = confunid.id_confunid and unidades.id_cliente = ".$_SESSION['guia_cliente']." order by unidades.codigo_principal asc"));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($unid==$r[0]) echo "selected";?> ><?php echo $r[2]." ".$r[1];?></option> 
<?php } } ?>
</select></div>
</div>

<div class="form-group">
<label>Tipo de Despacho</label>
<div><select id="conf" name="conf" class="selectpicker" onchange="CargarConfUnid();">
<option value="0" selected="selected">Seleccione una Configuración de Guía de Despacho</option>
<?php $rs = pg_query($link, filtrar_sql("select id_confguia, nombre, n_configuracion_01, n_configuracion_02, n_configuracion_03, codigo_principal from confguia where id_cliente = ".$_SESSION['guia_cliente']." order by nombre asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ 
$id = $r[0].":::".$r[2].":::".$r[3].":::".$r[4].":::".$r[5];?>   
 
<option value="<?php echo $id;?>" <?php if($conf==$r[0]) echo "selected";?> >
<?php echo $r[1];?></option> 

<?php } } ?>
</select></div>
</div>

<div class="form-group"><label>Rutas</label>
<div><select id="ruta" name="ruta" class="selectpicker">
<option value="0" selected="selected">Seleccione una Ruta</option>
<?php $rs = pg_query($link, filtrar_sql("select id_ruta, nombre from rutas where id_cliente = ".$_SESSION['guia_cliente']." order by nombre asc "));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($ruta==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>
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
<input id="est" name="est" type="text" placeholder="Estatus" class="form-control" value="<?php echo $est;?>" /></div>

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
	if(document.getElementById('conf').value=="0"){ 
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

$("#unid").select2();
$("#conf").select2();
$("#ruta").select2();
$("#est").select2();</script>

<script src="../jquery/development-bundle/ui/jquery.ui.core.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.widget.js"></script>
<script src="../jquery/development-bundle/ui/jquery.ui.datepicker.js"></script>
<link rel="stylesheet" href="../../jquery/development-bundle/themes/custom-theme/jquery.ui.datepicker.css"/>
<script> 
$(function() {
	$( "#fe" ).datepicker();
});</script>


<script>
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

CargarConfUnid();
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