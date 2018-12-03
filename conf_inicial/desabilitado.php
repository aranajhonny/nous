<?php  
include_once("../complementos/auditoria.php");
$acc="";
$msj = "";

if(isset($_REQUEST['id'])){ 
	$id = filtrar_campo('int', 6, $_REQUEST['id']);
	switch($id){ 
		case 2: 
			$msj="Debe Generar El Cliente para Habilitar<br/>Las Áreas"; 
			$acc = "<script>window.open('desabilitado.php?id=3','zonas');</script>";
		break;
		case 3: 
			$msj="Debe Generar El Cliente para Habilitar<br/>Las Zonas Géograficas";
			$acc = "<script>window.open('desabilitado.php?id=4','pers');</script>";
		break;
		case 4: 
			$msj="Debe Generar El Cliente para Habilitar<br/>El Personal"; 		
			$acc = "<script>window.open('desabilitado.php?id=5','usus');</script>";
		break;
		case 5: 
			$msj="Debe Generar Personal para <br/>Asignar Los Usuarios";
			$acc = "<script>window.open('desabilitado.php?id=6','asigs');</script>";
		break;
		case 6: 
			$msj="Debe Asignar Los Responsables por [Área y Zona Geografica]"; 
		break;
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Desabilitado</title>
<meta name="author" content="Nous Technologies" />
<link rel="shortcut icon" href="../img/icono.png" />
<title>.:: NousTrack ::.</title>
<?php echo "<link href='../Legend/admin/assets/bootstrap/css/bootstrap.css' rel='stylesheet'/>";?>
<?php echo "<link href='../Legend/admin/assets/css/styles.css' rel='stylesheet'/>"; ?>
</head>

<body style="max-width:845px; max-height:700px; background:#FFF; overflow:hidden;">
<table align="center"><tr><td height="700" align="center"><h2 align="center"><?php echo $msj;?></h2></td></tr></table>

<?php if(isset($acc)) echo $acc; ?>
</body>
</html>