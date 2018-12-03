<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 65;
$_SESSION['acc']['form'] = 171;
include("../complementos/permisos.php");


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
	$rs = pg_query($link, filtrar_sql("select count(id_cliente) from clientes where rif='$rif'"));
	$rs = pg_fetch_array($rs);
	if($rs[0]>0){ 
		$_SESSION['mensaje1']="numero de rif ya registrado";
	} else { // si rif 
	
		$rs = pg_query($link, filtrar_sql("insert into clientes(id_tipo_cliente, rif, razon_social, direccion, telefono, contacto, correo_contacto) values( $tipo, '$rif', '$razon', '$dir', '$tlf', '$contacto', '$correo')"));
		if($rs){ 
			$rs = pg_query($link, filtrar_sql("select max(id_cliente) from clientes "));
			$rs = pg_fetch_array($rs);
			$_SESSION['confini']['cli'] = $rs[0];
			$_SESSION['confini']['paso'] = 1;
			Auditoria("En Configuración Incial Agrego Cliente: $rif $razon",$rs[0]);
			$_SESSION['mensaje3']="Cliente Agregado... Recuerda Agregar Las Áreas, Zonas, Personal y Usuarios";
			header("location: cliente_vista.php");
			exit();

		} else { 
			$_SESSION['mensaje1']="No se logro agregar el cliente";
			Auditoria("Problema al registrar El Cliente Error: ".pg_last_error($link),0);
		}
	} // si rif
} // si validar
} else { 
	$rif = $razon = $correo = $contacto = $tlf = $dir = $tipo = "";
	Auditoria("En Configuración Inicial Accedio a Agregar Clientes",0);
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

<div class="header">Agregar Cliente</div>
<form name="agregar" method="post" action="cliente_agregar.php" onsubmit="return validar();">
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
<input id="tlf" name="tlf" type="text" placeholder="02441234567" title="El formato debe ser 02441234567" class="form-control" maxlength="11" value="<?php echo $tlf;?>" onkeypress="return permite(event,'telef')" required/><p class="help-block">Ejemplo: 02431234567</p></div>

<div class="form-group"><label>Contacto</label>
<input id="contacto" name="contacto" type="text" placeholder="Nombre del Contacto" class="form-control" maxlength="200" value="<?php echo $contacto;?>" onkeypress="return permite(event,'car')" onkeyup="mayu(this)" required/></div>

<div class="form-group"><label>Correo del Contacto</label>
<input id="correo" name="correo" type="text" placeholder="contact@example.com" onkeyup="this.value=this.value.toUpperCase()" pattern="[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{1,63}$" title="El formato debe ser contact@example.com" class="form-control" maxlength="100" value="<?php echo $correo;?>" onkeypress="return permite(event,'todo')"  onkeyup="mayu(this)" required/><p class="help-block">Ejemplo: contact@example.com</p></div>


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