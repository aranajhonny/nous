<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 45;
$_SESSION['acc']['form'] = 103;
include("../complementos/permisos.php");

if(isset($_REQUEST['master'])){ $_SESSION['master']['id']=$_REQUEST['master']; }

if(isset($_POST['guardar'])){ 
$nom = $_POST['nom'];  
$res= $_POST['res']; 
$sensor = $_POST['sensor'];
$mod = $_POST['mod'];
$est = $_POST['est'];
if($sensor==0){ $unidmed = 0;
} else { list($sensor, $unidmed) = explode(":::",$sensor); }
$porc = $_POST['porc']; if(empty($porc)) $porc=0;
$prom = $_POST['prom'];
$proveedor = $_POST['prov'];

if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre del Plan Maestro";
} else if(empty($sensor)==false && empty($prom)){ $_SESSION['mensaje1']="Debe indicar El Valor Promedio Mensual";
} else if(empty($porc)){$_SESSION['mensaje1']="Debe seleccionar un porcentaje de tolerancia";
} else if(empty($res)){ $_SESSION['mensaje1']="Debe seleccionar un Responsable";
} else if(in_array(236,$_SESSION['acl'])==false){$_SESSION['mensaje']= "no posee permiso para guardar este registro";

} else { // si validar 

$sql="update planmaes set nombre='$nom', estatus='$est', id_responsable=$res, id_modelo=$mod, id_tipo_sensor=$sensor, id_unidad_medida=$unidmed, porc_tol=$porc, valor_prom=$prom, id_provserv=$proveedor where id_planmaes = ".$_SESSION['master']['id'];
	$rs = pg_query($sql);
	if($rs){ 
		Auditoria("Actualizo Plan Maestro de Mantenimiento: $nom",$_SESSION['master']);
		
		$rs = pg_query("select id_planmant, valor, tiempo from planmant where id_planmaes = ".$_SESSION['master']['id']);
		while($r = pg_fetch_array($rs)){ 

$dif = ($r[1]/100)*$porc;
$val_max = $r[1]+$dif;
$val_min = $r[1]-$dif;
$dif = ($r[2]/100)*$porc;
$tiempo_max = $r[2]+$dif;
$tiempo_min = $r[2]-$dif;

if($proveedor == 0){ 
	pg_query("update planmant set id_modelo=$mod, id_responsable=$res, id_tipo_sensor=$sensor, id_unidad_medida=$unidmed, porc_tol=$porc, valor_prom=$prom, valor_min = $val_min, valor_max = $val_max, tiempo_min = $tiempo_min, tiempo_max = $tiempo_max where id_planmant = ".$r[0]);
} else { 
	pg_query("update planmant set id_modelo=$mod, id_responsable=$res, id_tipo_sensor=$sensor, id_unidad_medida=$unidmed, porc_tol=$porc, valor_prom=$prom, valor_min = $val_min, valor_max = $val_max, tiempo_min = $tiempo_min, tiempo_max = $tiempo_max, id_provserv = $proveedor where id_planmant = ".$r[0]);
}
			
			
		}

		unset($_SESSION['master']);
		$_SESSION['mensaje3']="Plan Maestro de Mantenimiento Editado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro Editar el Plan Maestro de Mantenimiento";
	}

} // si validar

} else if(isset($_SESSION['master'])){
$rs = pg_query("select * from planmaes where id_planmaes = ".$_SESSION['master']['id']);
$rs = pg_fetch_array($rs);
$nom = $rs[1]; 
$est = $rs[2]; 
$_SESSION['master']['cli'] = $rs[3];
$_SESSION['master']['conf'] = $rs[4];
$res = $rs[5];
$sensor = $rs[6];
$unidmed = $rs[7];
$mod = $rs[8];
$porc = $rs[9];
$prom = $rs[10];
$proveedor = $rs[11];
Auditoria("Accedio Al Modulo Editar Plan Maestro de Mantenimiento: $nom",$_SESSION['master']);


} else { 
	$_SESSION['mensaje1']="No se identifico el Plan Maestro de Mantenimiento";
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
<li><a href="#">Mantenimiento</a></li>
<li><a href="#">Plan Maestro de Mantenimiento</a></li>
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

<div class="header">Editar Plan Maestro de Mantenimiento<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>
                      
<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Planes Maestro de Mantenimiento" class="form-control" maxlength="120" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<?php $cli = "";
$rs = pg_query("select rif, razon_social from clientes where id_cliente = ".$_SESSION['master']['cli']); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<div><?php $rs = pg_query("select id_confunid, codigo_principal from confunid where id_confunid = ".$_SESSION['master']['conf']." order by nombre asc "); $r = pg_fetch_array($rs); ?><input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $r[1];?>" readonly="readonly" /> 
</div>
</div>


<div class="form-group"><label>Marca - Modelo</label>
<div><select id="mod" name="mod" class="selectpicker">
<option value="0" selected="selected">Seleccione un Modelo</option>
<?php $rs=pg_query("select id_modelo, marcas.descripcion, modelos.descripcion from      marcas, modelos where modelos.id_marca = marcas.id_marca and id_cliente = ".$_SESSION['master']['cli']." order by marcas.descripcion, modelos.descripcion asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($mod==$r[0]) echo "selected";?> ><?php echo $r[1]." - ".$r[2];?></option> 
<?php } } ?>  
</select></div>
</div>


<div class="form-group"><label>Tipo de Sensor</label>
<div><select id="sensor" name="sensor" class="selectpicker" onchange="bloquear();">
<option value="0" selected="selected">Seleccione un Tipo de Sensor</option>
<?php $rs=pg_query("select id_tipo_sensor, descripcion, unidmed.id_unidmed, magnitudes.nombre, unidmed.nombre from tipo_sensores, magnitudes, unidmed  
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
<?php $rs = pg_query("select id_personal, ci, nombre from personal where id_cliente = ".$_SESSION['master']['cli']." order by ci asc "); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($res==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>   
</select></div></div>

<div class="form-group"><label>Proveedor de Servicios</label>
<div><select id="prov" name="prov" class="selectpicker">
<option value="0" selected="selected">Seleccione un Proveedor de Servicio</option>
<?php $rs = pg_query("select id_provserv, rif, nombre_prov from provserv where id_cliente = ".$_SESSION['master']['cli']." order by rif asc "); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($proveedor==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>
</select></div></div>

<div class="form-group"><label>Estatus</label>
<div><select id="est" name="est" class="selectpicker">
<option selected>Activo</option>
<option <?php if(strcmp($est,"Inactivo")==0)echo"selected";?>>Inactivo</option>
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
	if(document.getElementById('res').value=="0"){ 
		mensaje("Debe seleccionar un responsable",1);
		
	} else if(document.getElementById('nom').value.length<1){ 
		mensaje("Debe indicar el nombre del Plan Maestro",1);
		
	} else if(document.getElementById('sensor').value!="0" && 
			  document.getElementById('prom').value.length<1){ 
		mensaje("Debe indicar el valor promedio diario",1);
	
	} else if(document.getElementById('porc').value=="0"){ 
		mensaje("Debe seleccionar un porcentaje de tolerancia",1);
		
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
$("#mod").select2();
$("#res").select2();
$("#sensor").select2();
$("#est").select2();
$("#prov").select2();
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