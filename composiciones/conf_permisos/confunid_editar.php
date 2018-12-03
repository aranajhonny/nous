<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 69;
$_SESSION['acc']['form'] = 179;
include("../complementos/permisos.php");

if(isset($_REQUEST['conf'])){ $_SESSION['confperm']['conf']=filtrar_campo('int', 6,$_REQUEST['conf']); }



if(isset($_POST['guardar'])){  
	$cod = filtrar_campo('todo', 50,$_POST['cod']); 
	$nom = filtrar_campo('todo', 100,$_POST['nom']); 
	$conf4 = filtrar_campo('todo', 50,$_POST['conf4']);
	$conf1 = filtrar_campo('todo', 50,$_POST['conf1']);  
	$conf2 = filtrar_campo('todo', 50,$_POST['conf2']);   
	$conf3 = filtrar_campo('todo', 50,$_POST['conf3']); 
	
	if(empty($cod)){ $_SESSION['mensaje1']="Debe indicar el tipo de unidad";
	} else if(empty($nom)){ $_SESSION['mensaje1']="Debe indicar el nombre identificador";
	} else if(empty($conf1)){ $_SESSION['mensaje1']="Debe indicar la primera caracteristica";
	} else if(empty($conf2)){ $_SESSION['mensaje1']="Debe indicar la segunda caracteristica";
	} else if(in_array(454,$_SESSION['acl'])==false){$_SESSION['mensaje1']= "no posee permiso para guardar este registro";
	} else { // si validar 
	
		$rs = pg_query($link, filtrar_sql("update confunid set nombre='$nom', codigo_principal='$cod', n_configuracion_01='$conf1', n_configuracion_02='$conf2', n_configuracion_03='$conf3', n_configuracion_04='$conf4' where id_confunid = ".$_SESSION['confperm']['conf']));
		if($rs){
			Auditoria("Actualizo Los Datos de la Configuracion de Unidades: $nom",$_SESSION['confperm']['conf']);
			$_SESSION['mensaje3']="Datos de la Configuración de Unidad Actualizados";
			header("location: confunid_listado.php");
			exit();
		} else { 
			$_SESSION['mensaje1']="No se logro actualizar los datos de la configuracion de unidad";
			Auditoria("Problema al Actualizar Los Datos de la Configuración de Unidad Error: ".pg_last_error($link),0);
		}
	
	
	} // si validar
	
} else if(isset($_SESSION['confperm']['conf'])){
	$rs = pg_query($link, filtrar_sql("select * from confunid where id_confunid = ".$_SESSION['confperm']['conf']));
	$r = pg_num_rows($rs);
	if($r==false || $r<1){ 
		$_SESSION['mensaje1']="No se identifico La Configuración de Unidad";
		Auditoria("Configuración de Unidad No Identificada ",$_SESSION['confperm']['conf']);
		unset($_SESSION['confperm']['conf']);
		header("location: confunid_listado.php");
		exit();
	} else {
		$rs = pg_fetch_array($rs);
		$_SESSION['cfcli'] = $rs[1]; 
		$nom=$rs[2]; 
		$cod=$rs[3]; 
		$conf1=$rs[4]; 
		$conf2=$rs[5]; 
		$conf3=$rs[6]; 
		$conf4=$rs[7];
		Auditoria("Accedio a Editar Configuracion de unidades: $cod ",$_SESSION['confperm']['conf']);
	}

} else { 
	$_SESSION['mensaje1']="No se identifico la configuración de la unidad";
	Auditoria("Configuración de Unidad No Identificada ",0);
	unset($_SESSION['confperm']['conf']);
	header("location: confunid_listado.php");
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

<div class="header">Editar Datos de la Configuración de Unidades</div>
<form name="editar" method="post" action="confunid_editar.php" onsubmit="return validar();">
<fieldset>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tipo de Unidad</label>
<input id="cod" name="cod" type="text" placeholder="Tipo de Unidad" class="form-control" maxlength="50" value="<?php echo $cod;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>
            
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Nombre del Identificador</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Identificador" class="form-control" maxlength="100" value="<?php echo $nom;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" /></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Primera Caracteristica</label>
<input id="conf1" name="conf1" type="text" placeholder="Primera Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf1;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" />
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Segunda Caracteristica</label>
<input id="conf2" name="conf2" type="text" placeholder="Segunda Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf2;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" />
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tercera Caracteristica</label>
<input id="conf3" name="conf3" type="text" placeholder="Tercera Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf3;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" />
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Cuarta Caracteristica</label>
<input id="conf4" name="conf4" type="text" placeholder="Cuarta Caracteristica" class="form-control" maxlength="60" value="<?php echo $conf4;?>" onkeypress="return permite(event,'todo')" onkeyup="mayu(this)" />
</div>

</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="button" name="volver" value="Volver" class="btn btn-info btn-block" onclick="location.href='confunid_listado.php'"/></div>
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="guardar" id="guardar" value="Guardar" class="btn btn-primary btn-block"/></div></div>

</form>
</div>

<script>function validar(){ 
val = false;
	if(document.getElementById('cod').value.length < 1){ 
		mensaje("Debe indicar el tipo de unidad",1);
		
	} else if(document.getElementById('nom').value.length < 1){ 
		mensaje("Debe indicar el nombre identificador",1);
		
	} else if(document.getElementById('conf1').value.length < 1){ 
		mensaje("Debe indicar la Primera Caracteristica",1);
		
	} else if(document.getElementById('conf2').value.length < 1){ 
		mensaje("Debe indicar la Segunda Caracteristica",1);
		
	} else { 
		val = true;
		mensaje("Registrando...",3);
		$('#guardar').css('display','none');
	}
	
return val; }</script>


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
<script>
$("#cod").maxlength({ alwaysShow: true });
$("#nom").maxlength({ alwaysShow: true });
$("#conf1").maxlength({ alwaysShow: true });
$("#conf2").maxlength({ alwaysShow: true });
$("#conf3").maxlength({ alwaysShow: true });
$("#conf4").maxlength({ alwaysShow: true });
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