<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 66;
$_SESSION['acc']['form'] = 172;
include("../complementos/permisos.php");


if(isset($_POST['guardar'])){ 
$tipo = filtrar_campo('int', 6,$_POST['tipo']); 
$lote = filtrar_campo('int', 6,$_POST['lote']); 
$cli = filtrar_campo('int', 6,$_POST['cli']); 
$inicio = filtrar_campo('int', 6,$_POST['inicio']);
$cant = filtrar_campo('int', 6,$_POST['cant']);

if(empty($tipo)){ $_SESSION['mensaje1']="Debe seleccionar el tipo de dispositivo";
} else if(empty($lote)){ $_SESSION['mensaje1']="Debe seleccionar un lote";
} else if(empty($inicio)){ $_SESSION['mensaje1']="Debe indicar el inicio del serial";
} else if(empty($cant)){ $_SESSION['mensaje1']="Debe indicar la cantidad de dispositivos";
} else if(in_array(447,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

$rs = false;
$tdisp = $inicio+$cant;
for($i=$inicio; $i<$tdisp; $i++){ 
	$serial = $i;
	if(empty($serial)==false){ 
		if( pg_query($link, filtrar_sql("insert into dispositivos(id_tipo_disp, id_cliente, id_lote, serial, estatus) values($tipo, $cli, $lote, '$serial', 'Operación')")) ){ 
			$rs = true;
			$qs=pg_query($link, filtrar_sql("select max(id_dispositivo) from dispositivos"));
			$qs = pg_fetch_array($qs);
 			Auditoria("En Instalación Agrego Dispositivo Serial: #$serial", $qs[0]);
		} else { 
			Auditoria("Problema al registrar Dispositivo Error: ".pg_last_error($link),0);
		}
	}
}

if($rs==true){ 
	$_SESSION['instalacion']['disp'] = true;
	$_SESSION['instalacion']['cli'] = filtrar_campo('int', 6, $cli);
	$_SESSION['mensaje3']="Dispositivos Agregados";
	header("location: dispositivos_listado.php");
	exit();
} else { 
	$_SESSION['mensaje1']="No se logro agregar los dispositivos";
	Auditoria("En Instalación Problema al registrar Los Dispositivo ",0);
}
	
	
} // si validar
} else { 
	$cant = $tipo = $cli = $lote = "";
	$rs = pg_query($link, filtrar_sql("select serial from dispositivos order by id_dispositivo desc limit 1"));
	$rs = pg_fetch_array($rs);
	$inicio = 1+$rs[0];
	Auditoria("En Instalación Accedio a Agregar Dispositivos",0);
}

?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png" />
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png" />
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png" />  
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<title>.:: NousTrack ::.</title>
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<link href="../Legend/admin/assets/select2/css/select2.css" rel="stylesheet"/>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
<script src="../complementos/utilidades.js"></script>
<style>
	.wrap { margin:0px;padding:0px; }
	.wrap .container { padding:0px; }
	body { background-color:#FFF; }
</style>
</head>
<body>          
<section class="wrap">
<div class="container">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">

<div class="header">Agregar Dispositivos</div>
<form name="agregar" method="post" action="dispositivos_agregar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Cliente</label>
<div><select id="cli" name="cli" class="selectpicker">
	<option value="0" selected="selected">Seleccione un Cliente</option>
<?php $rs = pg_query($link, filtrar_sql("select id_cliente, rif, razon_social from clientes order by rif asc"));
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($cli==$r[0]) echo "selected";?> ><?php echo $r[1]." ".$r[2];?></option> 
<?php } } ?>    
</select></div>
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Lote</label>
<div><select id="lote" name="lote" class="selectpicker">
	<option value="0" selected="selected">Seleccione un Lote</option>
<?php $rs = pg_query($link, filtrar_sql("select id_lote, nro from lotes order by nro asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($lote==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="form-group"><label>Tipo de Dispositivo</label>
<div><select id="tipo" name="tipo" class="selectpicker">
	<option value="0" selected="selected">Seleccione un Tipo de Dispositivo</option>
<?php $rs = pg_query($link, filtrar_sql("select id_tipo_disp, descripcion from tipo_disp order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($tipo==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><div class="form-group">
<label>Inicio del Serial</label>
<input id="inicio" name="inicio" type="text" placeholder="Inicio del Serial " class="form-control" maxlength="20" value="<?php echo $inicio;?>" readonly="readonly" /></div></div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><div class="form-group">
<label>Cantidad de Dispositivos</label>
<input id="cant" name="cant" type="text" placeholder="Cantidad de Dispositivos" class="form-control" maxlength="4" value="<?php echo $cant;?>" onkeypress="return permite(event,\'num\')"/></div></div>


<input type="hidden" name="tdisp" id="tdisp" value="0"/>                            
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" id="guardar" value="Generar Dispositivos" class="btn btn-primary btn-block"/></div></div>
</form>

</div>
</div>
</div>
</section>
<script>
function validar(){ 
val = false;
var tdisp = Number(document.getElementById('tdisp').value);	

	if(document.getElementById('cli').value=="0"){ 
		mensaje("Debe seleccionar el cliente",1);
		
	} else if(document.getElementById('tipo').value=="0"){ 
		mensaje("Debe seleccionar el tipo de dispositivo",1);
		
	} else if(document.getElementById('lote').value=="0"){ 
		mensaje("Debe seleccionar un lote",1);
		
	} else if(Number(document.getElementById('cant').value) < 1){
		mensaje("Debe Indicar La Cantidad de Dispositivos ",1);
	
	} else { 
		val = true;
	}
	
return val; }
</script>

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

<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>
$("#tipo").select2();
$("#cli").select2();
$("#lote").select2();
</script>
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
<?php include("../complementos/closdb.php"); ?>
</body>
</html>