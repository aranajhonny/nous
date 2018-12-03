<?php 
session_start();
include("../complementos/condb.php");
include_once("../complementos/auditoria.php");

$_SESSION['acc']['mod'] = 40;
$_SESSION['acc']['form'] = 147;
include("../complementos/permisos.php");


Auditoria("Accedio Al Modulo de Estatus General",0);

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
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet"/>
<script src="../complementos/utilidades.js"></script>

</head>
<body>
<?php echo $_SESSION['miss'][4]; ?>
<div class="overlay"></div>
<div class="controlshint" ><img src="../img/swipe.png" alt="Menu Help" /></div>
<section class="wrap">
<div class="container">
<img src="../img/logo.png" height="67" width="454" onclick="location.href='../inicio/principal.php'" /><br/>

<ol class="breadcrumb">
<li><a href="#">Monitoreo</a></li>
<li><a href="#">Vista General de Unidades</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>

<div class="row">
           
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="hora.php" frameborder="0" scrolling="no" width="420" height="300" ></iframe>
</div></div>
                   
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="porc_estatus.php" frameborder="0" scrolling="no" width="420" height="300" ></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="cant_area.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="porc_area.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>

    
<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="cant_zona.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="porc_zona.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="cant_confunid.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="porc_confunid.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>


<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="cant_control.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="porc_control.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>

<div class="col-xs-12">
<div class="well">
<iframe src="../beyondadmin/html/grafica02.php?ctrl=33" frameborder="0" scrolling="no" width="915" height="340" ></iframe>
</div></div>

<div class="col-xs-12">
<div class="well">
<iframe src="../beyondadmin/html/grafica02.php?ctrl=35" frameborder="0" scrolling="no" width="915" height="340" ></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="porc_alarmas.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="porc_notificaciones.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="top_unidades.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="top_controles.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>


<div class="col-xs-12">
<div class="well">
<iframe src="../permisos/carga_de_trabajo.php" frameborder="0" scrolling="no" width="915" height="420" ></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="../permisos/porc_permisos_estatus.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="../permisos/permisos_vencidos.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>

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