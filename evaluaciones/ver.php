<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");
include("../complementos/util.php");

$_SESSION['acc']['mod'] = 61;
$_SESSION['acc']['form'] = 163;
include("../complementos/permisos.php");


if(isset($_REQUEST['eva'])){ $_SESSION['ejemant']=filtrar_campo('int', 6, $_REQUEST['eva']); }

if(isset($_SESSION['ejemant'])){ 
	$rs = pg_query($link, filtrar_sql("select id_unidad, id_planmant, id_confunid, fe, total from ejemant where id_ejemant = ".$_SESSION['ejemant']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico La Evaluación del Mantenimiento";
	Auditoria("Evaluación de Mantenimiento No Identificado ",$_SESSION['eva']);
	unset($_SESSION['eva']);
	header("location: listado.php");
	exit();
} else {
	$rs = pg_fetch_array($rs);
	$unidad = $rs[0];
	$plan = $rs[1];
	$confunid = $rs[2];
	$fe = date1($rs[3]);
	$costo = $rs[4]." Bfs.";

	$rs = pg_query($link, filtrar_sql("select confunid.codigo_principal, unidades.codigo_principal from confunid, unidades where unidades.id_confunid = confunid.id_confunid and unidades.id_unidad = $unidad")); 
	$rs = pg_fetch_array($rs); 
	$unidades = $rs[0]." ".$rs[1];
	
	if($plan==0){ 
		$plan = " - - "; 
	} else { 
		$rs = pg_query($link, filtrar_sql("select descripcion from planmant where id_planmant = $plan")); 
		$rs = pg_fetch_array($rs); 
		$plan = $rs[0];
	}
	
	$rs = pg_query($link, filtrar_sql("select * from evaluaciones where id_ejemant = ".$_SESSION['ejemant']));
	$rs = pg_fetch_array($rs);
	$_SESSION['evamant'] = $rs[0];
	$tiempo = $rs[2];
	$fi = date1($rs[3]);
	$ff = date1($rs[4]);
	$cant_t = $rs[5];
	$cant_tr = $rs[6];
	$cant_tsr = $cant_t - $cant_tr;
	$porc_tr = $rs[7]."%";
	$porc_tsr = (100 - $porc_tr)."%";
	$cant_prov = $rs[8];
	$cant_fact = $rs[9];
	
	Auditoria("Accedio Al Modulo Ver Evaluación del Mantenimiento para la Unidad: $unidades Segun Plan de Mantenimiento: $plan en Fecha: $fe",$_SESSION['evamant']);
}
	
} else { 
	$_SESSION['mensaje1']="No se identifico La Evaluación del Mantenimiento";
	Auditoria("Evaluación de Mantenimiento No Identificado ",$_SESSION['eva']);
	unset($_SESSION['eva']);
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
<li><a href="#">Evaluación de Mantenimiento</a></li>
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

<div class="header">Ver Evaluación del Mantenimiento<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="ver" method="post" action="ver.php">
<fieldset>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >                                
<div class="form-group"><label>Fecha de Ejecucion</label>
<input id="fe" name="fe" type="text" placeholder="Fecha de Registro de la Ejecución del Mantenimiento" class="form-control" value="<?php echo $fe;?>" readonly="readonly" /></div></div>

<!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6" >
<div class="form-group"><label>Tiempo de Duración del Mantenimiento</label>
<input id="dur" name="dur" type="text" placeholder="Tiempo que se Tardo para Llevar Acabo el Mantenimiento" class="form-control" value="<?php echo $dur;?>" readonly="readonly" /></div></div> -->

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Tareas</label>
<input id="cant_t" name="cant_t" type="text" placeholder="Cantidad de Tareas" class="form-control" value="<?php echo $cant_t;?>" readonly="readonly" /></div></div>

<!-- <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p></div> -->

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Tareas Sin Realizar</label>
<input id="cant_tsr" name="cant_tsr" type="text" placeholder="Cantidad de Tareas Sin Realizar" class="form-control" value="<?php echo $cant_tsr;?>" readonly="readonly" /></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Tareas Realizadas</label>
<input id="cant_tr" name="cant_tr" type="text" placeholder="Cantidad de Tareas Realizadas" class="form-control" value="<?php echo $cant_tr;?>" readonly="readonly" /></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Porcentaje de Tareas Sin Realizar</label>
<input id="porc_tsr" name="porc_tsr" type="text" placeholder="Porcentaje de Tareas Sin Realizar" class="form-control" value="<?php echo $porc_tsr;?>" readonly="readonly" /></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Porcentaje de Tareas Realizadas</label>
<input id="porc_tr" name="porc_tr" type="text" placeholder="Porcentaje de Tareas Realizadas" class="form-control" value="<?php echo $porc_tr;?>" readonly="readonly" /></div></div> 


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Proveedores de Servicio</label>
<input id="cant_prov" name="cant_prov" type="text" placeholder="Cantidad de Proveedores de Servicio que Intervinieron en el Mantenimiento" class="form-control" value="<?php echo $cant_prov;?>" readonly="readonly" /></div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">                                
<div class="form-group"><label>Cantidad de Facturas</label>
<input id="cant_fact" name="cant_fact" type="text" placeholder="Cantidad de Facturas Generadas Por el Mantenimiento" class="form-control" value="<?php echo $cant_fact;?>" readonly="readonly" /></div></div> 

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Costo Total Bfs.</label>
<input id="costo" name="costo" type="text" placeholder="Costo Total del Mantenimiento en Bfs." class="form-control" value="<?php echo $costo;?>" readonly="readonly" /></div></div>
 
<p>&nbsp;</p>
<p>&nbsp;</p>
<p>&nbsp;</p>                    
                                                                                   
<div class="form-group"><label>Observaciones</label>
<textarea rows="12" name="obs" id="obs" class="form-control" onkeypress="return permite(event,'todo');" readonly="readonly"><?php echo $obs; ?></textarea></div>
                                
</fieldset>
<p>&nbsp;</p>
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