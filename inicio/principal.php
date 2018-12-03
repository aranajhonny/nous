<?php 
session_start(); 
include("../complementos/session.php");
include_once("../complementos/auditoria.php");

Auditoria("Accedio a la Pagina Principal",0);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="author" content="Nous Technologies">
<link rel="shortcut icon" href="../img/icono.png">
<title>.:: NousTrack ::.</title>
<link href="../Legend/admin/assets/humane/css/bigbox.css" rel="stylesheet">
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet">
<link href="../Legend/admin/assets/biccalendar/css/bic_calendar.css" rel="stylesheet">
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'>
<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'>"; ?>
<link href="../Legend/admin/assets/css/leftmenu.css" rel="stylesheet">

</head>
<body>
<?php echo $_SESSION['miss'][4]; ?>           
<div class="overlay"></div>
<div class="controlshint" ><img src="../img/swipe.png" alt="Menu Help"></div>
<section class="wrap">
<div class="container">
<img src="../img/logo.png" height="67" width="454"  onclick="location.href='principal.php'" /><br/>
<ol class="breadcrumb">
<li><a href="#">Principal</a></li>
<li class="pull-right"><a href="" class="text-muted"><i class="fa fa-refresh"></i></a></li>
<li class="pull-right"><?php echo date('d/m/Y');?></li>
<li class="pull-right"><a href="" class="text-muted"><?php echo $_SESSION['miss'][6];?></a></li>
</ol>
<div class="row">

<div class="col-xs-12">
<div class="well">
<iframe src="../beyondadmin/html/grafico01.php" frameborder="0" scrolling="no" width="915" height="300" ></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="../estatus_general/cant_zona.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="../estatus_general/porc_confunid.php" frameborder="0" scrolling="no" width="420" height="300"></iframe>
</div></div>

<div class="col-xs-12">
<div class="well">
<iframe src="../beyondadmin/html/grafica02.php?ctrl=35" frameborder="0" scrolling="no" width="915" height="340" ></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="../panico/ultimos_panicos.php" frameborder="0" scrolling="no" width="420" height="440"></iframe>
</div></div>

<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6">
<div class="well">
<iframe src="../notificaciones/ultimas_notificaciones.php" width="420" height="440" frameborder="0" scrolling="no"></iframe>
</div></div>

</div>


</div>
</section>  
<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">$(document).bind("mobileinit", function(){$.extend(  $.mobile , {autoInitializePage: false})});</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../Legend/admin/assets/js/leftmenu.js"></script>
<script src="../Legend/admin/assets/js/theme.js"></script>


<script src="../Legend/admin/assets/humane/js/humane.min.js"></script>
<script> 
function bienvenida(texto){
var notify = humane.create({
   timeout: 6000,
   baseCls: 'humane-bigbox',
   addnCls: 'humane-bigbox-info'
});
	notify.log(''+texto);
}
</script>


<script src="../Legend/admin/assets/biccalendar/js/bic_calendar.min.js"></script>
<script> function biccal() {
     mesos = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

     dias = ["L", "M", "M", "J", "V", "S", "D"];

     $('#cal').bic_calendar({
         nombresMes: mesos,
         dias: dias,
         req_ajax: {
             type: 'get'
         }
     });
}
biccal();</script>
 
 

<script>
function mensaje(texto, tipo) { 
         var notify2;
		 if(tipo==1){ notify2 = humane.create({
             timeout: 3000,
             baseCls: 'humane-jackedup',
             addnCls: 'humane-jackedup-error'
         });
		 } else if(tipo==2){ notify2 = humane.create({
             timeout: 3000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-info'
         });
		 } else if(tipo==3){ notify2 = humane.create({
             timeout: 3000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-success'
         }); } 
         notify2.log(''+texto);
}</script>

<?php 
if(isset($_SESSION['mensaje1'])){ 
echo "<script>mensaje('".$_SESSION['mensaje1']."',1);</script>"; 
unset($_SESSION['mensaje1']);}

if(isset($_SESSION['mensaje2'])){ 
echo "<script>mensaje('".$_SESSION['mensaje2']."',2);</script>"; 
unset($_SESSION['mensaje2']);}

if(isset($_SESSION['mensaje3'])){ 
echo "<script>mensaje('".$_SESSION['mensaje3']."',3);</script>"; 
unset($_SESSION['mensaje3']);} 

if(isset($_SESSION['mensaje_ini'])){ 
echo "<script>bienvenida('".$_SESSION['mensaje_ini']."');</script>"; 
unset($_SESSION['mensaje_ini']);} ?>
</body>
</html>