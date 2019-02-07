<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 46;
$_SESSION['acc']['form'] = 106;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$des = $_POST['des']; $cli = $_POST['cli']; $res= $_POST['res'];
$sensor = $_POST['sensor']; $prog = $_POST['prog']; $mod = $_POST['mod'];
$val_max = $_POST['val_max'];  $val_min = $_POST['val_min'];
$tiempo_max = $_POST['tiempo_max'];  $tiempo_min = $_POST['tiempo_min'];
$porc = $_POST['porc']; $proveedor = $_POST['prov']; $inst = $_POST['inst']; 

if(empty($porc)) $porc=0;

if(strcmp($prog,"Incremental")==0){ 
	$maestro = $_POST['maestro'];
} else { 
	$maestro=0;
}

if($sensor==0){ 
	$val=$_POST['val']; 
	$prom = $_POST['prom'];
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

for($i=1; $i<=$CantItems; $i++){
	$_SESSION['plan']['det'][$i][0] = $_POST["id_det_".$i]; // ID del detalle
	$_SESSION['plan']['det'][$i][1] = $_POST["det_".$i]; // descripcion del Detalle
	$_SESSION['plan']['det'][$i][2] = $_POST["comp_".$i]; // composicion del Detalle
	if($proveedor==0){ 
if(isset($_POST["prov_".$i])) $_SESSION['plan']['det'][$i][3] = $_POST["prov_".$i]; 
else $_SESSION['plan']['det'][$i][3] = 0;
	} else { 
		$_SESSION['plan']['det'][$i][3] = $proveedor;
	}
}

if( $sensor==0 ){ $prog="Cíclica"; } else { $prog="Incremental"; }

if(empty($des)){ $_SESSION['mensaje1']="Debe indicar el nombre";
} else if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de unidad";
} else if(empty($prog)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de programacion";
} else if(empty($sensor)==false && (empty($val) || empty($prom))){ $_SESSION['mensaje1']="Debe indicar el Valor y el Valor Promedio Diario";
} else if(empty($tiempo) && empty($sensor)){ $_SESSION['mensaje1']="Debe indicar el Limite de Tiempo";
} else if(empty($porc)){ $_SESSION['mensaje1']="Debe seleccionar un porcentaje de tolerancia";
} else if(empty($res)){ $_SESSION['mensaje1']="Debe seleccionar un Responsable";
} else if(empty($inst)){ $_SESSION['mensaje1']="Debe Indicar Las Instrucciones del Mantenimiento";
} else if(in_array(97,$_SESSION['acl'])==false){$_SESSION['mensaje']= "no posee permiso para guardar este registro";

} else { // si validar 

$sql="insert into planmant(id_cliente, id_confunid, id_tipo_sensor, id_unidad_medida, descripcion, valor, tiempo, id_unidad_tiempo, id_responsable, valor_prom, tipo_prog, id_planmaes, id_modelo, id_tipo_mant, valor_min, valor_max, tiempo_min, tiempo_max, porc_tol, id_provserv) values ($cli, $conf, $sensor, $unidmed, '$des', $val, $tiempo, 0, $res, $prom, '$prog', $maestro, $mod, 0, $val_min, $val_max, $tiempo_min, $tiempo_max, $porc, $proveedor)";

	$rs = pg_query($link, $sql);
	if($rs){ 
$rs = pg_query($link, "select max(id_planmant) from planmant");
$rs = pg_fetch_array($rs); $id = $rs[0];
Auditoria("Agrego Plan de Mantenimiento: $des",$rs[0]);
/* ============================================================================== */
$sql2="";
for($i=1; $i<=$CantItems; $i++){	
	if(empty($_SESSION['plan']['det'][$i][1])==false){  
			$sql2 .="($id, ".$_SESSION['plan']['det'][$i][2].", '".$_SESSION['plan']['det'][$i][1]."', ".$_SESSION['plan']['det'][$i][3]."),";
	}
}
if(empty($sql2)==false){ 
	$sql2 = "insert into det_planmant(id_planmant, id_composicion, descripcion, id_provserv) values ".$sql2;
	$sql2 = substr($sql2,0,(strlen($sql2)-1)).";";
	pg_query($link, $sql2);
	Auditoria("En Agregar Plan de Mantenimiento se registraron los Detalles del Mantenimiento para el plan: $des",$id);
}
/* ============================================================================== */
pg_query($link, "insert into instrucciones(id_planmant, html) values ($id, '$inst')");
Auditoria("En Agregar Plan de MAntenimiento se registraron las instrucciones para el plan: $des",$id);
/* ============================================================================== */
		$_SESSION['mensaje3']="Plan de Mantenimiento Agregado";
		unset($_SESSION['plan']['det']);
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro agregar el plan de mantenimiento";
	}

} // si validar


} else { 
	$des = $cli = $conf = $sensor = $unidmed = $val = $tiempo = "";
	$res = $prog = $prom = $inst = "";
	$val_max = $val_min = $tiempo_max = $tiempo_min = $porc = 0;
Auditoria("Accedio Al Modulo Agregar Plan de Mantenimiento",0);
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

<div class="header">Agregar Plan de Mantenimiento<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar.php" onsubmit="return validar();">
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
<div class="form-group"><label>Nombre</label>
<input id="des" name="des" type="text" placeholder="Nombre del Plan de Mantenimiento" class="form-control" maxlength="250" value="<?php echo $des;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)"/></div>

<div class="form-group"><label>Cliente</label>
<div><select id="cli" name="cli" class="selectpicker">
<option value="0" selected="selected">Seleccione un Cliente</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

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

<div class="form-group"><label>Tipo de Programación</label>
<div><select id="prog" name="prog" class="selectpicker" onchange="dependencia_planmaestro();">
<option value="0" selected="selected">Seleccione un Tipo de Programación</option>
<option <?php if(strcmp($prog,"Incremental")==0)echo"selected";?>>Incremental</option>
<option <?php if(strcmp($prog,"Cíclica")==0)echo"selected";?>>Cíclica</option>
</select></div>
</div>

<div class="form-group"><label>Plan Maestro de Mantenimiento</label>
<div><select id="maestro" name="maestro" class="selectpicker">
<option value="0" selected="selected">Seleccione un Plan Maestro de Mantenimiento</option>
<!-- LLENADO POR JAVASCRIPT -->
</select></div>
</div>

<div class="form-group"><label>Valor</label>
<input id="val" name="val" type="text" placeholder="Valor" class="form-control" maxlength="12" value="<?php echo $val;?>" onkeypress="return permite(event,'float')" onkeyup="calcular(); tolerancia();"  /></div>

<div class="form-group"><label>Valor Promedio Mensual</label>
<input id="prom" name="prom" type="text" placeholder="Valor Promedio Mensual" class="form-control" maxlength="12" value="<?php echo $prom;?>" onkeypress="return permite(event,'float')" onkeyup="calcular();" /></div>

<div class="form-group"><label>Limite de Tiempo</label>
<input id="tiempo" name="tiempo" type="text" placeholder="Días" class="form-control" maxlength="3" value="<?php echo $tiempo;?>" onkeypress="return permite(event,'num')" onkeyup="tolerancia();" /><p class="help-block">Ejemplo: 7 Días</p></div>

<div class="form-group"><label>Porcentaje de Tolerancia:
<input type="text" name="porc" id="porc" value="<?php echo $porc;?>" onkeypress="return permite(event,'num')" size="4" maxlength="2" onkeyup="tolerancia();" />%</label>
</div>

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
<!-- LLENADO POR JAVASCRIPT -->   
</select></div></div>
		                            </div>
                                    
                                    
                                    
                                    
                                    
                                    
<div class="step-pane" id="step2">
<div class="form-group"><label>Proveedor de Servicios</label>
<div><select id="prov" name="prov" class="selectpicker" onchange="bloquear2();">
<option value="0" selected="selected">Seleccione un Proveedor de Servicio</option>
<!-- LLENADO POR JAVASCRIPT -->   
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
<script>
var items = 0;
var listprov="";
var listcomp="";
function agregar_detalle(id, valor, valor2, valor3){ 
	items++;
	$('#cuerpo').append("<tr><td>#"+items+"</td><td><input type='hidden' name='id_det_"+items+"' id='id_det_"+items+"' value='"+id+"' /><input type='text' name='det_"+items+"' id='det_"+items+"' maxlength='120' size='40' value='"+valor+"' onkeypress='return permite(event,"+"'"+"todo"+"'"+")' placeholder='Indique El Detalle del Plan' /></td><td><select name='comp_"+items+"' id='comp_"+items+"' style='max-width:200px;' class='comps'><option value='0'>Lista Vacia</option></select></td><td><select name='prov_"+items+"' id='prov_"+items+"' style='max-width:200px;' class='provs'><option value='0'>Lista Vacia</option></select></td></tr>");
	$('#prov_'+items).empty();
	$('#prov_'+items).append(listprov);
	$('#comp_'+items).empty();
	$('#comp_'+items).append(listcomp);
	document.getElementById('CantItems').value = items;
}</script>
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
		mensaje("Debe indicar el valor promedio diario",1);
		
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
		dia = Number(document.getElementById('prom').value) / 30;
		tiempo = parseInt( aux / dia );
		document.getElementById('tiempo').value = tiempo;
	}
}


function bloquear(){

	var tmp = document.getElementById('sensor').value;
	if(tmp==0){ 
		
		tmp = document.getElementById('val');
		tmp.disabled = false;
		tmp.value="0";
		tmp.disabled = true;
		
		tmp = document.getElementById('prom');
		tmp.disabled = false;
		tmp.value="0";
		tmp.disabled = true;
		
		tmp = document.getElementById('tiempo');
		tmp.disabled = false;
		tmp.value="";
		
		document.getElementById('med').value="";
		document.getElementById('prog').value="Cíclica";
		$("#prog").select2();
	} else { 
	
		tmp = tmp.split(":::");
		document.getElementById('med').value=tmp[2];
		
		tmp = document.getElementById('val');
		tmp.disabled = false;
		tmp.value="";
		
		tmp = document.getElementById('prom');
		tmp.disabled = false;
		tmp.value="";
		
		tmp = document.getElementById('tiempo');
		tmp.disabled = false;
		tmp.value="0";
		tmp.disabled = true;
		
		document.getElementById('prog').value="Incremental";
		$("#prog").select2();
	}	

}
bloquear();


function bloquear2(){  
	var tmp = document.getElementById('prov').value;
	if( tmp == "0" ){ 
		$('.provs').attr('disabled',false);
	} else { 
		$('.provs').attr('disabled',true);
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
<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#des").maxlength({ alwaysShow: true });
$("#val").maxlength({ alwaysShow: true });
$("#tiempo").maxlength({ alwaysShow: true });
$("#prom").maxlength({ alwaysShow: true });

$("#cli").select2();
$("#conf").select2();
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

<script>

$(document).ready(function(){
	cargar_clientes();
	$("#cli").change(function(){ 
		dependencia_confunid();
		dependencia_resp();
		dependencia_modelos();
		dependencia_planmaestro();
		dependencia_proveedores();
	});
	$("#conf").attr("disabled",true);
	$("#mod").attr("disabled",true);
	$("#maestro").attr("disabled",true);
	$("#res").attr("disabled",true);
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
function dependencia_proveedores(){
	var code = $("#cli").val();
	$.get("../combox/dependencia_proveedores.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				listprov = resultado;
				$("#prov").attr("disabled",false);
				document.getElementById("prov").options.length=0;
				$('#prov').append(listprov);	
				$('.provs').empty();
				$('.provs').append(listprov);
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
function dependencia_planmaestro(){	
	var code = $("#cli").val();
	
	if( code == "0"){ 
		mensaje("Debe Seleccionar Un Cliente", 1);
		
	} else if(  $("#prog").val().toString() == "Incremental"){ 
		$.get("../combox/dependencia_planmaestro.php", { code: code },
			function(resultado){
				if(resultado == false){ alert("Error"); }
				else {
					$("#maestro").attr("disabled",false);
					document.getElementById("maestro").options.length=0;
					$('#maestro').append(resultado);			
				}
			}
		);
		
	} else { 
		$("#maestro").attr("disabled",false);
		document.getElementById("maestro").options.length=0;
		$('#maestro').append("<option value='0' selected='selected'>Seleccione un Plan Maestro de Mantenimiento</option>");
		$("#maestro").attr("disabled",true);
	}
}
</script>

<script> 
function CargarDetComposicion(){ 
	
	var id = document.getElementById('conf').value;
	id = id.split(":::");

	$.get("det_composiciones.php?conf="+id[0], function(resultado){ 
		if(resultado == false){  
		} else { 
			listcomp = resultado;
			$('.comps').empty();
			$('.comps').append(resultado);
		} 
	}); 

}</script> 

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




<?php  if(isset($_SESSION['plan']['det'])){ 
$CantItems = count($_SESSION['plan']['det']);
for($i=1; $i<=$CantItems; $i++){
echo "<script>agregar_detalle(".$_SESSION['plan']['det'][$i][0].",'".$_SESSION['plan']['det'][$i][1]."', ".$_SESSION['plan']['det'][$i][2].", ".$_SESSION['plan']['det'][$i][3].");</script>";
} } ?>
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