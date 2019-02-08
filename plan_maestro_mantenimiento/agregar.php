<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 45;
$_SESSION['acc']['form'] = 102;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$nom = $_POST['nom']; 
$cli = $_POST['cli']; 
$res= $_POST['res'];
$sensor = $_POST['sensor']; 
$prog = "Incremental"; 
$mod = $_POST['mod']; 
if($sensor==0){ $unidmed = 0; } else { list($sensor, $unidmed) = explode(":::",$sensor); }
$porc = $_POST['porc']; if(empty($porc)) $porc=0;
$prom = $_POST['prom'];
$conf = $_POST['conf'];
list($conf,$tmp) = explode(":::",$conf,2);
$prov = $_POST['prov'];

if(empty($cli)){ $_SESSION['mensaje1']="Debe Seleccionar un Cliente";
} else if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre del Plan Maestro";
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de unidad";
} else if(empty($prog)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de programacion";
} else if(empty($sensor)==false && empty($prom)){ $_SESSION['mensaje1']="Debe indicar El Valor Promedio Mensual";
} else if(empty($porc)){$_SESSION['mensaje1']="Debe seleccionar un porcentaje de tolerancia";
} else if(empty($res)){ $_SESSION['mensaje1']="Debe seleccionar un Responsable";
} else if(in_array(96,$_SESSION['acl'])==false){$_SESSION['mensaje']= "no posee permiso para guardar este registro";

} else { // si validar 

$sql="insert into planmaes(nombre, estatus, id_cliente, id_confunid, id_responsable, id_tipo_sensor, id_unidad_medida, id_modelo, porc_tol, valor_prom, id_provserv) values('$nom','Activo', $cli, $conf, $res, $sensor, $unidmed, $mod, $porc, $prom, $prov)";

	$rs = pg_query($link, $sql);
	if($rs){ 
		$rs = pg_query($link, "select max(id_planmaes) from planmaes");
		$rs = pg_fetch_array($rs);
		$_SESSION['master']['id'] = $rs[0];
		$_SESSION['master']['cli'] = $cli;
		$_SESSION['master']['conf'] = $conf;
		$_SESSION['master']['sensor'] = $sensor;
		$_SESSION['master']['unidmed'] = $unidmed;
		$_SESSION['master']['mod'] = $mod;
		$_SESSION['master']['res'] = $res;
		$_SESSION['master']['porc'] = $porc;
		$_SESSION['master']['prom'] = $prom;
		$_SESSION['master']['prov'] = $prov;
		
Auditoria("Agrego plan Maestro de Mantenimiento: $nom",$_SESSION['master']['id']);
		
		$_SESSION['mensaje3']="Plan Maestro de Mantenimiento Creado... Debe Definir Planes de Mantenimiento y Detalles";
		header("location: agregar_planes.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el Plan Maestro de Mantenimiento";
	}

} // si validar
} else { 
	$cli = $nom = $conf = $sensor = $unidmed = $res = "";
	$prog = $mod = $porc = $prom = $prov = "";
	unset($_SESSION['master']);
	Auditoria("Accedio Al Modulo Agregar Plan Maestro de Mantenimiento",0);

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
<li><a href="#">Mantenimiento</a></li>
<li><a href="#">Plan Maestro de Mantenimiento</a></li>
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

<div class="header">Agregar Plan Maestro de Mantenimiento Paso 1/2<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Cliente</label>
<div><select id="cli" name="cli" class="selectpicker">
<option value="0" selected="selected">Seleccione un Cliente</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>
                      
<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Planes Maestro de Mantenimiento" class="form-control" maxlength="120" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>


<div class="form-group"><label>Tipo de Unidad</label>
<div><select id="conf" name="conf" class="selectpicker" onchange="CargarDetComposicion();">
<option value="0" selected="selected">Seleccione un Tipo de Unidad</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Marca - Modelos</label>
<div><select id="mod" name="mod" class="selectpicker">
<option value="0" selected="selected">Seleccione un Modelo</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Tipo de Sensor</label>
<div><select id="sensor" name="sensor" class="selectpicker" onchange="bloquear();">
<option value="0" selected="selected">Seleccione un Tipo de Sensor</option>
<?php $rs=pg_query($link, "select id_tipo_sensor, descripcion, unidmed.id_unidmed, magnitudes.nombre, unidmed.nombre from tipo_sensores, magnitudes, unidmed  
where  tipo_sensores.id_unidmed = unidmed.id_unidmed and
magnitudes.id_magnitud = unidmed.id_magnitud 
order by descripcion asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){ ?>    
<option value="<?php echo $r[0].":::".$r[2].":::".$r[3]." - ".$r[4];?>" <?php if($sensor==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>  
</select></div>
</div>

<div class="form-group"><label>Magnitud / Unidad de Medida</label>
<input id="med" name="med" type="text" placeholder="Magnitud y Unidad de Medida" class="form-control" maxlength="250" value="<?php echo $med;?>" readonly="readonly"/></div>

<div class="form-group"><label>Valor Promedio Mensual</label>
<input id="prom" name="prom" type="text" placeholder="Valor Promedio Mensual" class="form-control" maxlength="12" value="<?php echo $prom;?>" onkeypress="return permite(event,'float')" /></div>

<div class="form-group"><label>Porcentaje de Tolerancia:
<input type="text" name="porc" id="porc" value="<?php echo $porc;?>" onkeypress="return permite(event,'num')" size="4" maxlength="2"/>%</label>
</div>

<div class="form-group"><label>Responsable</label>
<div><select id="res" name="res" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable</option>
<!-- LLENADO POR JAVASCRIPT -->   
</select></div></div>

<div class="form-group"><label>Proveedor de Servicios</label>
<div><select id="prov" name="prov" class="selectpicker" onchange="bloquear2();">
<option value="0" selected="selected">Seleccione un Proveedor de Servicio</option>
<!-- LLENADO POR JAVASCRIPT -->   
</select></div></div>
      
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
		mensaje("Debe seleccionar un Cliente",1);
		
	} else if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar el nombre del Plan Maestro",1);
	
	} else if(document.getElementById('conf').value=="0"){ 
		mensaje("Debe seleccionar un tipo de unidad",1);
	
	} else if(document.getElementById('prom').value.length<1){ 
		mensaje("Debe indicar el valor promedio diario",1);
	
	} else if(document.getElementById('porc').value=="0"){ 
		mensaje("Debe seleccionar un porcentaje de tolerancia",1);
			
	} else if(document.getElementById('prog').value=="0"){ 
		mensaje("Debe seleccionar un tipo de programación",1);
		
	} else if(document.getElementById('res').value=="0"){ 
		mensaje("Debe seleccionar un responsable",1);
	
	} else { 
		val = true;
	}
	
return val; }  


function bloquear(){
	var tmp = document.getElementById('sensor').value;
	if(tmp==0){ 
		document.getElementById('med').value="";
		
	} else { 
		tmp = tmp.split(":::");
		document.getElementById('med').value=tmp[2];
		
	}	
}
bloquear();

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
<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#nom").maxlength({ alwaysShow: true });
$("#porc").maxlength({ alwaysShow: true });
$("#prom").maxlength({ alwaysShow: true });
$("#cli").select2();
$("#conf").select2();
$("#mod").select2();
$("#sensor").select2();
$("#res").select2();
$("#prov").select2();
</script>


<script>

$(document).ready(function(){
	cargar_clientes();
	$("#cli").change(function(){ 
		dependencia_confunid();
		dependencia_resp();
		dependencia_modelos();
		dependencia_proveedores();
	});
	$("#conf").attr("disabled",true);
	$("#res").attr("disabled",true);
	$("#mod").attr("disabled",true);
	$("#prov").attr("disabled",true);
});

function cargar_clientes(){
	$.get("../combox/cargar_clientes.php", function(resultado){
		if(resultado == false){ alert("Error"); }
		else{ $('#cli').append(resultado);	}
	});	
}

function dependencia_confunid(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_confunid.php", { code: code },
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

function dependencia_resp(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_personal.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#res").attr("disabled",false);
				document.getElementById("res").options.length=0;
				$('#res').append(resultado);			
			}
		}
	);
}

function dependencia_modelos(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_modelos.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#mod").attr("disabled",false);
				document.getElementById("mod").options.length=0;
				$('#mod').append(resultado);			
			}
		}
	);
}

function dependencia_proveedores(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_proveedores.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#prov").attr("disabled",false);
				document.getElementById("prov").options.length=0;
				$('#prov').append(resultado);		
			}
		}
	);
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