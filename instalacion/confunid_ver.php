<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 11;
$_SESSION['acc']['form'] = 4;
include("../complementos/permisos.php");

if(isset($_REQUEST['id'])){ $_SESSION['instalacion']['cfunid']=filtrar_campo('int', 6,$_REQUEST['id']); }

if(isset($_POST['volver'])){ 
	unset($_SESSION['instalacion']['cfunid']);
	header("location: confunid_listado.php");
	exit(); 
}

if(isset($_SESSION['instalacion']['cfunid'])){
$rs = pg_query($link, filtrar_sql("select * from confunid where id_confunid = ".$_SESSION['instalacion']['cfunid']));
$r = pg_num_rows($rs);
if($r==false || $r<1){ 
	$_SESSION['mensaje1']="No se identifico La Configuración de Unidad";
	Auditoria("Configuración de Unidad No Identificado ",$_SESSION['instalacion']['cfunid']);
	unset($_SESSION['instalacion']['cfunid']);
	header("location: confunid_listado.php");
	exit();
} else { 
	$rs = pg_fetch_array($rs);
	$nom=$rs[2]; 
	$cod=$rs[3]; 
	$conf1=$rs[4]; 
	$conf2=$rs[5]; 
	$conf3=$rs[6]; 
	$conf4=$rs[7];
}

} else { 
	$_SESSION['mensaje1']="No se identifico la configuración de la unidad";
	Auditoria("Configuración de Unidad No Identificado ",$_SESSION['instalacion']['cfunid']);
	unset($_SESSION['instalacion']['cfunid']);
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


<div class="header">Ver Configuración de Unidades</div>
<form name="ver" method="post" action="confunid_ver.php">
<fieldset>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tipo de Unidad</label>
<input id="cod" name="cod" type="text" placeholder="Tipo de Unidad" class="form-control"  value="<?php echo $cod;?>" readonly="readonly" /></div>
                                
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Nombre del Identificador</label>
<input id="nom" name="nom" type="text" placeholder="Nombre del Identificador" class="form-control" value="<?php echo $nom;?>" readonly="readonly" /></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Primera Caracteristica</label>
<input id="conf1" name="conf1" type="text" placeholder="Primera Caracteristica" class="form-control" value="<?php echo $conf1;?>" readonly="readonly" />
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Segunda Caracteristica</label>
<input id="conf2" name="conf2" type="text" placeholder="Segunda Caracteristica" class="form-control" value="<?php echo $conf2;?>" readonly="readonly" />
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Tercera Caracteristica</label>
<input id="conf3" name="conf3" type="text" placeholder="Tercera Caracteristica" class="form-control"  value="<?php echo $conf3;?>" readonly="readonly" />
</div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6"><label>Cuarta Caracteristica</label>
<input id="conf4" name="conf4" type="text" placeholder="Cuarta Caracteristica" class="form-control"  value="<?php echo $conf4;?>" readonly="readonly" />
</div>
                        
</fieldset>
<p>&nbsp;</p><p>&nbsp;</p>
<div class="row">
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<input type="submit" name="volver" value="Volver" class="btn btn-info btn-block" /></div>
</div>

</form>
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

<?php if(isset($_SESSION['instalacion']['cfunid'])) { 
	echo "<script>window.open('unidades_agregar.php','unids');</script>";
} else { 
	echo "<script>window.open('desabilitado.php?id=4','sensor');</script>";
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