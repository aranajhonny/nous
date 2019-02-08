<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 45;
$_SESSION['acc']['form'] = 152;
include("../complementos/permisos.php");

if(isset($_POST['guardar'])){ 
$des = $_POST['des']; 
$cli = $_SESSION['master']['cli']; 
$res= $_SESSION['master']['res'];
$sensor = $_SESSION['master']['sensor'];
$unidmed = $_SESSION['master']['unidmed'];
$prog = "Incremental"; 
$mod = $_SESSION['master']['mod'];
$conf = $_SESSION['master']['conf'];
$maestro = $_SESSION['master']['id'];
$porc = $_SESSION['master']['porc'];
$prom = $_SESSION['master']['prom'];
$proveedor = $_SESSION['master']['prov'];
$inst = $_POST['inst'];

$val_max = $_POST['val_max'];  $val_min = $_POST['val_min'];
$tiempo_max = $_POST['tiempo_max'];  $tiempo_min = $_POST['tiempo_min'];

if($sensor==0){ 
	$val=$_POST['val']; 
	$tiempo = $_POST['tiempo']; 
} else { 
	$val = $_POST['val'];
	$tiempo = $_POST['tiempo']; 
}

$CantItems = $_POST['CantItems'];
for($i=1; $i<=$CantItems; $i++){
	$_SESSION['planmaestro']['det'][$i][0] = $_POST["id_det_".$i]; // ID del detalle
	$_SESSION['planmaestro']['det'][$i][1] = $_POST["det_".$i]; // descripcion del Detalle
	$_SESSION['planmaestro']['det'][$i][2] = $_POST["comp_".$i]; // composicion del Detalle
	if($proveedor==0){ 
if(isset($_POST["prov_".$i])) $_SESSION['planmaestro']['det'][$i][3] = $_POST["prov_".$i]; 
else $_SESSION['planmaestro']['det'][$i][3] = 0;
	} else { 
		$_SESSION['planmaestro']['det'][$i][3] = $proveedor;
	}
}

if(empty($des)){ $_SESSION['mensaje1']="Debe indicar el nombre";
} else if(empty($cli)){ $_SESSION['mensaje1']="Debe seleccionar un cliente";
} else if(empty($conf)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de unidad";
} else if(empty($prog)){ $_SESSION['mensaje1']="Debe seleccionar un tipo de programacion";
} else if(empty($sensor)==false && empty($val)){ $_SESSION['mensaje1']="Debe indicar el Valor";
} else if(empty($tiempo) && empty($sensor)){ $_SESSION['mensaje1']="Debe indicar el Limite de Tiempo";
} else if(empty($res)){ $_SESSION['mensaje1']="Debe seleccionar un Responsable";
} else if(empty($inst)){ $_SESSION['mensaje1']="Debe Indicar Las Instrucciones del Mantenimiento";
} else if(in_array(425,$_SESSION['acl'])==false){$_SESSION['mensaje']= "no posee permiso para guardar este registro";

} else { // si validar 

$sql="insert into planmant(id_cliente, id_confunid, id_tipo_sensor, id_unidad_medida, descripcion, valor, tiempo, id_unidad_tiempo, id_responsable, valor_prom, tipo_prog, id_planmaes, id_modelo, id_tipo_mant, valor_min, valor_max, tiempo_min, tiempo_max, porc_tol, id_provserv) values ($cli, $conf, $sensor, $unidmed, '$des', $val, $tiempo, 0, $res, $prom, '$prog', $maestro, $mod, 0, $val_min, $val_max, $tiempo_min, $tiempo_max, $porc, $proveedor)";


	$rs = pg_query($link, $link,$sql);
	if($rs){ 
$rs = pg_query($link, $link,"select max(id_planmant) from planmant");
$rs = pg_fetch_array($rs); $id = $rs[0];

Auditoria("En Agregar Plan Maestro de Mantenimiento Agrego Plan de Mantenimiento: $des",$id);
/* ============================================================================== */
$sql2="";
for($i=1; $i<=$CantItems; $i++){	
	if(empty($_SESSION['planmaestro']['det'][$i][1])==false){  
			$sql2 .="($id, ".$_SESSION['planmaestro']['det'][$i][2].", '".$_SESSION['planmaestro']['det'][$i][1]."', ".$_SESSION['planmaestro']['det'][$i][3]."),";
	}
}
if(empty($sql2)==false){ 
$sql2 = "insert into det_planmant(id_planmant, id_composicion, descripcion, id_provserv) values ".$sql2;
	$sql2 = substr($sql2,0,(strlen($sql2)-1)).";";
	pg_query($link, $link,$sql2);
	Auditoria("En Agregar Plan Maestro de Mantenimiento Se Agregaron Detalles del Plan de Mantenimiento: $des",$id);
}
/* ============================================================================== */
pg_query($link, $link,"insert instrucciones(id_planmant, html) values ($id, '$inst')");
Auditoria("En Agregar Plan Maestro de Mantenimiento Se Agregaron Instrucciones para el Plan de Mantenimiento: $des",$id);
/* ============================================================================== */
	$_SESSION['mensaje3']="Plan de Mantenimiento Agregado al Plan Maestro";
	$des = $val = $tiempo = $prom = $inst = "";
	$val_max = $val_min = $tiempo_max = $tiempo_min = $porc = 0;
	unset($_SESSION['planmaestro']['det']);

	} else { 
		$_SESSION['mensaje1']="No se logro agregar el plan de mantenimiento";
	}

} // si validar


} else if(isset($_SESSION['master'])){ 
	$des = $val = $tiempo = $prom = $inst = "";
	$val_max = $val_min = $tiempo_max = $tiempo_min = $porc = 0;
	unset($_SESSION['planmaestro']['det']);
	Auditoria("Accedio Al Modulo Agregar Pla Maestro de Mantenimiento",0);
	
} else { 
	$_SESSION['mensaje3']="Plan Maestro No Definido";
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
<div class="header">Plan Maestro de Mantenimiento Paso 2<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>

<?php $rs = pg_query($link, $link,"select nombre from planmaes where id_planmaes = ".$_SESSION['master']['id']); $rs = pg_fetch_array($rs); $maestro = $rs[0]; ?>
<div class="form-group"><label>Plan Maestro</label>
<input id="ma" name="ma" type="text" placeholder="Nombre del Plan de Maestro" class="form-control" maxlength="250" value="<?php echo $maestro;?>" readonly="readonly"/></div>


<table class="table">
	<thead>
		<tr>
		    <th>Nro</th>
		    <th>Plan de Mantenimiento</th>
		    <th>Valor</th>
		    <th>Tiempo</th>
            <th>% Tolerancia</th>
		</tr>
	</thead>
	<tbody>
<?php $rs = pg_query($link, $link,"select id_planmant, descripcion, valor, tiempo, porc_tol from planmant where id_planmaes = ".$_SESSION['master']['id']." order by valor asc "); 
$r = pg_num_rows($rs);
if($r==false || $r<1){ ?>
<tr><td colspan="5">Recuerde Que Debe Agregar Planes de Mantenimiento</td></tr>
<?php } else { $i=1; while($r = pg_fetch_array($rs)){ ?> 
<tr><td><?php echo $i;?></td>
<td><?php echo $r[1];?></td>
<td><?php echo 1*$r[2];?></td>
<td><?php echo $r[3];?></td>
<td><?php echo $r[4]."%";?></td></tr>   
<?php $i++; } } ?>                          
	</tbody>
</table>

<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='listado.php'"/></div></div>

</div>
</div>

<div class="well">
<div class="header">Agregar Planes de Mantenimiento <a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="agregar_planes.php" onsubmit="return validar();">
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

<div class="form-group"><label>Valor</label>
<input id="val" name="val" type="text" placeholder="Valor" class="form-control" maxlength="12" value="<?php echo $val;?>" onkeypress="return permite(event,'float')" onkeyup="calcular(); tolerancia();"  /></div>

<div class="form-group"><label>Limite de Tiempo</label>
<input id="tiempo" name="tiempo" type="text" placeholder="Días" class="form-control" maxlength="3" value="<?php echo $tiempo;?>" onkeypress="return permite(event,'num')" onkeyup="tolerancia();" /><p class="help-block">Ejemplo: 7 Días</p></div>

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
</div>     


                       
<div class="step-pane" id="step2">
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
	if( <?php echo $_SESSION['master']['prov']; ?> != 0 ){ 
		$('#prov_'+items).attr('disabled',true);
	}
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
<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>
</form>
</div>

<script>function validar(){ 
val = false;
	if(document.getElementById('des').value.length<1){ 
		mensaje("Debe indicar el nombre",1);
				
	} else if(document.getElementById('val').value.length<1){ 
		mensaje("Debe indicar el valor",1);
		
	} else if(document.getElementById('tiempo').value.length<1){ 
		mensaje("Debe indicar el limite del tiempo",1);
	
	} else if(document.getElementById('inst').value.length<1){ 
		mensaje("Debe indicar las Instrucciones del Mantenimiento",1);
			
	}  else {
		val = true;
	}
	
return val; }


function calcular(){ 
	var tiempo = 0;
	var aux=0, dia=0;
	if(document.getElementById('val').value.length>0 ){ 
		aux = Number(document.getElementById('val').value);
		dia = <?php echo $_SESSION['master']['prom']; ?> / 30;
		tiempo = parseInt( aux / dia );
		document.getElementById('tiempo').value = tiempo;
	}
}

function bloquear2(){  
	var tmp = <?php echo $_SESSION['master']['prov'];?>;
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
		var porc = <?php echo $_SESSION['master']['porc']; ?>;
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
function CargarDetPlanMant(){ 
	var id = <?php echo $_SESSION['master']['conf']; ?>;
	$.get("../plan_mantenimiento/det_composiciones.php?conf="+id, function(resultado){ 
		if(resultado == false){  
		} else { 
			listcomp = resultado;
		} 
	}); 
	
	var code = <?php echo $_SESSION['master']['cli']; ?>;
	$.get("../combox/dependencia_proveedores.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {	
				listprov = resultado;
			}
		}
	);
}

CargarDetPlanMant();
bloquear2();</script>


<?php if(isset($_SESSION['planmaestro']['det'])){ 
$CantItems = count($_SESSION['planmaestro']['det']);
for($i=1; $i<=$CantItems; $i++){
echo "<script>agregar_detalle(".$_SESSION['planmaestro']['det'][$i][0].",'".$_SESSION['planmaestro']['det'][$i][1]."', ".$_SESSION['planmaestro']['det'][$i][2].", ".$_SESSION['planmaestro']['det'][$i][3].");</script>";
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
