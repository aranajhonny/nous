<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 65;
$_SESSION['acc']['form'] = 171;
include("../complementos/permisos.php");

if(isset($_REQUEST['cli'])){ $_SESSION['aux']=filtrar_campo('int', 6, $_REQUEST['cli']); }

if(isset($_POST['guardar'])){ 
$rif = filtrar_campo('rif', 15, $_POST['rif']); 
$razon = filtrar_campo('string', 200, $_POST['razon']); 
$dir = filtrar_campo('string', 200, $_POST['dir']);
$tlf = filtrar_campo('tlf', 12, $_POST['tlf']); 
$correo = filtrar_campo('todo', 100, $_POST['correo']); 
$contacto = filtrar_campo('string', 200, $_POST['contacto']);
$tipo = filtrar_campo('int', 6, $_POST['tipo']);

if(empty($rif)){ $_SESSION['mensaje1']="Debe indicar el numero del RIF";
} else if(empty($razon)){ $_SESSION['mensaje1']="Debe indicar la razón social";
} else if(empty($dir)){ $_SESSION['mensaje1']="Debe indicar la dirección";
} else if(empty($tlf)){ $_SESSION['mensaje1']="Debe indicar el numero de télefono";
} else if(empty($tipo)){ $_SESSION['mensaje1']="Debe seleccionar el tipo de cliente";
} else if(in_array(446,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 
	$rs = pg_query($link, filtrar_sql("select count(id_cliente) from clientes where rif='$rif' and id_cliente = ".$_SESSION['aux']));
	$rs = pg_fetch_array($rs);
//	if($rs[0]>0){ 
//		$_SESSION['mensaje1']="numero de rif ya registrado";
//	} else { // si rif 
	
		$rs = pg_query($link, filtrar_sql("update clientes set rif='$rif', razon_social='$razon', direccion='$dir', telefono='$tlf', contacto='$contacto', correo_contacto='$correo', id_tipo_cliente=$tipo where id_cliente = ".$_SESSION['aux']));
		if($rs){ 
			Auditoria("En Configuración Incial Edito Los Datos del Cliente: $rif $razon",$_SESSION['aux']);
			$_SESSION['mensaje3']="Datos del Cliente Actualizados...";
			header("location: cliente_listado.php?limpiar=true");
			exit();

		} else { 
			$_SESSION['mensaje1']="No se logro actualizar los datos del cliente";
			Auditoria("Problema al Actualizar los Datos del Cliente Error: ".pg_last_error($link),0);
		}
//	} // si rif
} // si validar

} else if(isset($_SESSION['aux'])){
	$rs = pg_query($link, filtrar_sql("select * from clientes where id_cliente = ".$_SESSION['aux']));
	$r = pg_num_rows($rs);
	if($r==false || $r<1){ 
		$_SESSION['mensaje1']="No se identifico el cliente";
		Auditoria("En Configuracion Inicial Cliente No Identificado ",$_SESSION['aux']);
		unset($_SESSION['aux']);
		header("location: cliente_listado.php?limpiar=true");
		exit();
	} else { 
		$rs = pg_fetch_array($rs);
		$tipo = $rs[1]; 
		$rif = $rs[2]; 
		$razon = $rs[3]; 
		$dir = $rs[4]; 
		$tlf = $rs[5];
		$contacto = $rs[6]; 
		$correo = $rs[7];
		Auditoria("En Configuracion Inicial Accedio a Editar Cliente: $rif $razon ",$_SESSION['aux']);
	}

} else { 
	$_SESSION['mensaje1']="No se identifico el cliente";
	Auditoria("En Configuracion Inicial Cliente No Identificado ",0);
	unset($_SESSION['aux']);
	header("location: cliente_listado.php?limpiar=true");
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
<div class="row">
<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
<div class="well">

<div class="header">Editar Datos del Cliente</div>
<form name="editar" method="post" action="cliente_editar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>RIF</label>
<input id="rif" name="rif" type="text" pattern="^([JVEG]{1})-([0-9]{8})-([0-9]{1})$" title="El formato debe ser J-12345678-9" placeholder="J-12345678-9" class="form-control" maxlength="12" value="<?php echo $rif;?>" onkeypress="return permite(event,'rif')" onkeyup="mayu(this)" required/><p class="help-block">Ejemplo: J-12345678-0</p></div>
<!--ó  V-20123456-->
                                
<div class="form-group"><label>Razón Social</label>
<input id="razon" name="razon" type="text" placeholder="Razón Social" class="form-control" maxlength="200" value="<?php echo $razon;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" required/></div>

<div class="form-group"><label>Dirección</label>
<input id="dir" name="dir" type="text" placeholder="Dirección" class="form-control" maxlength="200" value="<?php echo $dir;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" required/>
<p class="help-block">Ejemplo: Estado / Ciudad / Municipio / Establecimiento</p></div>

<div class="form-group"><label>Télefono</label>
<input id="tlf" name="tlf" type="text" placeholder="Numero de Télefono" class="form-control" maxlength="12" value="<?php echo $tlf;?>" onkeypress="return permite(event,'telef')" onkeyup="mayu(this)" required/><p class="help-block">Ejemplo: 0243-1234567</p></div>

<div class="form-group"><label>Contacto</label>
<input id="contacto" name="contacto" type="text" placeholder="Nombre del Contacto" class="form-control" maxlength="200" value="<?php echo $contacto;?>" onkeypress="return permite(event,'car')" onkeyup="mayu(this)" required/></div>

<div class="form-group"><label>Correo del Contacto</label>
<input id="correo" name="correo" type="text" placeholder="contact@example.com" onkeyup="this.value=this.value.toUpperCase()" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" title="El formato debe ser contact@example.com" class="form-control" maxlength="100" value="<?php echo $correo;?>" onkeypress="return permite(event,'todo')" required/><p class="help-block">Ejemplo: contact@example.com</p></div>


<div class="form-group"><label>Tipos de Cliente </label>
<div><select id="tipo" name="tipo" class="selectpicker" required>
<option value="0" selected="selected">Seleccione un Tipo de Cliente</option>
<?php $rs = pg_query($link, filtrar_sql("select id_tipo_cliente, descripcion from tipo_clientes order by descripcion asc")); 
$r = pg_num_rows($rs);
if($r!=false && $r>0){ while($r = pg_fetch_array($rs)){?>    
<option value="<?php echo $r[0];?>" <?php if($tipo==$r[0]) echo "selected";?> ><?php echo $r[1];?></option> 
<?php } } ?>    
</select></div>
</div>
                                
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>

<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='cliente_listado.php?limpiar=true'"/></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" id="guardar" value="Guardar" class="btn btn-primary btn-block"/></div>
</div>

</form>
</div>

<script>
function validar(){ 
	val = false;
	if(document.getElementById('rif').value.length<10){ mensaje("Debe indicar el numero de rif y debe contener al menos 10 digitos",1);
	} else if(document.getElementById('razon').value.length<1){ mensaje("Debe indicar la razon social o nombre del cliente",1);
	} else if(document.getElementById('dir').value.length<1){ mensaje("Debe indicar la direccion",1);
	} else if(document.getElementById('tlf').value.length<10){ mensaje("Debe indicar el numero de telefono y debe contener al menos 11 digitos",1);
	} else if(document.getElementById('tipo').value=="0"){ mensaje("Debe seleccionar el tipo de cliente",1);
	} else { 
		val = true;
		mensaje("Registrando...",3);
		$('#guardar').css('display','none');
	}
		
	return val; 
}
</script>


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

<script src="../Legend/admin/assets/bootstrapmaxlength/js/bootstrap-maxlength.min.js"></script>
<script src="../Legend/admin/assets/select2/js/select2.min.js"></script>
<script>

$("#rif").maxlength({ alwaysShow: true });
$("#razon").maxlength({ alwaysShow: true });
$("#contacto").maxlength({ alwaysShow: true });
$("#correo").maxlength({ alwaysShow: true });
$("#tlf").maxlength({ alwaysShow: true });
$("#dir").maxlength({ alwaysShow: true });

$("#tipo").select2();</script>

<!--Nuestro code-->
<script>
	$('#tlf').bind('change', function(){
	    telephone_user = $('#tlf').val().replace(/[^0-9]/g, '');
	    telephone_user_regex = telephone_user.replace(/(\d{4})(\d{7})/, "$1-$2");
	    $('#tlf').val(telephone_user_regex);
	});
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