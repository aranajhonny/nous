<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 46;
$_SESSION['acc']['form'] = 107;
include("../complementos/permisos.php");

if(isset($_REQUEST['plan'])){ $_SESSION['planmant']=$_REQUEST['plan']; }

if(isset($_POST['guardar'])){ 
$des = $_POST['des']; $res= $_POST['res']; $sensor = $_POST['sensor']; 
$prog = $_POST['prog']; $mod = $_POST['mod']; $val_max = $_POST['val_max'];  $val_min = $_POST['val_min']; $tiempo_max = $_POST['tiempo_max']; $tiempo_min = $_POST['tiempo_min'];
$proveedor = $_POST['prov']; $porc = $_POST['porc']; $inst = $_POST['inst']; 

if(empty($porc)) $porc=0;

if(strcmp($prog,"Incremental")==0){ 
	$maestro = $_POST['maestro'];
} else { 
	$maestro=0;
}

if($sensor==0){ 
	$val=$_POST['val']; 
	$prom = $_POST['prom'];; 
	$tiempo = $_POST['tiempo']; 
	$unidmed = 0;
} else { 
	$val = $_POST['val'];
	$prom = $_POST['prom'];
	$tiempo = $_POST['tiempo'];
	list($sensor, $unidmed) = explode(":::",$sensor);
}

if(empty($val)) $val=0;
if(empty($prom)) $prom=0;

$conf = $_POST['conf'];
list($conf,$tmp) = explode(":::",$conf,2);

$CantItems = $_POST['CantItems'];

for($i=1; $i<($CantItems+1); $i++){
	$_SESSION['plan']['det'][$i][0] = $_POST["id_det_".$i]; // ID del detalle
	$_SESSION['plan']['det'][$i][1] = $_POST["det_".$i]; // descripcion del Detalle
	$_SESSION['plan']['det'][$i][2] = $_POST["comp_".$i]; // composicion del Detalle
	if($proveedor==0){ 
if(isset($_POST["prov_".$i])) $_SESSION['plan']['det'][$i][3] = $_POST["prov_".$i]; 
else $_SESSION['plan']['det'][$i][3] = $proveedor;
	} else { 
		$_SESSION['plan']['det'][$i][3] = $proveedor;
	}
}

if(empty($des)){ $_SESSION['mensaje1']="Debe indicar el nombre";
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de unidad";
} else if(empty($prog)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de programacion";
} else if(empty($sensor)==false && (empty($val) || empty($prom))){ $_SESSION['mensaje1']="Debe indicar el Valor y el Valor Promedio Diario";
} else if(empty($tiempo) && empty($sensor)){ $_SESSION['mensaje1']="Debe indicar el Limite de Tiempo";
} else if(empty($porc)){ $_SESSION['mensaje1']="Debe seleccionar un porcentaje de tolerancia";
} else if(empty($res)){ $_SESSION['mensaje1']="Debe seleccionar un Responsable";
} else if(empty($inst)){ $_SESSION['mensaje1']="Debe Indicar Las Instrucciones del Mantenimiento";
} else if(in_array(237,$_SESSION['acl'])==false){$_SESSION['mensaje']= "no posee permiso para guardar este registro";

} else { // si validar 

$sql="update planmant set id_tipo_sensor=$sensor, id_unidad_medida=$unidmed, descripcion='$des', valor=$val, tiempo=$tiempo, id_responsable=$res, valor_prom=$prom, tipo_prog='$prog', id_planmaes=$maestro, id_modelo=$mod, valor_min = $val_min, valor_max = $val_max, tiempo_min = $tiempo_min, tiempo_max = $tiempo_max, porc_tol = $porc where id_planmant = ".$_SESSION['planmant'];

	$rs = pg_query($link, $sql);
	if($rs){
Auditoria("Actualizo Plan de Mantenimiento: $des",$_SESSION['planmant']); 
/* ============================================================================== */
$id = $_SESSION['planmant'];
for($i=1; $i<($CantItems+1); $i++){
if(empty($_SESSION['plan']['det'][$i][1])==false){ 
	if($_SESSION['plan']['det'][$i][0]==0){ 
		$sql = "insert into det_planmant(id_planmant, id_composicion, descripcion, id_provserv) values ($id, ".$_SESSION['plan']['det'][$i][2].", '".$_SESSION['plan']['det'][$i][1]."', ".$_SESSION['plan']['det'][$i][3].")";
	} else { 
		$sql = "update det_planmant set id_composicion=".$_SESSION['plan']['det'][$i][2].", descripcion='".$_SESSION['plan']['det'][$i][1]."', id_provserv = ".$_SESSION['plan']['det'][$i][3]." where id_detplanmant = ".$_SESSION['plan']['det'][$i][0];
	}
	pg_query($link, $sql);
} } 
/* ============================================================================== */
$rs = pg_query($link, "select count(id_instruccion) from instrucciones where id_planmant = $id");
$rs = pg_fetch_array($rs);
if($rs[0]==0){ 
	pg_query($link, "insert into instrucciones(id_planmant, html) values ($id, '$inst')");
} else { 
	pg_query($link, "update instrucciones set html='$inst' where id_planmant = $id");
}
/* ============================================================================== */
		unset($_SESSION['plan']['det']);
		$_SESSION['mensaje3']="Plan de Mantenimiento Editado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar el plan de mantenimiento";
	}

} // si validar

} else if(isset($_SESSION['planmant'])){
unset($_SESSION['plan']['det']);
$rs = pg_query($link, "select * from planmant where id_planmant = ".$_SESSION['planmant']);
$rs = pg_fetch_array($rs);
$_SESSION['planmant_cliente'] = $rs[1];
$res = $rs[2];
$_SESSION['planmant_conf'] = $rs[3]; 
$sensor = $rs[4]; 
$unidmed = $rs[5];
$maestro = $rs[7]; 
$mod = $rs[8]; 
$tipo = $rs[9];
$des = $rs[10];
$porc = $rs[11];
$val = 1*$rs[12]; $prom = 1*$rs[13]; $val_min = 1*$rs[14]; $val_max = 1*$rs[15];
$tiempo = $rs[16]; $tiempo_min = $rs[17]; $tiempo_max = $rs[18];
$prog = $rs[19];
$_SESSION['planmant_prov'] = $rs[20];

Auditoria("Accedio Al Modulo Editar Plan de Mantenimiento: $des",$_SESSION['planmant']);


$rs = pg_query($link, "select html from instrucciones where id_planmant = ".$_SESSION['planmant']);
$r = pg_num_rows($rs);
if($r==false || $r==0){ $inst=""; 
} else { 
	$rs = pg_fetch_array($rs);
	$inst = $rs[0];
}

$rs = pg_query($link, "select id_detplanmant, descripcion, id_composicion, id_provserv from det_planmant where id_planmant = ".$_SESSION['planmant']." order by id_detplanmant asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=1;
	while($r = pg_fetch_array($rs)){
$_SESSION['plan']['det'][$i][0] = $r[0]; // ID del detalle
$_SESSION['plan']['det'][$i][1] = $r[1]; // descripcion del Detalle
$_SESSION['plan']['det'][$i][2] = $r[2]; // composicion del Detalle
$_SESSION['plan']['det'][$i][3] = $r[3]; // proveedor del Detalle
		$i++; 
	}
}



} else { 
	$_SESSION['mensaje1']="No se identifico el plan de mantenimiento";
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
<link href="../Legend/admin/assets/fuelux/css/fuelux.min.css" rel="stylesheet"/>
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
<li><a href="#">Planes de Manteniemto</a></li>
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

<div class="header">Editar Plan de Mantenimiento<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>

		                    <div class="fuelux">
		                        <div id="MyWizard" class="wizard">
		                            <ul class="steps">
<li data-target="#step1"  onclick="$('#MyWizard').wizard('selectedItem', { step: 1 });"  class="active">1.- Plan<span class="chevron"></span></li>
<li data-target="#step2"  onclick="$('#MyWizard').wizard('selectedItem', { step: 2 });" >2.- Detalles<span class="chevron"></span></li>
<li data-target="#step3"  onclick="$('#MyWizard').wizard('selectedItem', { step: 3 });" >3.- Instrucciones<span class="chevron"></span></li>
		                            </ul>
		                        </div>
		                        <div class="step-content">
		                            <div class="step-pane active" id="step1">
<div class="form-group"><label>Descripción</label>
<input id="des" name="des" type="text" placeholder="Descripción" class="form-control" maxlength="250" value="<?php echo $des;?>" onkeypress="return permite(event,'todo')" /></div>

<?php $cli = "";
$rs = pg_query($link, "select rif, razon_social from clientes where id_cliente = ".$_SESSION['planmant_cliente']); $rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1]; ?>
<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<div>
<?php $rs = pg_query($link, "select id_confunid, codigo_principal from confunid where id_confunid = ".$_SESSION['planmant_conf']." order by nombre asc "); $r = pg_fetch_array($rs); ?>
<input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $r[1];?>" readonly="readonly" /> 
</div>
</div>


<div class="form-group"><label>Marca - Modelo</label>
<div><select id="mod" name="mod" class="selectpicker">
<option value="0" selected="selected">Seleccione un Modelo</option>
<?php $rs=pg_query($link, "select id_modelo, marcas.descripcion, modelos.descripcion from      marcas, modelos where modelos.id_marca = marcas.id_marca and id_cliente = ".$_SESSION['planmant_cliente']." order by marcas.descripcion, modelos.descripcion asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($mod==$r[0]) echo "selected";?> ><?php echo $r[1]." - ".$r[2];?></option> 
<?php } } ?>  
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

<div class="form-group"><label>Tipo de Programación</label>
<div><select id="prog" name="prog" class="selectpicker" onchange="bloquear2();">
<option value="0" selected="selected">Seleccione un Tipo de Programación</option>
<option <?php if(strcmp($prog,"Incremental")==0)echo"selected";?>>Incremental</option>
<option <?php if(strcmp($prog,"Cíclica")==0)echo"selected";?>>Cíclica</option>
</select></div>
</div>

<div class="form-group"><label>Plan Maestro de Mantenimiento</label>
<div><select id="maestro" name="maestro" class="selectpicker">
<option value="0" selected="selected">Seleccione un Plan Maestro de Mantenimiento</option>
<?php $rs=pg_query($link, "select id_planmaes, nombre from planmaes where id_cliente = ".$_SESSION['planmant_cliente']." order by nombre asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
while($r = pg_fetch_array($rs)){ ?>    
<option value="<?php echo $r[0];?>" <?php if($maestro==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>  
</select></div>
</div>

<div class="form-group"><label>Valor</label>
<input id="val" name="val" type="text" placeholder="Valor" class="form-control" maxlength="12" value="<?php echo $val;?>" onkeypress="return permite(event,'float')" onkeyup="calcular(); tolerancia();" /></div>

<div class="form-group"><label>Valor Promedio Mensual</label>
<input id="prom" name="prom" type="text" placeholder="Valor Promedio Mensual" class="form-control" maxlength="12" value="<?php echo $prom;?>" onkeypress="return permite(event,'float')" onkeyup="calcular();" /></div>

<div class="form-group"><label>Limite de Tiempo</label>
<input id="tiempo" name="tiempo" type="text" placeholder="Días" class="form-control" maxlength="3" value="<?php echo $tiempo;?>" onkeypress="return permite(event,'num')" onkeyup="tolerancia();" /><p class="help-block">Ejemplo: 7 Días</p></div>

<div class="form-group"><label>Porcentaje de Tolerancia:
<input type="text" name="porc" id="porc" value="<?php echo $porc;?>" onkeypress="return permite(event,'num')" size="4" maxlength="2" onkeyup="tolerancia();" />%</label></div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Mínimo</label>
<input id="val_min" name="val_min" type="text" placeholder="Valor Mínimo" class="form-control" value="<?php echo $val_min;?>" readonly="readonly" /></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Valor Maximo</label>
<input id="val_max" name="val_max" type="text" placeholder="Valor Maximo" class="form-control" value="<?php echo $val_max;?>" readonly="readonly" /></div>
</div>

<div class="form-group">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo Mínimo</label>
<input id="tiempo_min" name="tiempo_min" type="text" placeholder="Días" class="form-control"  value="<?php echo $tiempo_min;?>" readonly="readonly" /></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tiempo Máximo</label>
<input id="tiempo_max" name="tiempo_max" type="text" placeholder="Días" class="form-control"  value="<?php echo $tiempo_max;?>" readonly="readonly" /></div>
</div>
<p>&nbsp;</p>

<div class="form-group"><label>Responsable</label>
<div><select id="res" name="res" class="selectpicker">
<option value="0" selected="selected">Seleccione un Responsable</option>
<?php $rs = pg_query($link, "select id_personal, ci, nombre from personal where id_cliente = ".$_SESSION['planmant_cliente']." order by ci asc "); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($res==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>   
</select></div></div>


</div>
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2"> 

<div class="form-group"><label>Proveedor de Servicios</label>
<div><select id="prov" name="prov" class="selectpicker" onchange="bloquear2();">
<option value="0" selected="selected">Seleccione un Proveedor de Servicio</option>
<?php $rs = pg_query($link, "select id_provserv, rif, nombre_prov from provserv where id_cliente = ".$_SESSION['planmant_cliente']." order by rif asc "); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($_SESSION['planmant_prov']==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>   
</select></div></div>


<table class="table">
<thead>
    <tr>
<td colspan="4" align="right">Agregar Detalles al Plan <img src="../img/plus.png" height="15" width="15" onclick="agregar_detalle(0,'',0,0);" /></td>
    </tr>
</thead>
<thead>
	<tr>
		<th>Nro</th>
		<th>Detalle</th>
        <th>Composición</th>
        <th>Proveedor</th>
	</tr>
</thead>
<tbody id="cuerpo"></tbody>
</table>
<input type="hidden" name="CantItems" id="CantItems" value="0" />
</div>


<div class="step-pane" id="step3">
<div class="form-group"><label>Instrucciones</label>
<textarea rows="24" name="inst" id="inst" onkeypress="return permite(event, 'todo')" class="form-control"><?php echo $inst; ?></textarea>
</div>
</div>


		                            
		                        </div>
		                        <br>
<button type="button" class="btn btn-default" id="btnWizardPrev">Ant.</button>
<button type="button" class="btn btn-primary" id="btnWizardNext">Sig.</button>
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
	if(document.getElementById('des').value.length<1){ 
		mensaje("Debe indicar el nombre",1);
	
	} else if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar un cliente",1);
		
	} else if(document.getElementById('conf').value=="0"){ 
		mensaje("Debe seleccionar un tipo de unidad",1);

	} else if(document.getElementById('prog').value=="0"){ 
		mensaje("Debe seleccionar un tipo de programación",1);
	
	} else if(document.getElementById('prog').value == "Incremental"  && 
			document.getElementById('maestro').value=="0"){ 
		mensaje("Debe seleccionar un plan maestro de mantenimiento",1);
			
	} else if(document.getElementById('val').value.length<1){ 
		mensaje("Debe indicar el valor",1);
		
	} else if(document.getElementById('prom').value.length<1){ 
		mensaje("Debe indicar el valor promedio mensual",1);
		
	} else if(document.getElementById('tiempo').value.length<1){ 
		mensaje("Debe indicar el limite del tiempo",1);
	
	} else if(document.getElementById('porc').value=="0"){ 
		mensaje("Debe seleccionar un porcentaje de tolerancia",1);
		
	} else if(document.getElementById('res').value=="0"){ 
		mensaje("Debe seleccionar un responsable",1);

	} else if(document.getElementById('inst').value.length<1){ 
		mensaje("Debe indicar las Instrucciones del Mantenimiento",1);
					
	}  else {
		val = true;
	}
	
return val; }</script>

<script>
function calcular(){ 
	var tiempo = 0;
	var aux=0, dia=0;
	if(document.getElementById('val').value.length>0 && 
	document.getElementById('prom').value.length>0){ 
		aux = Number(document.getElementById('val').value);
		dia = Number(document.getElementById('prom').value)/30;
		tiempo = parseInt( aux / dia );
		document.getElementById('tiempo').value = tiempo;
	}
}

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

function bloquear2(){
	if( document.getElementById("prog").value == "Incremental" ){ 
		document.getElementById('maestro').disabled=false;
	} else { 
		document.getElementById('maestro').disabled=true;
	}	
}
bloquear2();

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

$("#des").maxlength({ alwaysShow: true });
$("#val").maxlength({ alwaysShow: true });
$("#tiempo").maxlength({ alwaysShow: true });
$("#prom").maxlength({ alwaysShow: true });

$("#sensor").select2();
$("#res").select2();
$("#prog").select2();
$("#mod").select2();
$("#maestro").select2();
$("#prov").select2();
</script>

<script src="../Legend/admin/assets/fuelux/js/all.min.js"></script>
<script src="../Legend/admin/assets/fuelux/js/loader.min.js"></script>
<script>
 function fueluxwizard() {
     $('#MyWizard').on('change', function (e, data) {
         console.log('change');
         if (data.step === 3 && data.direction === 'next') {
              //return e.preventDefault();
         }
     });
     $('#MyWizard').on('changed', function (e, data) {
         console.log('changed');
     });
     $('#MyWizard').on('finished', function (e, data) {
         console.log('finished');
     });
     $('#btnWizardPrev').on('click', function () {
         $('#MyWizard').wizard('previous');
     });
     $('#btnWizardNext').on('click', function () {
         $('#MyWizard').wizard('next', 'foo');
     });
     $('#btnWizardStep').on('click', function () {
         var item = $('#MyWizard').wizard('selectedItem');
         console.log(item.step);
     });
     $('#MyWizard').on('stepclick', function (e, data) {
         console.log('step' + data.step + ' clicked');
         if (data.step === 1) {
              //return e.preventDefault();
         }
     });

     // optionally navigate back to 2nd step
     $('#btnStep2').on('click', function (e, data) {
         $('[data-target=#step2]').trigger("click");
     });
 }
fueluxwizard();</script>

<script> function tolerancia(){ 
	var va = document.getElementById('val').value;
	var ti = document.getElementById('tiempo').value;
	if(va.length<1 || ti.length<1){ 
		document.getElementById('val_min').value=0;
		document.getElementById('val_max').value=0;
		document.getElementById('tiempo_min').value=0;
		document.getElementById('tiempo_max').value=0;
	} else { 
		va = Number(va); 
		ti = Number(ti);
		var porc = document.getElementById('porc').value;
		if(porc.length<1){ 
			document.getElementById('val_min').value=Math.ceil(va);
			document.getElementById('val_max').value=Math.ceil(va);
			document.getElementById('tiempo_min').value=Math.ceil(ti);
			document.getElementById('tiempo_max').value=Math.ceil(ti);
		} else { 
 			var dif = calcula_porcentaje(porc, va);
			document.getElementById('val_min').value=Math.ceil(va-dif);
			document.getElementById('val_max').value=Math.ceil(va+dif);
			dif = calcula_porcentaje(porc, ti);
			document.getElementById('tiempo_min').value=Math.ceil(ti-dif);
			document.getElementById('tiempo_max').value=Math.ceil(ti+dif);
		}
	}
} 
function calcula_porcentaje(porc, tmp){ 
	return ( Number(tmp) / 100 ) * Number( porc );
}</script>
<script>
function bloquear2(){  
	var tmp = document.getElementById('prov').value;
	if( tmp == "0" ){ 
		$('.provs').attr('disabled',false);
	} else { 
		$('.provs').attr('disabled',true);
	}
} 

</script>




<?php  // PROBLEMAS PARA CARGAR CON EL $.GET  RESOLVER MAS ADELANTE
$provs = "<option value='0' selected>Seleccione un Proveedor</option>";
$rs = pg_query($link, "select id_provserv, rif, nombre_prov from provserv where id_cliente = ".$_SESSION['planmant_cliente']." order by rif asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0) { while($r = pg_fetch_array($rs)){ $provs .= "<option value='".$r[0]."'>".$r[1]." ".$r[2]."</option>"; } 
} else { $provs = "<option value='0' selected>Lista de Proveedores Vacia</option>"; }

include("../composiciones/composiciones_CompUnid.php");
$rs=pg_query($link, "select id_composicion, nombre from composiciones where id_dependencia=0 and id_confunid = ".$_SESSION['planmant_conf']." order by nombre asc");
$html.="<option value='0' selected='selected'>Seleccione Una Composición</option>";
$r = pg_num_rows($rs);
if($r!=false && $r>0){ 
while($r = pg_fetch_array($rs)){
$qs = pg_query($link, "select count(id_composicion) from composiciones where id_dependencia = ".$r[0]); $qs = pg_fetch_array($qs); 
if($comp[0][$i]==$r[0]) $aux=' selected '; else $aux="";
if($qs[0]==0){ 
	$html .= "<option value='".$r[0]."'>".$r[1]."</option>";
} else { 
	$html .= "<option value='".$r[0]."'>".$r[1]."</option>";
	$html .= ComponerComboxCompUnid($r[0], $r[1], $comp[0][$i], "&emsp;"); 
} } } 
?>

<script>
var items = 0;
var listprov="<?php echo $provs; ?>";
var listcomp="<?php echo str_replace("\n","",$html); ?>";

function agregar_detalle(id, valor, valor2, valor3){ 
	items++;
	$('#cuerpo').append("<tr><td>#"+items+"</td><td><input type='hidden' name='id_det_"+items+"' id='id_det_"+items+"' value='"+id+"' /><input type='text' name='det_"+items+"' id='det_"+items+"' maxlength='120' size='40' value='"+valor+"' onkeypress='return permite(event,"+"'"+"todo"+"'"+")' placeholder='Indique El Detalle del Plan' /></td><td><select name='comp_"+items+"' id='comp_"+items+"' style='max-width:200px;' class='comps'>"+listcomp+"</select></td><td><select name='prov_"+items+"' id='prov_"+items+"' style='max-width:200px;' class='provs'>"+listprov+"</select></td></tr>");
	document.getElementById('prov_'+items).value = valor3;
	document.getElementById('comp_'+items).value = valor2;
	
	document.getElementById('CantItems').value = items;
}
</script>

<?php  if(isset($_SESSION['plan']['det'])){ 
$CantItems = count($_SESSION['plan']['det']);
for($i=1; $i<($CantItems+1); $i++){
echo "<script>agregar_detalle(".$_SESSION['plan']['det'][$i][0].",'".$_SESSION['plan']['det'][$i][1]."', ".$_SESSION['plan']['det'][$i][2].", ".$_SESSION['plan']['det'][$i][3].");</script>";
} } ?>
<script>bloquear2();</script>
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