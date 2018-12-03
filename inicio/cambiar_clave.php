<?php 
session_start();


?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
<meta name="description" content="Inicio de Sesión">
<meta name="author" content="Nous Technologies">
<link rel="apple-touch-icon-precomposed" sizes="144x144" href="../Legend/admin/assets/ico/apple-touch-icon-144-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="114x114" href="../Legend/admin/assets/ico/apple-touch-icon-114-precomposed.png">
<link rel="apple-touch-icon-precomposed" sizes="72x72" href="../Legend/admin/assets/ico/apple-touch-icon-72-precomposed.png">
<link rel="apple-touch-icon-precomposed" href="../Legend/admin/assets/ico/apple-touch-icon-57-precomposed.png">
<link rel="shortcut icon" href="../img/icono.png">
<link href="../Legend/admin/assets/humane/css/jackedup.css" rel="stylesheet" />
<link href="../Legend/admin/assets/humane/css/bigbox.css" rel="stylesheet" />
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'>
<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'>"; ?>
<script src="../complementos/utilidades.js"></script>
<title>.:: NousTrack ::.</title>
</head>
<body>

<section class="login">
	<div class="login-panel" style="">
		<h3 class="text-muted">Recuperar Contraseña</h3>
		<form role="form" action="../complementos/cambio_clave.php" method="post">
				<div class="form-group">
					<input type="password" name="clave1" class="form-control" placeholder="Contraseña Nueva" required="true">
				</div>
				<div class="form-group">
					<input type="password" name="clave2" class="form-control" placeholder="Confirme su nueva contraseña" required="true" />
				</div>
				<center><button type="submit" name="inicio" class="btn btn-primary block full-width m-b">Actualizar</button></center>
			</div>
		</form>
		<hr />
	</div>
</section>
<script src="../jquery/development-bundle/jquery-1.10.2.js"></script>
<script type="text/javascript">
$(document).bind(
	"mobileinit", 
	function(){
		$.extend(  
			$.mobile , 
			{ autoInitializePage: false }
		)
	}
);</script>
<script src="../jquerymobile/jquery.mobile.custom.js"></script>
<script src="../Legend/admin/assets/bootstrap/js/bootstrap.min.js"></script>
<script src="../Legend/admin/assets/backstretch/js/jquery.backstretch.min.js"></script>
<script src="../Legend/admin/assets/js/theme.js"></script>

<script>
function login() {
	$(".login").backstretch("../img/fondo.jpg");
	$(".login-panel").fadeIn();
}
login();
</script>


<script src="../Legend/admin/assets/humane/js/humane.min.js"></script>
<script>
function mensaje(texto, tipo) { 
         var notify;
		 if(tipo==1){ notify = humane.create({
             timeout: 3000,
             baseCls: 'humane-jackedup',
             addnCls: 'humane-jackedup-error'
         });
		 } else if(tipo==2){ notify = humane.create({
             timeout: 3000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-info'
         });
		 } else if(tipo==3){ humane.create({
             timeout: 3000,
             baseCls: 'humane-jackedup',
			 addnCls: 'humane-jackedup-success'
         }); } 
         notify.log(''+texto);
}</script>

<script>
function denegado(texto){ 
	 var notify = humane.create({
          timeout: 4000,
          baseCls: 'humane-bigbox',
          addnCls: 'humane-bigbox-error'
     })
     notify.log(''+texto)
}
</script>

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

if(isset($_REQUEST['messaje_den'])){ echo "<script>denegado('".$_REQUEST['messaje_den']."')</script>"; }
?>
</body>
</html>