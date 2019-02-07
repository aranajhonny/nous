<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 46;
$_SESSION['acc']['form'] = 108;
include("../complementos/permisos.php");

if(isset($_REQUEST['plan'])){ $_SESSION['planmant']=$_REQUEST['plan']; }

if(isset($_SESSION['planmant'])){
$rs = pg_query($link, "select * from planmant where id_planmant = ".$_SESSION['planmant']);
$rs = pg_fetch_array($rs);
$cli = $rs[1];
$res = $rs[2];
$conf = $rs[3]; 
$sensor = $rs[4]; 
$unidmed = $rs[5];
$maestro = $rs[7]; 
$mod = $rs[8]; 
$tipo = $rs[9];
$des = $rs[10];
$porc = $rs[11];
$val = 1*$rs[12]; 
$prom = 1*$rs[13]; 
$val_min = 1*$rs[14]; 
$val_max = 1*$rs[15];
$tiempo = $rs[16]." Días"; 
$tiempo_min = $rs[17]." Días"; 
$tiempo_max = $rs[18]." Días";
$prog = $rs[19];
$prov = $rs[20];

$rs = pg_query($link, "select rif, razon_social from clientes where id_cliente = $cli");
$rs = pg_fetch_array($rs); $cli = $rs[0]." ".$rs[1];

$rs = pg_query($link, "select codigo_principal from confunid where id_confunid = $conf");
$rs = pg_fetch_array($rs); $conf = $rs[0];

$rs = pg_query($link, "select ci, nombre from personal where id_personal = $res");
$rs = pg_fetch_array($rs); $res = $rs[0]." ".$rs[1];

if($sensor==0){ $sensor="- -"; } else { 
$rs = pg_query($link, "select descripcion, nombre from tipo_sensores where id_tipo_sensor = $sensor");
$rs = pg_fetch_array($rs); $sensor = $rs[0]." ".$rs[1]; }

if($unidmed==0){ $unidmed="- -"; } else { 
$rs = pg_query($link, "select magnitudes.nombre, unidmed.nombre from magnitudes, unidmed where unidmed.id_magnitud = magnitudes.id_magnitud and  id_unidmed = $unidmed");
$rs = pg_fetch_array($rs); $unidmed = $rs[0]." ".$rs[1]; }

if($maestro==0){ $maestro="- -"; } else { 
$rs = pg_query($link, "select nombre from planmaes where id_planmaes = $maestro");
$rs = pg_fetch_array($rs); $maestro = $rs[0]; }

if($mod==0){ $mod="- -"; } else { 
$rs = pg_query($link, "select marcas.descripcion, modelos.descripcion from marcas, modelos where modelos.id_marca = marcas.id_marca and id_modelo = $mod");
$rs = pg_fetch_array($rs); $mod = $rs[0]." - ".$rs[1]; }

if($prov==0){ $prov="- -"; } else { 
$rs = pg_query($link, "select rif, nombre_prov from provserv where id_provserv = $prov");
$rs = pg_fetch_array($rs); $prov = $rs[0]." ".$rs[1]; }

$rs = pg_query($link, "select html from instrucciones where id_planmant = ".$_SESSION['planmant']);
$r = pg_num_rows($rs);
if($r==false || $r==0){ $inst=""; 
} else { 
	$rs = pg_fetch_array($rs);
	$inst = $rs[0];
}

$detalle[1] = array ( 0,0,0,0,0,0,0,0,0,0,0,0,0,0,0 );
$detalle[0] = array ( "","","","","", "","","","","", "","","","","" );

$rs = pg_query($link, "select descripcion, id_composicion, id_provserv from det_planmant where id_planmant = ".$_SESSION['planmant']." order by id_detplanmant asc ");
$r = pg_num_rows($rs);
if($r!=false && $r>0){ $i=0; 
	while($r = pg_fetch_array($rs)){ 
		$detalle[0][$i] = $r[0];
		if($r[1]==0){ $detalle[1][$i] = "- -"; } else { 
$qs = pg_query($link, "select nombre from composiciones where id_composicion=".$r[1]);
			$qs = pg_fetch_array($qs);
			$detalle[1][$i] = $qs[0];
		} 
		if($r[2]==0){ $detalle[2][$i] = "- -"; } else { 
$qs = pg_query($link, "select rif, nombre_prov from provserv where id_provserv=".$r[2]);
			$qs = pg_fetch_array($qs);
			$detalle[2][$i] = $qs[0]." ".$qs[1];
		} 
		$i++; 
	} 
}

Auditoria("Accedio Al Modulo Ver Plan de Mantenimiento: $des",$_SESSION['planmant']);


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
<li><a href="#">Ver</a></li>
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

<div class="header">Ver Plan de Mantenimiento<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="ver.php">
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
<div class="form-group">
<label>Nombre</label>
<input id="des" name="des" type="text" placeholder="Descripción" class="form-control" value="<?php echo $des;?>" readonly="readonly" /></div>

<div class="form-group"><label>Cliente</label>
<input id="des" name="des" type="text" placeholder="Cliente" class="form-control" value="<?php echo $cli;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Unidad</label>
<input id="conf" name="conf" type="text" placeholder="Tipo de Unidad" class="form-control" value="<?php echo $conf;?>" readonly="readonly" /></div>

<div class="form-group"><label>Marca - Modelo</label>
<input id="mod" name="mod" type="text" placeholder="Modelo" class="form-control" value="<?php echo $mod;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Sensor</label>
<input id="des" name="des" type="text" placeholder="Tipo de Sensor" class="form-control" value="<?php echo $sensor;?>" readonly="readonly" /></div>

<div class="form-group"><label>Magnitud / Unidad de Medida</label>
<input id="unidmed" name="unidmed" type="text" placeholder="Unidad de Medida" class="form-control" value="<?php echo $unidmed;?>" readonly="readonly" /></div>

<div class="form-group"><label>Tipo de Programación</label>
<input id="prog" name="prog" type="text" placeholder="Tipo de Programación" class="form-control" value="<?php echo $prog;?>" readonly="readonly" /></div>

<div class="form-group"><label>Plan Maestro de Mantenimiento</label>
<input id="maestro" name="maestro" type="text" placeholder="Plan Maestro de Mantenimiento" class="form-control" value="<?php echo $maestro;?>" readonly="readonly" /></div>

<div class="form-group"><label>Valor</label>
<input id="val" name="val" type="text" placeholder="Valor" class="form-control"  value="<?php echo $val;?>" readonly="readonly" /></div>

<div class="form-group"><label>Valor Promedio Mensual</label>
<input id="prom" name="prom" type="text" placeholder="Valor Promedio Mensual" class="form-control"  value="<?php echo $prom;?>" readonly="readonly" /></div>

<div class="form-group"><label>Limite de Tiempo</label>
<input id="tiempo" name="tiempo" type="text" placeholder="Días" class="form-control"  value="<?php echo $tiempo;?>" readonly="readonly" /></div>

<div class="form-group"><label>Porcentaje de Tolerancia:
<input type="text" name="porc" id="porc" value="<?php echo $porc;?>"  readonly="readonly" size="4" readonly="readonly" />%</label>
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

<div class="form-group"><label>Proveedor de Servicio</label>
<input id="prov" name="prov" type="text" placeholder="Proveedor de Servicio" class="form-control" value="<?php echo $prov;?>" readonly="readonly" /></div>

<div class="form-group"><label>Responsable</label>
<input id="res" name="res" type="text" placeholder="CI Apellido y Nombre" class="form-control" value="<?php echo $res;?>" readonly="readonly" /></div>

		                            </div>
                                    
                                    
                                    
                                    
                                    
                                    
		                            <div class="step-pane" id="step2">
<?php $t = $i;  
if($t==0){ ?>
<div class="form-group"><label>No Hay Detalles Para Este Plan de Mantenimiento</label></div>

<?php } else { 
for($i=0; $i<$t; $i++){ ?>

<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
<label>Detalle Nro <?php echo ($i+1); ?></label>
<input id='' name='' type='text' placeholder='Breve Descripción del Mantenimiento a Realizar' class='form-control' value='<?php echo $detalle[0][$i];?>' readonly="readonly"/>
</div>

<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
<label>Composición</label>
<input id='' name='' type='text' placeholder='Breve Descripción de la Composición' class='form-control' value='<?php echo $detalle[1][$i];?>' readonly="readonly"/>
</div>

<div class='col-xs-12 col-sm-4 col-md-4 col-lg-4'>
<label>Proveedor de Servicio</label>
<input id='' name='' type='text' placeholder='Breve Descripción de la Proveedor de Servicio' class='form-control' value='<?php echo $detalle[2][$i];?>' readonly="readonly"/>
</div>

<?php } } ?><p>&nbsp;</p>   
		                            </div>


<div class="step-pane" id="step3">
<div class="form-group"><label>Instrucciones</label>
<textarea rows="24" name="inst" id="inst" readonly="readonly" class="form-control"><?php echo $inst; ?></textarea>
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
</div>
</form>
</div>

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