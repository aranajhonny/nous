<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 65;
$_SESSION['acc']['form'] = 171;
include("../complementos/permisos.php");

if(isset($_REQUEST['cli'])){
	$id = filtrar_campo('int', 6, $_REQUEST['cli']); 
	
	$rs = pg_query($link, filtrar_sql("select * from clientes where id_cliente = ".$id));
	$r = pg_num_rows($rs);
	if($r==false || $r<1){ 
		$_SESSION['mensaje1']="No se identifico el Cliente";
		Auditoria("En Configuración Inicial Cliente No Identificado ",$id);
		unset($_SESSION['confini']);
		header("location: cliente_listado.php");
		exit();
	} else { 
		$rs = pg_fetch_array($rs);
		$tipo = $rs[1]; $rif = $rs[2]; $razon = $rs[3]; $dir = $rs[4]; $tlf = $rs[5];
		$contacto = $rs[6]; $correo = $rs[7]; 
		$_SESSION['confini']['cli'] = $rs[0];
		$_SESSION['confini']['paso'] = 1;
		$rs = pg_query($link, filtrar_sql("select descripcion from tipo_clientes where id_tipo_cliente = $tipo"));
		$rs = pg_fetch_array($rs);
		$tipo = $rs[0];
		Auditoria("En Configuración Inicial Accedio a Ver Datos del Cliente: $rif $razon",$_SESSION['confini']['cli']);
	}

} else if(isset($_SESSION['confini']['cli'])){
	$rs = pg_query($link, filtrar_sql("select * from clientes where id_cliente = ".$_SESSION['confini']['cli']));
	$r = pg_num_rows($rs);
	if($r==false || $r<1){ 
		$_SESSION['mensaje1']="No se identifico el Cliente";
		Auditoria("En Configuración Inicial Cliente No Identificado ",$_SESSION['confini']['cli']);
		unset($_SESSION['confini']['cli']);
		header("location: cliente_listado.php");
		exit();
	} else { 
		$rs = pg_fetch_array($rs);
		$tipo = $rs[1]; $rif = $rs[2]; $razon = $rs[3]; $dir = $rs[4]; $tlf = $rs[5];
		$contacto = $rs[6]; $correo = $rs[7];
		$rs = pg_query($link, filtrar_sql("select descripcion from tipo_clientes where id_tipo_cliente = $tipo"));
		$rs = pg_fetch_array($rs);
		$tipo = $rs[0];
		Auditoria("En Configuración Inicial Accedio a Ver Datos del Cliente: $rif $razon",$_SESSION['confini']['cli']);
	}

} else { 
	$_SESSION['mensaje1']="No se identifico el cliente";
	Auditoria("En Configuración Inicial Cliente No Identificado ",$_SESSION['confini']['cli']);
	unset($_SESSION['confini']['cli']);
	header("location: cliente_listado.php");
	exit();
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
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">

<div class="header">Datos del Cliente</div>
<fieldset>

<div class="form-group"><label>RIF</label>
<input id="rif" name="rif" type="text" pattern="^([JVEG]{1})-([0-9]{8})-([0-9]{1})$" title="El formato debe ser J-12345678-9" placeholder="J-12345678-9" class="form-control" maxlength="12" onkeyup="mayu(this)" value="<?php echo $rif;?>" readonly="readonly" required/></div>
                                
<div class="form-group"><label>Razón Social</label>
<input id="razon" name="razon" type="text" placeholder="Razón Social" class="form-control" maxlength="200" onkeyup="mayu(this)" value="<?php echo $razon;?>" readonly="readonly" required/></div>

<div class="form-group"><label>Dirección</label>
<input id="dir" name="dir" type="text" placeholder="Dirección" class="form-control" maxlength="200" onkeyup="mayu(this)" value="<?php echo $dir;?>" readonly="readonly" required/></div>

<div class="form-group"><label>Télefono</label>
<input id="tlf" name="tlf" type="text" placeholder="Numero de Télefono" class="phone form-control" onkeyup="mayu(this)" maxlength="12" value="<?php echo $tlf;?>" readonly="readonly" required/></div>

<div class="form-group"><label>Contacto</label>
<input id="contacto" name="contacto" type="email" placeholder="Nombre del Contacto" class="form-control" onkeyup="mayu(this)" maxlength="200" value="<?php echo $contacto;?>" readonly="readonly" required/></div>

<div class="form-group"><label>Correo del Contacto</label>
<input id="correo" name="correo" type="text" placeholder="contact@example.com" onkeyup="this.value=this.value.toUpperCase()" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" title="El formato debe ser contact@example.com" class="form-control" maxlength="100" onkeyup="mayu(this)" value="<?php echo $correo;?>" readonly="readonly" required/></div>


<div class="form-group"><label>Tipos de Cliente</label>
<input id="tipo" name="tipo" type="text" placeholder="Tipo de Cliente" class="form-control" maxlength="200" onkeyup="mayu(this)" value="<?php echo $tipo;?>" readonly="readonly" required/></div>

<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='cliente_listado.php?limpiar=true'"/></div>
</div>
                                
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>

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

<!--Nuestro code-->
<script>
	$('#tlf').bind('change', function(){
	    telephone_user = $('#tlf').val().replace(/[^0-9]/g, '');
	    telephone_user_regex = telephone_user.replace(/(\d{4})(\d{7})/, "$1-$2");
	    $('#tlf').val(telephone_user_regex);
	});
</script>

<?php if( isset($_SESSION['confini']['cli']) ) {  
	echo "<script>window.open('area_listado.php','areas');</script>";
}?>

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