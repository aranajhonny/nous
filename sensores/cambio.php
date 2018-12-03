<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");


$_SESSION['acc']['mod'] = 23;
$_SESSION['acc']['form'] = 134;
include("../complementos/permisos.php");


if(isset($_REQUEST['sensor'])){ 
	$_SESSION['senctrl']['sensor']=filtrar_campo('int', 6, $_REQUEST['sensor']);
}



if(isset($_POST['guardar'])){ 
$ctrl = filtrar_campo('int', 6, $_POST['ctrl']); 
	
	
if(pg_query($link, filtrar_sql("update sensores set id_control = $ctrl where id_sensor = ".$_SESSION['senctrl']['sensor']))){ 
if(empty($ctrl)){ $control = "- -"; 
} else { 
$rs = pg_query($link, filtrar_sql("select nombre from controles where id_control=$ctrl"));
$rs = pg_fetch_array($rs); $control = $rs[0]; }
$rs = pg_query($link, filtrar_sql("select descripcion from sensores where id_sensor = ".$_SESSION['senctrl']['sensor']));$rs = pg_fetch_array($rs); $sensor = $rs[0]." ".$rs[1];
	
	Auditoria("Cambio de Control Sensor: $sensor y Control $ctrl ",$_SESSION['senctrl']['sensor']);
	$_SESSION['mensaje3']="Cambio de Control Completo";
	unset($_SESSION['senctrl']);
	header("location: sensor_control.php");
	exit();
} 



} else if(isset($_SESSION['senctrl']['sensor'])){
$rs = pg_query($link, filtrar_sql("select sensores.serial, tipo_sensores.descripcion, confunid.codigo_principal, unidades.codigo_principal, unidades.id_unidad, sensores.id_control, sensores.id_cliente, rif, razon_social from sensores, tipo_sensores, unidades, confunid, clientes where unidades.id_cliente = clientes.id_cliente and sensores.id_cliente = clientes.id_cliente and tipo_sensores.id_tipo_sensor = sensores.id_tipo_sensor and unidades.id_confunid = confunid.id_confunid and unidades.id_unidad = sensores.id_unidad and sensores.id_sensor = ".$_SESSION['senctrl']['sensor']." limit 1"));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se Identifico El Sensor Control";
	Auditoria("Sensor Control No Identificado ",$_SESSION['senctrl']['sensor']);
	unset($_SESSION['senctrl']);
	header("location: sensor_control.php");
	exit();
} else { 
	$rs = pg_fetch_array($rs);
	$_SESSION['senctrl']['dessensor'] = $rs[1]." ".$rs[0];
	$_SESSION['senctrl']['unid'] = $rs[4];
	$_SESSION['senctrl']['desunid'] = $rs[2]." ".$rs[3];
	$_SESSION['senctrl']['ctrl'] = $rs[5];
	$_SESSION['senctrl']['cli'] = $rs[6];
	$_SESSION['senctrl']['descli'] = $rs[7]." ".$rs[8];
	Auditoria("En Sensor - Control Accedio Al Modulo Cambiar Control",$_SESSION['senctrl']['sensor']);
}

} else { 
	$_SESSION['mensaje1']="No se Identifico El Sensor Control";
	Auditoria("Sensor Control No Identificado ",$_SESSION['senctrl']['sensor']);
	unset($_SESSION['senctrl']);
	header("location: sensor_control.php");
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
<li><a href="#">Configuración</a></li>
<li><a href="#">Dispositivo</a></li>
<li><a href="#">Sensor - Control</a></li>
<li><a href="#">Cambiar</a></li>
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

<div class="header">Cambiar Control<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="cambio.php">
<fieldset>

<div class="form-group"><label>Cliente</label>
<input id="cli" name="cli" type="text" placeholder="Cliente" class="form-control" maxlength="200" value="<?php echo $_SESSION['senctrl']['descli'];?>" readonly="readonly" /></div>

<div class="form-group"><label>Unidad</label>
<input id="unid" name="unid" type="text" placeholder="Unidad" class="form-control" maxlength="200" value="<?php echo $_SESSION['senctrl']['desunid'];?>" readonly="readonly" /></div>

<div class="form-group"><label>Sensor</label>
<input id="sen" name="sen" type="text" placeholder="Sensor" class="form-control" maxlength="200" value="<?php echo $_SESSION['senctrl']['dessensor'];?>" readonly="readonly" /></div>

<div class="form-group"><label>Controles</label>
<div><select id="ctrl" name="ctrl" class="selectpicker">

</select></div>
</div>
                                
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='sensor_control.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>

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

<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>



$(document).ready(function(){
	dependencia_controles();
});

function dependencia_controles(){
	var code = <?php echo $_SESSION['senctrl']['cli'];?>;
	$.get("../combox/dependencia_controles.php", { code: code },
		function(resultado){
			if(resultado == false){ alert("Error"); }
			else {
				$("#ctrl").attr("disabled",false);
				$('#ctrl').append(resultado);	
				document.getElementById("ctrl").value = '<?php echo $_SESSION['senctrl']['ctrl'];?>';			
				$("#ctrl").select2();
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