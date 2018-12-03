<?php 
session_start();
include("../complementos/condb.php");

if(isset($_REQUEST['paracomp'])){ $_SESSION['paracomp']=$_REQUEST['paracomp']; }

if(isset($_POST['guardar'])){ 
$comp = $_POST['comp']; $unid = $_POST['unid'];  $dias = $_POST['dias'];
$valor = $_POST['valor']; $porc = $_POST['porc'];

if(empty($comp)){ $_SESSION['mensaje1']="Debe seleccionar una Composición";
} else if(empty($unid)){ $_SESSION['mensaje1']="Debe seleccionar una Unidad de Medida";
} else if(empty($dias)){ $_SESSION['mensaje1']="Debe indicar los días de tolerancia";
} else if(empty($valor)){ $_SESSION['mensaje1']="Debe indicar el valor";
} else { // si validar 

$sql="update paracomp set id_composicion = $comp, id_unidmed = $unid, dias_tolerancia = $dias, valor = $valor, porcentaje_tolerancia = $porc where id_paracomp = ".$_SESSION['paracomp'];
	$rs = pg_query($sql);
	if($rs){ 
		$_SESSION['mensaje3']="Parámetro de Composición Editado";
		header("location: listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro editar el Parámetro de Composición";
	}

} // si validar

} else if(isset($_SESSION['paracomp'])){
$rs = pg_query("select * from paracomp where id_paracomp = ".$_SESSION['paracomp']);
$rs = pg_fetch_array($rs);
$comp = $rs[1]; $unid = $rs[2]; $dias = $rs[3]; $valor = $rs[4]; $porc = $rs[5];

} else { 
	$_SESSION['mensaje1']="No se identifico el Parámetro de Composición";
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
<link href="../Legend/admin/assets/bootstrapui/css/jquery-ui-1.9.2.custom.css" rel="stylesheet"/>
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
<li><a href="#">Parámetros de Composición</a></li>
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

<div class="header">Editar Parámetro de Composición<a class="headerclose"><i class="fa fa-times pull-right"></i></a> <a class="headerrefresh"><i class="fa fa-refresh pull-right"></i></a> <a class="headershrink"><i class="fa fa-chevron-down pull-right"></i></a></div>
<form name="agregar" method="post" action="editar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Composición</label>
<div><select id="comp" name="comp" class="selectpicker">
	<option value="0" selected="selected">Seleccione una Composición</option>
<?php $rs = pg_query("select id_composicion, nombre, descripcion from composiciones order by nombre, descripcion asc"); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($comp==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div>

<div class="form-group"><label>Unidad de Medida</label>
<div><select id="unid" name="unid" class="selectpicker">
	<option value="0" selected="selected">Seleccione una Unidad de Medida</option>
<?php $rs = pg_query("select id_unidmed, nombre from unidmed order by nombre asc"); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($unid==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div>
                                
<div class="form-group"><label>Días de Tolerancia</label>
<input id="dias" name="dias" type="text" placeholder="Días de Tolerancia" class="form-control" maxlength="12" value="<?php echo $dias;?>" onkeypress="return permite(event,'num')" /></div>

<div class="form-group"><label>Valor</label>
<input id="valor" name="valor" type="text" placeholder="Valor" class="form-control" maxlength="12" value="<?php echo $valor;?>" onkeypress="return permite(event,'num')" /></div>

<div class="form-group"><label>Porcentaje de Tolerancia</label>
<small class="text-muted">
<input type="text" name="porc" id="porc" value="<?php echo $porc;?>" readonly="readonly" size="10" style="width:35px;" />%</small>
<div>
<div id="slider" class="ui-slider-primary"></div>
</div>
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
	if(document.getElementById('comp').value=="0"){ 
		mensaje("Debe seleccionar la composición",1);
		
	} else if(document.getElementById('unid').value=="0"){ 
		mensaje("Debe seleccionar la unidad de medida",1);
		
	} else if(document.getElementById('dias').value.length<1){ 
		mensaje("Debe indicar los dias de tolerancia",1);
		
	} else if(document.getElementById('valor').value.length<1){ 
		mensaje("Debe indicar el valor",1);
		
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

$("#dias").maxlength({ alwaysShow: true });
$("#valor").maxlength({ alwaysShow: true });

$("#comp").select2();
$("#unid").select2();</script>

<script src="../Legend/admin/assets/bootstrapui/js/jquery-ui-1.9.2.custom.min.js"></script>
<script> var barra = jQuery.noConflict();
barra("#slider").slider({
	animate: true,
	range: "min",
	value: <?php echo $porc;?>,
	min: 0,
	max: 100,
	slide: function (event, ui) {
		document.getElementById('porc').value = ui.value;
	}
});
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