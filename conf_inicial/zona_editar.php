<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 65;
$_SESSION['acc']['form'] = 171;
include("../complementos/permisos.php");

if(isset($_REQUEST['zona'])){ $_SESSION['confini']['zona']=filtrar_campo('int', 6, $_REQUEST['zona']); }


if(isset($_POST['guardar'])){ 
$nom = filtrar_campo('todo', 120, $_POST['nom']);  
 
if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre de la Zona Geográfica";
} else if(in_array(446,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
} else { // si validar 

	$rs = pg_query($link, filtrar_sql("update zongeo set nombre='$nom' where id_zongeo = ".$_SESSION['confini']['zona']));
	if($rs){ 
		Auditoria("En Configuración Incial Actualizo Los Datos de la Zona Geografica: $nom",$_SESSION['confini']['zona']);
		$_SESSION['mensaje3']="Datos de la Zona Geográfica Actualizados";
		header("location: zona_listado.php");
		exit();
	} else { 
		$_SESSION['mensaje1']="No se logro actualizar los datos la Zona Geográfica";
		Auditoria("Problema al actualizar los datos de La Zona Geografica Error: ".pg_last_error($link),$_SESSION['confini']['zona']);
	}

} // si validar

} else if(isset($_SESSION['confini']['zona'])){
$rs = pg_query($link, filtrar_sql("select * from zongeo where id_zongeo=".$_SESSION['confini']['zona']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico La Zona Geografica";
	Auditoria("Zona Geografica No Identificada ",$_SESSION['confini']['zona']);
	header("location: zona_listado.php");
	exit();
} else { 
	$rs = pg_fetch_array($rs);
	$nom = $rs[2];
	Auditoria("Accedio Al Modulo Editar Zona Geografica: $nom",$_SESSION['confini']['zona']);
}

} else { 
	$_SESSION['mensaje1']="No se identifico la Zona Geográfica";
	Auditoria("Zona Geografica No Identificada ",$_SESSION['confini']['zona']);
	header("location: zona_listado.php");
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

<div class="header">Editar Datos de la Zona Geográfica</div>
<form name="agregar" method="post" action="zona_editar.php" onsubmit="return validar();">
<fieldset>

<div class="form-group"><label>Nombre</label>
<input id="nom" name="nom" type="text" placeholder="Nombre de la Zona Geográfica" class="form-control" maxlength="60" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" required/></div>
                                
</fieldset>
<p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='zona_listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" id="guardar" value="Guardar" class="btn btn-primary btn-block"/></div>
</div>

</form>
</div>

<script>
function validar(){ 
val = false;
	if(document.getElementById('nom').value.length<3){ 
		mensaje("Debe indicar el nombre de la Zona Geográfica",1);
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
<script> $("#nom").maxlength({ alwaysShow: true }); </script>

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